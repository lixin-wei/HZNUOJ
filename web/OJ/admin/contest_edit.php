<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.28
 **/
?>

<?php
require("admin-header.php");
include_once("kindeditor.php") ;
include_once("../include/const.inc.php");

if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
}

if (isset($_POST['startdate'])) { // 如果有POST过来的信息，则获取POST值并更新
    /* 更新部分 start */
    require_once("../include/check_post_key.php");
    $starttime=$_POST['startdate']." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
    $endtime=$_POST['enddate']." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";    
    
    $title = $mysqli->real_escape_string($_POST['title']);
    $password=$mysqli->real_escape_string($_POST['password']);
    $description=$mysqli->real_escape_string($_POST['description']);
    $private=$mysqli->real_escape_string($_POST['private']);
    $user_limit = $mysqli->real_escape_string($_POST['user_limit']);
    $defunct_TA = $mysqli->real_escape_string($_POST['defunct_TA']);
    $open_source = $mysqli->real_escape_string($_POST['open_source']);
    $practice = $mysqli->real_escape_string($_POST['practice']);
    if (get_magic_quotes_gpc ()) {
        $title = stripslashes ( $title);
        $private = stripslashes ($private);
        $password = stripslashes ( $password);
        $description = stripslashes ( $description);
    }
    
    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t){
        $langmask+=1<<$t;
    }
    //echo $langmask;
    
    $cid=intval($_POST['cid']);
    $sql = "UPDATE `contest` 
            SET `title`='$title',description='$description',`start_time`='$starttime',`end_time`='$endtime',
                `private`='$private', user_limit='$user_limit', defunct_TA='$defunct_TA', open_source='$open_source',
                `practice` = $practice, `langmask`=$langmask  ,password='$password'
            WHERE `contest_id`=$cid";
    //echo $sql;
    $mysqli->query($sql) or die($mysqli->error);
    //更新题目列表和分值 start
    $sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
    $mysqli->query($sql);
    $sql = "UPDATE solution SET num=-1 WHERE contest_id = $cid";
    $mysqli->query($sql);
	  $temp=stripslashes(trim($_POST['cproblem']));
    if($temp!="") {	
	    $plist=explode("\n", $temp);
      $score_list=explode("\n", trim($_POST['score_list']));
        $sql_1=<<<TEXT
      INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`, score) VALUES
TEXT;
        foreach ($plist as $i => $pid) {
            $pid = intval($pid);
            $score = 100; //每题分值若留空则默认100分
			if($i < count($score_list) && $score_list[$i]!="") {
            $score = intval($score_list[$i]);
			}
            if($i) $sql_1 .= ",";
            $sql_1=$sql_1."('$cid','$pid',$i,$score)";
        
            $sql_2="update solution set num='$i' where contest_id='$cid' and problem_id='$pid';";
            $mysqli->query($sql_2);
        
        }
        //var_dump($sql_1);die(0);
        $mysqli->query($sql_1) or die($mysqli->error);
    }
  //更新题目列表和分值 end
  
  //更新比赛不参加排名用户 start
    $sql="DELETE FROM contest_excluded_user WHERE contest_id=$cid";
    $mysqli->query($sql);
    $temp=stripslashes(trim($_POST['ex_ulist']));
    if($temp!=""){
        $ex_users=explode("\n",$temp);
        $sql="INSERT INTO contest_excluded_user (contest_id,user_id) VALUES";
        foreach ($ex_users as $i => $uid) {
          $uid=trim($uid);
          if($i) $sql .= ",";
          $sql.="($cid,'$uid')";
        }
        $mysqli->query($sql);
    }
//更新比赛不参加排名用户 end
	//更新私有比赛免密用户 start
	$sql = "DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
	$mysqli->query($sql);
	$temp=stripslashes(trim($_POST['ulist']));
    if($temp!=""){
		$users=explode("\n",$temp);
		$sql="INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES";
		foreach ($users as $i => $uid) {
			$uid=trim($uid);
			if($i) $sql .= ",";
			$sql.="('$uid','c$cid')";               
		}
		$mysqli->query($sql) or die($mysqli->error);
	}
	//更新私有比赛免密用户 end	
    echo "<pre>done!</pre>";
	  echo "<script language='javascript'>\n";
    echo "history.go(-2);\n";
    echo "</script>";
	//echo "<script>window.location.href=\"contest_list.php\";</script>";
    /* 更新部分 end */
    
}

$cid=intval($_GET['cid']);
$sql="SELECT * FROM `contest` WHERE `contest_id`=$cid";
$result=$mysqli->query($sql);
if ($result->num_rows!=1){
    $result->free();
    echo "No such Contest!";
    exit(0);
}
$row=$result->fetch_array();
$starttime=$row['start_time'];
$endtime=$row['end_time'];
$private=$row['private'];
$user_limit = $row['user_limit']=="Y"?'Y':'N';
$defunct_TA = $row['defunct_TA']=="Y"?'Y':'N';
$open_souce = $row['open_source']=="Y"?'Y':'N';
$practice = $row['practice'];
$password=$row['password'];
$langmask=$row['langmask'];
$description=$row['description'];
$description = str_replace("<!---->","",$description);//kindeditor会在内容的末尾加入<!---->
$title=htmlentities($row['title'],ENT_QUOTES,"UTF-8");
$result->free();
$plist="";
$slist="";
$sql="SELECT `problem_id`, score FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
$result=$mysqli->query($sql) or die($mysqli->error);
for ($i=$result->num_rows;$i>0;$i--){
    $row=$result->fetch_row();
    $plist.=$row[0];
	  $slist.=$row[1];
    if ($i>1) {
      $plist.="\n";
      $slist.="\n";
	  }
}

$ulist="";
$sql="SELECT `user_id` FROM `privilege` WHERE `rightstr`='c$cid' order by user_id";
$result=$mysqli->query($sql) or die($mysqli->error);
for ($i=$result->num_rows;$i>0;$i--){
    $row=$result->fetch_row();
    $ulist=$ulist.$row[0];
    if ($i>1) $ulist=$ulist."\n";
}
$ex_ulist="";
$sql="SELECT user_id FROM contest_excluded_user WHERE contest_id=$cid";
$result=$mysqli->query($sql);
for ($i=$result->num_rows;$i>0;$i--){
    $row=$result->fetch_row();
    $ex_ulist.=$row[0];
    if ($i>1) $ex_ulist.="\n";
}
?>
<title><?php echo $html_title.$MSG_EDIT.$MSG_CONTEST ?></title>
<form method=POST >
    <?php require_once("../include/set_post_key.php");?>
  <h1>
    <?php echo $MSG_EDIT.$MSG_CONTEST ?>:
      <?php
      echo "<a href='/OJ/contest.php?cid={$_GET['cid']}'>{$_GET['cid']}</a>"
      ?>
  </h1>
  <input type=hidden name='cid' value='<?php echo $cid?>'>
  <table class='table table-condensed' style='white-space: nowrap;'><tr><td>
  <p><input type=submit value="<?php echo $MSG_SUBMIT ?>" name=submit>&nbsp;<input type=reset value="<?php echo $MSG_RESET ?>" name=reset></p>
  <p align=left><strong><?php echo $MSG_TITLE ?>&nbsp;:</strong><input class=input-xxlarge type=text name=title size=71 value='<?php echo $title?>' required></p>
  <p align=left><strong><?php echo $MSG_StartTime ?>&nbsp;:</strong>
  <input class=input-large type=date name='startdate' value='<?php echo substr($starttime,0,10)?>' size=10 >&nbsp;
    Hour:<input class=input-mini  type=text name=shour size=2 value='<?php echo substr($starttime,11,2)?>'>&nbsp;
    Minute:<input class=input-mini  type=text name=sminute size=2 value='<?php echo substr($starttime,14,2)?>'></p>
  <p align=left><strong><?php echo $MSG_EndTime ?>&nbsp;:</strong>
    <input class=input-large type=date name='enddate' value='<?php echo substr($endtime,0,10)?>' size=10 >&nbsp;
    Hour:<input class=input-mini  type=text name=ehour size=2 value='<?php echo substr($endtime,11,2)?>'>&nbsp;
    Minute:<input class=input-mini  type=text name=eminute size=2 value='<?php echo substr($endtime,14,2)?>'>
  </p>
  <div style="color: #ff0000">
    visit <a href="../faqs.php#p-5" target="_blank">FAQ</a> to know differences between types of contest.
        <br />
        <strong><?php echo $MSG_Importance."&nbsp;:&nbsp;".$MSG_Practice."&nbsp;&gt;&nbsp;".$MSG_Special."&nbsp;&gt;&nbsp;".$MSG_Public."/".$MSG_Private."(密码最多15位，只能包含数字、字母和下划线)"  ?></strong> </div>
 <p align=left> <strong><?php echo $MSG_Practice ?>&nbsp;:</strong>&nbsp;
  <select name='practice' style='width:100px'>
    <option value='1' <?php echo $practice==1?'selected=selected':''?>>Yes</option>
    <option value='0' <?php echo $practice==0?'selected=selected':''?>>No</option>
  </select>&nbsp;&nbsp;
  <strong><?php echo $MSG_Special ?>&nbsp;:</strong>&nbsp;
  <select name='user_limit' style='width:50px'>
    <option value='Y' <?php echo $user_limit=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $user_limit=='N'?'selected=selected':''?>>No</option>
  </select>&nbsp;&nbsp;
  <strong>Defunct TA&nbsp;:</strong>&nbsp;
  <select name='defunct_TA' style='width:50px'>
    <option value='Y' <?php echo $defunct_TA=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $defunct_TA=='N'?'selected=selected':''?>>No</option>
  </select>
  </p>
  <p align=left><strong><?php echo $MSG_Public."/".$MSG_Private ?>&nbsp;:</strong>&nbsp;
  <select name='private' style='width:80px'>
    <option value=0 <?php echo $private=='0'?'selected=selected':''?>><?php echo $MSG_Public ?></option>
    <option value=1 <?php echo $private=='1'?'selected=selected':''?>><?php echo $MSG_Private ?></option>
  </select>&nbsp;&nbsp;  
  <strong><?php echo $MSG_PASSWORD ?>:</strong>&nbsp;<input name=password type=text style='width:150px' value="<?php echo htmlentities($password,ENT_QUOTES,'utf-8')?>" pattern="^[_a-zA-Z0-9]{1,15}$" maxlength="15">&nbsp;&nbsp;
  <strong><?php echo $MSG_OpenSource ?>&nbsp;:</strong>&nbsp;
  <select name='open_source' style='width:50px'>
    <option value='Y' <?php echo $open_souce=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $open_souce=='N'?'selected=selected':''?>>No</option>
  </select>
  </p>
  <p align=left>
  </p>
    <table >
    <tr>
        <td>
        <strong><?php echo $MSG_LANG ?>&nbsp;:</strong><br />
  <select name="lang[]" size="13"  multiple="multiple" required>
      <?php
      $lang_count=count($language_ext);
      $lang=$langmask;
      if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
      else $lastlang=0;
      for($i=0;$i<$lang_count;$i++){
        $j = $language_order[$i];
        if($OJ_LANGMASK & (1<<$j))
          echo  "<option value=$j ".( $lang&(1<<$j)?"selected":"").">
              ".$language_name[$j]."
        </option>";
      }
      ?>
  </select>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td><td>
  <strong><?php echo $MSG_PROBLEM_ID ?>&nbsp;:</strong><br />
  <textarea name="cproblem" cols="15" rows="10" required placeholder="*示例:<?php echo "\n"?>1000<?php echo "\n"?>1001<?php echo "\n"?>1002<?php echo "\n"?>"><?php if(isset($plist)){ echo $plist;}?></textarea>
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
  </td><td>
  <strong><?php echo $MSG_SCORE ?>&nbsp;:</strong><br />
  <textarea name="score_list" cols="15" rows="10"  placeholder="示例:<?php echo "\n"?>100<?php echo "\n"?>100<?php echo "\n"?>100<?php echo "\n"?>每题所占分值，留空则默认每题100分。"><?php if(isset($slist)){ echo $slist;}?></textarea>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 </td><td>
 <strong><?php echo $MSG_CONTEST."-".$MSG_USER ?>&nbsp;:</strong><br />
  <textarea name="ulist" cols="15" rows="10"  placeholder="示例:<?php echo "\n"?>user1<?php echo "\n"?>user2<?php echo "\n"?>user3<?php echo "\n"?>可以将学生用户名从Excel整列复制过来，学生登录后就能免密进入<?php echo $MSG_Private ?>的比赛作为作业和测验。"><?php if(isset($ulist)){ echo $ulist;}?></textarea>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td><td>
  <strong><?php echo $MSG_RankingExcludedUsers ?>&nbsp;:</strong><br />
  <textarea name="ex_ulist" cols="15" rows="10" placeholder="示例:<?php echo "\n"?>user1<?php echo "\n"?>user2<?php echo "\n"?>user3<?php echo "\n"?>填入不参与比赛排名的用户名。"><?php if (isset($ex_ulist)) { echo $ex_ulist; } ?></textarea>
        </td>
    </tr>
</table><br>
  <p align=left><strong><?php echo $MSG_Description ?>:</strong><br><textarea class="kindeditor" rows=13 name=description cols=80><?php echo htmlentities($description,ENT_QUOTES,"UTF-8")?></textarea></p>
    
  <p><input type=submit value="<?php echo $MSG_SUBMIT ?>" name=submit>&nbsp;<input type=reset value="<?php echo $MSG_RESET ?>" name=reset></p>
  
  </td></tr></table>
</form>
<?php require_once("admin-footer.php");?>
