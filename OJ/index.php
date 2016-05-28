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


// 旧版公告查询代码
/*  $view_news = "";
  $sql= "SELECT * "
      ."FROM `news` "
      ."WHERE `defunct`!='Y'"
      ."ORDER BY `importance` ASC,`time` DESC "
      ."LIMIT 10";
  $result = mysql_query($sql); //mysqli_escape_string($sql));

  if (!$result){
    $view_news = "<h3>No News Now!</h3>";
    $view_news .= mysql_error();
  } else {
    $view_news.= "<table width=96%>";
    
    while ($row=mysql_fetch_object($result)) {
      
      $view_news.= "<tr><td><td><big><b>".$row->title."</b></big>-<small>[".$row->user_id."]</small></tr>";
      $view_news.= "<tr><td><td>".$row->content."</tr>";
    }
    mysql_free_result($result);
    $view_news.= "<tr><td width=20%><td>This <a href=http://cm.baylor.edu/welcome.icpc>ACM/ICPC</a> OnlineJudge is a GPL product from <a href=https://github.com/zhblue/hustoj>hustoj</a></tr>";
    $view_news.= "</table>";
  }
$view_apc_info="";*/


/*

$sql= "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc ";
  $result=mysql_query($sql);//mysqli_escape_string($sql));
  $chart_data_all= array();
//echo $sql;
    
  while ($row=mysql_fetch_array($result)){
    $chart_data_all[$row['md']]=$row['c'];
    }
    
$sql= "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc ";
  $result=mysql_query($mysqlOJ, $sql);//mysqli_escape_string($sql));
  $chart_data_ac= array();
//echo $sql;
    
  while ($row=mysql_fetch_array($result)){
    $chart_data_ac[$row['md']]=$row['c'];
    }
    

if(function_exists('apc_cache_info')){
   $_apc_cache_info = apc_cache_info(); 
    $view_apc_info =_apc_cache_info;
}
*/

  /* 获取轮播图片路径 start */
  $slider_url = array();
  $sql = "SELECT * FROM slide WHERE defunct!='Y' ORDER BY img_id DESC LIMIT 5";
  $result = mysql_query($sql);
  for ($i=0; $row=mysql_fetch_array($result); ++$i) {
    $slider_url[$i] = $row['url'];
  }
  mysql_free_result($result);
  /* 获取轮播图片路径 end */

  /* 获取公告 start */
  $news_title = array();
  $news_content = array();
  $news_importance = array();
  $i = 0;
  // 获取置顶公告
  $sql = "SELECT * FROM `news` WHERE `defunct`!='Y' AND importance='10' ORDER BY `importance` DESC,`time` DESC LIMIT 5"; 
  $result = mysql_query($sql);
  for (; $row=mysql_fetch_array($result); ++$i) {
    $news_title[$i] = $row['title']."&nbsp;&nbsp;&nbsp;&nbsp;<b>[置顶]</b>";
    $news_content[$i] = $row['content'];
    $news_importance[$i] = $row['importance'];
  }
  mysql_free_result($result);
  // 获取非置顶公告
  $sql= "SELECT * FROM `news` WHERE `defunct`!='Y' AND importance!='10' ORDER BY `importance` DESC,`time` DESC LIMIT 5"; 
  $result = mysql_query($sql);
  for (; $row=mysql_fetch_array($result); ++$i) {
    $news_title[$i] = $row['title'];
    $news_content[$i] = $row['content'];
    $news_importance[$i] = $row['importance'];
    if ($news_importance[$i] == 1) $news_title[$i] .= "&nbsp;&nbsp;&nbsp;&nbsp;<i class='am-icon-star-o'></i>";
    if ($news_importance[$i] == 2) $news_title[$i] .= "&nbsp;&nbsp;&nbsp;&nbsp;<i class='am-icon-star-half-o'></i>";
    if ($news_importance[$i] == 3) $news_title[$i] .= "&nbsp;&nbsp;&nbsp;&nbsp;<i class='am-icon-star'></i>";
  }
  mysql_free_result($result);
  /* 获取公告 end */


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/index.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>
