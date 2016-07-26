/*	This file is part of the auxiliaries library.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: ForEachFile.c,v 1.14 2012-05-04 10:56:47 Gebruiker Exp $
*/

#include	<string.h>
#include	<sys/types.h>
#include	<sys/stat.h>
#include	<dirent.h>
#include	<errno.h>

#include	"ForEachFile.h"

#define	MAX_NL		256		/* maximum file name length */

struct ino_link {			/* to detect loop in file system */
	struct ino_link *next;
	long il_ino;
	long il_device;
};

static void do_FEF(
	Fchar *fn,
	void (*proc)(const Fchar *, const char *, const struct stat *),
	int dev,
	struct ino_link *inop,
	Fchar separator,
	int max_depth
);

static Fchar
get_separator(const Fchar *fn) {
#ifndef	MSDOS
	(void)(fn);			/* use fn */
	return '/';
#else
	/* under MSDOS, conform to user's use, or use '\' */
	Fchar sep = 0;

	while (*fn) {
		if (*fn == '/' || *fn == '\\') {
			if (sep == 0) {
				sep = *fn;
			}
			else
			if (sep != *fn) return 0;	/* bad mixed use */
		}
		fn++;
	}
	return (sep ? sep : '\\');
#endif
}

static void
clean_name(Fchar *fn, Fchar sep) {
	Fchar *f1 = fn;
	Fchar *f2 = fn;

	/* remove multiple separators */
	while (*f1) {
		if (*f1 == sep && *(f1+1) == sep) {
			f1++;
		} else {
			*f2++ = *f1++;
		}
	}
	*f2 = '\0';

	/* remove a trailing separator */
	if (f2-1 > fn && *(f2-1) == sep) {
		*(f2-1) = '\0';
	}
}

static void
do_ForEachFile(
	const Fchar *fn,
	void (*proc)(const Fchar *, const char *, const struct stat *),
	int max_depth
) {
	Fchar fname[MAX_NL];
	Fchar separator;

	Fnamecpy(fname, (!fn || !*fn) ? str2Fname(".") : fn);
	separator = get_separator(fname);
	if (!separator) {
		(*proc)(fname, "both / and \\ used as separators", 0);
		return;
	}

	clean_name(fname, separator);
	do_FEF(fname, proc, -1, (struct ino_link *)0, separator, max_depth);
}

static int in_ino_list(const struct ino_link *inop, const struct stat *st);
static void link_ino_list(
	struct ino_link *inop,
	struct ino_link *ninop,
	const struct stat *st
);

void
ForEachFile(
	const Fchar *fn,
	void (*proc)(const Fchar *, const char *, const struct stat *)
) {
	do_ForEachFile(fn, proc, -1);	/* infinitely deep */
}

void
ForEachLocalFile(
	const Fchar *fn,
	void (*proc)(const Fchar *, const char *, const struct stat *)
) {
	do_ForEachFile(fn, proc, 1);	/* one level deep */
}

#ifdef	S_IFLNK				/* system with symbolic links */
#define	LSTAT	lstat
#else	/* S_IFLNK */
#define	LSTAT	Stat
#endif	/* S_IFLNK */

static void
do_FEF(
	Fchar *fn,
	void (*proc)(const Fchar *, const char *, const struct stat *),
	int dev,
	struct ino_link *inop,
	Fchar separator,
	int max_depth
) {
	struct stat fs;
	Dir_t *dir;

	if (LSTAT(fn, &fs) < 0) {
		(*proc)(fn, strerror(errno), &fs);
		return;
	}

	/* report on file fn */
	(*proc)(fn, (char*)0, &fs);

	if (max_depth == 0) return;
	if ((fs.st_mode & S_IFMT) != S_IFDIR) return;

#ifdef	S_IFLNK
	/* don't follow links */
	if ((fs.st_mode & S_IFMT) == S_IFLNK) return;
#endif

	/* treat directory */
	if (dev < 0) {
		/* no device known yet */
		dev = fs.st_dev;
	}
	if (fs.st_dev != dev) {
		return;
	}

	dir = Opendir(fn);
	if (dir == 0) {
		(*proc)(fn, "directory not readable", &fs);
	}
	else {
		/* scan new directory */
		int fnl = Fnamelen(fn);
		Dirent_t *dent;
		struct ino_link ino;

		/* worry about loops in the file system */
		if (in_ino_list(inop, &fs)) {
			(*proc)(fn, "loop in file system", &fs);
			Closedir(dir);
			return;
		}
		link_ino_list(inop, &ino, &fs);

		/* shape up the directory name */
		if (fn[fnl-1] != separator) {
			/* append separator */
			fn[fnl++] = separator;
			fn[fnl] = '\0';
		}

		/* descend */
		while ((dent = Readdir(dir)) != (Dirent_t *)0) {
			if (	Fnamecmp(dent->d_name, str2Fname(".")) == 0
				||	Fnamecmp(dent->d_name, str2Fname("..")) == 0
			)	continue;

			if (Fnamecmp(dent->d_name, str2Fname("")) == 0) {
				(*proc)(fn,
					"directory contains empty file name",
					&fs
				);
				continue;
			}

			/* append name */
			Fnamecat(fn, dent->d_name);
			do_FEF(fn, proc, dev, &ino, separator, max_depth-1);
			/* remove name again*/
			fn[fnl] = '\0';
		}
		Closedir(dir);
	}
}

static int
in_ino_list(const struct ino_link *inop, const struct stat *st) {
	while (inop) {
#ifdef	UNIX
		if (	inop->il_ino == st->st_ino
		&&	inop->il_device == st->st_dev
		)	return 1;
#else
#ifdef	lint
		st = st;
#endif
#endif
		inop = inop->next;
	}
	return 0;
}

static void
link_ino_list(
    struct ino_link *inop, struct ino_link *ninop, const struct stat *st
) {
	ninop->next = inop;
	ninop->il_ino = st->st_ino;
	ninop->il_device = st->st_dev;
}
