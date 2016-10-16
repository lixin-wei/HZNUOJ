/*	This file is part of the debugging module DEBUG.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: debug.h,v 1.5 2012-01-25 21:43:05 Gebruiker Exp $
*/

/*
DEBUG defines one routine,

	extern void wr_info(const char *s, int b, int v);

which, when compiled with a -DDEBUG option, writes the string s, a space
character, the value v in base b, and a newline to standard error output (file
descriptor 2), without interfering with other program activities.

The following values for b are accepted:
    b = 0:      the string s only
    b = 8:	octal
    b = 16:	hex
    b = 128:	char
    otherwise:	decimal

This allows debugging info to be obtained in the presence of sudden crashes
and other nefarious program activity.

Compiled without the -DDEBUG option wr_info does nothing. This allows easy
switching off of the debugging feature by recompiling debug.c.
*/

extern void wr_info(const char *s, int b, int v);
