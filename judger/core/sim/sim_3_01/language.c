/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: language.c,v 2.4 2016-08-05 15:31:19 dick Exp $
*/

/*
	This is a dummy implementation of the abstract class 'language', so
	there will not be a language.o file.
	The actual implementation is provided by Xlang.o deriving through
	Xlang.c from the pertinent Xlang.l file.
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"token.h"
#include	"language.h"

const char *Subject;

void
Init_Language(void) {
	abort();
}

int
May_Be_Start_Of_Run(Token ch) {
	if (ch == ch) abort();
	return 0;
}

size_t
Best_Run_Size(const Token *str, size_t size) {
	if (str == str || size == size) abort();
	return 0;
}

