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

$page_cnt = 30;
$language = -1;
$result = 4;
$page = max(intval($_GET['page']), 0);
$order_method = "length";
if(isset($_GET['language'])) $language = intval($_GET['language']);
if(isset($_GET['result'])) $result = intval($_GET['result']);
if(isset($_GET['order'])) $order_method = $_GET['order'];
$left_bound = $page*$page_cnt;
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

