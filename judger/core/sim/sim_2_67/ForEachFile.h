/*	This file is part of the auxiliaries library.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: ForEachFile.h,v 1.6 2012-05-04 10:56:47 Gebruiker Exp $
*/

#include	"fname.h"
#include	<sys/types.h>
#include	<sys/stat.h>

extern void ForEachFile(
	const Fchar *fn,
	void (*proc)(const Fchar *fn, const char *msg, const struct stat *fs)
);
extern void ForEachLocalFile(
	const Fchar *fn,
	void (*proc)(const Fchar *fn, const char *msg, const struct stat *fs)
);
/*
	Each file reachable from fn is passed to the procedure proc, which
	is declared as:

	void proc(const Fchar *fn, const char *msg, const struct stat *fs)

	The file fn is reached; if msg != NULL, an error prevails
	the text of which is *msg; otherwise fs points to the
	stat buffer for fn.

	ForEachLocalFile() restricts itself to the directory fn and its
	local contents.
*/
