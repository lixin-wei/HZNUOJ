/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lang.c,v 2.9 2016-05-13 19:00:52 dick Exp $
*/

/*
	This is a dummy implementation of the  module 'lang'.
	Its actual implementation derives from one of the *lang.l files.
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"token.h"

#include	"language.h"
#include	"algollike.h"
#include	"idf.h"
#include	"lex.h"
#include	"lang.h"


FILE *yyin;

int
yylex(void) {
	abort();
#ifdef	lint
	(void)May_Be_Start_Of_Algol_Run(0);
	(void)Best_Algol_Run_Size(0, 0);
	(void)idf_in_list(0, 0, 0, 0);
	(void)idf_hashed(0);
	(void)lower_case(0);
#endif
	return 0;
}

void
yystart(void) {
	abort();
#ifdef	lint
	Init_Algol_Language(0, 0, 0, 0);
#endif
}

Token lex_token;
size_t lex_nl_cnt;
size_t lex_tk_cnt;
size_t lex_non_ascii_cnt;
