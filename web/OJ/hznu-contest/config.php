<?php
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
$contest_id = 2;

$sql="SELECT password, is_end FROM formal_contest WHERE id = $contest_id";
$res = $mysqli->query($sql)->fetch_array();

$is_end = $res['is_end'];
$contest_password = $res['password'];


$has_login = isset($_SESSION['user_id']);

$team_name = "";
$school = "";
$name1 = "";
$name2 = "";
$name3 = "";
$anonymous = 0;
$phone = "";
if ($has_login) {
	$user_id = $_SESSION['user_id'];
	$sql="SELECT team_name, school, name1, name2, name3, register_time, anonymous, phone FROM formal_contest_user WHERE user_id='$user_id' AND contest_id='$contest_id'";
	$res = $mysqli->query($sql);
	$form_data = $res->fetch_array();
	$team_name = $form_data['team_name'];
	$school = $form_data['school'];
	$name1 = $form_data['name1'];
	$name2 = $form_data['name2'];
	$name3 = $form_data['name3'];
	$anonymous = $form_data['anonymous'];
	$phone = $form_data['phone'];
}