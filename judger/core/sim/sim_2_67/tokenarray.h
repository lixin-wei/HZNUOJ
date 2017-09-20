/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: tokenarray.h,v 1.4 2012-06-08 06:52:17 Gebruiker Exp $
*/

/* Interface for the token storage */
extern void Init_Token_Array(void);
extern void Store_Token(Token tk);
extern unsigned int Text_Length(void);	/* also first free token position */
extern Token *Token_Array;

