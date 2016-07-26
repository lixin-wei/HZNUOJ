/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: compare.c,v 2.16 2012-06-08 06:52:14 Gebruiker Exp $
*/

#include	"sim.h"
#include	"text.h"
#include	"token.h"
#include	"tokenarray.h"
#include	"hash.h"
#include	"language.h"
#include	"options.h"
#include	"add_run.h"
#include	"compare.h"
#include	"debug.par"

static void compare_one_text(int, int, int);
static unsigned int lcs(
	struct text *, unsigned int, struct text **, unsigned int *,
	unsigned int, unsigned int
);

/*	The overall structure of the routine Compare_Files() is:

	for all new files
		for all texts it must be compared to
			for all positions in the new file
				for all positions in the text
					for ever increasing sizes
						try to match and keep the best
*/

void
Compare_Files(void) {
	int n;

	for (	/* all new texts */
		n = 0; n < Number_Of_New_Texts; n++
	) {
		int first =
			(	/* if compare to old only */
				is_set_option('S')
			?	Number_Of_New_Texts + 1
			:	/* else if do not compare to self */
				is_set_option('s')
				? n + 1
				/* else */
				: n
			);

		if (is_set_option('e')) {
			/* from first to Number_Of_Texts in steps */
			int m;

			for (m = first; m < Number_Of_Texts; m++) {
				compare_one_text(n, m, m+1);
			}
		}
		else {
			/* from first to Number_Of_Texts in one action */
			if (first < Number_Of_Texts) {
				compare_one_text(n, first, Number_Of_Texts);
			}
		}
	}
}

static void
compare_one_text(
	int n,				/* text to be compared */
	int first,			/* first text to be compared to */
	int limit			/* first text not to be compared to */
) {
	unsigned int i_first = Text[first].tx_start;
	unsigned int i_limit = Text[limit-1].tx_limit;
	struct text *txt0 = &Text[n];
	unsigned int i0 = txt0->tx_start;

	while (	/* there may be a useful substring */
		i0 + Min_Run_Size <= txt0->tx_limit
	) {
		/* see if there really is one */
		struct text *txt_best;
		unsigned int i_best;
		unsigned int size_best =
			lcs(txt0, i0, &txt_best, &i_best, i_first, i_limit);

		if (size_best) {
			/* good run found; enter it */
			add_run(txt0, i0, txt_best, i_best, size_best);
			/* and skip it */
			i0 += size_best;
		}
		else {
			/* we try our luck at the next token */
			i0++;
		}
	}
}

static unsigned int
lcs(	struct text *txt0,		/* input: starting position */
	unsigned int i0,
	struct text **tbp,		/* output: position of best run */
	unsigned int *ibp,
	unsigned int i_first,		/* no comparison before this pos. */
	unsigned int i_limit		/* no comparison after this pos. */
) {
	/*	Finds the longest common substring (not subsequence) in:
			txt0, starting precisely at i0 and
			the text from i_first to i_limit-1.
		Writes the position in tbp and ibp and returns the size.
		Returns 0 if no common substring is found.
	*/
	struct text *txt1 = txt0;
	unsigned int i1 = i0;
	unsigned int size_best = 0;

	while (	/* there is a next opportunity */
		(i1 = Forward_Reference(i1))
	&&	/* it is still in range */
		i1 < i_limit
	) {
		unsigned int min_size= (size_best ? size_best+1 : Min_Run_Size);

		if (i1 < i_first) {	/* not in range */
			continue;
		}

		/* bump txt1; we may have to skip a text or two */
		while (i1 >= txt1->tx_limit) {
			txt1++;
		}

		/* are we looking at something better than we have got? */
		{	/* comparing backwards */
			unsigned int j0 = i0 + min_size - 1;
			unsigned int j1 = i1 + min_size - 1;
			if (	/* j0 still inside txt0 */
				j0 < txt0->tx_limit
			&&	/* j1 still inside txt1 */
				j1 < txt1->tx_limit
			&&	/* j0 and j1 don't overlap */
				j0 + min_size <= j1
			) {
				/* there is room enough for a match */
				int cnt = min_size;

				/* text matches for at least min_size tokens? */
				while (	cnt
				&&	Token_EQ(Token_Array[j0],
						 Token_Array[j1])
				) {
					cnt--, j0--, j1--;
				}
				if (cnt) continue;	/* forget it */
			}
			else continue;			/* forget it */
		}

		/* yes, we are; how long can we make it? */
		unsigned int new_size = min_size;
		{	/* extending forwards */
			unsigned int j0 = i0 + min_size;
			unsigned int j1 = i1 + min_size;

			while (	/* j0 still inside txt0 */
				j0 < txt0->tx_limit
			&&	/* j1 still inside txt1 */
				j1 < txt1->tx_limit
			&&	/* j0 and j1 don't overlap */
				j0 + new_size < j1
			&&	/* tokens are the same */
				Token_EQ(Token_Array[j0], Token_Array[j1])
			) {
				j0++, j1++, new_size++;
			}
		}

		/*	offer the run to the Language Department which may
			reject it or may cut its tail
		*/
		new_size = (	May_Be_Start_Of_Run(Token_Array[i0])
			   ?	Best_Run_Size(&Token_Array[i0], new_size)
			   :	0
			   );

		if (	/* we still have something acceptable */
			new_size >= Min_Run_Size
		&&	/* it is better still than what we had */
			new_size > size_best
		) {
			/* record it */
			*tbp = txt1;
			*ibp = i1;
			size_best = new_size;
		}
	}

	return size_best;
}
