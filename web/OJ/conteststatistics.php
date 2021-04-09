<?php
	$OJ_CACHE_SHARE=true;
	$cache_time=30;
	require_once("./include/db_info.inc.php");
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<NOW()";
$result=$mysqli->query($sql);
$num=$result->num_rows;
if ($num==0){
	$view_errors= $MSG_notStart;
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
$row=$result->fetch_object();
$start_time=strtotime($row->start_time);
$end_time=strtotime($row->end_time);
$unlock=$row->unlock;
switch($unlock){
    case 0: //用具体时间来控制封榜
        $lock=$end_time-$row->lock_time;
        break;
    case 2: //用时间比例来控制封榜
        $lock = $end_time - ($end_time - $start_time) * $row->lock_time / 100;
        break;
}
$result->free();

$view_title= $MSG_CONTEST.$MSG_STATISTICS;

$sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=$mysqli->query($sql);
$row=$result->fetch_array();
$pid_total=intval($row[0]);

//跳过不存在题目的题号
$sql = "SELECT `num` FROM contest_problem a 
	        inner join (select problem_id from `problem`) b 
			on a.problem_id = b.problem_id 
			WHERE contest_id = $cid and num >=0 order by num" ;
$result=$mysqli->query($sql) or die($mysqli->error);
$pid_nums=$result->fetch_all(MYSQLI_BOTH);

$sql_lockboard="";
if($unlock != 1) $sql_lockboard=" AND `in_date`<'".date("Y-m-d H:i:s",$lock)."' ";

$sql="SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`='$cid' and num>=0 $sql_lockboard";
$result=$mysqli->query($sql);
$R=array();
while ($row=$result->fetch_object()){
	$res=intval($row->result)-4;
	if ($res<0) $res=8;
	$num=intval($row->num);
	$lag=intval($row->language);
	if(!isset($R[$num][$res]))
		$R[$num][$res]=1;
	else
		$R[$num][$res]++;
	if(!isset($R[$num][$lag+10]))
		$R[$num][$lag+10]=1;
	else
		$R[$num][$lag+10]++;
	if(!isset($R[$pid_total][$res]))
		$R[$pid_total][$res]=1;
	else
		$R[$pid_total][$res]++;
	if(!isset($R[$pid_total][$lag+10]))
		$R[$pid_total][$lag+10]=1;
	else
		$R[$pid_total][$lag+10]++;
	if(!isset($R[$num][9]))
		$R[$num][9]=1;
	else
		$R[$num][9]++;
	if(!isset($R[$pid_total][9]))
		$R[$pid_total][9]=1;
	else
		$R[$pid_total][9]++;
}
$result->free();

$sql="SELECT date_format(in_date, '%H:%i') m, count(1) c FROM `solution` where `contest_id`='$cid' $sql_lockboard group by m order by m";
$result=$mysqli->query($sql);//$mysqli->real_escape_string($sql));
$chart_data_all= array();
$xAxis_data=array();
//echo $sql;
while ($row=$result->fetch_array()){
  $chart_data_all[$row['m']]['total']=$row['c'];
  $chart_data_all[$row['m']]['ac']=0;
  array_push($xAxis_data,$row['m']);
}

$sql="SELECT date_format(in_date, '%H:%i') m, count(1) c FROM `solution` where `contest_id`='$cid' and result=4 $sql_lockboard group by m order by m";
$result=$mysqli->query($sql);//$mysqli->real_escape_string($sql));
//echo $sql;
while ($row=$result->fetch_array()){
	$chart_data_all[$row['m']]['ac']=$row['c'];
}
$result->free();


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/conteststatistics.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
