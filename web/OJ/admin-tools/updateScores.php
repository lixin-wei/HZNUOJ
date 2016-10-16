<?php
/**
 * This file is written by yybird
 **/
  require_once('./include/db_info.inc.php');

	// get user numbers
	$sql = "SELECT count(*) as num FROM users WHERE solved>10";
	$result_user  = $mysqli->query($sql) or die($mysqli->error);
	$row = $result_user->fetch_object();
	$user_cnt = $row->num;
	$result_user->free();
//echo $user_cnt;

	// get all problem id
	$sql = "SELECT problem_id FROM problem";
	$result_prob  = $mysqli->query($sql) or die($mysqli->error);
	if($result_prob) $prob_cnt = $result_prob->num_rows;
  else $prob_cnt = 0;
	
  for ($i=0; $i<$prob_cnt; $i++) {
		$row_prob = $result_prob->fetch_object();
		$prob_id = $row_prob->problem_id;

		// get AC and submit numbers
		$sql = "SELECT solved_user, submit_user FROM problem WHERE problem_id=".$prob_id;
		$result = $mysqli->query($sql) or die($mysqli->error);
		$row = $result->fetch_object();
		$solved = $row->solved_user;
		$submit = $row->submit_user;
		
		// calculate scores
		$scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt);
		if ($scores < 10) $scores = 10;
		//echo $user_cnt." ".$prob_id.":".$solved." ".$submit." ".$scores."<br/>";
		// update scores
		$sql = "UPDATE problem SET scores=".$scores." WHERE problem_id=".$prob_id;
		$mysqli->query($sql) or die($mysqli->error);

		echo $prob_id.":".$scores."<br/>";

		$result->free();
  }
  $result_prob->free();

	echo "update scores successfully!";
?>
