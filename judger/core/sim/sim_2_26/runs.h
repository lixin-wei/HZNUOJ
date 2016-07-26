/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: runs.h,v 1.3 2008/03/31 10:35:17 dick Exp $
*/

/*	Although all other segments of data in this program are described by
	giving the position of the first in the segment and that of the
	first not in the segment (so the size is the difference of the two),
	a `chunk' is given by first and last. This is done because later on we
	are interested in the actual position of the last token of it, and
	the position of the first token not in the segment gives no
	indication about that.
*/

struct chunk {
	/* a chunk of text in various representations */
	struct text *ch_text;		/* pointer to the file */
	struct position ch_first;	/* first in chunk */
	struct position ch_last;	/* last in chunk */
};

struct run {				/* a 'run' of coincident tokens */
	struct chunk rn_cn0;		/* chunk in left file */
	struct chunk rn_cn1;		/* chunk in right file */
	unsigned int rn_size;
};

#define	AISO_TYPE	struct run *
#define	AISO_ITER

#define	add_to_runs(r)	InsertAiso(r)

#include	"aiso.spc"
