/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lex.h,v 2.12 2012-09-30 11:55:19 dick Exp $
*/

/* Macros for the *lang.l files */
#define	return_tk(tk)	{lex_tk_cnt++; lex_token = (tk); return 1;}
#define	return_ch(ch)	{lex_tk_cnt++; lex_token = int2Token((int)(ch)); return 1;}
#define	return_eol()	{lex_nl_cnt++; lex_token = End_Of_Line; return 1;}
