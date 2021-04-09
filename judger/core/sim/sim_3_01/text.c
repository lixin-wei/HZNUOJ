/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: text.c,v 1.23 2016-08-03 19:14:04 dick Exp $
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"debug.par"
#include	"sim.h"
#include	"token.h"
#include	"stream.h"
#include	"lang.h"
#include	"Malloc.h"
#include	"options.h"
#include	"text.h"

struct text *Text;			/* to be filled in by Malloc() */
int Number_of_Texts;
int Number_of_New_Texts;

typedef unsigned short nl_tk_diff_t;

struct newline {
	nl_tk_diff_t nl_tk_diff;	/* token position difference */
};

#define	NL_START	1024		/* initial newline buffer size */

static struct newline *nl_buff;		/* to be filled by Malloc() */
static size_t nl_size;			/* size of nl_buff[] */
static size_t nl_free;			/* next free position in nl_buff[] */

static size_t nl_next, nl_limit;	/* nl_buff[] pointers during pass 2 */

static void store_newline(void);
static void init_nl_buff(void);

/*							TEXT INTERFACE */

static size_t last_tk_cnt;		/* token count at newline */
static size_t last_nl_cnt;		/* nl counter during pass 2 */

void
Init_Text(int nfiles) {
	/* allocate the array of text descriptors */
	if (Text) {
		Free(Text);
		Text = 0;
	}
	Number_of_Texts = nfiles;
	Text = (struct text *)
		Malloc((size_t)(Number_of_Texts*sizeof (struct text)));

	init_nl_buff();
}

int
Open_Text(enum Pass pass, struct text *txt) {
	switch (pass) {
	case First_Pass:
		last_tk_cnt = 0;
		if (nl_buff) {
			txt->tx_nl_start = nl_free;
		}
		break;

	case Second_Pass:
		last_tk_cnt = 0;
		if (nl_buff) {
			nl_next = txt->tx_nl_start;
			nl_limit = txt->tx_nl_limit;
			last_nl_cnt = 1;
			lex_nl_cnt = 1;
			lex_tk_cnt = 0;
			return 1;
		}
		break;
	}

	return Open_Stream(txt->tx_fname);
}

int
Next_Text_Token_Obtained(void) {
	if (!Next_Stream_Token_Obtained()) return 0;
	if (Token_EQ(lex_token, End_Of_Line)) {
		store_newline();
		last_tk_cnt = lex_tk_cnt;
	}
	return 1;
}

int
Next_Text_EOL_Obtained(void) {
	/* get newline info from the buffer or from the file itself */
	if (nl_buff) {
		if (nl_next == nl_limit) return 0;

		struct newline *nl = &nl_buff[nl_next++];
		lex_nl_cnt = ++last_nl_cnt;
		lex_tk_cnt = (last_tk_cnt += nl->nl_tk_diff);
		lex_token = End_Of_Line;
		return 1;
	} else {
		int ok;
		while (	(ok = Next_Stream_Token_Obtained())
		&&	!Token_EQ(lex_token, End_Of_Line)
		) {
			/* skip */
		}
		return ok;
	}
}

void
Close_Text(enum Pass pass, struct text *txt) {
	switch (pass) {
	case First_Pass:
		if (nl_buff) {
			if (last_tk_cnt != lex_tk_cnt) {
				/* there were tokens after the last newline */
				store_newline();
			}
			txt->tx_nl_limit = nl_free;
		}
		break;
	case Second_Pass:
		break;
	}
	Close_Stream();
}

void
Free_Text(void) {
	if (nl_buff) {
		Free(nl_buff); nl_buff = 0;
	}
	if (Text) {
		Free(Text); Text = 0;
	}
}

/*							NEWLINE CACHING */

/*	To speed up pass2 which is interested in token positions at line ends,
	the newline buffer keeps this info from pass1. To reduce the size of
	the newline buffer, the info is kept as the differences of the values
	at consecutive line ends. This allows unsigned chars to be used rather
	than integers.

	The recording of token position differences at End_Of_Line is optional,
	and is switched off if
	-	there is not room enough for the newline buffer;
	-	a difference would not fit in the field in the struct;
	-	we are reporting percentages.
	Switching off is done by freeing the buffer and setting nl_buff to 0.
	Anybody using nl_buff should therefore test for nl_buff being zero.
*/

static void abandon_nl_buff(const char *);

static void
init_nl_buff(void) {
	/* if we are doing percentages, we don't need the nl_buff mechanism */
	if (is_set_option('p')) return;

	/* Allocate the newline buffer, if possible */
	nl_size = 0 + NL_START;
	nl_buff = (struct newline *)TryMalloc(sizeof (struct newline)*nl_size);
	nl_free = 0;
}

static void
store_newline(void) {
	if (!nl_buff) return;

	if (nl_free == nl_size) {
		/* allocated array is full; try to increase its size */
		size_t new_size = nl_size + nl_size/2;
		if (new_size < nl_free) {
			abandon_nl_buff("out of address space");
			return;
		}

		struct newline *new_buff = (struct newline *)TryRealloc(
			(char *)nl_buff,
			sizeof (struct newline) * new_size
		);

		if (!new_buff) {
			abandon_nl_buff("out of memry");
			return;
		}
		nl_buff = new_buff, nl_size = new_size;
	}

	/* now we are sure there is room enough */
	{
		struct newline *nl = &nl_buff[nl_free++];
		size_t tk_diff = lex_tk_cnt - last_tk_cnt;

		nl->nl_tk_diff = (nl_tk_diff_t) tk_diff;
		if (nl->nl_tk_diff != tk_diff) {
			abandon_nl_buff("tk_diff does not fit in nl_tk_diff");
		}
	}
}

static void	/*ARGSUSED*/
abandon_nl_buff(const char *msg) {
#undef	DB_BUFF
#ifdef	DB_BUFF
	fprintf(Debug_File, "abandon_nl_buff, %s\n", msg);
#endif	/* DB_BUFF */
	if (nl_buff) {
		Free(nl_buff);
		nl_buff = 0;
	}
}

#ifdef	DB_NL_BUFF

void
db_print_nl_buff(size_t start, size_t limit) {
	size_t i;

	fprintf(Debug_File, "\n**** DB_NL_BUFF ****\n");
	if (!nl_buff) {
		fprintf(Debug_File, ">>>> NO NL_BUFF\n\n");
		return;
	}

	if (start > nl_free) {
		fprintf(Debug_File, ">>>> start (%s) > nl_free (%s)\n\n",
			size_t2string(start), size_t2string(nl_free)
		);
		return;
	}
	if (limit > nl_free) {
		fprintf(Debug_File, ">>>> limit (%s) > nl_free (%s)\n\n",
			size_t2string(limit), size_t2string(nl_free)
		);
		return;
	}

	fprintf(Debug_File, "nl_buff: %s entries:\n", size_t2string(nl_free));
	for (i = start; i < limit; i++) {
		struct newline *nl = &nl_buff[i];

		fprintf(Debug_File, "nl_tk_diff = %d\n", nl->nl_tk_diff);
	}
	fprintf(Debug_File, "\n");
}

#endif	/* DB_NL_BUFF */
