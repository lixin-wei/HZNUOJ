/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: text.h,v 1.9 2016-07-28 07:00:48 dick Exp $
*/

/*	Implements the access to the lexical scanner.
	Additionally, the module tries to save newline information,
	anticipating a second scan which is interested in this
	information only.
*/

/* The input files are called "texts" */

struct text {
	const char *tx_fname;	/* the file name */
	size_t tx_start;	/* index of first token in Token_Array[]
				   belonging to the text */
	size_t tx_limit;	/* index of first position in Token_Array[]
				   not belonging to the text */
	size_t tx_nl_start;	/* possibly newline pointer for pass2 */
	size_t tx_nl_limit;
	int tx_EOL_terminated;	/* Boolean */
	struct position *tx_pos;/* list of positions in this file that are
				   part of a chunk; sorted and updated by
				   Pass 2
				*/
};

struct position {
	/* position of first and last token of a chunk */
	struct position *ps_next;
	int ps_type;		/* first = 0, last = 1 */
	size_t ps_tk_cnt;	/* in tokens; set by add_run()
				   in Read_Input_Files() */
	size_t ps_nl_cnt;	/* same, in line numbers;set by Retrieve_Runs(),
				   used by Show_Runs(), to report line numbers
				*/
};

extern struct text *Text;		/* Text[], one for each input file */
extern int Number_of_Texts;		/* number of text files;
					   this includes the new/old separator
					   if present; actually a design flaw ZZ
					*/
extern int Number_of_New_Texts;		/* number of new text files */

extern void Init_Text(int nfiles);
enum Pass {First_Pass, Second_Pass};
extern int Open_Text(enum Pass pass, struct text *txt);
extern int Next_Text_Token_Obtained(void);
extern int Next_Text_EOL_Obtained(void);
extern void Close_Text(enum Pass pass, struct text *txt);
extern void Free_Text(void);

#ifdef	DB_NL_BUFF
extern void db_print_nl_buff(size_t start, size_t limit);
#endif
