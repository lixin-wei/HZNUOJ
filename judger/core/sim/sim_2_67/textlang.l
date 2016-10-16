%{
/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: textlang.l,v 1.13 2012-06-08 16:04:30 Gebruiker Exp $
*/

/*
	Text front end for the similarity tester.
*/

#include	"sim.h"
#include	"token.h"
#include	"idf.h"

#include	"lex.h"
#include	"lang.h"
#include	"language.h"

/* General language front end data */
Token lex_token;
unsigned int lex_nl_cnt;
unsigned int lex_tk_cnt;
unsigned int lex_non_ascii_cnt;

/* Language-dependent code */

void
Init_Language(void) {
	token_name = "word";
	if (!min_run_string) {
		Min_Run_Size = 8;
	}
	if (!threshold_string) {
		Threshold_Percentage = 20;
	}
}

/*ARGSUSED*/
int
May_Be_Start_Of_Run(Token tk) {
	/* any token is acceptable */
	return 1;
}

/*ARGSUSED*/
unsigned int
Best_Run_Size(const Token *str, unsigned int size) {
	/* any run size is acceptable */
	return size;
}

%}

%option	noyywrap

WordElem	([-a-zA-Z\200-\377])
TightWord	({WordElem}+)

NonWordElem	([^-a-zA-Z\200-\377])
LooseElem	({WordElem}(" "))
SpacedWord	({LooseElem}+{WordElem})

%%

{TightWord}	{
		return_tk(idf_hashed(yytext));
	}

{SpacedWord}/{NonWordElem}	{
		/* the / operator works at the top level only */
		return_tk(idf_hashed(yytext));
	}


\n	{				/* count newlines */
		return_eol();
	}

.	{			/* ignore the rest */
	}

%%

/* More language-dependent code */

void
yystart(void) {
	BEGIN INITIAL;
}
