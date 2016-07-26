/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: newargs.c,v 2.1 2008/09/23 09:07:11 dick Exp $
*/

#include	<stdio.h>
#include	<ctype.h>

#include	"Malloc.h"
#include	"error.h"

#define	ARGS_INCR	1024

static char *
std_input(void) {
	/* in the form {name [\n|space|tab|...]}* \0 */
	char *args = 0;
	int args_free = 0;
	int args_size = 0;

	/* get all of standard input */
	int ch;
	int last_char = ' ';

	do {
		ch = getchar();
		/* omit duplicate layout */
		if (ch != EOF && isspace(ch) && isspace(last_char)) continue;

		/* add ch to args */
		if (args_free == args_size) {
			/* allocated array is full; increase its size */
			int new_size = args_size + ARGS_INCR;
			char *new_args = (char *)Realloc(
				(char *)args, sizeof (char *) * new_size
			);
			args = new_args, args_size = new_size;
		}

		/* now we are sure there is room enough */
		args[args_free++] = ch;

		last_char = ch;
	} while (ch != EOF);
	/* now make sure the result conforms to the form above  */
	if (args_free == 1 || isspace(args[args_free-2])) {
		/* OK */
	}
	else	fatal("standard input not terminated with newline");
	args[args_free-1] = '\0';	/* overwrites the EOF */

	return args;
}

static int
n_names(const char *s) {
	int cnt = 0;

	while (*s) {
		if (isspace(*s)) {
			cnt++;
		}
		s++;
	}
	return cnt;
}

static const char **
new_argv(int argc, char *args) {
	/* converts the layout in args to \0, and constructs an argv list */
	const char **argv = (const char **)Malloc((argc+1) * sizeof (char *));
	char *p = args;
	char last_char = ' ';

	argc = 0;
	while (*p) {
		if (isspace(last_char)) {
			/* here a new name starts */
			argv[argc++] = p;
		}
		last_char = *p;
		if (isspace(*p)) {
			*p = '\0';
		}
		p++;
	}
	argv[argc] = 0;

	return argv;
}

void
get_new_args(int *argcp, const char **argvp[]) {
	char *args = std_input();
	int argc = n_names(args);
	const char **argv = new_argv(argc, args);

	*argcp = argc, *argvp = argv;
}
