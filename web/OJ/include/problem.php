<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php

  function addproblem($problemset, $title, $time_limit, $memory_limit, $description, $input, $output, $hint, $author, $source, $spj,$OJ_DATA) {
    global $mysqli, $MSG_PROBLEMSET, $MSG_TestData, $MSG_ADD, $OJ_SAE;
    $title=$mysqli->real_escape_string($title);
    $problemset=$mysqli->real_escape_string($problemset);
    $time_limit=$mysqli->real_escape_string($time_limit);
    $memory_limit=$mysqli->real_escape_string($memory_limit);
    $description=$mysqli->real_escape_string($description);
    $input=$mysqli->real_escape_string($input);
    $output=$mysqli->real_escape_string($output);
  //  $sample_input=$mysqli->real_escape_string($sample_input);
  //  $sample_output=$mysqli->real_escape_string($sample_output);
  //  $test_input=($test_input);
  //  $test_output=($test_output);
    $hint=$mysqli->real_escape_string($hint);
    $author = $mysqli->real_escape_string($author);
    $source=$mysqli->real_escape_string($source);
  //  $spj=($spj);
    $sql = "SELECT * FROM `problemset` WHERE `set_name`='$problemset'";
    $result = @$mysqli->query ( $sql );
    if($row=$result->fetch_object()){
      $problemset2=$problemset."【".$row->set_name_show."】";
    } else {
      $problemset="default";
      $problemset2="default";
    }
    $sql = "INSERT into `problem` (`problemset`,`title`,`time_limit`,`memory_limit`,
    `description`,`input`,`output`,`hint`, author, `source`,`spj`,`in_date`,`defunct`)
    VALUES('$problemset','$title','$time_limit','$memory_limit','$description','$input',
    '$output','$hint','$author','$source','$spj',NOW(),'Y')";
    @$mysqli->query ( $sql ) or die ( $mysqli->error );
    $pid = $mysqli->insert_id;
    // echo $sql;
    echo $MSG_PROBLEMSET.' : '.$problemset2;
    //echo "<pre>".$sql."</pre>";
    echo "<br>$MSG_ADD $pid ";
    if (isset ( $_POST['contest_id'] ) && $_POST['contest_id']!="") {
      $sql = "SELECT count(*) FROM `contest_problem` WHERE `contest_id`=" . strval ( intval ( $_POST ['contest_id'] ) );
      $result = @$mysqli->query ( $sql ) or die ( $mysqli->error );
      $row = $result->fetch_row();
      $cid = $_POST ['contest_id'];
      $num = $row [0];
      echo "Num=" . $num . ":";
      $sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES('$pid','$cid','$num')";
      $result->free();
      $mysqli->query ( $sql );
    }

    $basedir = "$OJ_DATA/$pid";
    if(!isset($OJ_SAE)||!$OJ_SAE){
        echo "[$title] $MSG_TestData in $basedir.<br>";
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

  function convert2UTF8($OJ_DATA,$pid,$filename){
    $basedir = "$OJ_DATA/$pid";
    $source = file_get_contents($basedir . "/$filename");
    $encode = mb_detect_encoding($source, array("ASCII","GB2312","GBK","UTF-8"));
    if($encode != "UTF-8"){
      $source = mb_convert_encoding($source, "UTF-8",$encode);
      mkdata($pid, $filename, $source, $OJ_DATA);//返写文件，以绝后患
    }
    return $source;
  }
?>
