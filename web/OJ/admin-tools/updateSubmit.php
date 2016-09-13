<?php
/**
 * This file is written by yybird
 **/

  require_once('./include/db_info.inc.php');

	// get all problem id
	$sql = "SELECT problem_id FROM problem";
	$result_prob  = mysql_query($sql) or die(mysql_error());
	if($result_prob) $prob_cnt = mysql_num_rows($result_prob);
  else $prob_cnt = 0;
	
  for ($i=0; $i<$prob_cnt; $i++) {
		$row_prob = mysql_fetch_object($result_prob);
		$prob_id = $row_prob->problem_id;

		// get AC numbers
		$sql = "SELECT count(*) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_object($result);
		$AC = $row->num;
		mysql_free_result($result);

		// get AC user numbers
		$sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_object($result);
		$solved_user = $row->num;
		mysql_free_result($result);

		// get submit numbers
		$sql = "SELECT count(*) AS num FROM solution WHERE problem_id=".$prob_id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_object($result);
		$submit = $row->num;
		mysql_free_result($result);

		// get submit user numbers
		$sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE problem_id=".$prob_id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_object($result);
		$submit_user = $row->num;
		mysql_free_result($result);

		// update
		$sql = "UPDATE problem SET accepted=".$AC.", solved_user=".$solved_user.", submit=".$submit.", submit_user=".$submit_user." WHERE problem_id=".$prob_id;
		mysql_query($sql) or die(mysql_error());
  }
  mysql_free_result($result_prob);
	echo "update submit successfully!"
?>
