<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.22
  **/
?>

<?php
////////////////////////////Common head
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Welcome To Online Judge";
///////////////////////////MAIN 
$news_id = array();
$news_title = array();
$news_importance = array();
$i = 0;
$sql = <<<SQL
  SELECT
    news_id,
    title,
	`time`,
    importance
  FROM
    `news`
  WHERE
    `defunct` != 'Y'
  ORDER BY
    `importance` DESC,
    `time` DESC
SQL;

$result = $mysqli->query($sql);
for (; $row=$result->fetch_array(); ++$i) {	
  $news_id[$i]=$row['news_id'];
  $news_title[$i] = $row['title']."&nbsp;&nbsp;&nbsp;&nbsp;[".date("Y-m-d",strtotime($row['time']))."]";
  if($row['importance']==10)$news_title[$i].="&nbsp;&nbsp;&nbsp;&nbsp;<b>[置顶]</b>";
  $news_title[$i].="&nbsp&nbsp<i id='news-load-icon-{$row['news_id']}' style='display:none;' class='am-icon-spinner am-icon-pulse'></i>";
  $news_importance[$i] = $row['importance'];
}
/* 获取公告 end */


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/index.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>