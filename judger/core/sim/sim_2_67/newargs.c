/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: newargs.c,v 2.7 2012-06-05 09:58:53 Gebruiker Exp $
*/

#include	<stdio.h>

#include	"ForEachFile.h"
#include	"Malloc.h"
#include	"error.h"
#include	"newargs.h"

#define	ARGS_INCR	1024
static char *args = 0;
static int args_free = 0;
static int args_size = 0;

static void
add_to_args(char ch) {
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
}

static char *
std_input(void) {
	/* in the form (name \n)* \0 */

	/* get all of standard input */
	int ch;
	int last_char = '\n';

	while (ch = getchar(), ch != EOF) {
		/* omit duplicate layout (= empty name) */
		if (last_char == '\n' && ch == '\n') continue;

		add_to_args(ch);
		last_char = ch;
	}
	add_to_args('\0');

	/* make sure the result conforms to the form above  */
	if (args[args_free-2] != '\n')
		fatal("standard input not terminated with newline");

	return args;
}

static int
n_names(const char *s) {
	int cnt = 0;

	while (*s) {
		if (*s == '\n') {
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
	char last_char = '\n';

	argc = 0;
	while (*p) {
		if (last_char == '\n') {
			/* here a new name starts */
			argv[argc++] = p;
		}
		last_char = *p;
		if (*p == '\n') {
			*p = '\0';
		}
		p++;
	}
	argv[argc] = 0;

	return argv;
}

void
get_new_std_input_args(int *argcp, const char **argvp[]) {
	char *n_args = std_input();
	int argc = n_names(n_args);
	const char **argv = new_argv(argc, n_args);

	*argcp = argc, *argvp = argv;
}

static void
register_file(const Fchar *fn, const char *msg, const struct stat *fs) {
	if (msg) {
		fprintf(stderr, "could not handle file %s: %s\n", fn, msg);
		return;
	}

	if (	/* it is a non-empty regular file */
	    S_ISREG(fs->st_mode) && fs->st_size > 0
	) {
		while (*fn) {
			add_to_args(*fn++);
		}
		add_to_args('\n');
	}
}

static char *
recursive_args(int argc, const char *argv[]) {
	if (argc == 0) {
		ForEachFile(str2Fname("."), register_file);
	}
	else {
		int i;

		for (i = 0; i < argc; i++) {
			ForEachFile(str2Fname(argv[i]), register_file);
		}
	}
	add_to_args('\0');

	return args;
}

void
get_new_recursive_args(int *argcp, const char **argvp[]) {
	char *n_args = recursive_args(*argcp, *argvp);
	int argc = n_names(n_args);
	const char **argv = new_argv(argc, n_args);

	*argcp = argc, *argvp = argv;
}
