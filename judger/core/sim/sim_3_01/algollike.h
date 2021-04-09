/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: algollike.h,v 1.7 2013-04-28 16:30:40 dick Exp $
*/

/*	The class Algollike is a subclass of Language.  It implements
	the routines
	    void Init_Algol_Language()
	    int May_Be_Start_Of_Algol_Run() and
	    size_t Best_Algol_Run_Size()
	for ALGOL-like languages, languages in which it is meaningful and
	useful to isolate function bodies. These routines can be used in
	Init_Language(), May_Be_Start_Of_Run(), and Best_Run_Size(), required
	by language.h .

	It requires the user to define four token sets, represented as
	Token set[] and terminated by No_Token:
	    Token Non_Finals[]		tokens that may not end a chunk
	    Token Non_Initials[]	tokens that may not start a chunk
	    Token Openers[]		openers of parentheses that must
					balance in functions
	    Token Closers[]		the corresponding closers,
					in the same order
	These must be passed to Init_Algol_Language(), in the above order.
*/

extern void Init_Algol_Language(
    const Token Non_Finals[], const Token Non_Initials[],
    const Token Openers[], const Token Closers[]
); /* note the order of the arguments: Non_Finals ~ Openers, etc. */
extern int May_Be_Start_Of_Algol_Run(Token ch);
extern size_t Best_Algol_Run_Size(const Token *str, size_t size);
