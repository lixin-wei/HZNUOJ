/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: options.c,v 1.5 2008/09/23 09:07:11 dick Exp $
*/

#include	<stdio.h>
#include	<stdlib.h>

#include	"options.h"

static char options[128];

static void bad_option(
	const char *progname, const struct option *optlist, char *msg, int c
);
static int opt_value(
	const char *progname, const struct option *op,
	const char *arg, const char *argv[]
);

static int do_arg(
	const char *progname, const struct option *optlist,
	const char *arg, const char *argv[]
);

int
do_options(
	const char *progname, const struct option *optlist,
	int argc, const char *argv[]
) {
	int skips = 0;

	while (argc > 0 && argv[0][0] == '-' && argv[0][1] != '\0') {
		int consumed = do_arg(progname, optlist, &argv[0][1], argv);

		argc -= consumed, argv += consumed, skips += consumed;
	}

	return skips;
}

int
option_set(int ch) {
	return options[ch];
}

static int
do_arg(
	const char *progname, const struct option *optlist,
	const char *arg, const char *argv[]
) {
	int consumed = 0;

	while (*arg) {
		/* treat argument character */
		char opc = *arg++;
		const struct option *op;

		for (op = optlist; op->op_char; op++) {
			/* for every allowed option */
			if (opc == op->op_char) {
				options[(int)opc]++;
				if (op->op_indicator != ' ') {
					consumed = opt_value(
						progname, op, arg, argv
					);
				}
				break;
			}
		}
		if (!op->op_char) {
			bad_option(progname, optlist,
				"*option -%c unknown", opc
			);
			/*NOTREACHED*/
		}
		if (consumed) break;
	}
	if (!consumed) {
		consumed = 1;
	}
	
	return consumed;
}

static int
opt_value(
	const char *progname, const struct option *op,
	const char *arg, const char *argv[]
) {
	/* locate the option value */
	if (*arg) {
		/* argument is continuation of option */
		*op->op_stringp = arg;
		return 1;
	}
	else
	if (argv[1])  {
		/* argument follows option */
		*op->op_stringp = argv[1];
		return 2;
	}
	else {
		bad_option(progname, (struct option *)0,
			" option -%c requires another argument",
			op->op_char
		);
		/*NOTREACHED*/
	}

}

static void
bad_option(
	const char *progname, const struct option *optlist, char *msg, int c
) {
	fprintf(stderr, "%s: ", progname);
	fprintf(stderr, &msg[1], c);
	fprintf(stderr, "\n");

	if (msg[0] != ' ') {
		const struct option *op;

		fprintf(stderr, "Possible options are:\n");
		for (op = optlist; op->op_char; op++) {
			fprintf(stderr, "\t-%c%c\t%s\n",
				op->op_char, op->op_indicator, op->op_text
			);
		}
	}
	exit(1);
}
