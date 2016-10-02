<?php
/**
 * This file is written by yybird
 **/

  require_once('./include/db_info.inc.php');

	// get all problem id
	$sql = "SELECT problem_id FROM problem";
	$result_prob  = $mysqli->query($sql) or die($mysqli->error);
	if($result_prob) $prob_cnt = $result_prob->num_rows;
  else $prob_cnt = 0;
	
  for ($i=0; $i<$prob_cnt; $i++) {
		$row_prob = $result_prob->fetch_object();
		$prob_id = $row_prob->problem_id;

		// get AC numbers
		$sql = "SELECT count(*) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
		$result = $mysqli->query($sql) or die($mysqli->error);
		$row = $result->fetch_object();
		$AC = $row->num;
		$result->free();

		// get AC user numbers
		$sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
		$result = $mysqli->query($sql) or die($mysqli->error);
		$row = $result->fetch_object();
		$solved_user = $row->num;
		$result->free();

		// get submit numbers
		$sql = "SELECT count(*) AS num FROM solution WHERE problem_id=".$prob_id;
		$result = $mysqli->query($sql) or die($mysqli->error);
		$row = $result->fetch_object();
		$submit = $row->num;
		$result->free();

		// get submit user numbers
		$sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE problem_id=".$prob_id;
		$result = $mysqli->query($sql) or die($mysqli->error);
		$row = $result->fetch_object();
		$submit_user = $row->num;
		$result->free();

		// update
		$sql = "UPDATE problem SET accepted=".$AC.", solved_user=".$solved_user.", submit=".$submit.", submit_user=".$submit_user." WHERE problem_id=".$prob_id;
		$mysqli->query($sql) or die($mysqli->error);
  }
  $result_prob->free();
	echo "update submit successfully!"
?>
