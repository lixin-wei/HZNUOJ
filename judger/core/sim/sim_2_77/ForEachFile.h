/*	This file is part of the auxiliaries library.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: ForEachFile.h,v 1.8 2013-05-12 09:58:34 Gebruiker Exp $
*/

#ifndef	_FOREACHFILE_H_
#define _FOREACHFILE_H_

#include	"fname.h"
#include	<sys/types.h>
#include	<sys/stat.h>

/****
* ForEachFile(const Fchar *fn, void (*proc)(...):
  each file reachable from fn is passed to the procedure proc, which is
  declared as:

    void proc(const Fchar *fn, const char *msg, const struct stat *fs):
	the file fn is reached; if msg != NULL, an error prevails the text of
	which is *msg; otherwise fs points to the stat buffer for fn.

* ForEachLocalFile() restricts itself to the directory fn and its local
  contents.

* MAX_FILE_NAME_LENGTH is the maximum length of the file name fn, including
  directories.
****/

/* Public entries */
#define	MAX_FILE_NAME_LENGTH	1024		/* maximum file name length */

extern void ForEachFile(
	const Fchar *fn,
	void (*proc)(const Fchar *fn, const char *msg, const struct stat *fs)
);
extern void ForEachLocalFile(
	const Fchar *fn,
	void (*proc)(const Fchar *fn, const char *msg, const struct stat *fs)
);

#endif	/* _FOREACHFILE_H_ */
