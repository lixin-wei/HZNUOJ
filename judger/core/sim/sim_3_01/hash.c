/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: hash.c,v 2.39 2017-02-04 16:58:54 dick Exp $
*/

/*	Text is compared by comparing every substring to all substrings
	to the right of it; this process is in essence quadratic.  However,
	only substrings of length at least 'Min_Run_Size' are of interest,
	which gives us the possibility to speed up this process by using
	a hash table.

	For every position p in the text, we construct an index table entry
	forward_reference[p] which gives the next position in the text
	at which a run of Min_Run_Size tokens starts that has the same
	hash code, as calculated by hash1().  If there is no such run,
	the index is 0.

	To construct this array, we use a hash table latest_index[] whose size
	is a prime and which is about the same size as the text array.
	The hash table latest_index[] is set up such that latest_index[i] is the
	index of the latest token with hash_code i, or 0 if there is none.
	See Make_Forward_References().

	The forward references produced this way are not perfect, due to hashing
	coincidences. A second sweep (make_forward_references_perfect()) makes
	them perfect by doing a full comparison on Min_Run_Size tokens.
	For the LaTeX sources of our book Modern Compiler Design, 2nd Ed. this
	reduced the total forward chain length from 103555 to 345, as
	determined by db_forward_reference_check().

	The forward references can be checked with db_forward_reference_check(),
	which also collects statistics.
*/

#include	<stdio.h>
#include	<stdint.h>

#include	"system.par"
#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"Malloc.h"
#include	"any_int.h"
#include	"token.h"
#include	"language.h"
#include	"token.h"
#include	"tokenarray.h"
#include	"options.h"
#include	"hash.h"

							/* MAIN ENTRIES */
static size_t *forward_reference;		/* to be filled by Malloc() */
static size_t n_forward_references;

static void make_forward_references_using_hash(void);
static void make_forward_references_perfect(void);
static void make_chains_circular(void);

#ifdef	DB_FORW_REF
static void db_forward_reference_check(const char *);
static void db_print_forward_references(void);
#endif	/* DB_FORW_REF */

void
Make_Forward_References(void) {
	/*	Constructs the forward references table.
	*/
	n_forward_references = Token_Array_Length();
	forward_reference =
		(size_t *)Calloc(n_forward_references, sizeof (size_t));
	make_forward_references_using_hash();
	make_forward_references_perfect();
	if (is_set_option('a')) {
		make_chains_circular();
	}
#ifdef	DB_FORW_REF_PRINT
	db_print_forward_references();
#endif	/* DB_FORW_REF_PRINT */
}

size_t
Forward_Reference(size_t i, size_t i0) {
	if (i == 0 || i >= n_forward_references) {
		fatal("internal error, bad forward reference");
	}
	size_t new_i = forward_reference[i];
	size_t res = new_i == 0 || new_i == i0 /*circular*/ ? 0 : new_i;
	return res;
}

void
Free_Forward_References(void) {
	Free(forward_reference);
}

							/* HASHING */
static size_t *latest_index;
static size_t latest_index_table_size;

/* The prime numbers of the form 4 * i + 3 for some i, all greater
   than twice the previous one and smaller than 2^40 (for now).
*/
static const uint64_t prime[] = {
#if 0
	3,
	7,
	19,
	43,
	103,
	211,
	431,
	863,
	1747,
	3499,
	7019,
#endif
	14051,
	28111,
	56239,
	112507,
	225023,
	450067,
	900139,
	1800311,
	3600659,
	7201351,
	14402743,
	28805519,
	57611039,
	115222091,
	230444239,
	460888499,
	921777067,
	1843554151,
	UINT64_C (3687108307),
	UINT64_C (7374216631),
	UINT64_C (14748433279),
	UINT64_C (29496866579),
	UINT64_C (58993733159),
	UINT64_C (117987466379),
	UINT64_C (235974932759),
	UINT64_C (471949865531),
	UINT64_C (943899731087)
	/* 2^40= 1099511627776 */
};

static void
init_hash_table(void) {
	int n;

	/* find the ideal hash table size */
	n = 0;
	while (prime[n] < Token_Array_Length()) {
		n++;
		/* this will always terminate, if prime[] is large enough */
	}

	/* see if we can allocate that much space, and if not, step down */
	latest_index = 0;
	while (	/* we have not yet obtained our array */
	        !latest_index
	&&	/* and there is still a (prime) size left to try */
	        n >= 0
	) {
		latest_index_table_size = prime[n];
		latest_index = (size_t *)
			TryCalloc(latest_index_table_size, sizeof (size_t));
		n--;
	}
	if (!latest_index) {
		fatal("out of memory: no room for hash table");
	}
}

static void
make_forward_references_using_hash(void) {
	int n;

	init_hash_table();

	/* Set up the forward references using the latest_index[] hash table. */
	for (n = 0; n < Number_of_Texts; n++) {
		const struct text *txt = &Text[n];
		size_t j;
		uint32_t hash = 0;

#define	Left_Circular_32(i, s)	(((i) << (s)) | ((i) >> (32-(s))))
#define	SHIFT	(5)

		for (j = txt->tx_start;	j < txt->tx_limit; j++) {
			if (	/* we have a complete hash value */
				j - txt->tx_start >= Min_Run_Size
			) {	/* remove the oldest token */
				Token oldest_token =
					Token_Array[j - Min_Run_Size];
				int oldest_shift =
					((Min_Run_Size-1) * SHIFT) % 32;
				hash ^=
				   Left_Circular_32(oldest_token, oldest_shift);
			}
			/* Circular left shift */
			hash = Left_Circular_32(hash, SHIFT);
			/* Add new token */
			hash ^= Token_Array[j];

			/* If have we assembled a complete hash value now,
			   the corresponding run would start at
			   j - (Min_Run_Size - 1). For it to be valid it
			   should start at or after txt->tx_start, so we would
			   like to write the test
			   j - (Min_Run_Size - 1) >= txt->tx_start. However,
			   the type of this computation is size_t, which is
			   unsigned, and j - (Min_Run_Size - 1) may be negative,
			   so we code instead:
			*/
			if (j - txt->tx_start < (Min_Run_Size - 1)) {
				/* no */
				continue;
			}

			/* We now have the complete hash value for a run ending
			   at j and can safely compute j - (Min_Run_Size - 1).
			*/
			size_t run_start = j - (Min_Run_Size - 1);

			/* Can the run be useful? */
			if (!May_Be_Start_Of_Run(Token_Array[run_start]))
				continue;			/* no*/

			/* the hash value is used here for an index */
			size_t h = hash % latest_index_table_size;

			if (latest_index[h]) {
				forward_reference[latest_index[h]] = run_start;
			}
			/*latest_index[h] = j;*/
			latest_index[h] = run_start;
		}
	}

	Free(latest_index);

#ifdef	DB_FORW_REF
	db_forward_reference_check("first hashing");
#endif	/* DB_FORW_REF */
}

static void
make_chains_circular(void) {
	size_t i;

	/* Make the chains circular, by a slightly quadratic algorithm. */
	for (i = 0; i+Min_Run_Size < Token_Array_Length(); i++) {
		if (!forward_reference[i]) continue;
		size_t j = i;
		while (forward_reference[j]) {
			size_t j1 = forward_reference[j];
			if (j1 < j) break;	/* has already been treated */
			j = j1;
		}
		if (forward_reference[j] == 0 && j != i) {
			/* tie it back to the beginning of the chain */
			forward_reference[j] = i;
		}
	}
}

static int
is_eq_min_run(const Token *p, const Token *q) {
	/* a full comparison for the tertiary sweep */
	size_t n;

	for (n = 0; n < Min_Run_Size; n++) {
		if (!Token_EQ(p[n], q[n])) return 0;
	}
	return 1;
}

static void
make_forward_references_perfect(void) {
	size_t i;

	/* Simulate a perfect hash by doing a full comparison
	   over Min_Run_Size, for gathering statistics.
	*/

	for (i = 0; i+Min_Run_Size < Token_Array_Length(); i++) {
		size_t j = i;

		while (	/* there is still a forward reference */
			(j = forward_reference[j])
		&&	/* it does not match over Min_Run_Size */
			!is_eq_min_run(&Token_Array[i], &Token_Array[j])
		) {
			/* continue searching */
		}
		/* short-circuit forward reference to it, or to zero */
		forward_reference[i] = j;
	}
	/* now we have perfect forward references */

#ifdef	DB_FORW_REF
	db_forward_reference_check("full Min_Run_Size comparison");
#endif	/* DB_FORW_REF */
}

#ifdef	DB_FORW_REF

static void
db_print_forward_references(void) {
	/* also determines the lengths of the chains, for statistics */
	size_t n;
	size_t n_frw_chains = 0;
	size_t tot_frwc_len = 0;
	size_t *print_loc_of =
		(size_t *)Calloc(Token_Array_Length(), sizeof (size_t));
	size_t *number_of_chains_of_length =
		(size_t *)Calloc(Token_Array_Length(), sizeof (size_t));

	/* print the references */
	for (n = 1; n < Token_Array_Length(); n++) {
		size_t fw = forward_reference[n];
		if (fw == 0) continue;

		/* we have a chain */
		fprintf(Debug_File, "FWR[%s]:", any_uint2string(n, 0));

		/* is it old? */
		if (print_loc_of[n]) {
			fprintf(Debug_File, " see %s\n",
				any_uint2string(print_loc_of[n], 0));
			continue;
		}

		/* no, we have the beginning of a new chain */
		size_t count = 0;
		do {
			count++;
			fprintf(Debug_File, " %s",
				any_uint2string(fw, 0));
			print_loc_of[fw] = n;
			fw = forward_reference[fw];
		} while(fw && fw != n);	/* continuing and not circular */
		if (fw) {	/* circular */
			fprintf(Debug_File, " C");
			count++;
		}
		n_frw_chains++;
		tot_frwc_len += count;
		number_of_chains_of_length[count]++;
		fprintf(Debug_File, "\n");
	}

	/* print the chain lengths */
	for (n = 1; n < Token_Array_Length(); n++) {
		if (number_of_chains_of_length[n]) {
			fprintf(Debug_File, "length[%d]:\t%d\n",
				n, number_of_chains_of_length[n]);
		}
	}

	fprintf(Debug_File,
		"text length = %s, # forward chains = %s, av. frw chain length = %.2f\n\n",
		any_uint2string(Token_Array_Length(), 0),
		any_uint2string(n_frw_chains, 0),
		(n_frw_chains ? 1.0 * tot_frwc_len / n_frw_chains : 0.0)
	);

	Free(number_of_chains_of_length);
	Free(print_loc_of);
}

static void
db_frw_chain(size_t n, char *crossed_out) {
	if (forward_reference[n] == 0) {
		fprintf(Debug_File,
			">>>> db_frw_chain() forward_reference[n] == 0 <<<<\n"
		);
		return;
	}

	size_t n_entries = 0;
	size_t fw;

	for (fw = n; fw; fw = forward_reference[fw]) {
		if (crossed_out[fw]) {
			fprintf(Debug_File,
				">>>> error: forward references cross <<<<\n"
			);
		}
		n_entries++;
		crossed_out[fw] = 1;
	}
#ifdef	DB_FORW_REF_PRINT
	fprintf(Debug_File, "chain_start = %s, n_entries = %s\n",
		any_uint2string(n, 0), any_uint2string(n_entries, 0));
#endif	/* DB_FORW_REF_PRINT */
}

static void
db_forward_reference_check(const char *msg) {
	/*	Each forward_reference[n] starts in principle a new
		chain, and these chains never touch each other.
		We check this property by marking the positions in each
		chain in an array; if we meet a marked entry while
		following a chain, it must have been on an earlier chain
		and we have an error.
	*/
	size_t n;
	char *crossed_out = (char *)Calloc(Token_Array_Length(), sizeof (char));

	fprintf(Debug_File, "\n\n**** DB_FORWARD_REFERENCES, %s ****\n", msg);
	fprintf(Debug_File, "latest_index_table_size = %s\n",
		any_uint2string(latest_index_table_size, 0));

	if (forward_reference[0]) {
		fprintf(Debug_File,
			">>>> forward_reference[0] is not zero <<<<\n"
		);
	}
	for (n = 1; n < Token_Array_Length(); n++) {
		if (forward_reference[n] && !crossed_out[n]) {
			/* start of a new chain */
			db_frw_chain(n, crossed_out);
		}
	}
#ifdef	DB_FORW_REF_PRINT
	db_print_forward_references();
#endif	/* DB_FORW_REF_PRINT */

	Free(crossed_out);
}

#endif	/* DB_FORW_REF */
