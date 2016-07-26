/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: language.c,v 2.2 2012-06-08 16:04:28 Gebruiker Exp $
*/

/*
	This is a dummy implementation of the abstract class 'language'.
	The actual implementation is provided by one of the *lang.l files.
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"token.h"
#include	"language.h"

void
Init_Language(void) {
	abort();
}

int
May_Be_Start_Of_Run(Token ch) {
	if (ch == ch) abort();
	return 0;
}

unsigned int
Best_Run_Size(const Token *str, unsigned int size) {
	if (str == str || size == size) abort();
	return 0;
}

