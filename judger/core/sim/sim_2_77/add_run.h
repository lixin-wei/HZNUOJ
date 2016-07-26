/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: add_run.h,v 1.4 2013-04-28 16:30:39 Gebruiker Exp $
*/

/*	Interface between front-end and back-end: all information about
	runs passes through add_run().  Its parameters are the two chunks,
	each identified by their struct text and the position of the common
	segment in Token_Array[], and the number of tokens in the common
	segment.
*/

extern void add_run(
	struct text *txt0,		/* text of first chunk */
	size_t i0,			/* chunk position in Token_Array[] */
	struct text *txt1,		/* text of second chunk */
	size_t i1,			/* chunk position in Token_Array[] */
	size_t size			/* number of tokens in the chunk */
);
