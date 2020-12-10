/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: language.h,v 1.9 2016-04-27 19:09:48 dick Exp $
*/

/*
	The *lang.l files provide two interfaces:
	    language.[ch]	static data about the language
	    lang.[ch]		dynamic data about the input file's content
	This is language.[ch].
*/

/*
	The abstract class 'language' declares the names that give access to
	the properties of the language.
	The corresponding definitions must be provided by the *lang.l file.

	There is a dummy implementation language.c, to satisfy the module
	consistency checker check-ch.

*/

extern const char *Subject;
extern void Init_Language(void);
extern int May_Be_Start_Of_Run(Token ch);
extern size_t Best_Run_Size(const Token *str, size_t size);
