/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: options.h,v 1.5 2008/09/23 09:07:11 dick Exp $
*/

/*	Setting and consulting command line options
*/

struct option {
	char op_char;		/* char as in call */
	char *op_text;		/* elucidating text */
	char op_indicator;	/* type indicator, N = int, F = file name */
	const char **op_stringp;/* string value to be picked up */
};

extern int option_set(int ch);
extern int do_options(
	const char *progname, const struct option *optlist,
	int argc, const char *argv[]
);
