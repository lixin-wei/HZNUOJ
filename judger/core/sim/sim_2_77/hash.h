/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: hash.h,v 1.4 2013-04-28 16:30:40 Gebruiker Exp $
*/

/*	Creating and consulting forward_reference[], used to speed up
	the Longest Substring Allgorithm.
*/

extern void Make_Forward_References(void);
extern void Free_Forward_References(void);
extern size_t Forward_Reference(size_t i);
