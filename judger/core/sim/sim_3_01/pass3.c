/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass3.c,v 2.31 2016-07-31 18:55:44 dick Exp $
*/

#include	<stdio.h>
#include	<string.h>

#include	"system.par"
#include	"settings.par"
#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"token.h"
#include	"runs.h"
#include	"options.h"
#include	"pass3.h"
#include	"percentages.h"

#ifdef	DB_RUN
#include	"tokenarray.h"
static void db_run(const struct run *);
#endif

/* Positioning of UTF-8 characters must be done with a finer grain than just
   10 Courier characters to the inch. On the other hand we do not know the
   exact width of each UNICODE character, so whatever we do is an approximation.
   We use a granularity of 1 pt and a font size of 10 pts.

   Since C does not have type-checked integer subtypes, we put the the fact
   that they handle UTF-8 chars in the variable and routine names to avoid
   errors.
*/
typedef int pts;
#define	FONT_SIZE	(10)

static FILE *open_chunk(const struct chunk *);
static void print_char(char);
static void print_spaces(int);
static pts print_UTF8_line(FILE *);
static pts print_UTF8_char(int ch, FILE *);
static void print_UTF8_spaces(pts);
static void show_run(const struct run *);
static void show_1C_line(FILE *, const char *);
static int print_header(const struct chunk *);
static int print_string(const char *);
static int print_size_t(size_t);
static int length_size_t(size_t);

static int max_line_length;		/* Actual maximum line length */
static pts max_line_length_UTF8;

void
Show_Runs(void) {
#ifdef	DB_RUN
	fprintf(Debug_File, "Starting Show_Runs()\n");
#endif	/* DB_RUN */
	const struct run *run = /*ZZ*/
		(is_set_option('u') ? unsorted_runs() : sorted_runs());

	while (run) {
#ifdef	DB_RUN
		db_run(run);
#endif	/* DB_RUN */
		show_run(run);
		print_char('\n');
		fflush(Output_File);
		run = run->rn_next;
	}

	discard_runs();
}

static void
show_run(const struct run *run) {
	max_line_length = Page_Width / 2 - 1;
	max_line_length_UTF8 = max_line_length * FONT_SIZE;

	/* The animals came in two by two ... */
	const struct chunk *cnk0 = &run->rn_chunk0;
	const struct chunk *cnk1 = &run->rn_chunk1;
	size_t nl_cnt0 = cnk0->ch_last.ps_nl_cnt - cnk0->ch_first.ps_nl_cnt;
	size_t nl_cnt1 = cnk1->ch_last.ps_nl_cnt - cnk1->ch_first.ps_nl_cnt;
	FILE *f0;
	FILE *f1;

	/* display heading of chunk */
	if (!is_set_option('d')) {
		/* no assumptions about the lengths of the file names! */
		size_t size = run->rn_size;
		int pos = print_header(cnk0);
		print_spaces(max_line_length - pos);
		print_char('|');
		pos = print_header(cnk1);
		print_spaces(max_line_length - pos - length_size_t(size) - 2);
		fprintf(Output_File, "[%s]\n", size_t2string(size));
	}
	else {
		/* diff-like format */
		(void)print_header(cnk0);
		print_char('\n');
		(void)print_header(cnk1);
		print_char('\n');
	}

	/* stop if that suffices */
	if (is_set_option('n'))
		return;			/* ... had enough so soon ... */

	/* open the files that hold the chunks */
	f0 = open_chunk(cnk0);
	f1 = open_chunk(cnk1);

	/* display the chunks in the required format */
	if (!is_set_option('d')) {
		/* print 2-column format */
		while (nl_cnt0 != 0 || nl_cnt1 != 0) {
			int pos_UTF8 = 0;
			if (nl_cnt0) {
				pos_UTF8 = print_UTF8_line(f0);
				nl_cnt0--;
			}
			print_UTF8_spaces(max_line_length_UTF8 - pos_UTF8);
			print_char('|');
			if (nl_cnt1) {
				(void)print_UTF8_line(f1);
				nl_cnt1--;
			}
			print_char('\n');
		}
	}
	else {
		/* display the chunks in a diff(1)-like format */
		while (nl_cnt0--) {
			show_1C_line(f0, "<");
		}
		(void)print_string("---\n");
		while (nl_cnt1--) {
			show_1C_line(f1, ">");
		}
	}

	/* close the pertinent files */
	fclose(f0);
	fclose(f1);
}

static int
print_header(const struct chunk *cnk) {
	int pos = 0;

	pos += print_string(cnk->ch_text->tx_fname);
	pos += print_string(": line ");
	pos += print_size_t(cnk->ch_first.ps_nl_cnt);
	pos += print_string("-");
	pos += print_size_t(cnk->ch_last.ps_nl_cnt - 1);
	return pos;
}

static int
print_string(const char *str) {
	fprintf(Output_File, "%s", str);
	return (int) strlen(str);
}

static int
print_size_t(size_t u) {
	fprintf(Output_File, "%s", size_t2string(u));
	return length_size_t(u);
}

static int
length_size_t(size_t u) {
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
	size_t nl_cnt;

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

static pts
print_UTF8_line(FILE *f) {
	/* Reads one line from f and prints it in condensed form. */
	int indent = 0, pos_UTF8 = 0;
	int ch;

	/* condense initial blanks */
	while ((ch = getc(f)), ch == ' ' || ch == '\t') {
		if (ch == '\t') {
			indent = 8;
		}
		else {
			indent++;
		}
		if (indent == 8) {
			/* every eight blanks give one blank */
			if (pos_UTF8 < max_line_length_UTF8) {
				pos_UTF8 += print_UTF8_char(' ', 0);
			}
			indent = 0;
		}
	}

	/* print the rest */
	while (ch >= 0 && ch != '\n') {
		if (ch == '\t') {
			/* replace tabs by blanks */
			ch = ' ';
		}
		if (pos_UTF8 < max_line_length_UTF8) {
			pos_UTF8 += print_UTF8_char(ch, f);
		}
		ch = getc(f);
	}
	return pos_UTF8;
}

static void
print_char(char ch) {
	fprintf(Output_File, "%c", ch);
}

static void
print_spaces(int n) {
	while (n > 0) {
		print_char(' '), --n;
	}
}

static pts
print_UTF8_char(int ch, FILE *f) {
	int pos_UTF8 = FONT_SIZE;
	fprintf(Output_File, "%c", ch);
	if (ch < 192) return pos_UTF8;

	while (ch & 0x40) {
		int ch1 = getc(f);
		fprintf(Output_File, "%c", ch1);
		if ((ch1 & 0xC0) != 0x80) {
			/* bad UTF-8 */
			return pos_UTF8;	/* no recovery */
		}
		pos_UTF8 += 4;
		/* ^ Stupid heuristic: the longer the UTF sequence, the bigger
		   the character. Rough, but the best we can reasonably do.
		*/
		ch <<= 1;
	}
	return pos_UTF8;
}

static void
print_UTF8_spaces(pts n) {
	while (n > 0) {
		print_char(' '), n -= FONT_SIZE;
	}
}

static void
show_1C_line(FILE *f, const char *marker) {
	/*	displays one line from f, preceded by the marker
	*/
	int ch;

	fprintf(Output_File, "%s ", marker);
	while ((ch = getc(f)), ch > 0 && ch != '\n') {
		print_char(ch);
	}
	print_char('\n');
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
	size_t i;
	const struct position *first = &cnk->ch_first;
	const struct position *last = &cnk->ch_last;
	size_t start = cnk->ch_text->tx_start;

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
