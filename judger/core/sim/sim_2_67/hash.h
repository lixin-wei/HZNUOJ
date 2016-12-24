/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: hash.h,v 1.3 2012-05-13 09:05:49 Gebruiker Exp $
*/

/*	Creating and consulting forward_reference[], used to speed up
	the Longest Substring Allgorithm.
*/

extern void Make_Forward_References(void);
extern void Free_Forward_References(void);
extern unsigned int Forward_Reference(int i);
