/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: compare.h,v 1.3 2012-05-16 07:56:05 Gebruiker Exp $
*/

/*	Compares each new text to the appropriate texts.
	Stores the runs found in the AISO heap.
	Runs contain references to positions in the input files.
*/

extern void Compare_Files(void);
