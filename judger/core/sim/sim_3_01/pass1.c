/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass1.c,v 2.35 2016-07-29 12:50:02 dick Exp $
*/

#include	<stdio.h>
#include	<string.h>

#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"token.h"
#include	"tokenarray.h"
#include	"lang.h"
#include	"options.h"
#include	"pass1.h"

#ifdef	DB_TEXT
static void db_print_text(const struct text *);
#endif

static void fprint_count(FILE *f, size_t cnt, const char *);

void
Read_Input_Files(int argc, const char *argv[]) {
	int n;

	Init_Text(argc);
	Init_Token_Array();

	/* Initially assume all texts to be new */
	Number_of_New_Texts = Number_of_Texts;

	/* Read the files */
	for (n = 0; n < Number_of_Texts; n++) {
		const char *fname = argv[n];
		struct text *txt = &Text[n];

		if (!is_set_option('T')) {
			fprintf(Output_File, "File %s: ", fname);
		}

		txt->tx_fname = fname;
		txt->tx_pos = 0;
		txt->tx_start = Token_Array_Length();
		txt->tx_limit = Token_Array_Length();

		if (is_new_old_separator(fname)) {
			if (!is_set_option('T')) {
				fprintf(Output_File, "new/old separator\n");
			}
			if (Number_of_New_Texts == Number_of_Texts) {
				Number_of_New_Texts = n;
			} else fatal("more than one new/old separator");
		}
		else {
			int file_opened = 0;
			if (Open_Text(First_Pass, txt)) {
				file_opened = 1;
			} else {
				/* print a warning */
				if (is_set_option('T')) {
					/* the file name has not yet been
					   printed; print it now
					*/
					fprintf(Output_File, "File %s: ",
						fname);
				}
				fprintf(Output_File,
					">>>> cannot open <<<<\n");
				/*	the file has still been opened
					with a null file for uniformity
				*/
			}
			while (Next_Text_Token_Obtained()) {
				if (!Token_EQ(lex_token, End_Of_Line)) {
					Store_Token(lex_token);
				}
			}
			Close_Text(First_Pass, txt);
			txt->tx_limit = Token_Array_Length();
			txt->tx_EOL_terminated =
				Token_EQ(lex_token, End_Of_Line);

			/* report */
			if (file_opened && !is_set_option('T')) {
				fprint_count(Output_File,
					     txt->tx_limit - txt->tx_start,
					     Token_Name
				);
				fprintf(Output_File, ", ");
				fprint_count(Output_File,
					lex_nl_cnt - 1 +
					     (!txt->tx_EOL_terminated ? 1 : 0),
					"line"
				);
				if (!txt->tx_EOL_terminated) {
					fprintf(Output_File,
						" (not NL-terminated)");
				}
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
	int sep_present = (Number_of_Texts != Number_of_New_Texts);
	fprintf(Output_File, "Total input: ");
	fprint_count(Output_File,
		     (!sep_present ? Number_of_Texts : Number_of_Texts - 1),
		     "file"
	);
	fprintf(Output_File, " (%d new, %d old), ",
		Number_of_New_Texts,
		(!sep_present ? 0 :  Number_of_Texts - Number_of_New_Texts - 1)
	);
	fprint_count(Output_File, Token_Array_Length() - 1, Token_Name);
	fprintf(Output_File, "\n\n");
	fflush(Output_File);
}

static void
fprint_count(FILE *f, size_t cnt, const char *unit) {
	/*	Prints a grammatically correct string "%u %s[s]"
		for units that form their plural by suffixing -s.
	*/
	fprintf(f, "%s %s%s", size_t2string(cnt), unit, (cnt == 1 ? "" : "s"));
}

#ifdef	DB_TEXT

static void
db_print_text(const struct text *txt) {
	/* prints a text (in compressed form) */
	size_t i;

	fprintf(Debug_File, "\n\n**** DB_PRINT_TEXT ****\n");

	fprintf(Debug_File, "File \"%s\", %s %ss, ",
		txt->tx_fname,
		size_t2string(txt->tx_limit - txt->tx_start),
		Token_Name
	);
	fprintf(Debug_File, "txt->tx_start = %s, txt->tx_limit = %s\n",
		size_t2string(txt->tx_start),
		size_t2string(txt->tx_limit)
	);

	int BoL = 1;
	for (i = txt->tx_start; i < txt->tx_limit; i++) {
		if (BoL) {
			fprintf(Debug_File, "[%s]:", size_t2string(i));
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
