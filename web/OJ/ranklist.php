<?php
/**
 * This file is modified
 * by yybird
 * @2016.04.15
 **/
?>

<?php
$OJ_CACHE_SHARE=false;
$cache_time=30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (isset($_SESSION['contest_id'])){ //不允许比赛用户查看比赛外的排名
    $view_errors= "<font color='red'>$MSG_HELP_TeamAccount_forbid</font>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
//require_once('updateRank.php'); // 有此语句后每次点击ranklist会自动更新排名

$view_title= $MSG_RANKLIST;

  //分页start
$page = 1;
if(isset($_GET['page'])) $page = intval($_GET['page']);
$page_cnt = 50;
$rank = $page_cnt*($page-1);
$pstart = $page_cnt*$page-$page_cnt;
$pend = $page_cnt;    
  //分页end 
$sql_filter = " WHERE users.defunct='N' "; // SQL中的筛选语句
$sql_limit = " limit ".strval($pstart).",".strval($pend); 
$sql_orderby = "";

// check the order_by arg
if(isset($_GET['prefix'])){
	$prefix=$_GET['prefix'];
	$sql_filter .=" AND users.user_id like '%$prefix%'";
}
// check the order_by arg
$order_by="";
if(isset($_GET['order_by'])) {
    $order_by=$_GET['order_by'];
	if ($order_by=="ac"){
		$sql_orderby = " ORDER BY solved DESC, submit, reg_time ";
	}
	if ($order_by=="level"){
		$sql_orderby = " ORDER BY strength DESC, solved, submit, reg_time ";
	}
}

// check the class arg
if (isset($_GET['class']) ) {
    $class_get = $mysqli->real_escape_string(trim($_GET['class']));
    if ($class_get != "all")
        $sql_filter .= " AND users.class='".$class_get."' ";
}

// check the scope arg
$scope="";
if(isset($_GET['scope'])) {
    $scope=$_GET['scope'];
}
if($scope!=""&&$scope!='d'&&$scope!='w'&&$scope!='m')
   $scope='y';
if($scope){
    $s="";
    switch ($scope){
        case 'd':
            $s=date('Y').'-'.date('m').'-'.date('d');
            break;
        case 'w':
            $monday=mktime(0, 0, 0, date("m"),date("d")-(date("w")+7)%8+1, date("Y"));
            //$monday->subDays(date('w'));
            $s=strftime("%Y-%m-%d",$monday);
            break;
        case 'm':
            $s=date('Y').'-'.date('m').'-01';
            ;break;
        default :
            $s=date('Y').'-01-01';
    }
	$sql="SELECT * FROM `users`
                    right join
                    (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc) s on users.user_id=s.user_id
                    left join
                    (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc) t on users.user_id=t.user_id
            ".$sql_filter." ORDER BY s.solved DESC,t.submit,reg_time ".$sql_limit;
	$sql_page = "SELECT count(1) FROM `users`
                    right join
                    (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc) s on users.user_id=s.user_id
                    left join
                    (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc) t on users.user_id=t.user_id
            ".$sql_filter;
    /*$sql="SELECT * FROM `users`
                    right join
                    (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc limit " . strval ( $rank ) . ",$page_size) s on users.user_id=s.user_id
                    left join
                    (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc limit " . strval ( $rank ) . ",".($page_size*2).") t on users.user_id=t.user_id
            ".$sql_filter." ORDER BY solved DESC,t.submit,reg_time  LIMIT  0,50";*/
} else {	
	$sql = "SELECT * FROM users ".$sql_filter.$sql_orderby.$sql_limit;
	$sql_page = "SELECT count(1) FROM users ".$sql_filter.$sql_orderby;
}
$rows =$mysqli->query($sql_page)->fetch_all(MYSQLI_BOTH) or die($mysqli->error);
if($rows) $total = $rows[0][0];  
$view_total_page = intval($total/$page_cnt)+($total%$page_cnt?1:0);//计算页数

if($OJ_MEMCACHE){
    require("./include/memcache.php");
    $result = $mysqli->query_cache($sql) ;//or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
} else {
    $result = $mysqli->query($sql) or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
}

$view_rank=Array();
$i=0;
for ( $i=0;$i<$rows_cnt;$i++ ) {
    if($OJ_MEMCACHE)
        $row=$result[$i];
    else
        $row=$result->fetch_array();
    
    $rank ++;
    $total = $row['solved'];//+$row['ZJU']+$row['HDU']+$row['PKU']+$row['UVA']+$row['CF'];
    $view_rank[$i][0] = $rank;
    $view_rank[$i][1] = "<a href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>";
    $view_rank[$i][2] = htmlentities($row['nick']);
    $view_rank[$i][3] = "<a href='status.php?user_id=".$row['user_id']."&jresult=4'>".$row['solved']."</a>";
	$view_rank[$i][4] = "<a href='status.php?user_id=".$row['user_id']."'>".$row['submit']."</a>";
	if ($row['submit'] == 0){
        $view_rank[$i][5]= "0.000%";
	} else {
        $view_rank[$i][5]= sprintf ( "%.03lf%%", 100 * $row['solved'] / $row['submit'] );
	}
    $view_rank[$i][10]= "<font color='".$row['color']."'>".$row['level']."</font>";
    $view_rank[$i][11]= round($row['strength']);
}

/* 获取所有班级 start */
$classSet = array();
if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
	$sql_class = "SELECT DISTINCT(class) FROM users";
	$result_class = $mysqli->query($sql_class);
	
	while ($row_class = $result_class->fetch_array()) {
		$class = $row_class['class'];
	//    echo $class."<br />";
		if (!is_null($class) && $class!="" && $class!="null" && $class!="其它") {
			$grade = "";
			$strlen = strlen($class);
			for ($i=0; $i<$strlen; ++$i) {
				if (is_numeric($class[$i])) {
					$grade = $class[$i].$class[$i+1];
					break;
				}
			}
			$classSet[] = $grade." - ".$class;
			//echo $grade." - ".$class."<br />";
		}
	}
	rsort($classSet);
	$result_class->free();
}
/* 获取所有班级 end */

if($OJ_MEMCACHE){
    // require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}else{
    
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
}
if($OJ_MEMCACHE)
    $row=$result[0];
else
    $row=$result->fetch_array();
echo $mysqli->error;

if(!$OJ_MEMCACHE)  $result->free();


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/ranklist.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
