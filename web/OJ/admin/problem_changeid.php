<?php
/**
 * This file is modified
 * by yybird
 * @2016.03.25
 **/
?>

<?php
require("admin-header.php");
ini_set("display_errors","On");
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
if(isset($_POST['do'])){
    require "../include/check_post_key.php";
}
?>
<?php

$show_form=true;
if(!isset($OJ_SAE)||!$OJ_SAE){
    if(!writable($OJ_DATA)){
        echo " You need to add  $OJ_DATA into your open_basedir setting of php.ini,<br>
          or you need to execute:<br>
             <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
          you can't use import function at this time.<br>";
        $show_form=false;
    }
}
if($show_form){
    ?>
  <title>Change ProblemID</title>
  <h1>Change ProblemID</h1><hr>
  <ol>
    <li>Move Problem
      <form action='problem_changeid.php' method=post>
        Move<input type=input name='a'>->
        <input type=input name='b'>
        <input type='hidden' name='do' value='move'>
        <input type=submit value=submit>
        <?php require "../include/set_post_key.php"?>
      </form>
    </li>
    <li>Swap Problem
      <form action='problem_changeid.php' method=post>
        Swap<input type=input name='a'>with
        <input type=input name='b'>
        <input type=submit value=submit>
        <input type='hidden' name='do' value='swap'>
        <?php require "../include/set_post_key.php"?>
      </form>
    </li>
  </ol>
<hr/>
<?php }
?>
<?php
function move_problem($from, $to) {
    echo "<pre>moving problem:$from to problem:$to\n";
    $row=0;
    global $mysqli;
    global $OJ_DATA;
    if($result=$mysqli->query("select 1 from problem where problem_id=$to")){
        $row=$result->num_rows;
        $result->free();
    }
    
    if($row==0){
        echo "moving $OJ_DATA/$from"."->"."$OJ_DATA/$to...";
        rename("$OJ_DATA/$from","$OJ_DATA/$to");
        echo "done\n";
        
        echo "updating problem table...";
        $sql="UPDATE `problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!$mysqli->query($sql)){
            echo "filed table problem\n";
            rename("$OJ_DATA/$to","$OJ_DATA/$from");
            exit(1);
        }
        echo "done\n";
        
        echo "updating solution table...";
        $sql="UPDATE `solution` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!$mysqli->query($sql)){
            echo "filed table solution\n";
            rename("$OJ_DATA/$to","$OJ_DATA/$from");
            exit(1);
        }
        echo "done\n";
        
        echo "updating contest_problem table...";
        $sql="UPDATE `contest_problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!$mysqli->query($sql)){
            echo "filed table contest_problem\n";
            rename("$OJ_DATA/$to","$OJ_DATA/$from");
            exit(1);
        }
        echo "done\n";
        
        echo "updating topic table...";
        $sql="UPDATE `topic` SET `pid`=$to WHERE `pid`=".$from;
        if(!$mysqli->query($sql)){
            echo "filed table topic\n";
            rename("$OJ_DATA/$to","$OJ_DATA/$from");
            exit(1);
        }
        echo "done\n";
        
        echo "updating problem_samples table...";
        $sql="UPDATE `problem_samples` SET `problem_id`=$to WHERE `problem_id`=".$from;
        if(!$mysqli->query($sql)){
            echo "filed table problem_samples\n";
            rename("$OJ_DATA/$to","$OJ_DATA/$from");
            exit(1);
        }
        echo "done\n";
        
        
        $sql="select max(problem_id) from problem";
        if($result=$mysqli->query($sql)){
            $f=$result->fetch_array();
            $nextid=$f[0]+1;
            $result->free();
            $sql="ALTER TABLE problem AUTO_INCREMENT = $nextid";
            $mysqli->query($sql);
        }
        
        echo "all done!";
    }else{
        echo "problem $to already exists!";
    }
    echo "</pre>";
}
if(isset($_POST['do'])){
    if (isset($_POST['a'])){
        $a=intval($_POST['a']);
        $b=intval($_POST['b']);
        if($_POST['do']=="move") {
            move_problem($a,$b);
        }
        else if($_POST['do']=="swap") {
            $sql = "SELECT MAX(problem_id) FROM problem";
            $temp_id = $mysqli->query($sql)->fetch_array()[0]+100;
            move_problem($a,$temp_id);
            move_problem($b,$a);
            move_problem($temp_id,$b);
        }
    }
}
?>
<?php
require_once("admin-footer.php")
?>