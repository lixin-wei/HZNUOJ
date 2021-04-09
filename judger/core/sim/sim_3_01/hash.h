/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: hash.h,v 1.5 2016-04-27 12:59:11 dick Exp $
*/

/*	Creating and consulting forward_reference[], used to speed up
	the Longest Substring Allgorithm.
*/

extern void Make_Forward_References(void);
extern void Free_Forward_References(void);
/* with circularity check: */
extern size_t Forward_Reference(size_t i, size_t i0);
