/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lang.c,v 2.5 2012-06-08 16:04:28 Gebruiker Exp $
*/

/*
	This is a dummy implementation of the  module 'lang'.
	Its actual implementation derives from one of the *lang.l files.
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"token.h"
#include	"lang.h"

FILE *yyin;

int
yylex(void) {
	abort();
}

void
yystart(void) {
	abort();
}

Token lex_token;
unsigned int lex_nl_cnt;
unsigned int lex_tk_cnt;
unsigned int lex_non_ascii_cnt;
