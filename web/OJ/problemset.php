<?php
/**
 * This file is modified
 * by yybird
 * @2016.05.26
 **/
?>

<?php
$OJ_CACHE_SHARE=false;
$cache_time=60;
require_once('./include/cache_start.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');
$view_title= "Problem Set";
if (isset($_SESSION['contest_id'])){ //不允许比赛用户查看比赛外的题库
    $view_errors= "<font color='red'>$MSG_HELP_TeamAccount_forbid</font>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
//get all problemsets START

//get all problemsets END
/* 获取OJ start */
if (isset($_GET['OJ']) && $_GET['OJ']!="") $OJ = $_GET['OJ'];
else $OJ = "all";
if(isset($_GET['sort_method'])){
    $sort_method=$_GET['sort_method'];
}
/* 获取OJ end */


$page_cnt = 100;

//remember page
$page="1";
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}
//end of remember page


/* 是否显示标签 start */
$show_tag = false;

if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
    $uid = $_SESSION['user_id'];
    $sql = "SELECT tag FROM users WHERE user_id='$uid'";
    $result = $mysqli->query($sql);
    $row_h = $result->fetch_array();
    $result->free();
    if ($row_h['tag'] == "Y") $show_tag = true;
} else if (isset($_SESSION['tag'])) {
    if ($_SESSION['tag'] == "N") $show_tag = false;
    else $show_tag = true;
}

if ($show_tag) $_SESSION['tag'] = "Y";
else $_SESSION['tag'] = "N";
/* 是否显示标签 end */


/* 获取当前用户提交过的题目 start */
$sub_arr=Array();
if (isset($_SESSION['user_id'])) {
    $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'"." group by `problem_id`";
    $result=@$mysqli->query($sql) or die($mysqli->error);
    while ($row=$result->fetch_array())
        $sub_arr[$row[0]]=true;
}
/* 获取当前用户提交过的题目 end */


/* 获取当前用户已AC的题目 start */
$acc_arr=Array();
if (isset($_SESSION['user_id'])) {
    $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'"." AND `result`=4"." group by `problem_id`";
    $result=@$mysqli->query($sql) or die($mysqli->error);
    while ($row=$result->fetch_array())
        $acc_arr[$row[0]]=true;
}
/* 获取当前用户已AC的题目 end */


/* 获取sql语句中的筛选部分 start */
if(isset($_GET['search'])&&trim($_GET['search'])!="") {
    $search=$mysqli->real_escape_string(trim($_GET['search']));
    $filter_sql="(title like '%$search%' or source like '%$search%' or author like '%$search%' OR tag1 like '%$search%' OR tag2 like '%$search%' OR tag3 like '%$search%')";
} else {
    $filter_sql="1";
}

/* 获取sql语句中的筛选部分 end */
$res_set = $mysqli->query("SELECT set_name FROM problemset");
$first = true;
$sql = "";
$cnt = 0;
while($set_name=$res_set->fetch_array()[0]){
    if($OJ=='all' || $OJ==$set_name){
        if(HAS_PRI("see_hidden_".$set_name."_problem")){
            $t_sql="
SELECT problem.problem_id,`title`,author,`source`,`submit`,`accepted`,score, tag1, tag2, tag3, 0 as locked, `defunct`
FROM `problem` WHERE $filter_sql AND problemset='$set_name'";
        }
        else{
            $t_sql=<<<SQL
SELECT problem.problem_id,`title`,author,`source`,`submit`,`accepted`,score, tag1, tag2, tag3, COUNT(running_problem.problem_id) > 0 AS locked, `defunct`
FROM problem
LEFT JOIN (
    SELECT problem_id FROM contest_problem
    INNER JOIN contest ON
        contest.contest_id = contest_problem.contest_id
        AND contest.start_time < NOW()
        AND contest.end_time > NOW()
        AND contest.practice = 0
    ) as running_problem
  ON running_problem.problem_id = problem.problem_id
  WHERE $filter_sql AND problem.defunct = 'N' AND problemset='$set_name'
  GROUP BY problem.problem_id
SQL;
        }
        //count the number of problem START
        $res = $mysqli->query($t_sql);
        $cnt += $res->num_rows;
        //count the number of problem END
        
        if($first) $first = false;
        else $t_sql = " UNION ".$t_sql;
        $sql .= $t_sql;
        
        
    }
}
switch ($sort_method) {
    case "SCORE_DESC":
        $sort_cmd=" ORDER BY `score` DESC, accepted";
        break;
    case "SCORE_ASCE":
        $sort_cmd=" ORDER BY `score`, accepted DESC";
        break;
    default:
        $sort_cmd=" ORDER BY `problem_id`";
        break;
}
$sql.=$sort_cmd;
/* 计算页数cnt start */
$view_total_page = ceil($cnt / $page_cnt); //计算页数
$view_total_page = $view_total_page>0?$view_total_page:1;
if ($page > $view_total_page) $page = $view_total_page;
if ($page < 1) $page = 1;
/* 计算页数cnt end */
$st=($page-1)*$page_cnt;
$sql.=" LIMIT $st,$page_cnt";

if($first) $sql="";
//echo "<pre>sql:".$sql."</pre>";
/* 获取数据库查询语句 end */


//echo "<pre>".htmlentities($sql)."</pre>";
$result=$mysqli->query($sql) or die($mysqli->error);




$cnt=0;
$view_problemset=Array();
$i=0;


/* 把结果放入表格 start */
while ($row=$result->fetch_object()) {
    $view_problemset[$i]=Array();
    
    // 获取problem ID
    $p_id = $row->problem_id;
    // 将信息放入表格
    if (isset($sub_arr[$p_id])) {
        if (isset($acc_arr[$p_id]))
            $view_problemset[$i][0] = "<th style='width:30px'><font color='green'>Y</font></th>";
        else
            $view_problemset[$i][0] = "<th style='width:30px'><font color='red'>N</font></th>";
    } else {
        $view_problemset[$i][0] = "<td style='width:30px'></td>";
    }
    $view_problemset[$i][1] = "<td>".$p_id."</td>";
    $view_problemset[$i][2] = "<td style='text-align:left;";
    if(!$row->locked)
        $view_problemset[$i][2] .= "'><a href='problem.php?id=".$p_id."' target='_blank'>".$row->title."</a>";
    else
        $view_problemset[$i][2] .= "color: dimgrey;'><span title='this problem is locked because they are in running contest.'>{$row->title}</span> <i class='am-icon-lock'></i>";
    if(strtolower($row->defunct)=="y") $view_problemset[$i][2] .= " <i class='am-icon-eye-slash'></i>";
    $view_problemset[$i][2] .= "</td>";
    if ($show_tag) {
        $view_problemset[$i][3] = "<td >";
        if($row->tag1) $view_problemset[$i][3] .= "<span class='am-badge am-badge-danger'>".$row->tag1."</span>\n";
        if($row->tag2) $view_problemset[$i][3] .= "<span class='am-badge am-badge-warning'>".$row->tag2."</span>\n";
        if($row->tag3) $view_problemset[$i][3] .= "<span class='am-badge am-badge-primary'>".$row->tag3."</span>\n";
        $view_problemset[$i][3] .= "</td>";
    }
    $view_problemset[$i][4] = "<td style='text-align:left;'><nobr>".mb_substr(($row->author?$row->author:$MSG_IMPORTED),0,40,'utf8')."</nobr></td >";
    // 题目来源 ajax html代码 start
    $view_problemset[$i][5] = "<td style='text-align:left;white-space:normal;'><div pid='".$row->problem_id."' fd='source' class='center' >\n";
    $view_problemset[$i][5] .= show_category($row->source,"default");
    if(HAS_PRI("edit_".get_problemset($row->problem_id)."_problem")) {
        $view_problemset[$i][5] .="<span><span class='am-icon-plus' pid='$row->problem_id' style='cursor: pointer;' onclick='problem_add_source(this,\"$row->problem_id\");'></span></span>&nbsp;\n";
    }
    $view_problemset[$i][5] .= "</div></td>";
    // 题目来源 ajax html代码 end
    $view_problemset[$i][6]="<td style='text-align:left;'><a href='status.php?problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a>/<a href='status.php?problem_id=".$row->problem_id."'>".$row->submit."</a></td>";
    $view_problemset[$i][7]="<td style='text-align:left;'>".round($row->score,2)."</td>";
    $i++;
}
$result->free();
/* 查询并把结果放入表格 end */

require("template/".$OJ_TEMPLATE."/problemset.php");
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');

?>
