/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass3.c,v 2.20 2012-06-08 06:52:16 Gebruiker Exp $
*/

#include	<stdio.h>
#include	<string.h>

#include	"system.par"
#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"token.h"
#include	"runs.h"
#include	"Malloc.h"
#include	"error.h"
#include	"options.h"
#include	"pass3.h"
#include	"percentages.h"

#ifdef	DB_RUN
#include	"tokenarray.h"
static void db_run(const struct run *);
#endif

static FILE *open_chunk(const struct chunk *);
static void fill_line(FILE *, char []);
static void clear_line(char []);
static void show_run(const struct run *);
static void show_2C_line(const char [], const char []);
static void show_1C_line(FILE *, const char *);
static int pr_head(const struct chunk *);
static int prs(const char *);
static int pru(unsigned int);
static int unslen(unsigned int);

static int max_line_length;		/* Actual maximum line length */
static char *line0;			/* by Malloc() */
static char *line1;

void
Show_Runs(void) {
	AisoIter iter;
	struct run *run;

#ifdef	DB_RUN
	fprintf(Debug_File, "Starting Show_Runs()\n");
#endif	/* DB_RUN */
	max_line_length = Page_Width / 2 - 2;
	line0 = Malloc((unsigned int)((max_line_length + 1) * sizeof (char)));
	line1 = Malloc((unsigned int)((max_line_length + 1) * sizeof (char)));

	OpenIter(&iter);
	while (GetAisoItem(&iter, &run)) {
#ifdef	DB_RUN
		db_run(run);
#endif	/* DB_RUN */
		show_run(run);
		fprintf(Output_File, "\n");
	}
	CloseIter(&iter);

	Free(line0); line0 = 0;
	Free(line1); line1 = 0;
}

static void
show_run(const struct run *run) {
	/* The animals came in two by two ... */
	const struct chunk *cnk0 = &run->rn_chunk0;
	const struct chunk *cnk1 = &run->rn_chunk1;
	unsigned int nl_cnt0 =
			cnk0->ch_last.ps_nl_cnt - cnk0->ch_first.ps_nl_cnt;
	unsigned int nl_cnt1 =
			cnk1->ch_last.ps_nl_cnt - cnk1->ch_first.ps_nl_cnt;
	FILE *f0;
	FILE *f1;

	/* display heading of chunk */
	if (!is_set_option('d')) {
		/* no assumptions about the lengths of the file names! */
		unsigned int size = run->rn_size;
		int pos = 0;

		pos += pr_head(cnk0);
		while (pos < max_line_length + 1) {
			pos += prs(" ");
		}
		pos += prs("|");
		pos += pr_head(cnk1);
		while (pos < 2*max_line_length - unslen(size)) {
			pos += prs(" ");
		}
		fprintf(Output_File, "[%u]\n", size);
	}
	else {
		(void)pr_head(cnk0);
		fprintf(Output_File, "\n");
		(void)pr_head(cnk1);
		fprintf(Output_File, "\n");
	}

	/* stop if that suffices */
	if (is_set_option('n'))
		return;			/* ... had enough so soon ... */

	/* open the files that hold the chunks */
	f0 = open_chunk(cnk0);
	f1 = open_chunk(cnk1);

	/* display the chunks in the required format */
	if (!is_set_option('d')) {
		/* fill 2-column lines and print them */
		while (nl_cnt0 != 0 || nl_cnt1 != 0) {
			if (nl_cnt0) {
				fill_line(f0, line0);
				nl_cnt0--;
			}
			else {
				clear_line(line0);
			}
			if (nl_cnt1) {
				fill_line(f1, line1);
				nl_cnt1--;
			}
			else {
				clear_line(line1);
			}
			show_2C_line(line0, line1);
		}
	}
	else {
		/* display the lines in a diff(1)-like format */
		while (nl_cnt0--) {
			show_1C_line(f0, "<");
		}
		fprintf(Output_File, "---\n");
		while (nl_cnt1--) {
			show_1C_line(f1, ">");
		}
	}

	/* close the pertinent files */
	fclose(f0);
	fclose(f1);
}

static int
pr_head(const struct chunk *cnk) {
	int pos = 0;

	pos += prs(cnk->ch_text->tx_fname);
	pos += prs(": line ");
	pos += pru(cnk->ch_first.ps_nl_cnt);
	pos += prs("-");
	pos += pru(cnk->ch_last.ps_nl_cnt - 1);
	return pos;
}

static int
prs(const char *str) {
	fprintf(Output_File, "%s", str);
	return strlen(str);
}

static int
pru(unsigned int u) {
	fprintf(Output_File, "%u", u);
	return unslen(u);
}

static int
unslen(unsigned int u) {
	int res = 1;

	while (u > 9) {
		u /= 10, res++;
	}
	return res;
}

static FILE *
open_chunk(const struct chunk *cnk) {
	/*	Opens the file in which the chunk resides, positions the
		file at the beginning of the chunk and returns the file pointer.
		Note that we use fopen() here, which opens a character stream,
		rather than Open_Text(), which opens a token stream.
	*/
	const char *fname = cnk->ch_text->tx_fname;
	FILE *f = fopen(fname, "r");
	unsigned int nl_cnt;

	if (!f) {
		fprintf(stderr, ">>>> File %s disappeared <<<<\n", fname);
		f = fopen(NULLFILE, "r");
	}

	nl_cnt = cnk->ch_first.ps_nl_cnt;
	while (nl_cnt > 1) {
		int ch = getc(f);

		if (ch < 0) break;
		if (ch == '\n') {
			nl_cnt--;
		}
	}

	return f;
}

static void
fill_line(FILE *f, char ln[]) {
	/*	Reads one line from f and puts it in condensed form in ln.
	*/
	int indent = 0, lpos = 0;
	int ch;

	/* condense and skip initial blank */
	while ((ch = getc(f)), ch == ' ' || ch == '\t') {
		if (ch == '\t') {
			indent = 8;
		}
		else {
			indent++;
		}
		if (indent == 8) {
			/* every eight blanks give one blank */
			if (lpos < max_line_length) {
				ln[lpos++] = ' ';
			}
			indent = 0;
		}
	}

	/* store the rest */
	while (ch >= 0 && ch != '\n') {
		if (ch == '\t') {
			/* replace tabs by blanks */
			ch = ' ';
		}
		if (lpos < max_line_length) {
			ln[lpos++] = ch;
		}
		ch = getc(f);
	}
	ln[lpos] = '\0';		/* always room for this one */
}

static void
clear_line(char ln[]) {
	/* a simple null byte will suffice */
	ln[0] = '\0';
}

static void
show_2C_line(const char ln0[], const char ln1[]) {
	/*	displays the contents of the two lines in a two-column
		format
	*/
	int i;

	for (i = 0; i < max_line_length && ln0[i] != '\0'; i++) {
		fputc(ln0[i], Output_File);
	}
	for (; i < max_line_length; i++) {
		fputc(' ', Output_File);
	}
	fprintf(Output_File, " |");

	for (i = 0; i < max_line_length && ln1[i] != '\0'; i++) {
		fputc(ln1[i], Output_File);
	}
	fprintf(Output_File, "\n");
}

static void
show_1C_line(FILE *f, const char *marker) {
	/*	displays one line from f, preceded by the marker
	*/
	int ch;

	fprintf(Output_File, "%s", marker);
	while ((ch = getc(f)), ch > 0 && ch != '\n') {
		fputc(ch, Output_File);
	}
	fputc('\n', Output_File);
}

#ifdef	DB_RUN

static void db_chunk(const struct chunk *);

static void
db_run(const struct run *run) {
	/* prints detailed data about a run */
	const struct chunk *cnk0 = &run->rn_chunk0;
	const struct chunk *cnk1 = &run->rn_chunk1;

	db_run_info(0, run, 1);
	db_chunk(cnk0);
	db_chunk(cnk1);
}

static void
db_chunk(const struct chunk *cnk) {
	/*	print the tokens in the chunk, with a one-char margin
	*/
	unsigned int i;
	const struct position *first = &cnk->ch_first;
	const struct position *last = &cnk->ch_last;
	unsigned int start = cnk->ch_text->tx_start;

	if (first->ps_tk_cnt > 0) {
		fprintf(Debug_File, "...");
		fprint_token(Debug_File,
			Token_Array[start + first->ps_tk_cnt - 1]);
		fprintf(Debug_File, "  ");
	}
	else {	/* create same offset as above */
		fprintf(Debug_File, "       ");
	}

	for (i = first->ps_tk_cnt; i <= last->ps_tk_cnt; i++) {
		fprintf(Debug_File, " ");
		fprint_token(Debug_File, Token_Array[start + i]);
	}

	if (start + last->ps_tk_cnt + 1 < cnk->ch_text->tx_limit) {
		fprintf(Debug_File, "  ");
		fprint_token(Debug_File,
			Token_Array[start + last->ps_tk_cnt + 1]);
		fprintf(Debug_File, "...");
	}

	fprintf(Debug_File, "\n");
}

#endif	/* DB_RUN */
