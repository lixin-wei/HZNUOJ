/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lang.h,v 1.8 2013-04-28 16:30:41 Gebruiker Exp $
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
extern size_t lex_nl_cnt;		/* line count */
extern size_t lex_tk_cnt;		/* token position */
extern size_t lex_non_ascii_cnt;	/* # of non-ASCII chars found */
