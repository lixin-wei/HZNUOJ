/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: percentages.c,v 1.13 2012-06-05 09:58:53 Gebruiker Exp $
*/

#include	<stdio.h>

#include	"sim.h"
#include	"text.h"
#include	"runs.h"
#include	"options.h"
#include	"Malloc.h"
#include	"error.h"
#include	"percentages.h"

/* To compute percentages fairly, the input files are read twice. This
   makes it impossible to use the struct text-s from the presented run as
   identifications of the files, since their order differs between the first
   and the second scan. Specific entries from the struct text-s
   are stored instead.
*/

struct match {
	struct match *ma_next;
	const char *ma_fname0;
	const char *ma_fname1;
	unsigned int ma_size;		/* # tokens of file 0 found in file 1 */
	unsigned int ma_size0;		/* # tokens in file 0 */
};

static struct match *match_start;	/* to be allocated by new() */

void
add_to_percentages(struct run *r) {
	struct match **match_hook = &match_start;

	/* percentages are only meaningful between different files */
	if (r->rn_chunk0.ch_text == r->rn_chunk1.ch_text) return;

	/* look (text0, text1) combination up in match list */
	while (*match_hook) {
		struct match *m = *match_hook;

		if (	m->ma_fname0 == r->rn_chunk0.ch_text->tx_fname
		&&	m->ma_fname1 == r->rn_chunk1.ch_text->tx_fname
		) {
			/* found it; now update it */
			m->ma_size += r->rn_size;
			return;
		}
		match_hook = &m->ma_next;
	}

	{	/* it's not there; make a new entry */
		struct match *m = *match_hook = new(struct match);
		struct text *text0 = r->rn_chunk0.ch_text;
		struct text *text1 = r->rn_chunk1.ch_text;

		m->ma_next = 0;
		m->ma_fname0 = text0->tx_fname;
		m->ma_fname1 = text1->tx_fname;
		m->ma_size = r->rn_size;
		m->ma_size0 = text0->tx_limit - text0->tx_start;
	}
}

static float
match_percentage(struct match *m) {
	return (m->ma_size*1.0/m->ma_size0);
}

/*
   We want the sorting order
      all contributors of the file with the highest percentage
      all contributors of the file with the next lower percentage
      etc.
   but this order cannot be specified by a single SORT_BEFORE().
   So we sort for percentage, and then reorder during printing.
*/

/* instantiate sort_match_list(struct match **listhook) */
#define	SORT_STRUCT		match
#define	SORT_NAME		sort_match_list
#define	SORT_BEFORE(p1,p2)	(match_percentage(p1) > match_percentage(p2))
#define	SORT_NEXT		ma_next
#include	"sortlist.bdy"

static void
print_perc_info(struct match *m) {
	int mp = match_percentage(m)*100.0;

	if (mp > 100) {
		/* this may result from overlapping matches */
		mp = 100;
	}
	if (mp >= Threshold_Percentage) {
		fprintf(Output_File,
			"%s consists for %d %% of %s material\n",
			m->ma_fname0, mp, m->ma_fname1
			);
	}
}

static void
print_and_remove_perc_info_for_top_file(struct match **m_hook) {
	struct match *m = *m_hook;
	const char *fname = m->ma_fname0;

	print_perc_info(m);
	*m_hook = m->ma_next;
	Free(m);

	while ((m = *m_hook)) {
		if (m->ma_fname0 == fname) {
			if (is_set_option('P')) {
				print_perc_info(m);
			}
			/* remove the struct */
			*m_hook = m->ma_next;
			Free(m);
		} else {
			/* skip the struct */
			m_hook = &m->ma_next;
			continue;
		}
	}
}

static void
print_percentages(void) {
	while (match_start) {
		print_and_remove_perc_info_for_top_file(&match_start);
	}
}

void
Show_Percentages(void) {
	sort_match_list(&match_start);
	print_percentages();
}
