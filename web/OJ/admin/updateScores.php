<?php

/**
 * This file is written by yybird
 **/
require_once("admin-header.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
// get user numbers
$sql = "SELECT count(*) as num FROM users WHERE solved>10";
$result_user  = $mysqli->query($sql) or die($mysqli->error);
$row = $result_user->fetch_object();
$user_cnt = $row->num?$row->num:1;
$result_user->free();
//echo $user_cnt;

// get all problem id
$sql = "SELECT problem_id FROM problem";
$result_prob  = $mysqli->query($sql) or die($mysqli->error);
if ($result_prob) $prob_cnt = $result_prob->num_rows;
else $prob_cnt = 0;

for ($i = 0; $i < $prob_cnt; $i++) {
  $row_prob = $result_prob->fetch_object();
  $prob_id = $row_prob->problem_id;

  // get AC user numbers
  $sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
  $result = $mysqli->query($sql) or die($mysqli->error);
  $row = $result->fetch_object();
  $solved_user = $row->num;
  $result->free();

  // get submit user numbers
  $sql = "SELECT count(DISTINCT user_id) AS num FROM solution WHERE problem_id=".$prob_id;
  $result = $mysqli->query($sql) or die($mysqli->error);
  $row = $result->fetch_object();
  $submit_user = $row->num;
  $result->free();

  // calculate scores
  $scores = 100.0 * (1 - ($solved_user + $submit_user / 2.0) / $user_cnt);
  if ($scores < 10) $scores = 10;
  //echo $user_cnt." ".$prob_id.":".$solved." ".$submit." ".$scores."<br/>";
  // update scores

  // get AC numbers
  $sql = "SELECT count(*) AS num FROM solution WHERE result=4 AND problem_id=".$prob_id;
  $result = $mysqli->query($sql) or die($mysqli->error);
  $row = $result->fetch_object();
  $AC = $row->num;
  $result->free();

  // get submit numbers
  $sql = "SELECT count(*) AS num FROM solution WHERE problem_id=".$prob_id;
  $result = $mysqli->query($sql) or die($mysqli->error);
  $row = $result->fetch_object();
  $submit = $row->num;
  $result->free();

  $sql = "UPDATE problem SET  accepted=".$AC.", solved_user=".$solved_user.", submit=".$submit.", submit_user=".$submit_user.",score=" . $scores . " WHERE problem_id=" . $prob_id;
  $mysqli->query($sql) or die($mysqli->error);
  echo $prob_id . ": " . round($scores,2) . "<br/>";
  $result->free();
}
$result_prob->free();
echo "update scores successfully!";
require_once("admin-footer.php")
?>