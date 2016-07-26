/*	This file is part of the module Arbitrary-In Sorted-Out (AISO).
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: aiso.spc,v 1.2 2008/02/05 16:48:42 dick Exp $
*/

/*
Description:
	This is the specification of a module that builds an arbitrary-in
	sorted-out data structure, to be used as a heap, a priority queue,
	etc.
	Elements can be inserted, the first element extracted and the set
	scanned at any moment.
	The module is not generic, in that only one copy of it can be
	instantiated per program.

Instantiation:
	The module is instantiated as follows.
	Create a file X.h, where X is arbitrary, which contains at least:

	-	a definition of AISO_TYPE, the type of the object to be stored
	-	a possible definition of AISO_EXTR; see below
	-	a possible definition of AISO_ITER; see below
	-	#include	"aiso.spc"

	This file X.h is to be included in all files that use the aiso
	package.

	Create a file X.c which contains at least:

	-	#include	"X.h"
	-	a definition of a routine
			int AISO_BEFORE(AISO_TYPE v, AISO_TYPE w)
		which yields non-zero if v is to be sorted before w
	-	#include	"aiso.bdy"

	This file X.c compiles into the module object.

Specification:
	The module always supplies:
	int InsertAiso(AISO_TYPE value)
		inserts value in its proper place; fails if out of memory

	If AISO_EXTR is defined, the module will also supply:
	int ExtractAiso(AISO_TYPE *value)
		yields the first value in the aiso and removes it;
		fails if empty

	If AISO_ITER is defined, the module also supplies a type AisoIter
	which declares an iterator, i.e., a structure that records a position
	in the ordered set, plus routines for manipulating the iterator, thus
	enabling the user to scan the ordered set.  The iterator should be
	declared as:
		AisoIter iter;
	and is manipulated by the following commands:

	OpenIter(AisoIter *iter)
		opens the iterator for scanning the existing set in order

	int GetAisoItem(AisoIter *iter, AISO_TYPE *value)
		yields the next value in the iterator; fails if exhausted

	CloseIter(AisoIter *iter)
		closes the iterator

	For the use of AISO_DEBUG see aiso.bdy.

Implementation:
	The AISO implementation is based on a self-adjusting binary tree.
	Degenerate behaviour of the tree is avoided by shaking the tree
	every 'ln aiso_size' node accesses.  This guarantees ln aiso_size
	behaviour in the long run, though it is possible for a single
	operation to take aiso_size node accesses.

	The iterator is implemented as an additional linear linked list
	through the tree.  This is simpler than and at least as efficient as
	clever tree-wiring.
*/

struct aiso_node {
	struct aiso_node *an_left;
	struct aiso_node *an_right;
#ifdef	AISO_ITER
	struct aiso_node *an_next;
#endif	/* AISO_ITER */
	AISO_TYPE an_value;
};

extern int InsertAiso(AISO_TYPE value);
#ifdef	AISO_EXTR
extern int ExtractAiso(AISO_TYPE *value);
#endif	/* AISO_EXTR */

#ifdef	AISO_ITER
typedef	struct aiso_node *AisoIter;
extern void OpenIter(AisoIter *iter);
extern int GetAisoItem(AisoIter *iter, AISO_TYPE *value);
extern void CloseIter(AisoIter *iter);
#endif	/* AISO_ITER */

