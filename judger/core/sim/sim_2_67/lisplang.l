%{
/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lisplang.l,v 2.16 2012-06-08 16:04:29 Gebruiker Exp $
*/

/*
	LISP language front end for the similarity tester.
	Author:	Gertjan Akkerman <akkerm@cs.vu.nl>
	Date:	Thu, 9 Apr 87 11:15:23 MDT
*/

#include	"token.h"
#include	"language.h"
#include	"algollike.h"
#include	"lex.h"
#include	"lang.h"

/* General language front end data */
Token lex_token;
unsigned int lex_nl_cnt;
unsigned int lex_tk_cnt;
unsigned int lex_non_ascii_cnt;

/* Language-dependent data */
#include	"idf.h"

static const struct idf reserved[] = {
	{"append",	NORM('a')},
	{"append1",	NORM('b')},
	{"atom",	NORM('t')},
	{"car",		NORM('h')},
	{"cdr",		NORM('t')},
	{"cond",	NORM('c')},
	{"cons",	NORM('s')},
	{"defun",	NORM('u')},
	{"do",		NORM('d')},
	{"eq",		NORM('e')},
	{"equal",	NORM('e')},		/* See eq */
	{"for",		NORM('f')},
	{"if",		NORM('i')},
	{"list",	NORM('l')},
	{"nconc",	NORM('n')},
	{"rplaca",	NORM('A')},
	{"rplacd",	NORM('D')}
};

/* Token sets for module algollike */
const Token Non_Finals[] = {
	NORM('('),
	NORM('['),
	No_Token
};
const Token Non_Initials[] = {
	NORM(')'),
	NORM(']'),
	No_Token
};
const Token Openers[] = {
	NORM('('),
	NORM('['),
	No_Token
};
const Token Closers[] = {
	NORM(')'),
	NORM(']'),
	No_Token
};

/* Language-dependent code */

void
Init_Language(void) {
	Init_Algol_Language(Non_Finals, Non_Initials, Openers, Closers);
}


int
May_Be_Start_Of_Run(Token ch) {
	return May_Be_Start_Of_Algol_Run(ch);
}

unsigned int
Best_Run_Size(const Token *str, unsigned int size) {
	return Best_Algol_Run_Size(str, size);
}

%}

%option	noyywrap

%Start	Comment

Layout		([ \t\r\f])
ASCII95		([- !"#$%&'()*+,./0-9:;<=>?@A-Z\[\\\]^_`a-z{|}~])

AnyQuoted	(\\.)
StrChar		([^"\n\\]|{AnyQuoted})
ChrChar		([^'\\]|{AnyQuoted})

IdfChar		([-!#$%&*+,/0-9:;<=>?@A-Z\\^_`a-z{}~])

EscIdf		(({IdfChar}|\\.)+)
QuotIdf		("|"[^\|\n]*"|")
Idf		({EscIdf}|{QuotIdf})

%%

";".*$	{				/* comment */
	}

\"{StrChar}*\"	{			/* strings */
		return_ch('"');
	}

{Idf}	{				/* identifier */
		return_tk(idf_in_list(yytext, reserved, sizeof reserved, IDF));
	}

\n	{				/* count newlines */
		return_eol();
	}

{Layout}	{			/* ignore layout */
	}

{ASCII95}	{			/* copy other text */
		return_ch(yytext[0]);
	}

.	{				/* count non-ASCII chars */
		lex_non_ascii_cnt++;
	}

%%

/* More language-dependent code */

void
yystart(void) {
	BEGIN INITIAL;
}
