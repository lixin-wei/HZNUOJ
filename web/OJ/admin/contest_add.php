<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.28
  **/
?>

<?php require_once("admin-header.php");?>
<?php
  require_once("../include/db_info.inc.php");
  require_once("../include/const.inc.php");
  if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
  }
  $description="";
  if (isset($_POST['startdate'])) { // 如果有POST过来的信息，则获取POST值并添加contest记录
    require_once("../include/check_post_key.php");
	//新建contest start	    
	//添加contest记录 start
    $starttime=$_POST['startdate']." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
    $endtime=$_POST['enddate']." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";    

    $title=$mysqli->real_escape_string($_POST['title']);
    $private=$mysqli->real_escape_string($_POST['private']);
    $password=$mysqli->real_escape_string($_POST['password']);
	  $user_id=$_SESSION['user_id'];
    $description=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$_POST['description']));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $description = str_replace("<!---->","",$description);//火狐浏览器中kindeditor会在内容的末尾加入<!---->
    $user_limit = $mysqli->real_escape_string($_POST['user_limit']);
    $defunct_TA = $mysqli->real_escape_string($_POST['defunct_TA']);
    $open_source = $mysqli->real_escape_string($_POST['open_source']);
    $practice = $mysqli->real_escape_string($_POST['practice']);
    $unlock = intval($mysqli->real_escape_string($_POST['unlock']));
    switch($unlock){
      case 0:
        $lock_time = intval($mysqli->real_escape_string($_POST['lock_time']))*3600;
        break;
      case 2:
        $lock_time = intval($mysqli->real_escape_string($_POST['lock_time']));
        break;
      default:
        $lock_time = 0;
    }
    $first_prize = $mysqli->real_escape_string($_POST['first_prize']);
    $second_prize = $mysqli->real_escape_string($_POST['second_prize']);
    $third_prize = $mysqli->real_escape_string($_POST['third_prize']);

    if (get_magic_quotes_gpc ()){
      $title = stripslashes ($title);
      $private = stripslashes ($private);
      $password = stripslashes ($password);
      $description = stripslashes ($description);
    }

    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t) {
	  //echo $t." ";
      $langmask+=1<<$t;
    }
    $sql="INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`, user_limit, defunct_TA, open_source, practice,`user_id`,`unlock`,`lock_time`,`first_prize`,`second_prize`,`third_prize`)
          VALUES('$title','$starttime','$endtime','$private',$langmask,'$description','$password', '$user_limit', '$defunct_TA', '$open_source', '$practice', '$user_id','$unlock','$lock_time','$first_prize','$second_prize','$third_prize')";
  //echo $sql;
  $mysqli->query($sql) or die($mysqli->error);
  //添加contest记录 end  
  $cid=$mysqli->insert_id;
  echo "Add Contest ".$cid;
  //添加题目列表和分值  start
  $sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
  $mysqli->query($sql);
  $sql = "DELETE FROM solution  WHERE `contest_id`=$cid";
  $mysqli->query($sql);
  $temp=stripslashes(trim($_POST['cproblem']));
  if($temp!="") {
	  $plist=explode("\n", $temp);
	  $score_list=explode("\n", trim($_POST['score_list']));
    $sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`, score) VALUES";
      foreach ($plist as $i => $pid) {
        $pid = intval($pid);
        $score = 100; //每题分值若留空则默认100分
	    if($i < count($score_list) && $score_list[$i]!="") {
				$score = intval($score_list[$i]);
		}
        if($i) $sql_1 .= ",";
        $sql_1=$sql_1."('$cid','$pid',$i,$score)";        
      }	  
    //echo $sql_1;
    $mysqli->query($sql_1) or die($mysqli->error);
    // $sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
    // $mysqli->query($sql) or die($mysqli->error);
  }
  //添加题目列表和分值 end  
  //添加私有比赛免密用户及标记比赛创建者 start
  $sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
  $mysqli->query($sql);
  $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','m$cid')";
  $mysqli->query($sql);
  $_SESSION["m$cid"]=true;
  $temp=stripslashes(trim($_POST['ulist']));
  if($temp!=""){
	$users=explode("\n",$temp);  
  $sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES";
	foreach ($users as $i => $uid) {
		$uid=trim($uid);
		if($i) $sql_1 .= ",";
		$sql_1.="('$uid','c$cid')";
  }
    //echo $sql_1;
    $mysqli->query($sql_1) or die($mysqli->error);
  }
  //添加私有比赛免密用户及标记比赛创建者 end
  //添加比赛不参加排名用户 start
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

  //添加比赛不参加排名用户 end  
  echo "<script>window.location.href=\"contest_list.php\";</script>";
  //新建contest end
}
else{

   if(isset($_GET['cid'])){
   //复制比赛copy contest start
       $cid=intval($_GET['cid']);
       $sql="select * from contest WHERE `contest_id`='$cid'";
       $result=$mysqli->query($sql);
       if ($result->num_rows!=1){
          $result->free();
          echo "No such Contest!";
          exit(0);
        }
        $row=$result->fetch_array();
  $unlock=$row['unlock'];
  $lock_time=$row['lock_time'];
  $first_prize=$row['first_prize'];
  $second_prize=$row['second_prize'];
  $third_prize=$row['third_prize'];
	$private=$row['private'];
	$user_limit = $row['user_limit']=="Y"?'Y':'N';
	$defunct_TA = $row['defunct_TA']=="Y"?'Y':'N';
	$open_souce = $row['open_source']=="Y"?'Y':'N';
	$practice = $row['practice'];
	$password=$row['password'];
	$langmask=$row['langmask'];
  $description=str_replace("<br />\r\n<!---->","",$row['description']);//kindeditor会在空白内容的末尾加入<br />\r\n<!---->
  $description = str_replace("<!---->","",$description);//火狐浏览器中kindeditor会在内容的末尾加入<!---->
	$title=htmlentities($row['title'],ENT_QUOTES,"UTF-8")." copy";
      $result->free();
      $plist="";
      $slist="";
      $sql="SELECT `problem_id`, score FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
      $result=$mysqli->query($sql) or die($mysqli->error);
      for ($i=$result->num_rows;$i>0;$i--){
        $row=$result->fetch_row();
        $plist=$plist.$row[0];
        $slist=$slist.$row[1];
        if ($i>1) {
          $plist.="\n";
          $slist.="\n";
        }
      }
      $result->free();
	$ulist="";
	$sql="SELECT `user_id` FROM `privilege` WHERE `rightstr`='c$cid' order by user_id";
	$result=$mysqli->query($sql) or die($mysqli->error);
	for ($i=$result->num_rows;$i>0;$i--){
		$row=$result->fetch_row();
		$ulist.=$row[0];
		if ($i>1) $ulist.="\n";}
	$ex_ulist="";
	$sql="SELECT user_id FROM contest_excluded_user WHERE contest_id=$cid";
	$result=$mysqli->query($sql);
	for ($i=$result->num_rows;$i>0;$i--){
		$row=$result->fetch_row();
		$ex_ulist.=$row[0];
		if ($i>1) $ex_ulist.="\n";}
  $result->free();
   //复制比赛copy contest end
   }
else if(isset($_POST['problem2contest'])){
	    //从题库里面直接添加到比赛
     $plist="";
     //echo $_POST['pid'];
     sort($_POST['pid']);
     foreach($_POST['pid'] as $i =>  $pid){
			 if ($i) $plist.="\n";
			 $plist.=$pid;    
     }
}else if(isset($_GET['spid'])){
  require_once("../include/check_get_key.php");
       $spid=intval($_GET['spid']);
     
      $plist="";
      $sql="SELECT `problem_id` FROM `problem` WHERE `problem_id`>=$spid ";
      $result=$mysqli->query($sql) or die($mysqli->error);
      for ($i=$result->num_rows;$i>0;$i--){
        $row=$result->fetch_row();
        $plist=$plist.$row[0];
        if ($i>1) $plist.="\n";
      }
      $result->free();
}  
}
  include_once("kindeditor.php") ;
  $starttime = date('Y-m-d H:i:s',time());
  $endtime = date('Y-m-d H:i:s',strtotime("+4 hours"));
?>
  <title><?php echo $html_title.$MSG_ADD.$MSG_CONTEST ?></title>
  <form method=POST >
    <?php require_once("../include/set_post_key.php");?>
  <h1><?php echo $MSG_ADD.$MSG_CONTEST ?>:</h1>
  <table class='table table-condensed' style='white-space: nowrap;'><tr><td>
  <p><input type=submit value="<?php echo $MSG_SUBMIT ?>" name=submit>&nbsp;<input type=reset value="<?php echo $MSG_RESET ?>" name=reset></p>
  <p align=left><strong><?php echo $MSG_TITLE ?>&nbsp;:</strong><input class=input-xxlarge type=text name=title size=71 value='<?php echo $title?>' required></p>
  <p align=left><strong><?php echo $MSG_StartTime ?>&nbsp;:</strong>
  <input class=input-large type=date name='startdate' value='<?php echo substr($starttime,0,10) ?>' size=10 >&nbsp;
    Hour:<input class=input-mini  type=text name=shour size=2 value='<?php echo substr($starttime,11,2)?>'>&nbsp;
    Minute:<input class=input-mini  type=text name=sminute size=2 value='00'></p>
  <p align=left><strong><?php echo $MSG_EndTime ?>&nbsp;:</strong>
    <input class=input-large type=date name='enddate' value='<?php echo substr($endtime,0,10)?>' size=10 >&nbsp;
    Hour:<input class=input-mini  type=text name=ehour size=2 value='<?php echo substr($endtime,11,2)?>'>&nbsp;
    Minute:<input class=input-mini  type=text name=eminute size=2 value='00' >
  </p>
  <div style="color: #ff0000">
    visit <a href="../faqs.php#p-5" target="_blank">FAQ</a> to know differences between types of contest.
        <br />
        <strong><?php echo $MSG_Importance."&nbsp;:&nbsp;".$MSG_Practice."&nbsp;&gt;&nbsp;".$MSG_Special."&nbsp;&gt;&nbsp;".$MSG_Public."/".$MSG_Private."(密码最多15位，只能包含数字、字母和下划线)" ?></strong> </div>
 <p align=left> <strong><?php echo $MSG_Practice ?>&nbsp;:</strong>&nbsp;
  <select name='practice' style='width:100px'>
  <?php if(isset($_GET['cid'])){ ?>
    <option value='1' <?php echo $practice==1?'selected=selected':''?>>Yes</option>
    <option value='0' <?php echo $practice==0?'selected=selected':''?>>No</option>
  <?php  }else { 
	 echo "<option value='1'>Yes</option>";
     echo "<option value='0' selected='selected'>No</option>";
  } ?>
  </select>&nbsp;&nbsp;
  <strong><?php echo $MSG_Special ?>&nbsp;:</strong>&nbsp;
  <select name='user_limit' style='width:50px'>
  <?php if(isset($_GET['cid'])){ ?>
    <option value='Y' <?php echo $user_limit=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $user_limit=='N'?'selected=selected':''?>>No</option>
  <?php  }else { 
	 echo "<option value='Y'>Yes</option>";
     echo "<option value='N' selected='selected'>No</option>";
  } ?>
  </select>&nbsp;&nbsp;
  <strong>Defunct TA&nbsp;:</strong>&nbsp;
  <select name='defunct_TA' style='width:50px'>
  <?php if(isset($_GET['cid'])){ ?>
    <option value='Y' <?php echo $defunct_TA=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $defunct_TA=='N'?'selected=selected':''?>>No</option>
  <?php  }else { 
	 echo "<option value='Y'>Yes</option>";
     echo "<option value='N' selected='selected'>No</option>";
  } ?>
  </select>
  </p>
  <p align=left><strong><?php echo $MSG_Public."/".$MSG_Private ?>&nbsp;:</strong>&nbsp;
  <select name='private' style='width:80px'>
  <?php if(isset($_GET['cid'])){ ?>
    <option value=0 <?php echo $private=='0'?'selected=selected':''?>><?php echo $MSG_Public ?></option>
    <option value=1 <?php echo $private=='1'?'selected=selected':''?>><?php echo $MSG_Private ?></option>
  <?php  }else { 
	 echo "<option value='0' selected='selected'>$MSG_Public</option>";
     echo "<option value='1'>$MSG_Private</option>";
  } ?>
  </select>&nbsp;&nbsp;  
  <strong><?php echo $MSG_PASSWORD ?>:</strong>&nbsp;<input name=password type=text style='width:150px' value="<?php echo htmlentities($password,ENT_QUOTES,'utf-8')?>" pattern="^[_a-zA-Z0-9]{1,15}$" maxlength="15">&nbsp;&nbsp;
  <strong><?php echo $MSG_OpenSource ?>&nbsp;:</strong>&nbsp;
  <select name='open_source' style='width:50px'>
   <?php if(isset($_GET['cid'])){ ?>
    <option value='Y' <?php echo $open_souce=='Y'?'selected=selected':''?>>Yes</option>
    <option value='N' <?php echo $open_souce=='N'?'selected=selected':''?>>No</option>
  <?php  }else { 
	 echo "<option value='Y'>Yes</option>";
     echo "<option value='N' selected='selected'>No</option>";
  } ?>
  </select>
  </p>
  <p align=left>
    <strong><?php echo $MSG_LockBoard ?>&nbsp;:</strong>&nbsp;
  <select name='unlock' style='width:195px' onchange='if($(this).val()=="1") $("#lock_time").val("0"); else $("#lock_time").val("");'>
  <?php if(isset($_GET['cid'])){ ?>
    <option value='1' <?php echo $unlock==1?'selected=selected':''?>>No</option>
    <option value='0' <?php echo $unlock==0?'selected=selected':''?>><?php echo $MSG_LockByTime ?></option>
    <option value='2' <?php echo $unlock==2?'selected=selected':''?>><?php echo $MSG_LockByRate ?></option>
  <?php  }else { 
	   echo "<option value='1' selected='selected'>No</option>";
     echo "<option value='0'>$MSG_LockByTime</option>";
     echo "<option value='2'>$MSG_LockByRate</option>";
  } ?>
  </select>&nbsp;&nbsp;
  <strong><?php echo $MSG_LockTime ?>:</strong>&nbsp;<input name='lock_time' id='lock_time' type='number' style='width:50px' min="0" max="99" step="1" value="<?php if(isset($lock_time)&&$lock_time!="") {if($unlock==0) echo ceil($lock_time/3600); else echo $lock_time; } else echo 0?>" maxlength="2" required>
  </p>
  <p align=left>
  <strong><?php echo $MSG_GOLD ?>:</strong>&nbsp;<input name='first_prize' type='number' style='width:50px' min="0" max="99" step="1" value="<?php if(isset($first_prize)&&$first_prize!="") echo $first_prize; else echo 1?>" maxlength="2" required>&nbsp;&nbsp;
  <strong><?php echo $MSG_SILVER ?>:</strong>&nbsp;<input name='second_prize' type='number' style='width:50px' min="0" max="99" step="1" value="<?php if(isset($second_prize)&&$second_prize!="") echo $second_prize; else echo 3?>" maxlength="2" required>&nbsp;&nbsp;
  <strong><?php echo $MSG_BRONZE ?>:</strong>&nbsp;<input name='third_prize' type='number' style='width:50px' min="0" max="99" step="1" value="<?php if(isset($third_prize)&&$third_prize!="") echo $third_prize; else echo 5?>" maxlength="2" required>
  </p>
    <table >
    <tr>
        <td><strong><?php echo $MSG_LANG ?>&nbsp;:</strong></td>
        <td><strong><?php echo $MSG_PROBLEM_ID ?>&nbsp;:</strong></td>
        <td><strong><?php echo $MSG_SCORE ?>&nbsp;:</strong></td>
        <td><strong><?php echo $MSG_CONTEST."-".$MSG_USER ?>&nbsp;:</strong></td>
        <td><strong><?php echo $MSG_RankingExcludedUsers ?>&nbsp;:</strong></td>
    </tr>
    <tr>
        <td>
  <select name="lang[]" size="13"  multiple="multiple" required>
      <?php
      $lang_count=count($language_ext);
	  if(isset($langmask)){
          $lang=$langmask;
	  } else {
		  $lang=$OJ_LANGMASK;
	  }
      if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
      else $lastlang=0;
      for($i=0;$i<$lang_count;$i++){		  
        $j = $language_order[$i];
	    if($OJ_LANGMASK & (1<<$j))
          echo  "<option value=$j ".( $lang&(1<<$j)?"selected":"").">".$language_name[$j]."</option>\n";
      }
      ?>
  </select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td><td>
  <textarea name="cproblem" cols="15" rows="10" required placeholder="*示例:<?php echo "\n"?>1000<?php echo "\n"?>1001<?php echo "\n"?>1002<?php echo "\n"?>"><?php if(isset($plist)){ echo $plist;}?></textarea>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  </td><td>
  <textarea name="score_list" cols="15" rows="10"  placeholder="示例:<?php echo "\n"?>100<?php echo "\n"?>100<?php echo "\n"?>100<?php echo "\n"?>每题所占分值，留空则默认每题100分。"><?php if(isset($slist)){ echo $slist;}?></textarea>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 </td><td>
  <textarea id="ulist" name="ulist" cols="15" rows="10"  placeholder="示例:<?php echo "\n"?>user1<?php echo "\n"?>user2<?php echo "\n"?>user3<?php echo "\n"?>可以将学生用户名从Excel整列复制过来，或者在下方班级列表中选择班级加入，学生登录后就能免密进入<?php echo $MSG_Private ?>的比赛作为作业和测验。"><?php if(isset($ulist)){ echo $ulist;}?></textarea>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td><td>
  <textarea name="ex_ulist" cols="15" rows="10" placeholder="示例:<?php echo "\n"?>user1<?php echo "\n"?>user2<?php echo "\n"?>user3<?php echo "\n"?>填入不参与比赛排名的用户名。"><?php if (isset($ex_ulist)) { echo $ex_ulist; } ?></textarea>
        </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
    <select multiple name="class" class="selectpicker show-tick" data-live-search="true" data-width="150px" onchange='getUserList($(this).val());' data-title="选择<?php echo $MSG_Class ?>">
    <option value=''></option>
      <?php
        require_once("../include/classList.inc.php");
        $classList = get_classlist(false, "");
        foreach ($classList as $c){
          if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
          foreach ($c[1] as $cl){
            echo "<option value='$cl'>$cl</option>\n";
          }
          echo "</optgroup>\n";
        }
      ?>
    </select>
    </td>
    <td>&nbsp;</td>
  </tr>
</table><br>
  <p align=left><strong><?php echo $MSG_NEWS ?>:</strong><br><textarea class="kindeditor" rows=13 name=description cols=80><?php echo htmlentities($description,ENT_QUOTES,"UTF-8")?></textarea></p>
    
  <p><input type=submit value="<?php echo $MSG_SUBMIT ?>" name=submit>&nbsp;<input type=reset value="<?php echo $MSG_RESET ?>" name=reset></p>
  
  </td></tr></table>
  </form>
<script type="text/javascript">
function getUserList(classes){
  //console.log(classes);
  $.ajax({
    type: "POST",
    url: "./ajax.php?getUserList",
    data: {"classes":classes},
    dataType: "text",
    success: function(res){
      $("#ulist").val(res);
      //console.log(res)
    }
  });
}
</script>
<?php 
  require_once("admin-footer.php")
?>
