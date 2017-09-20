/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: error.c,v 2.6 2012-06-05 09:58:52 Gebruiker Exp $
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"sim.h"
#include	"error.h"

void
fatal(const char *msg) {
#ifdef	lint
	/* prevent non-use messages */
	min_run_string = 0;
	threshold_string = 0;
#endif
	fprintf(stderr, "%s: %s\n", progname, msg);
	exit(1);
}
