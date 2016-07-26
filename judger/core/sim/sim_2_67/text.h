/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: text.h,v 1.4 2012-06-05 09:58:55 Gebruiker Exp $
*/

/*	Implements the access to the lexical scanner.
	Additionally, the module tries to save newline information,
	anticipating a second scan which is interested in this
	information only.
*/

struct text {
	const char *tx_fname;	/* the file name */
	struct position *tx_pos;/* list of positions in this file that are
				   part of a chunk; sorted and updated by
				   Pass 2
				*/
	unsigned int tx_start;	/* positions in Token_Array[] for the text */
	unsigned int tx_limit;
	unsigned int tx_nl_start;/* possibly newline pointer for pass2 */
	unsigned int tx_nl_limit;
};

struct position {
	/* position of first and last token of a chunk */
	struct position *ps_next;
	int ps_type;		/* first = 0, last = 1 */
	unsigned int ps_tk_cnt;	/* in tokens; set by add_run()
				   in Read_Input_Files() */
	unsigned int ps_nl_cnt;	/* same, in line numbers;set by Retrieve_Runs(),
				   used by Show_Runs(), to report line numbers
				*/
};

extern struct text *Text;		/* Text[], one for each input file */
extern int Number_Of_Texts;		/* number of text files */
extern int Number_Of_New_Texts;		/* number of new text files */

extern void Init_Text(int nfiles);
enum Pass {First, Second};
extern int Open_Text(enum Pass pass, struct text *txt);
extern int Next_Text_Token_Obtained(enum Pass pass);
extern void Close_Text(enum Pass pass, struct text *txt);

#ifdef	DB_NL_BUFF
extern void db_print_nl_buff(unsigned int start, unsigned int limit);
#endif
