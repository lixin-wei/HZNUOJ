<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php

  function addproblem($problemset, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $author, $source, $spj,$OJ_DATA) {
    $title=mysql_real_escape_string($title);
    $problemset=mysql_real_escape_string($problemset);
    $time_limit=mysql_real_escape_string($time_limit);
    $memory_limit=mysql_real_escape_string($memory_limit);
    $description=mysql_real_escape_string($description);
    $input=mysql_real_escape_string($input);
    $output=mysql_real_escape_string($output);
    $sample_input=mysql_real_escape_string($sample_input);
    $sample_output=mysql_real_escape_string($sample_output);
  //  $test_input=($test_input);
  //  $test_output=($test_output);
    $hint=mysql_real_escape_string($hint);
    $author = mysql_real_escape_string($author);
    $source=mysql_real_escape_string($source);
  //  $spj=($spj);
    $sql = "INSERT into `problem` (`problemset`,`title`,`time_limit`,`memory_limit`,
    `description`,`input`,`output`,`sample_input`,`sample_output`,`hint`, author, `source`,`spj`,`in_date`,`defunct`)
    VALUES('$problemset','$title','$time_limit','$memory_limit','$description','$input','$output',
        '$sample_input','$sample_output','$hint','$author','$source','$spj',NOW(),'Y')";
    @mysql_query ( $sql ) or die ( mysql_error () );
    $pid = mysql_insert_id ();
    // echo $sql;
    echo '$problemset:'.$problemset;
    echo "<pre>".$sql."</pre>";
    echo "<br>Add $pid  ";
    if (isset ( $_POST ['contest_id'] )) {
      $sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=" . strval ( intval ( $_POST ['contest_id'] ) );
      $result = @mysql_query ( $sql ) or die ( mysql_error () );
      $row = mysql_fetch_row ( $result );
      $cid = $_POST ['contest_id'];
      $num = $row [0];
      echo "Num=" . $num . ":";
      $sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES('$pid','$cid','$num')";
      mysql_free_result ( $result );
      mysql_query ( $sql );
    }
    $basedir = "$OJ_DATA/$pid";
    if(!isset($OJ_SAE)||!$OJ_SAE){
        echo "[$title]data in $basedir";
    }
    return $pid;
  }
  function mkdata($pid,$filename,$input,$OJ_DATA){
    
    $basedir = "$OJ_DATA/$pid";
    
    $fp = @fopen ( $basedir . "/$filename", "w" );
    if($fp){
      fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
      fclose ( $fp );
    }else{
      echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
      
    }

  }

?>
