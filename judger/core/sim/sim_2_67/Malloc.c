/*	This file is part of the memory management and leak detector MALLOC.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: Malloc.c,v 1.6 2012-01-25 21:43:05 Gebruiker Exp $
*/

#include	<stdio.h>
#include	<stdlib.h>
#include	<unistd.h>
#include	<string.h>

#include	"Malloc.h"
#undef	new
#define	new		use_my_new	/* don't call Malloc in Malloc.c */
#define	my_new(type)	((type *)malloc(sizeof (type)))

/* All output goes through designated files, so we block printf, etc. */
#undef	printf
#define	printf	use_fprintf
#undef	putchar
#define	putchar	use_fprintf

#ifndef	lint

static void
fprintloc(FILE *f, const char *fname, int l_nmb) {
	fprintf(f, "\"%s\", line %d: ", fname, l_nmb);
}

static void
out_of_memory(const char *fname, int l_nmb, size_t size)  {
	fprintloc(stderr, fname, l_nmb);
	fprintf(stderr, "Out of memory, requested size = %lld\n",
		(long long int)size);
	exit(1);
}

#if	defined MEMLEAK || defined MEMCLOBBER
/* Both need almost the same information: MEMLEAK obviously needs a list of
   all blocks still allocated, but MEMCLOBBER needs the same list to find
   the size of a block given to Free(), in order to clobber it.
   MEMCLOBBER does not need total, balance and max, but finecombing them out
   would be too much.
*/

static long long int total = 0;
static long long int balance = 0;
static long long int max = 0;

struct record {
	struct record *next;
	const char *addr;
	size_t size;
	const char *fname;
	int l_nmb;
};

#define	HASH_SIZE	16381		/* largest prime under 2^16 */
static struct record *record_hash[HASH_SIZE];
#define	chain_start(x)	record_hash[((unsigned int)(x)%HASH_SIZE)]

static void
record_alloc(char *addr, size_t size, const char *fname, int l_nmb) {
	struct record *new;
	struct record **r_hook = &chain_start(addr);

	if (addr == 0) return;

	new = my_new(struct record);
	new->addr = addr;
	new->size = size;
	new->fname = fname;		/* no need to copy fname */
	new->l_nmb = l_nmb;
	new->next = *r_hook;
	*r_hook = new;

	total += size;
	balance += size;
	if (balance > max) {
		max = balance;
	}
}


static struct record **
record_pointer_for_address(const char *addr) {
	struct record **rp = &chain_start(addr);

	while (*rp) {
		if ((*rp)->addr == addr) break;
		rp = &(*rp)->next;
	}

	return rp;
}

static size_t
record_free(char *addr) {
	struct record **oldp = record_pointer_for_address(addr);
	struct record *old = *oldp;

	if (old == 0) return 0;

	*oldp = old->next;
	balance -= old->size;

	return old->size;
}

#endif	/* defined MEMLEAK || defined MEMCLOBBER */

void
MemClobber(void *p, size_t size) {
	unsigned char *s = (unsigned char *)p;
	unsigned char byte;
	size_t i;

	byte = 0xaa;
	for (i = 0; i < size; i++) {
		s[i] = byte;
		byte ^= 0xff;
	}
}

#ifdef	MEMLEAK

struct entry {
	struct entry *next;
	const char *fname;
	int l_nmb;
	int n_blocks;
	int var_size;	/* all blocks have the same size or not */
	int size;	/* !var_size: the one size; var_size: sum of sizes */
};

static struct entry *
compacted_leaks(void) {
	struct entry *res = 0;
	int i;

	for (i = 0; i < HASH_SIZE; i++) {
		struct record *r = record_hash[i];

		while (r) {
			struct entry *e = res;

			/* try to find an entry for this location */
			while (e) {
				if (	e->fname == r->fname
				&&	e->l_nmb == r->l_nmb
				)	break;
				e = e->next;
			}

			if (e) {	/* update the entry */
				if (e->var_size) {
					e->size += r->size;
				}
				else if (e->size != r->size) {
					/* switch to var_size */
					e->var_size = 1;
					e->size =
					    e->n_blocks*e->size + r->size;
				}
				e->n_blocks++;
			}
			else {		/* create a new entry */
				e = my_new(struct entry);
				e->fname = r->fname;
				e->l_nmb = r->l_nmb;
				e->n_blocks = 1;
				e->var_size = 0;
				e->size = r->size;

				e->next = res;
				res = e;
			}

			r = r->next;
		}
	}

	return res;
}

static int
number_of_leaks(const struct entry *e) {
	int res = 0;

	while (e != 0) {
		res++;
		e = e->next;
	}

	return res;
}

static void
report_actual_leaks(FILE *f) {
	const struct entry *e = compacted_leaks();
	int n_leaks = number_of_leaks(e);

	if (n_leaks == 0) return;

	fprintf(f, "There %s %d case%s of unreclaimed memory:\n",
		(n_leaks == 1 ? "was" : "were"),
		n_leaks,
		(n_leaks == 1 ? "" : "s")
	);

	while (e) {
		fprintloc(f, e->fname, e->l_nmb);
		fprintf(f, "left allocated: %d block%s of size ",
			e->n_blocks, (e->n_blocks == 1 ? "" : "s")
		);
		if (e->var_size) {
			/* e->size is the sum of the sizes */
			fprintf(f, "%d on average",
				 (e->size + e->n_blocks/2) / e->n_blocks
			);
		}
		else {
			/* e->size is the single size */
			fprintf(f, "%d", e->size);
		}
		if (e->n_blocks > 1) {
			fprintf(f, " = %d",
				(e->var_size ? e->size : e->size*e->n_blocks));
		}
		fprintf(f, "\n");

		e = e->next;
	}
}

void
ReportMemoryLeaks(FILE *f) {
	if (f == 0) f = stderr;
	if (balance == 0) return;
	report_actual_leaks(f);

	fprintf(f, "Total memory allocated= %lld", total);
	fprintf(f, ", maximum allocated = %lld", max);
	fprintf(f, ", garbage left = %lld", balance);
	fprintf(f, "\n");
}

#else	/* no MEMLEAK */

/*ARGSUSED*/
void
ReportMemoryLeaks(FILE *f) {
}

#endif	/* MEMLEAK */

void *
_leak_malloc(int chk, size_t size, const char *fname, int l_nmb) {
	void *res = malloc(size);

	if (chk && res == 0) {
		out_of_memory(fname, l_nmb, size);
		/*NOTREACHED*/
	}

#if	defined MEMLEAK || defined MEMCLOBBER
	record_alloc(res, size, fname, l_nmb);

#ifdef	MEMCLOBBER
	MemClobber((char *)res, size);
#endif
#endif

	return res;
}

void *
_leak_calloc(int chk, int n, size_t size, const char *fname, int l_nmb) {
	void *res = calloc(n, size);

	if (chk && res == 0) {
		out_of_memory(fname, l_nmb, n*size);
		/*NOTREACHED*/
	}

#if	defined MEMLEAK || defined MEMCLOBBER
	record_alloc(res, n*size, fname, l_nmb);
#endif

	return res;
}

void *
_leak_realloc(int chk, void *addr, size_t size, const char *fname, int l_nmb) {
	void *res;
#if	defined MEMLEAK || defined MEMCLOBBER
	size_t old_size = record_free(addr);

	/* we report first, because the realloc() below may cause a crash */
	if (	/* we are not reallocating address 0, which is allowed */
		addr != 0
	&&	/* the address was never handed out before */
		old_size == 0
	) {
		fprintloc(stderr, fname, l_nmb);
		fprintf(stderr, ">>>> unallocated block reallocated <<<<\n");
	}
#endif

	res = realloc(addr, size);
	if (chk && res == 0) {
		out_of_memory(fname, l_nmb, size);
		/*NOTREACHED*/
	}

#if	defined MEMLEAK || defined MEMCLOBBER
	record_alloc(res, size, fname, l_nmb);
#endif

#ifdef	MEMCLOBBER
	if (old_size > 0 && size > old_size) {
		MemClobber(((char *)res)+old_size, size-old_size);
	}
#endif

	return res;
}

/* ARGSUSED */
void
_leak_free(void *addr, const char *fname, int l_nmb) {
#if	defined MEMLEAK || defined MEMCLOBBER
	size_t old_size = record_free(addr);

	/* we report first, because the free() below may cause a crash */
	if (old_size == 0) {
		fprintloc(stderr, fname, l_nmb);
		fprintf(stderr, ">>>> unallocated block freed ");
		fprintf(stderr, "or multiple free of allocated block <<<<\n");
	}
	else {
#ifdef	MEMCLOBBER
	MemClobber((char *)addr, old_size);
#endif
	}
#endif
	free(addr);
}

char *
_new_string(const char *s, const char *fname, int l_nmb) {
	return strcpy((char *)(_leak_malloc(1, strlen(s)+1, fname, l_nmb)), s);
}
#endif	/* not lint */

#ifdef	lint
static void
satisfy_lint(void *x) {
	void *v;

	v = _leak_malloc(0, 0, 0, 0);
	v = _leak_calloc(0, 0, 0, 0, 0);
	v = _leak_realloc(0, 0, 0, 0, 0);
	_leak_free(x, 0, 0);

	ReportMemoryLeaks(0);

	v = _new_string(0, 0, 0);
	satisfy_lint(v);
}
#endif
