/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: stream.c,v 2.11 2012-06-08 16:04:29 Gebruiker Exp $
*/

#include	<stdio.h>
#include	<sys/types.h>
#include	<sys/stat.h>

#include	"system.par"
#include	"sim.h"
#include	"options.h"
#include	"token.h"
#include	"lang.h"
#include	"stream.h"

static FILE *fopen_regular_file(const char *fname);

int
Open_Stream(const char *fname) {
	int ok;

	lex_nl_cnt = 1;
	lex_tk_cnt = 0;
	lex_non_ascii_cnt = 0;

	/* start the lex machine */
	yyin = fopen_regular_file(fname);
	ok = (yyin != 0);
	if (!ok) {
		/* fake a stream, to simplify the rest of the program */
		yyin = fopen(NULLFILE, "r");
	}
	yystart();
	return ok;
}

static FILE *
fopen_regular_file(const char *fname) {
	struct stat buf;

	if (stat(fname, &buf) != 0) return 0;
	if ((buf.st_mode & S_IFMT) != S_IFREG) return 0;
	return fopen(fname, "r");
}

int
Next_Stream_Token_Obtained(void) {
	return yylex();
}

void
Close_Stream(void) {
	if (yyin) {
		fclose(yyin);
		yyin = 0;
	}
}

void
Print_Stream(const char *fname) {
	fprintf(Output_File, "File %s:", fname);
	if (!Open_Stream(fname)) {
		fprintf(Output_File, " cannot open\n");
		return;
	}

	if (!is_set_option('T')) {
		fprintf(Output_File,
			" showing token stream:\nnl_cnt, tk_cnt: %ss",
			token_name
		);

		lex_token = End_Of_Line;
		do {
			if (Token_EQ(lex_token, End_Of_Line)) {
				fprintf(Output_File, "\n%u,%u:",
					lex_nl_cnt, lex_tk_cnt
				);
			}
			else {
				fprintf(Output_File, " ");
				fprint_token(Output_File, lex_token);
			}
		} while (Next_Stream_Token_Obtained());

		fprintf(Output_File, "\n");
	}

	Close_Stream();
}
