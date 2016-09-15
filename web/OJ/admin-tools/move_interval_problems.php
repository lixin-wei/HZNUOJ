<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.25
  **/
?>

<?php

  require_once("../include/db_info.inc.php");
  echo "string";
  if (!HAS_PRI("inner_function")) {
    echo "Permission denied!";
    exit(1);
  }


  function writable($path){
    $ret=false;
    $fp=fopen($path."/testifwritable.tst","w");
    $ret=!($fp===false);
    fclose($fp);
    unlink($path."/testifwritable.tst");
    return $ret;
  }
    for($from=500000 ; $from<=500217 ; $from++){
      $to=$from-500000+1+1926;
      $row=0;
      if($result=mysql_query("select 1 from problem where problem_id=$to")){
        $row=mysql_num_rows($result);
        mysql_free_result($result);
      }
      
      if($row==0){
        rename("$OJ_DATA/$from","$OJ_DATA/$to");
        $sql="UPDATE `problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!mysql_query($sql)){
           rename("$OJ_DATA/$to","$OJ_DATA/$from");
           exit(1);
        }
        $sql="UPDATE `solution` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!mysql_query($sql)){
           rename("$OJ_DATA/$to","$OJ_DATA/$from");
           exit(1);
        }
        $sql="UPDATE `contest_problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!mysql_query($sql)){
           rename("$OJ_DATA/$to","$OJ_DATA/$from");
           exit(1);
        }
        $sql="UPDATE `topic` SET `pid`=$to WHERE `pid`=".$from;
        if(!mysql_query($sql)){
           rename("$OJ_DATA/$to","$OJ_DATA/$from");
           exit(1);
        }
        $sql="select max(problem_id) from problem";
        if($result=mysql_query($sql)){
          $f=mysql_fetch_array($result);
          $nextid=$f[0]+1;
          mysql_free_result($result);
          $sql="ALTER TABLE problem AUTO_INCREMENT = $nextid";
          mysql_query($sql);
        }
        
        echo "done!";
      }else{
        
          echo "fail...";
      }

    }
?>