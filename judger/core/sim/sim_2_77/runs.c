/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: runs.c,v 1.7 2014-01-26 21:52:59 Gebruiker Exp $
*/

#include	"sim.h"
#include	"text.h"
#include	"runs.h"
#include	"debug.par"

#define	AISO_BEFORE(r0,r1)	((r0)->rn_size > (r1)->rn_size)

#include	"aiso.bdy"

static int aiso_overflow;

void
add_to_runs(struct run *r) {
	if (InsertAiso(r)) return;

	if (!aiso_overflow) {
		fprintf(stderr, ">>>> Memory overflow: too many runs found\n");
		aiso_overflow = 1;
	}
}

#ifdef	DB_RUN

void
db_run_info(const char *msg, const struct run *run, int lines_too) {
	const struct chunk *cnk0 = &run->rn_chunk0;
	const struct chunk *cnk1 = &run->rn_chunk1;

	if (msg) {
		fprintf(Debug_File, "%s: ", msg);
	}
	fprintf(Debug_File, "File %s / file %s:\n",
		cnk0->ch_text->tx_fname, cnk1->ch_text->tx_fname
	);
	fprintf(Debug_File, "from %s %s/%s to %s/%s:", token_name,
		size_t2string(cnk0->ch_first.ps_tk_cnt),
		size_t2string(cnk1->ch_first.ps_tk_cnt),
		size_t2string(cnk0->ch_last.ps_tk_cnt),
		size_t2string(cnk1->ch_last.ps_tk_cnt)
	);
	if (lines_too) {
		fprintf(Debug_File, " from lines %s/%s to %s/%s:",
			size_t2string(cnk0->ch_first.ps_nl_cnt),
			size_t2string(cnk1->ch_first.ps_nl_cnt),
			size_t2string(cnk0->ch_last.ps_nl_cnt),
			size_t2string(cnk1->ch_last.ps_nl_cnt)
		);
	}
	fprintf(Debug_File, " %s %s%s\n",
		size_t2string(run->rn_size),
		token_name, (run->rn_size == 1 ? "" : "s")
	);
}

#endif	/* DB_RUN */
