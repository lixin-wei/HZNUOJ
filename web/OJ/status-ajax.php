<?php
  /**
   * This file is modified!
   * by yybird
   * @2015.07.02
  **/
?>

<?php
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  ////////////////////////////Common head
  $cache_time=2;
  $OJ_CACHE_SHARE=false;
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $view_title= "$MSG_STATUS";
  require_once("./include/const.inc.php");
  $solution_id=0;
  // check the top arg
  if (isset($_GET['solution_id'])){
    $solution_id=intval($_GET['solution_id']);
  }
  $sql="select * from solution where solution_id=$solution_id LIMIT 1";
  //echo $sql;
  if($OJ_MEMCACHE){
    require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
  } else {
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) 
      $rows_cnt=$result->num_rows;
    else 
      $rows_cnt=0;
  }
  for ($i=0; $i<$rows_cnt; $i++){
    if($OJ_MEMCACHE)
      $row=$result[$i];
    else
      $row=$result->fetch_array();
    if(isset($_GET['tr'])){
      $res=$row['result'];
      if ($res==11) {
        $sql="SELECT `error` FROM `compileinfo` WHERE `solution_id`='".$solution_id."'";
      } else {
        $sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$solution_id."'";
      }
      $result=$mysqli->query($sql);
      $row=$result->fetch_array();
      if($row){
        if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE"))
          echo str_replace("\n","<br>",htmlspecialchars(str_replace("\n\r","\n",$row['error'])));
        else
          echo htmlspecialchars(str_replace("\n\r","\n",$row['error']));
        $sql="delete from custominput where solution_id=".$solution_id;
        $mysqli->query($sql);     
      }
      //echo $sql.$res;
    }else{
      echo $row['result'].",".$row['memory'].",".$row['time'];
      //echo "hhhh".",".$row['memory'].",".$row['time'];
    }
}
if(!$OJ_MEMCACHE)$result->free();
?>