/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: options.h,v 1.8 2012-05-13 09:05:49 Gebruiker Exp $
*/

/*	Setting and consulting command line options
*/

struct option {
	char op_char;		/* char as in call */
	char *op_text;		/* explanatory  text */
	char op_indicator;	/* type indicator, N = int, F = file name */
	const char **op_stringp;/* string value to be picked up */
};

extern void set_option(char ch);
extern int is_set_option(int ch);
extern int do_options(
	const char *progname, const struct option *optlist,
	int argc, const char *argv[]
);
