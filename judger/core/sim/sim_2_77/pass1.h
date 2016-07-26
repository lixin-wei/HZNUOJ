/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: pass1.h,v 1.7 2012-05-16 07:56:06 Gebruiker Exp $
*/

/*	Reads the input files; stores the tokens in Token Token_Array[]
	and the input file descriptions in struct text text[].
*/
extern void Read_Input_Files(int argc, const char *argv[], int round);
