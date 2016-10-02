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
	// require_once('updateRank.php'); // 有此语句后每次点击ranklist会自动更新排名
  
	$view_title= $MSG_RANKLIST;
  $filter_url = ""; // URL中的筛选语句
  $filter_sql = ""; // SQL中的筛选语句

  $scope="";
  if(isset($_GET['scope'])) {
		$scope=$_GET['scope'];
    $filter_url .= "&scope=".$scope;
  }
  if($scope!=""&&$scope!='d'&&$scope!='w'&&$scope!='m')
		$scope='y';

	$order_by="";
  if(isset($_GET['order_by'])) {
  	$order_by=$_GET['order_by'];
    $filter_url .= "&order_by=".$order_by;
  }
  if($order_by!=""&&$order_by!='ac')
		$order_by='s';

	$rank = 0;
  $start = 0;
  if(isset( $_GET ['start'] )) {
  	$start = $rank = intval ( $_GET ['start'] );
  }

  if (isset($_GET['class'])) {
    if ($_GET['class'] != "all")
      $filter_sql = " WHERE class='".$_GET['class']."' ";
    $filter_url .= "&class=".$_GET['class'];
  }


  if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
  }
  $page_size=30;
  //$rank = intval ( $_GET ['start'] );
  if ($rank < 0) $rank = 0;

	if ($order_by=='ac') $sql = "SELECT * FROM users ".$filter_sql."ORDER BY solved DESC, submit, reg_time LIMIT ".strval($rank).",$page_size";
	else $sql = "SELECT * FROM users ".$filter_sql." ORDER BY strength DESC, solved, submit, reg_time LIMIT ".strval($rank).",$page_size";
		
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
                    (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc limit " . strval ( $rank ) . ",$page_size) s on users.user_id=s.user_id
                    left join
                    (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc limit " . strval ( $rank ) . ",".($page_size*2).") t on users.user_id=t.user_id
            ".$class_filter." ORDER BY solved DESC,t.submit,reg_time  LIMIT  0,50";
  }


       //         $result = $mysqli->query ( $sql ); //$mysqli->error;
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
    $total = $row['solved']+$row['ZJU']+$row['HDU']+$row['PKU']+$row['UVA']+$row['CF'];
    $view_rank[$i][0] = "<div class='am-text-center'>".$rank."</div>";
    $view_rank[$i][1] = "<div class='am-text-center'><a href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>"."</div>";
    $view_rank[$i][2] = "<div class='am-text-center'>".htmlspecialchars($row['nick'])."</div>";
    $view_rank[$i][3] = "<div class='am-text-center'><a href='status.php?user_id=".$row['user_id']."&jresult=4'>".$row['solved']."</a>"."</div>";
    $view_rank[$i][4] = "<div class='am-text-center'><a href='{$VJ_URL}/problem/status.action#un=".$row['user_id']."&OJId=ZOJ&res=1&orderBy=run_id'>".$row['ZJU']."</a>"."</div>";
    // $view_rank[$i][4]=  "<div class=center><a href='status.php?user_id=".$row['user_id']."'>".$row['submit']."</a>"."</div>";
    /*
    if ($row['submit'] == 0)
      $view_rank[$i][5]= "<div class=center>0.000%</div>";
    else
      $view_rank[$i][5]= sprintf ( "<div class=center>%.03lf%%</div>", 100 * $row['solved'] / $row['submit'] );
		*/
    $view_rank[$i][5] = "<div class='am-text-center'><a href='{$VJ_URL}/problem/status.action#un=".$row['user_id']."&OJId=HDU&res=1&orderBy=run_id'>".$row['HDU']."</a>"."</div>";
    $view_rank[$i][6] = "<div class='am-text-center'><a href='{$VJ_URL}/problem/status.action#un=".$row['user_id']."&OJId=POJ&res=1&orderBy=run_id'>".$row['PKU']."</a>"."</div>";
    $view_rank[$i][7] = "<div class='am-text-center'><a href='{$VJ_URL}/problem/status.action#un=".$row['user_id']."&OJId=UVA&res=1&orderBy=run_id'>".$row['UVA']."</a>"."</div>";
    $view_rank[$i][8] = "<div class='am-text-center'><a href='{$VJ_URL}/problem/status.action#un=".$row['user_id']."&OJId=CodeForces&res=1&orderBy=run_id'>".$row['CF']."</a>"."</div>";
    $view_rank[$i][9] = "<div class='am-text-center'>".$total."</div>";
    $view_rank[$i][10]= "<div class='am-text-center' style='width:110px;'><font color='".$row['color']."'>".$row['level']."</font></div>";
		$view_rank[$i][11]= "<div class='am-text-center'>".round($row['strength'])."</div>";
  }

  /* 获取所有班级 start */
  $sql_class = "SELECT DISTINCT(class) FROM users";
  $result_class = $mysqli->query($sql_class);
  $classSet = array();
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
  /* 获取所有班级 end */


if(!$OJ_MEMCACHE)$result->free();

                $sql = "SELECT count(1) as `mycount` FROM `users` ".$filter_sql;
        //        $result = $mysqli->query ( $sql );
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
  //$row = mysql_fetch_object ( $result );
                $view_total=$row['mycount'];

  //              mysql_free_result ( $result );

if(!$OJ_MEMCACHE)  $result->free();


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/ranklist.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>
