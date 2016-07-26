/*	This file is part of the debugging module DEBUG.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: debug.c,v 1.5 2012-01-25 21:43:05 Gebruiker Exp $
*/

#include	<stdlib.h>
#include	<unistd.h>
#include	<ctype.h>

#include	"debug.h"

#ifdef		DEBUG

static void
wr_char(char ch) {
	write(2, &ch, 1);
}

static void
wr_num(int b,int v) {
	if (v >= b) {
		wr_num(b, v/b);
	}
	wr_char("0123456789ABCDEF"[v%b]);
}

static void
wr_str(const char *s) {
	while (*s) {
		wr_char(*s++);
	}
}

void
wr_info(const char *s, int b, int v) {
	/* print the string */
	if (s) {
		int cnt = 0;

		while (*s) {
			int ch = *s++ &0377;

			/* cut short a possibly corrupted string */
			if (cnt++ > 50) {
				wr_str("...");
				break;
			}

			/* put not thy faith in chars, signed or unsigned */
			if (isprint(ch)) {
				wr_char(ch);
			}
			else {
				switch (ch) {
				case '\n': wr_str("\\n"); break;
				case '\t': wr_str("\\t"); break;
				case '\r': wr_str("\\r"); break;
				case '\f': wr_str("\\f"); break;
				default:
					wr_char('\\');
					wr_char(ch / 0100 % 010 + '0');
					wr_char(ch / 010 % 010 + '0');
					wr_char(ch / 01 % 010 + '0');
					break;
				}
			}
		}
	}
	else {
		wr_str("<wr_info: NO STRING>");
	}

	/* print the value */
	if (b != 0) {
		wr_char(' ');
		if (v < 0) {
			wr_char('-');
			v = -v;
		}
		switch (b) {
		case 8:
			wr_char('0');
			wr_num(b, v);
			break;
		default:
			wr_num(10, v);
			break;
		case 16:
			wr_char('#');
			wr_num(b, v);
			break;
		case 128:
			wr_char(v);
			break;
		}
	}

	wr_char('\n');
}

#else

/*ARGSUSED*/
void
wr_info(const char *s, int b, int v) {
}

#endif
