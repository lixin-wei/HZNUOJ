<?php
  /**
   * This file is created
   * by yybird
   * @2016.02.29
   * last modified
   * by yybird
   * @2016.02.29
  **/
?>

<?php

  /*
   * 该文件会将problem表中的$origin替换为$dest
   * 用处是在OJ目录更新时同步更新图片的目录
   */

  require_once('../include/db_info.inc.php');

  $origin = "JudgeOnline";
  $dest = "OJ";

  $sql = "SELECT problem_id, description FROM problem";
  $result = mysql_query($sql);
  $a = array();

  while ($row = mysql_fetch_array($result)) {
    $pid = $row['problem_id'];  
    $a[$pid] = $row['description'];
    $a[$pid] = str_replace($origin, $dest, (string)$a[$pid]);
    // file_put_contents('log.txt',$a[$pid],FILE_APPEND); 
  }

//  echo $a[1848];

  // echo $a[2014]; 
  for ($i=1000; $i<=2014; $i++) {
    $sql = "UPDATE problem SET description='$a[$i]' WHERE problem_id=$i";
    mysql_query($sql);
  }

?>