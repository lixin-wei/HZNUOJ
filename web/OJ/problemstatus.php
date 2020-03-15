<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.27
 **/
?>

<?php
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Statics";
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");

$pid = intval($_GET['id']);
if(!can_see_problem($pid)) {
    echo "problem not exists or is hidden!";
    exit(0);
}

$view_problem=array();
$view_echart;
// total submit ;
$sql="SELECT count(*) FROM solution WHERE problem_id=".$pid;
$result = $mysqli->query($sql)->fetch_all() or die("Error! ".$mysqli->error);
$row=$result[0];
$view_problem[0][0]=$MSG_SUBMISSIONS;
$view_problem[0][1]=$row[0];
$total=intval($row[0]);

// total users
$sql="SELECT count(DISTINCT user_id) FROM solution WHERE problem_id=".$pid;
$result = $mysqli->query($sql)->fetch_all() or die("Error! ".$mysqli->error);
$row=$result[0];
$view_problem[1][0]="($MSG_SUBMIT)$MSG_USER";
$view_problem[1][1]=$row[0];

// ac users
$sql="SELECT count(DISTINCT user_id) FROM solution WHERE problem_id=".$pid." AND result='4'";
$result = $mysqli->query($sql)->fetch_all() or die("Error! ".$mysqli->error);
$row=$result[0];
$acuser=intval($row[0]);
$view_problem[2][0]="($MSG_SOLVED)$MSG_USER";
$view_problem[2][1]=$row[0];

// submit results
$sql="SELECT result,count(1) FROM solution WHERE problem_id=".$pid." AND result>=4 group by result order by result";
$result = $mysqli->query($sql)->fetch_all();
$i=3;
foreach($result as $row){
    $view_problem[$i][0] =$jresult[$row[0]];
    $view_problem[$i][1] ="<a href=status.php?problem_id=$id&jresult=".$row[0]." >".$row[1]."</a>";
    $view_echart[$i][0] =$jresult[$row[0]];
    $view_echart[$i][1] =$row[1];
    $i++;
}

$page_cnt = 30;
$language = -1;
$result = 4;
$page = max(intval($_GET['page']), 1);
$order_method = "length";
if(isset($_GET['language'])) $language = intval($_GET['language']);
if(isset($_GET['result'])) $result = intval($_GET['result']);
if(isset($_GET['order'])) $order_method = $_GET['order'];
$left_bound = $page_cnt*($page-1);
$rank = $left_bound;
$filter_sql = "";
$sql = <<<SQL
    SELECT
      solution_id, solution.user_id, language, result, time, memory, code_length, in_date,
      team.contest_id as is_temp_user
    FROM solution
    LEFT JOIN team
    ON
      solution.contest_id = team.contest_id
      AND solution.user_id = team.user_id
SQL;
$filter_sql = <<<SQL
    WHERE
      problem_id = '$pid'
      AND solution.result = $result
SQL;
if($language != -1) $filter_sql .= " AND language = $language";
switch ($order_method) {
    case "length":
        $filter_sql.=" ORDER BY code_length";
        break;
    case "time":
        $filter_sql.=" ORDER BY time, memory, in_date, code_length";
        break;
    case "memory":
        $filter_sql.=" ORDER BY memory, time, in_date, code_length";
        break;
    case "date":
        $filter_sql.=" ORDER BY in_date, time, memory";
        break;
}
$sql .= $filter_sql;
$sql .= " LIMIT $left_bound, $page_cnt";
$res = $mysqli->query($sql);
$data = $res->fetch_all(MYSQLI_ASSOC);
$sql = <<<SQL
    SELECT
      count(1)
    FROM solution
    LEFT JOIN team
    ON
      solution.contest_id = team.contest_id
      AND solution.user_id = team.user_id
SQL;
$sql .= $filter_sql;

$total_page = ceil($mysqli->query($sql)->fetch_array()[0]/$page_cnt);
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/problemstatus.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

