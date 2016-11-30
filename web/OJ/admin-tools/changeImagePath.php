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

  $origin = "/web/OJ";
  $dest = "/OJ";

  $sql = "SELECT problem_id, description FROM problem";
  $result = $mysqli->query($sql);
  $a = array();

  while ($row = $result->fetch_array()) {
    $pid = $row['problem_id'];  
    $a[$pid] = $row['description'];
    $a[$pid] = str_replace($origin, $dest, (string)$a[$pid]);
    // echo substr(strstr((string)$a[$pid], $origin), 0, 10)."<br>";
    // file_put_contents('log.txt',$a[$pid],FILE_APPEND); 
  }

//  echo $a[1848];

   echo $a[500200]; 
  for ($i=500000; $i<=500208; $i++) {
    $sql = "UPDATE problem SET description='$a[$i]' WHERE problem_id=$i";
    $mysqli->query($sql);
  }

?>