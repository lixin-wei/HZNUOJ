/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: idf.h,v 2.13 2016-07-31 18:55:44 dick Exp $
*/

/*	Idf module:
	Token idf_in_list(
		const char *str,
		const struct idf list[],
		size_t listsize,
		Token default_token
	);
		looks up a keyword in a list of keywords l, represented as an
		array of struct idf, and returns its translation as a token;
		dflt is returned if the keyword is not found.
	Token idf_hashed(char *str);
		returns a token unequal to No_Token or End_Of_Line, derived
		from str through hashing
*/

/* the struct for keywords etc. */
struct idf {
	char *id_tag;	/* an interesting identifier */
	Token id_tr;	/* with its one-Token translation */
};

/* public functions */
extern Token idf_in_list(
	const char *str,
	const struct idf list[],
	size_t listsize,
	Token default_token
);
extern Token idf_hashed(const char *str);
extern void lower_case(char *str);
