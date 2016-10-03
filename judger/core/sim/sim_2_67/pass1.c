/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass1.c,v 2.19 2012-06-08 16:04:29 Gebruiker Exp $
*/

#include	<stdio.h>
#include	<string.h>

#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"token.h"
#include	"tokenarray.h"
#include	"lang.h"
#include	"error.h"
#include	"options.h"
#include	"pass1.h"

#ifdef	DB_TEXT
static void db_print_text(const struct text *);
#endif

static void fprint_count(FILE *f, unsigned int cnt, const char *);

void
Read_Input_Files(int argc, const char *argv[], int round) {
	int n;

	Init_Text(argc);
	Init_Token_Array();

	/* Assume all texts to be new */
	Number_Of_New_Texts = Number_Of_Texts;

	/* Read the files */
	for (n = 0; n < Number_Of_Texts; n++) {
		const char *fname = argv[n];
		struct text *txt = &Text[n];

		if (round == 1 && !is_set_option('T')) {
			fprintf(Output_File, "File %s: ", fname);
		}

		txt->tx_fname = fname;
		txt->tx_pos = 0;
		txt->tx_start =
		txt->tx_limit = Text_Length();
		if (strcmp(fname, "/") == 0) {
			if (round == 1 && !is_set_option('T')) {
				fprintf(Output_File, "separator\n");
			}
			Number_Of_New_Texts = n;
		}
		else {
			if (!Open_Text(First, txt)) {
				if (round == 1 && !is_set_option('T')) {
					fprintf(Output_File,
						">>>> cannot open <<<< ");
				}
				/*	the file has still been opened
					with a null file for uniformity
				*/
			}
			while (Next_Text_Token_Obtained(First)) {
				if (!Token_EQ(lex_token, End_Of_Line)) {
					Store_Token(lex_token);
				}
			}
			Close_Text(First, txt);
			txt->tx_limit = Text_Length();

			/* report */
			if (round == 1 && !is_set_option('T')) {
				fprint_count(Output_File,
					     txt->tx_limit - txt->tx_start,
					     token_name);
				if (lex_non_ascii_cnt) {
					fprintf(Output_File, ", ");
					fprint_count(Output_File,
						     lex_non_ascii_cnt,
						     "non-ASCII character"
					);
				}
				fprintf(Output_File, "\n");
			}

#ifdef	DB_TEXT
			db_print_text(txt);
#endif	/* DB_TEXT */
		}
		fflush(Output_File);
	}

	/* report total */
	if (round == 1 && !is_set_option('T')) {
		fprintf(Output_File, "Total: ");
		fprint_count(Output_File, Text_Length() - 1, token_name);
		fprintf(Output_File, "\n\n");
		fflush(Output_File);
	}
}

static void
fprint_count(FILE *f, unsigned int cnt, const char *unit) {
	/*	Prints a grammatically correct string "%u %s[s]"
		for units that form their plural by suffixing -s.
	*/
	fprintf(f, "%u %s%s", cnt, unit, (cnt == 1 ? "" : "s"));
}

#ifdef	DB_TEXT

static void
db_print_text(const struct text *txt) {
	/* prints a text (in compressed form) */
	int i;

	fprintf(Debug_File, "\n\n**** DB_PRINT_TEXT ****\n");

	fprintf(Debug_File, "File \"%s\", %u %ss, ",
		txt->tx_fname, txt->tx_limit - txt->tx_start, token_name
	);
	fprintf(Debug_File, "txt->tx_start = %u, txt->tx_limit = %u\n",
		txt->tx_start, txt->tx_limit
	);

	int BoL = 1;
	for (i = txt->tx_start; i < txt->tx_limit; i++) {
		if (BoL) {
			fprintf(Debug_File, "[%d]:", i);
			BoL = 0;
		}
		fprintf(Debug_File, " ");
		fprint_token(Debug_File, Token_Array[i]);
		if ((i - txt->tx_start + 1) % 10 == 0) {
			fprintf(Debug_File, "\n");
			BoL = 1;
		}
	}
	fprintf(Debug_File, "\n");
}

#endif	/* DB_TEXT */
