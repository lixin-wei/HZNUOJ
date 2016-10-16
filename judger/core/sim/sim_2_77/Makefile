#	This file is part of the software similarity tester SIM.
#	Written by Dick Grune, Vrije Universiteit, Amsterdam.
#	$Id: Makefile,v 2.58 2014-01-27 20:50:50 Gebruiker Exp $
#


#	E N T R Y   P O I N T S

help:
	@echo  'Entry points:'
	@echo  'test:           compile sim_c and run a simple test'
	@echo  ''
	@echo  'binaries:       create all binaries'
	@echo  'exes:           create executables in MSDOS'
	@echo  'install:        install all binaries'
	@echo  ''
	@echo  'lint:           lint sim sources'
	@echo  'simsim:         run sim_c on the sim sources'
	@echo  ''
	@echo  'fresh:          remove created files'

#
# When you modify any of the following macros, do 'make clean'
#

# System dependencies
#	=============== including ../lib/sysidf.mk here
#	This file is part of the auxiliary libraries.
#	Written by Dick Grune, dick@dickgrune.com
#	$Id: sysidf.mk,v 1.16 2014-01-25 21:29:34 Gebruiker Exp $
#

################################################################
# For UNIX-like systems

SYSTEM =	UNIX
SUBSYSTEM =	SOLARIS

# Locations
DIR =		/home/dick
BINDIR =	$(DIR)/bin.`$(DIR)/bin/arch`
MAN1DIR =	$(DIR)/man/man1

# Commands
COPY =		cp -p
EXE =		#
LEX =		flex
LN =		ln
ZIP =		zip -o

################################################################
# For MSDOS + MinGW

#SYSTEM =	MSDOS
#SUBSYSTEM =	MinGW

# Locations
#DIR =		C:/BIN
#BINDIR =	C:/BIN
#MAN1DIR =	C:/BIN

# Commands (cp required, since xcopy cannot handle forward slashes)
COPY =		cp -p
EXE =		.exe
LEX =		flex
LN =		ln
ZIP =		zip -o

################################################################
# General, compiling:
CC =		gcc -D$(SYSTEM) -D$(SUBSYSTEM)
LINT =		lint -ansi -D$(SYSTEM) -D$(SUBSYSTEM)
LINTFLAGS =	-xh

# General, manual:
.SUFFIXES:	.1 .3 .pdf

.1.pdf:
		man2pdf $<

.3.pdf:
		man2pdf $<
#	=============== end of ../lib/sysidf.mk

# Compiler Options
MEMORY =	-DMEMLEAK -DMEMCLOBBER
CFLAGS =	$(MEMORY) -O4
LIBFLAGS =	#
LINTFLAGS =	$(MEMORY) -h# -X

# Debugging
CFLAGS +=	-DDEBUG
DEBUG_C =	debug.c
DEBUG_O =	debug.o
DEBUG_H =	debug.h

#	T E S T   P A R A M E T E R S

# percentage test
TEST_LANG =	c
TEST_OPT =	-p
TEST_INP =	*.l

# text test
TEST_LANG =	text
TEST_OPT =	-r 5
TEST_INP =	test_seplet

# Rumen Stevanov test
TEST_LANG =	text
TEST_OPT =	-p
TEST_INP =	Rumen_Stefanov/new/*.txt

# Kuhl test 1
TEST_LANG =	c
TEST_OPT =	-p
TEST_INP =	Kuhl/simc1.c Kuhl/simc2.c

# Kuhl test 2
TEST_LANG =	c
TEST_OPT =	-p
TEST_INP =	Kuhl/simc2.c Kuhl/simc1.c

# -i option test
TEST_LANG =	c
TEST_OPT =	-f -r 20 -R -i <option-i.inp
TEST_INP =	#

# regular test
TEST_LANG =	c
TEST_OPT =	-r24
TEST_INP =	pass3.c


#	I N T R O D U C T I O N

#	Each module (set of programs that together perform some function)
#	has the following sets of files defined for it:
#		_FLS	all files of that module, for, e.g.,
#			sharring, inventory, etc.
#		_SRC	the source files, from which other files derive
#		_CFS	the C-files, from which the object files derive
#		_OBJ	object files
#		_GRB	garbage files produced by compiling the module
#
#	(This is a feeble attempt at software-engineering a Makefile.)
#

test:		sim.res stream.res percentages.res	# three simple tests


#	B I N A R I E S

BINARIES =	sim_c$(EXE) sim_java$(EXE) sim_pasc$(EXE) sim_m2$(EXE) \
	 	sim_lisp$(EXE) sim_mira$(EXE) sim_text$(EXE)

binaries:	$(BINARIES)

EXES =		sim_c.exe sim_java.exe sim_pasc.exe sim_m2.exe \
		sim_lisp.exe sim_mira.exe sim_text.exe
exes:		$(EXES)


#	A U X I L I A R Y   M O D U L E S

# Common modules:
COM_CFS =	token.c lex.c stream.c text.c tokenarray.c error.c $(DEBUG_C) \
		ForEachFile.c fname.c Malloc.c any_int.c
COM_OBJ =	token.o lex.o stream.o text.o tokenarray.o error.o $(DEBUG_O) \
		ForEachFile.o fname.o Malloc.o any_int.o
COM_SRC =	token.h lex.h stream.h text.h tokenarray.h error.h $(DEBUG_H) \
		ForEachFile.h fname.h Malloc.h any_int.h \
		lang.h language.h \
		sortlist.spc sortlist.bdy system.par $(COM_CFS)
COM_FLS =	$(COM_SRC)

# C files for the abstract modules:
ABS_CFS =	lang.c language.c

# The idf module:
IDF_CFS =	idf.c
IDF_OBJ =	idf.o
IDF_SRC =	idf.h $(IDF_CFS)
IDF_FLS =	$(IDF_SRC)

# The runs package:
RUNS_CFS =	runs.c percentages.c
RUNS_OBJ =	runs.o percentages.o
RUNS_SRC =	runs.h percentages.h aiso.spc aiso.bdy $(RUNS_CFS)
RUNS_FLS =	$(RUNS_SRC)

# The main program:
MAIN_CFS =	sim.c options.c newargs.c hash.c compare.c add_run.c \
		pass1.c pass2.c pass3.c
MAIN_OBJ =	sim.o options.o newargs.o hash.o compare.o add_run.o \
		pass1.o pass2.o pass3.o
MAIN_SRC =	sim.h options.h newargs.h hash.h compare.h add_run.h \
		pass1.h pass2.h pass3.h \
		debug.par settings.par $(MAIN_CFS)
MAIN_FLS =	$(MAIN_SRC)

# The similarity tester without the language part:
SIM_CFS =	$(COM_CFS) $(IDF_CFS) $(RUNS_CFS) $(MAIN_CFS)
SIM_OBJ =	$(COM_OBJ) $(IDF_OBJ) $(RUNS_OBJ) $(MAIN_OBJ)
SIM_SRC =	$(COM_SRC) $(IDF_SRC) $(RUNS_SRC) $(MAIN_SRC)
SIM_FLS =	$(COM_FLS) $(IDF_FLS) $(RUNS_FLS) $(MAIN_FLS)


#	L A N G U A G E S

# The algollike module:
ALG_CFS =	algollike.c
ALG_OBJ =	algollike.o
ALG_SRC =	algollike.h $(ALG_CFS)
ALG_FLS =	$(ALG_SRC)

# The C Language module:					# C
CLANG_CFS =	clang.c
CLANG_OBJ =	clang.o
CLANG_SRC =	clang.l
CLANG_FLS =	$(CLANG_SRC)

clang.c:	clang.l
		$(LEX) -t clang.l >$@

SIM_C_CFS =	$(SIM_CFS) $(ALG_CFS) $(CLANG_CFS)
SIM_C_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(CLANG_OBJ)

sim_c$(EXE):	$(SIM_C_OBJ)
		$(CC) $(SIM_C_OBJ) -o $@

SIM_GRB +=	clang.c sim_c

$(BINDIR)/sim_c$(EXE):	sim_c$(EXE)
		$(COPY) sim_c$(EXE) $@

# The Java Language module:					# Java
JAVALANG_CFS =	javalang.c
JAVALANG_OBJ =	javalang.o
JAVALANG_SRC =	javalang.l
JAVALANG_FLS =	$(JAVALANG_SRC)

javalang.c:	javalang.l
		$(LEX) -t javalang.l >$@

SIM_JAVA_CFS =	$(SIM_CFS) $(ALG_CFS) $(JAVALANG_CFS)
SIM_JAVA_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(JAVALANG_OBJ)

sim_java$(EXE):	$(SIM_JAVA_OBJ)
		$(CC) $(SIM_JAVA_OBJ) -o $@

SIM_GRB +=	javalang.c sim_java

$(BINDIR)/sim_java$(EXE):	sim_java$(EXE)
		$(COPY) sim_java$(EXE) $@

# The Pascal Language module:					# Pascal
PASCLANG_CFS =	pascallang.c
PASCLANG_OBJ =	pascallang.o
PASCLANG_SRC =	pascallang.l
PASCLANG_FLS =	$(PASCLANG_SRC)

pascallang.c:	pascallang.l
		$(LEX) -t pascallang.l >pascallang.c

SIM_PASC_CFS =	$(SIM_CFS) $(ALG_CFS) $(PASCLANG_CFS)
SIM_PASC_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(PASCLANG_OBJ)

sim_pasc$(EXE):	$(SIM_PASC_OBJ)
		$(CC) $(SIM_PASC_OBJ) -o $@

SIM_GRB +=	pascallang.c sim_pasc

$(BINDIR)/sim_pasc$(EXE):	sim_pasc$(EXE)
		$(COPY) sim_pasc$(EXE) $@

# The Modula-2 Language module:					# Modula-2
M2LANG_CFS =	m2lang.c
M2LANG_OBJ =	m2lang.o
M2LANG_SRC =	m2lang.l
M2LANG_FLS =	$(M2LANG_SRC)

m2lang.c:	m2lang.l
		$(LEX) -t m2lang.l >$@

SIM_M2_CFS =	$(SIM_CFS) $(ALG_CFS) $(M2LANG_CFS)
SIM_M2_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(M2LANG_OBJ)

sim_m2$(EXE):	$(SIM_M2_OBJ)
		$(CC) $(SIM_M2_OBJ) -o $@

SIM_GRB +=	m2lang.c sim_m2

$(BINDIR)/sim_m2$(EXE):	sim_m2$(EXE)
		$(COPY) sim_m2$(EXE) $@

# The Lisp Language module:					# Lisp
LISPLANG_CFS =	lisplang.c
LISPLANG_OBJ =	lisplang.o
LISPLANG_SRC =	lisplang.l
LISPLANG_FLS =	$(LISPLANG_SRC)

lisplang.c:	lisplang.l
		$(LEX) -t lisplang.l >$@

SIM_LISP_CFS =	$(SIM_CFS) $(ALG_CFS) $(LISPLANG_CFS)
SIM_LISP_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(LISPLANG_OBJ)

sim_lisp$(EXE):	$(SIM_LISP_OBJ)
		$(CC) $(SIM_LISP_OBJ) -o $@

SIM_GRB +=	lisplang.c sim_lisp

$(BINDIR)/sim_lisp$(EXE):	sim_lisp$(EXE)
		$(COPY) sim_lisp$(EXE) $@

# The Miranda Language module:					# Miranda
MIRALANG_CFS =	miralang.c
MIRALANG_OBJ =	miralang.o
MIRALANG_SRC =	miralang.l
MIRALANG_FLS =	$(MIRALANG_SRC)

miralang.c:	miralang.l
		$(LEX) -t miralang.l >$@

SIM_MIRA_CFS =	$(SIM_CFS) $(ALG_CFS) $(MIRALANG_CFS)
SIM_MIRA_OBJ =	$(SIM_OBJ) $(ALG_OBJ) $(MIRALANG_OBJ)

sim_mira$(EXE):	$(SIM_MIRA_OBJ)
		$(CC) $(SIM_MIRA_OBJ) -o $@

SIM_GRB +=	miralang.c sim_mira

$(BINDIR)/sim_mira$(EXE):	sim_mira$(EXE)
		$(COPY) sim_mira$(EXE) $@

# The Text module:						# Text
TEXTLANG_CFS =	textlang.c
TEXTLANG_OBJ =	textlang.o
TEXTLANG_SRC =	textlang.l
TEXTLANG_FLS =	$(TEXTLANG_SRC)

textlang.c:	textlang.l
		$(LEX) -t textlang.l >$@

SIM_TEXT_CFS =	$(SIM_CFS) $(TEXTLANG_CFS)
SIM_TEXT_OBJ =	$(SIM_OBJ) $(TEXTLANG_OBJ)

sim_text$(EXE):	$(SIM_TEXT_OBJ)
		$(CC) $(SIM_TEXT_OBJ) -o $@

SIM_GRB +=	textlang.c sim_text

$(BINDIR)/sim_text$(EXE):	sim_text$(EXE)
		$(COPY) sim_text$(EXE) $@















#	T E S T S

# Some simple tests:
sim.res:	sim_$(TEST_LANG)$(EXE) $(TEST_INP)
		./sim_$(TEST_LANG)$(EXE) $(TEST_OPT) $(TEST_INP)

stream.res:	sim_$(TEST_LANG)$(EXE) $(TEST_INP)
		./sim_$(TEST_LANG)$(EXE) -- $(TEST_OPT) $(TEST_INP) >$@
		wc $@ $(TEST_INP)

percentages.res:sim_$(TEST_LANG)$(EXE) $(TEST_INP)
		./sim_$(TEST_LANG)$(EXE) -p $(TEST_OPT) $(TEST_INP)

TEST_GRB =	stream.res

# More simple tests, using the C version only:
simsim:		sim_c$(EXE) $(SIM_CFS) $(ALG_CFS)
		./sim_c$(EXE) -fr 20 $(SIM_CFS) $(ALG_CFS)

# Lint
lint:		$(SIM_SRC) $(ALG_SRC) $(ABS_CFS)
		$(LINT) $(LINTFLAGS) $(SIM_CFS) $(ALG_CFS) $(ABS_CFS)


#	O T H E R   E N T R I E S

# Sets of files: general, modules, main programs, languages
CFS =		$(SIM_CFS) $(ALG_CFS) \
		$(CLANG_CFS) $(JAVALANG_CFS) $(PASCLANG_CFS) $(M2LANG_CFS) \
		$(LISPLANG_CFS) $(MIRALANG_CFS) $(TEXTLANG_CFS)
OBJ =		$(SIM_OBJ) $(ALG_OBJ) \
		$(CLANG_OBJ) $(JAVALANG_OBJ) $(PASCLANG_OBJ) $(M2LANG_OBJ) \
		$(LISPLANG_OBJ) $(MIRALANG_OBJ) $(TEXTLANG_OBJ)
SRC =		$(SIM_SRC) $(ALG_SRC) \
		$(CLANG_SRC) $(JAVALANG_SRC) $(PASCLANG_SRC) $(M2LANG_SRC) \
		$(LISPLANG_SRC) $(MIRALANG_SRC) $(TEXTLANG_SRC)
FLS =		$(SIM_FLS) $(ALG_FLS) \
		$(CLANG_FLS) $(JAVALANG_FLS) $(PASCLANG_FLS) $(M2LANG_FLS) \
		$(LISPLANG_FLS) $(MIRALANG_FLS) $(TEXTLANG_FLS) \
		sysidf.mk sysidf.msdos sysidf.unix
DOC =		README sim.1 sim.txt sim.html ChangeLog Answers TechnReport

# Installation
install_all:	install			# just a synonym
install:	$(MAN1DIR)/sim.1 \
		$(BINDIR)/sim_c$(EXE) \
		$(BINDIR)/sim_java$(EXE) \
		$(BINDIR)/sim_pasc$(EXE) \
		$(BINDIR)/sim_m2$(EXE) \
		$(BINDIR)/sim_lisp$(EXE) \
		$(BINDIR)/sim_mira$(EXE) \
		$(BINDIR)/sim_text$(EXE)

$(MAN1DIR)/sim.1:	sim.1
		$(COPY) sim.1 $@


# Clean-up
.PHONY:		clean fresh
clean:
		-rm -f *.o
		-rm -f $(SIM_GRB)
		-rm -f $(TEST_GRB)
		-rm -f a.out a.exe sim.txt core mon.out

fresh:		clean
		-rm -f *.exe

#	D E P E N D E N C I E S

# DO NOT DELETE THIS LINE -- make depend depends on it.
ForEachFile.o: ForEachFile.c ForEachFile.h fname.h
Malloc.o: Malloc.c any_int.h Malloc.h
add_run.o: add_run.c sim.h debug.par text.h runs.h aiso.spc percentages.h \
 Malloc.h options.h error.h add_run.h
algollike.o: algollike.c options.h error.h token.h algollike.h
clang.o: clang.c options.h token.h language.h algollike.h idf.h lex.h \
 lang.h
compare.o: compare.c sim.h text.h token.h tokenarray.h hash.h language.h \
 options.h add_run.h compare.h debug.par
count_sim_dup.o: count_sim_dup.c
debug.o: debug.c debug.h
error.o: error.c sim.h error.h
fname.o: fname.c fname.h
hash.o: hash.c system.par debug.par sim.h text.h Malloc.h error.h \
 any_int.h token.h language.h tokenarray.h options.h hash.h
idf.o: idf.c system.par token.h idf.h
javalang.o: javalang.c options.h token.h language.h algollike.h idf.h \
 lex.h lang.h
lang.o: lang.c token.h lang.h
language.o: language.c token.h language.h
lex.o: lex.c lex.h
lisplang.o: lisplang.c token.h language.h algollike.h lex.h lang.h idf.h
m2lang.o: m2lang.c options.h token.h language.h algollike.h idf.h lex.h \
 lang.h
miralang.o: miralang.c token.h language.h algollike.h lex.h lang.h idf.h
newargs.o: newargs.c sim.h ForEachFile.h fname.h Malloc.h error.h \
 newargs.h
options.o: options.c options.h
pascallang.o: pascallang.c options.h token.h language.h algollike.h idf.h \
 lex.h lang.h
pass1.o: pass1.c debug.par sim.h text.h token.h tokenarray.h lang.h \
 error.h options.h pass1.h
pass2.o: pass2.c debug.par sim.h token.h text.h lang.h pass2.h \
 sortlist.bdy
pass3.o: pass3.c system.par debug.par sim.h text.h token.h runs.h \
 aiso.spc Malloc.h error.h options.h pass3.h percentages.h
percentages.o: percentages.c sim.h text.h runs.h aiso.spc options.h \
 Malloc.h error.h percentages.h sortlist.bdy
runs.o: runs.c sim.h text.h runs.h aiso.spc debug.par aiso.bdy Malloc.h
sim.o: sim.c system.par settings.par sim.h options.h newargs.h token.h \
 language.h error.h text.h runs.h aiso.spc hash.h compare.h pass1.h \
 pass2.h pass3.h percentages.h stream.h lang.h Malloc.h any_int.h
stream.o: stream.c system.par sim.h options.h token.h lang.h stream.h
t.o: t.c
text.o: text.c debug.par sim.h token.h stream.h lang.h Malloc.h options.h \
 error.h text.h
textlang.o: textlang.c sim.h token.h idf.h lex.h lang.h language.h
token.o: token.c token.h
tokenarray.o: tokenarray.c error.h Malloc.h token.h lang.h tokenarray.h
any_int.o: any_int.c any_int.h
