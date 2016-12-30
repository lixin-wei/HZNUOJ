<?php
/**
 * This file is modified
 * by yybird
 * @2016.03.25
 **/
?>

<?php
require("admin-header.php");
//ini_set("display_errors","On");
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
?>
<?php
if(isset($_POST['do'])){
    require_once("../include/check_post_key.php");
    if (isset($_POST['from'])){
        $from=intval($_POST['from']);
        $to=intval($_POST['to']);
        $row=0;
        if($result=$mysqli->query("select 1 from problem where problem_id=$to")){
            $row=$result->num_rows;
            $result->free();
        }
        echo "$OJ_DATA/$from"."->"."$OJ_DATA/$to</br>";
        if($row==0){
            rename("$OJ_DATA/$from","$OJ_DATA/$to");
            $sql="UPDATE `problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
            if(!$mysqli->query($sql)){
                echo "filed table problem\n";
                rename("$OJ_DATA/$to","$OJ_DATA/$from");
                exit(1);
            }
            $sql="UPDATE `solution` SET `problem_id`=$to WHERE `problem_id`=".$from;
            if(!$mysqli->query($sql)){
                echo "filed table solution\n";
                rename("$OJ_DATA/$to","$OJ_DATA/$from");
                exit(1);
            }
            $sql="UPDATE `contest_problem` SET `problem_id`=$to WHERE `problem_id`=".$from;
            if(!$mysqli->query($sql)){
                echo "filed table contest_problem\n";
                rename("$OJ_DATA/$to","$OJ_DATA/$from");
                exit(1);
            }
            $sql="UPDATE `topic` SET `pid`=$to WHERE `pid`=".$from;
            if(!$mysqli->query($sql)){
                echo "filed table topic\n";
                rename("$OJ_DATA/$to","$OJ_DATA/$from");
                exit(1);
            }
            $sql="UPDATE `problem_samples` SET `problem_id`=$to WHERE `problem_id`=".$from;
            if(!$mysqli->query($sql)){
                echo "filed table problem_samples\n";
                rename("$OJ_DATA/$to","$OJ_DATA/$from");
                exit(1);
            }
            $sql="select max(problem_id) from problem";
            if($result=$mysqli->query($sql)){
                $f=$result->fetch_array();
                $nextid=$f[0]+1;
                $result->free();
                $sql="ALTER TABLE problem AUTO_INCREMENT = $nextid";
                $mysqli->query($sql);
            }
            
            echo "done!";
        }else{
            echo "problem $to already exists!";
        }
        
    }
}

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
  <li>Problem
  <form action='problem_changeid.php' method=post>
    Move<input type=input name='from'>->
    <input type=input name='to'>
    <input type='hidden' name='do' value='do'>
    <input type=submit value=submit>
      <?php require_once("../include/set_post_key.php");?>
  </form>

<?php }
?>
<?php
require_once("admin-footer.php")
?>