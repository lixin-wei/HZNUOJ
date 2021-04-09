/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass2.c,v 2.24 2016-05-13 19:00:53 dick Exp $
*/

#include	<stdio.h>

#include	"debug.par"
#include	"sim.h"
#include	"token.h"
#include	"text.h"
#include	"lang.h"
#include	"pass2.h"

#ifdef	DB_POS
static void db_print_pos_list(const char *, const struct text *);
static void db_print_lex(const char *);
#endif

static void pass2_txt(struct text *txt);

void
Retrieve_Runs(void) {
	int n;

	for (n = 0; n < Number_of_Texts; n++) {
		pass2_txt(&Text[n]);
	}
}

/* begin instantiate static void sort_pos_list(struct position **) */
#define	SORT_STRUCT		position
#define	SORT_NAME		sort_pos_list
#define	SORT_BEFORE(p1,p2)	((p1)->ps_tk_cnt < (p2)->ps_tk_cnt)
#define	SORT_NEXT		ps_next
#include	"sortlist.bdy"
/* end instantiate sort_pos_list() */

static void
pass2_txt(struct text *txt) {
	struct position *pos;
	size_t old_nl_cnt;

	if (!txt->tx_pos)	/* no need to scan the file */
		return;

	/* Open_Text() initializes lex_nl_cnt and lex_tk_cnt */
	if (!Open_Text(Second_Pass, txt)) {
		fprintf(stderr, ">>>> File %s disappeared <<<<\n",
			txt->tx_fname
		);
		return;
	}

	/* Sort the positions so they can be matched to the file; the linked
	   list of struct positions snakes through the struct positions in the
	   struct chunks in the struct runs.
	*/
#ifdef	DB_POS
	db_print_pos_list("before sorting", txt);
#endif	/* DB_POS */

	sort_pos_list(&txt->tx_pos);

#ifdef	DB_POS
	db_print_pos_list("after sorting", txt);
#endif	/* DB_POS */

#ifdef	DB_NL_BUFF
	db_print_nl_buff(txt->tx_nl_start, txt->tx_nl_limit);
#endif	/* DB_NL_BUFF */

#ifdef	DB_POS
	fprintf(Debug_File, "\n**** DB_PRINT_SCAN of %s ****\n", txt->tx_fname);
#endif	/* DB_POS */

	old_nl_cnt = 1;
	pos = txt->tx_pos;
	while (pos) {
		/* we scan the pos list and the file in parallel */

		/* find the corresponding line */
		while (pos->ps_tk_cnt >= lex_tk_cnt) {
			/* pos does not refer to this line, try the next */

			/* shift the administration */
			old_nl_cnt = lex_nl_cnt;
			/* and get the next eol position */
			if (!Next_Text_EOL_Obtained()) {
				/* reached end of file without obtaining EOL */
				if (!txt->tx_EOL_terminated) {
					/* that's OK then */
				} else {
					fprintf(stderr,
						">>>> File %s modified <<<<\n",
						txt->tx_fname
					);
				}
				break;
			}
#ifdef	DB_POS
			db_print_lex(txt->tx_fname);
#endif	/* DB_POS */
		}

		/* fill in the pos */
		switch (pos->ps_type) {
		case 0:	/* first token of run */
			pos->ps_nl_cnt = old_nl_cnt;
			break;
		case 1:	/* last token of run */
			pos->ps_nl_cnt = lex_nl_cnt;
			break;
		}
		/* and get the next pos */
		pos = pos->ps_next;
	}

#ifdef	DB_POS
	db_print_pos_list("after scanning", txt);
#endif	/* DB_POS */

	/* Flush the flex buffers; it's easier than using YY_BUFFER_STATE. */
	while (Next_Text_EOL_Obtained());

	Close_Text(Second_Pass, txt);
}

#ifdef	DB_POS

static void
db_print_pos(const struct position *pos) {
	fprintf(Debug_File, "pos type = %s; %s count = %u",
		(pos->ps_type == 0 ? "first" : " last"),
		Token_Name,
		pos->ps_tk_cnt
	);
	fprintf(Debug_File, ", line # = ");
	if (pos->ps_nl_cnt == (size_t) -1) {
		fprintf(Debug_File, "<NOT SET>");
	}
	else {
		fprintf(Debug_File, "%u", pos->ps_nl_cnt);
	}
	fprintf(Debug_File, "\n");
}

static void
db_print_pos_list(const char *msg, const struct text *txt) {
	fprintf(Debug_File, "\n**** DB_PRINT_POS_LIST of %s, %s ****\n",
		txt->tx_fname, msg);

	const struct position *pos = txt->tx_pos;
	while (pos) {
		db_print_pos(pos);
		pos = pos->ps_next;
	}
	fprintf(Debug_File, "\n");
}

static void
db_print_lex(const char *fn) {
	fprintf(Debug_File,
		"%s: lex_tk_cnt = %u, lex_nl_cnt = %u, lex_token = ",
		fn, lex_tk_cnt, lex_nl_cnt);
	fprint_token(Debug_File, lex_token);
	fprintf(Debug_File, "\n");
}

#endif	/* DB_POS */
