/*	This file is part of the module Arbitrary-In Sorted-Out (AISO).
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: aiso.bdy,v 1.4 2012-05-08 08:43:56 Gebruiker Exp $
*/

/*
Description:
	This is the body of a module that builds an arbitrary-in
	sorted-out data structure, to be used as a heap, a priority queue,
	etc.
	See aiso.spc for further information.
*/

#include	<stdio.h>

#include	"Malloc.h"

static struct aiso_node *root;		/* root of tree */
#ifdef	AISO_ITER
static struct aiso_node *list;		/* start of linked list */
#endif	/* AISO_ITER */

/* the policy */
static int aiso_size = 0;
static int acc_mark = 1;

#define	add_entry()	(aiso_size++)
#define	rem_entry()	(aiso_size--)
#define	reset_access()	(acc_mark = 1)
#define	count_access()	(acc_mark <<= 1)
#define	must_rotate()	(acc_mark > aiso_size)

int
InsertAiso(AISO_TYPE v) {
	struct aiso_node *new_node;
	struct aiso_node **hook = &root;
#ifdef	AISO_ITER
	struct aiso_node **prev = &list;
#endif	/* AISO_ITER */

	/*ZZ*/new_node = (struct aiso_node *)TryMalloc(sizeof (struct aiso_node));
	if (!new_node) {
		/* avoid modifying the tree */
		return 0;
	}

	while (*hook) {
		struct aiso_node *an = *hook;

		count_access();
		if (AISO_BEFORE(v, an->an_value)) {
			/* head left */
			if (!an->an_left || !must_rotate()) {
				/* standard action */
				hook = &an->an_left;
			}
			else {
				/* change (l A r) B (C) into (l) A (r B C) */
				struct aiso_node *anl = an->an_left;

				an->an_left = anl->an_right;
				anl->an_right = an;
				*hook = anl;
				reset_access();
			}
		}
		else {
			/* head right */
			if (!an->an_right || !must_rotate()) {
				/* standard action */
				hook = &an->an_right;
			}
			else {
				/* change (A) B (l C r) into (A B l) C (r) */
				struct aiso_node *anr = an->an_right;

				an->an_right = anr->an_left;
				anr->an_left = an;
				*hook = anr;
				reset_access();
			}
#ifdef	AISO_ITER
			prev = &an->an_next;
#endif	/* AISO_ITER */
		}
	}

	new_node->an_left = 0;
	new_node->an_right = 0;
#ifdef	AISO_ITER
	new_node->an_next = *prev;
	*prev = new_node;
#endif	/* AISO_ITER */
	new_node->an_value = v;
	*hook = new_node;
	add_entry();
	return 1;
}

#ifdef	AISO_EXTR

int
ExtractAiso(AISO_TYPE *vp) {
	struct aiso_node **hook = &root;
	struct aiso_node *an;

	if (!root) return 0;

	while ((an = *hook), an->an_left) {
		/* head left */
		count_access();
		if (!must_rotate()) {
			/* standard action */
			hook = &an->an_left;
		}
		else {
			/* change (l A r) B (C) into (l) A (r B C) */
			struct aiso_node *anl = an->an_left;

			an->an_left = anl->an_right;
			anl->an_right = an;
			*hook = anl;
			reset_access();
		}
	}
	/* found the first */
	*vp = an->an_value;
	*hook = an->an_right;
#ifdef	AISO_ITER
	list = an->an_next;
#endif	/* AISO_ITER */
	Free((void *)an);
	rem_entry();
	return 1;
}

#endif	/* AISO_EXTR */

#ifdef	AISO_ITER

void
OpenIter(AisoIter *ip) {
	*ip = list;
}

int
GetAisoItem(AisoIter *ip, AISO_TYPE *vp) {
	struct aiso_node *an = *ip;

	if (!an) return 0;

	*vp = an->an_value;
	*ip = an->an_next;
	return 1;
}

void
CloseIter(AisoIter *ip) {
	*ip = 0;
}

#endif	/* AISO_ITER */

#ifdef	AISO_DEBUG	/* requires AISO_FORMAT */

static void
pr_inf(int level, char ch, struct aiso_node *an) {
	int i;

	if (!an) return;

	pr_inf(level+1, '/', an->an_right);
	for (i = 0; i < level; i++) {
		printf("     ");
	}
	printf("%c", ch);
	printf(AISO_FORMAT, an->an_value);
	printf("\n");
	pr_inf(level+1, '\\', an->an_left);
}

void
pr_tree(void) {
	pr_inf(0, '-', root);
	printf("================\n");
}

#endif	/* AISO_DEBUG */

