/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: add_run.c,v 2.13 2013-04-28 16:30:39 Gebruiker Exp $
*/

#include	"sim.h"
#include	"debug.par"
#include	"text.h"
#include	"runs.h"
#include	"percentages.h"
#include	"Malloc.h"
#include	"options.h"
#include	"error.h"
#include	"add_run.h"

static void set_chunk(
	struct chunk *,
	struct text *,
	size_t,
	size_t
);

static void set_pos(
	struct position *,
	int,
	struct text *,
	size_t
);

void
add_run(struct text *txt0, size_t i0,
	struct text *txt1, size_t i1,
	size_t size
) {
	/*	Adds the run of given size to our collection.
	*/
	struct run *r = new(struct run);

	set_chunk(&r->rn_chunk0, txt0, i0 - txt0->tx_start, size);
	set_chunk(&r->rn_chunk1, txt1, i1 - txt1->tx_start, size);
	r->rn_size = size;

#ifdef	DB_RUN
	db_run_info("Added", r, 0);
#endif	/* DB_RUN */

	if (is_set_option('p')) {
		add_to_percentages(r);
	}
	else {
		add_to_runs(r);
	}
}

static void
set_chunk(struct chunk *cnk, struct text *txt,
	  size_t start, size_t size
) {
	/*	Fill the chunk *cnk with info about the piece of text
		in txt starting at start extending over size tokens.
	*/
	cnk->ch_text = txt;
	set_pos(&cnk->ch_first, 0, txt, start);
	set_pos(&cnk->ch_last, 1, txt, start + size - 1);
}

static void
set_pos(struct position *pos, int type, struct text *txt, size_t start) {
	/* Fill a single struct position */
	pos->ps_next = txt->tx_pos;
	txt->tx_pos = pos;

	pos->ps_type = type;
	pos->ps_tk_cnt = start;
	pos->ps_nl_cnt = (size_t) -1;		/* uninitialized */
}
