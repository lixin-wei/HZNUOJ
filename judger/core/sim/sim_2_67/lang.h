/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lang.h,v 1.7 2012-06-09 08:09:18 Gebruiker Exp $
*/

/*
	The *lang.l files provide two interfaces:
	    language.[ch]	static data about the language
	    lang.[ch]		dynamic data about the input file's content
	This is lang.[ch].
*/

/*
	The abstract module 'lang' provides access to the lowest-level
	token routines and data.
	The actual implementation derives from one of the *lang.l files.

	There is a dummy implementation lang.c.
*/

extern FILE *yyin;
extern int yylex(void);
extern void yystart(void);

extern Token lex_token;			/* token produced, or End_Of_Line */
extern unsigned int lex_nl_cnt;		/* line count */
extern unsigned int lex_tk_cnt;		/* token position */
extern unsigned int lex_non_ascii_cnt;	/* # of non-ASCII chars found */
