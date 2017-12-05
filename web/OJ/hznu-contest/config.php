<?php
require_once "../include/db_info.inc.php";
$year = 2017;

$sql="SELECT is_end FROM hznu_contest WHERE year = $year";
$res = $mysqli->query($sql)->fetch_array();

$isEnd = $res['is_end'];