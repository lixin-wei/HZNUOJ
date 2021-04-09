/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: stream.h,v 2.7 2012-06-08 16:04:30 dick Exp $
*/

/*
	Interface of the stream module.

	Implements the direct interaction with the lexical
	module.  It supplies the routines below.
*/

extern int Open_Stream(const char *);
extern int Next_Stream_Token_Obtained(void);
extern void Close_Stream(void);
extern void Print_Stream(const char *fname);
