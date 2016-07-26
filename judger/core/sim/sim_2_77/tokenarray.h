/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: tokenarray.h,v 1.5 2013-04-28 16:30:43 Gebruiker Exp $
*/

/* Interface for the token storage */
extern void Init_Token_Array(void);
extern void Store_Token(Token tk);
extern size_t Text_Length(void);	/* also first free token position */
extern Token *Token_Array;

