<?php
/**
 * This file is written by yybird
 **/
  require_once('./include/db_info.inc.php');

	// get user numbers
	$sql = "SELECT count(*) as num FROM users WHERE solved>10";
	$result_user  = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_object($result_user);
	$user_cnt = $row->num;
	mysql_free_result($result_user);
//echo $user_cnt;

	// get all problem id
	$sql = "SELECT problem_id FROM problem";
	$result_prob  = mysql_query($sql) or die(mysql_error());
	if($result_prob) $prob_cnt = mysql_num_rows($result_prob);
  else $prob_cnt = 0;
	
  for ($i=0; $i<$prob_cnt; $i++) {
		$row_prob = mysql_fetch_object($result_prob);
		$prob_id = $row_prob->problem_id;

		// get AC and submit numbers
		$sql = "SELECT solved_user, submit_user FROM problem WHERE problem_id=".$prob_id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_object($result);
		$solved = $row->solved_user;
		$submit = $row->submit_user;
		
		// calculate scores
		$scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt);
		if ($scores < 10) $scores = 10;
		//echo $user_cnt." ".$prob_id.":".$solved." ".$submit." ".$scores."<br/>";
		// update scores
		$sql = "UPDATE problem SET scores=".$scores." WHERE problem_id=".$prob_id;
		mysql_query($sql) or die(mysql_error());

		echo $prob_id.":".$scores."<br/>";

		mysql_free_result($result);
  }
  mysql_free_result($result_prob);

	echo "update scores successfully!";
?>
