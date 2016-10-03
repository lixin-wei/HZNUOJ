/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: lex.h,v 2.11 2012-06-08 16:04:28 Gebruiker Exp $
*/

/* Service macros for the *lang.l files */
#define	return_tk(tk)	{lex_tk_cnt++; lex_token = (tk); return 1;}
#define	return_ch(ch)	{lex_tk_cnt++; lex_token = int2Token((int)(ch)); return 1;}
#define	return_eol()	{lex_nl_cnt++; lex_token = End_Of_Line; return 1;}
