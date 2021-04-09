<?php
  /**
   * This file is created
   * by lixun516@qq.com
   * @2020.02.03
  **/
?>

<?php 
require_once("admin-header.php");
if (!HAS_PRI("edit_user_profile")) {
	$view_error="You can't edit this user!";
	require_once("error.php");
	exit(1);
}
require_once("../include/my_func.inc.php");
if(isset($_GET['del'])) { //删除账号
    require_once("../include/check_get_key.php");
    $cid = array();
    $admin_cid = array();
    if(isset($_GET['cid'])){
      $cid[0] =$mysqli->real_escape_string($_GET['cid']);;
    } else $cid = $_POST['cid'];
    if(!isset($_GET['team'])) { //删除普通用户
      foreach($cid as $c){ //用户是非管理员才能删除
        if(IS_ADMIN($c)) array_push($admin_cid, $c);
      }
      $cid = array_diff($cid, $admin_cid);
      $cid = "'". implode("','", $cid) ."'";
        //不清除用户的登录日志`loginlog`、访问日志`hit_log`、往来消息'mail'、发布的公告'news'、提交的代码`solution`
        //以及`reply`、`topic`、`message`、`contest_discuss`、`printer_code`、`solution_video_watch_log`表的相关记录。
        //$sql = "DELETE FROM `loginlog` WHERE `user_id`='$cid' and `password` NOT LIKE '%team account%'";
        //$mysqli->query($sql); 删除普通账号的登录日志
      $sql = "DELETE FROM `privilege` WHERE `user_id` IN ($cid)";
      $mysqli->query($sql); //删除非管理员的权限
      $sql = "DELETE FROM `tag` WHERE `user_id` IN ($cid)";
      $mysqli->query($sql); //删除用户的标签
      $sql = "DELETE FROM `users` WHERE `user_id` IN ($cid)";
      $mysqli->query($sql); //删除用户记录
      if($mysqli->affected_rows<0) $msg = "删除失败";
      else $msg = "成功删除{$mysqli->affected_rows}个{$MSG_USER}！";
    } else { //删除比赛临时用户
        //不清除比赛用户的登录日志`loginlog`、访问日志`hit_log`、提交的代码`solution`
        //以及`reply`、`topic`、`message`、`contest_discuss`、`printer_code`、`solution_video_watch_log`表的相关记录。
        //$sql = "DELETE FROM `loginlog` WHERE `user_id`='$cid' and `password` LIKE '%team account%'";
        //$mysqli->query($sql); 删除比赛账号的登录日志
        $cnt = 0;
        foreach($cid as $c){
          $tuser = explode("@", trim($c));
          $user_id = $mysqli->real_escape_string(trim($tuser[0]));
          $contest_id = $mysqli->real_escape_string(trim($tuser[1]));
          $sql = " DELETE FROM `team` WHERE `user_id`='$user_id' AND `contest_id`='$contest_id'";
          $mysqli->query($sql);//删除比赛用户记录
          if($mysqli->affected_rows==1) $cnt++;
        }
        $msg = "成功删除{$cnt}个{$MSG_TEAM}！";
    }
    echo "<script language=javascript>alert('$msg');</script>";
    echo "<script language=javascript>history.go(-1);</script>";
    exit(0);
} else if (isset($_POST['saveUser']) ){ //用户资料写入数据库，保存后资料后跳转回用户列表
  require_once("../include/check_post_key.php");
  $user_id=trim($mysqli->real_escape_string($_POST['cid']));
  if(!isset($_POST['team']) && $user_id != $_SESSION['user_id'] && get_order(get_group($user_id))<=get_order(get_group(""))){
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  if(isset($_POST['team'])) $args['team']=$_POST['team'];
  if(isset($_POST['class'])) $args['class']=urlencode($_POST['class']);
  if (isset($_POST['defunct'])) $args['defunct'] = $_POST['defunct'];
  if (isset($_POST['contest'])) $args['contest'] = $_POST['contest'];
  if(isset($_POST['sort_method'])) $args['sort_method']=$_POST['sort_method'];
  if (isset($_POST['keyword'])) {
    $_POST['keyword'] = trim($_POST['keyword']);
    $args['keyword'] = urlencode($_POST['keyword']);
  }
  if(isset($_POST['page'])) $args['page']=$_POST['page'];
  function generate_url($data){
      global $args;
      $link="user_list.php?";
      foreach ($args as $key => $value) {
          if(isset($data["$key"])){
              $value=htmlentities($data["$key"]);
              $link.="&$key=$value";
          }
          else if($value){
              $link.="&$key=".htmlentities($value);
          }
      }
      return $link;
  }
  $err_str="";
  $err_cnt=0;
  
  $nick=trim($mysqli->real_escape_string($_POST['nick']));
  $school=trim($mysqli->real_escape_string($_POST['school']));
  $class="其它";
  $stu_id="";
  $real_name="";
  if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){	  
    $stu_id=trim($mysqli->real_escape_string($_POST['stu_id']));
    $real_name=trim($mysqli->real_escape_string($_POST['real_name']));
    $class=trim($mysqli->real_escape_string($_POST['new_class']));
  }
  $len = strlen($nick);
  if ($len>=30){
    $err_str=$err_str."输入的{$MSG_NICK}过长！\\n";
    $err_cnt++;
  }else if ($len==0) $nick=$user_id;
  $len=strlen($school);
  if ($len>100){
    $err_str=$err_str."输入的就读学校名称过长！\\n";
    $err_cnt++;
  }
  if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
    $len = strlen($stu_id);
    if ($len>=30){
      $err_str=$err_str."输入的{$MSG_StudentID}过长！\\n";
      $err_cnt++;
    }
    $len = strlen($real_name);
    if ($len>=30){
      $err_str=$err_str."输入的{$MSG_REAL_NAME}过长！\\n";
      $err_cnt++;
    }
    if(!class_is_exist($class)){
      $err_str=$err_str."{$class}不存在！\\n";
      $err_cnt++;
    }
  }
  if(!isset($_POST['team'])) { //普通用户sql    
    $email=trim($mysqli->real_escape_string($_POST['email']));
    $len=strlen($email);
    if ($len>100){
      $err_str=$err_str."输入的电子邮箱地址过长！\\n";
      $err_cnt++;
    }
    if($len!=0 && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $_POST['email'])) {
      $err_str=$err_str."输入的电子邮箱地址不合法！\\n";
      $err_cnt++;
    }
    $sql="UPDATE `users` SET "
    ."`nick`='".($nick)."',"
    ."`school`='".($school)."',";
    if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
      $sql.="`stu_id`='".($stu_id)."',"
      ."`real_name`='".($real_name)."',"
      ."`class`='".($class)."',";
    }
    $sql.="`email`='".($email)."' "
    ."WHERE `user_id`='".$user_id."'";
  } else {  //比赛账号sql
    $contest_id=trim($mysqli->real_escape_string($_POST['contest_id']));
    $new_contest_id=trim($mysqli->real_escape_string($_POST['new_contest_id']));
    $seat=trim($mysqli->real_escape_string($_POST['seat']));
    $institute=trim($mysqli->real_escape_string($_POST['institute']));
    $sql = "SELECT `user_id` FROM `team` WHERE `user_id`='".$user_id."' AND `contest_id`='$new_contest_id' AND `contest_id`<>'$contest_id'";
    $result = $mysqli->query($sql);
    if($result->num_rows != 0){
      $err_str=$err_str."编号为{$new_contest_id}的{$MSG_CONTEST}中存在同名{$MSG_TEAM}，不能将{$user_id}放入该比赛！\\n";
      $err_cnt++;
    } else {
      $sql = "SELECT `title` FROM `contest` WHERE `contest_id`='$new_contest_id' AND NOT `practice` AND `user_limit`='Y'";
      $result = $mysqli->query($sql);
      if($result->num_rows == 0){
        $err_str=$err_str."编号为{$new_contest_id}的{$MSG_CONTEST}不存在或不是{$MSG_Special}！\\n";
        $err_cnt++;
      } else {
        $len = strlen($seat);
        if ($len>=30){
          $err_str=$err_str."输入的{$MSG_Seat}过长！\\n";
          $err_cnt++;
        }
        $len = strlen($institute);
        if ($len>=30){
          $err_str=$err_str."输入的{$MSG_Institute}过长！\\n";
          $err_cnt++;
        }
      }
    }
    $result->free();    
    $sql="UPDATE `team` SET "
    ."`nick`='".($nick)."',"
    ."`school`='".($school)."',"
    ."`contest_id`='".($new_contest_id)."',";
    if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ 
      $sql.="`stu_id`='".($stu_id)."',"
      ."`real_name`='".($real_name)."',"
      ."`class`='".($class)."',";
    }    
    $sql.="`seat`='".($seat)."',"
    ."`institute`='".($institute)."' "
    ."WHERE `user_id`='".$user_id."' AND `contest_id`='$contest_id'";
  }
  if ($err_cnt>0){
    print "<script language='javascript'>\n";
    echo "alert('";
    echo $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);  
  }
  //echo $sql;
  $mysqli->query($sql) or die("Update Error!\n");
  if ($mysqli->affected_rows > 0) $result="编辑成功！";
  else $result="No such user or No change!";
  echo "<script language='javascript'>\n";
  echo "alert('$result');";
	echo "window.location.href='".generate_url("")."';";
  echo "</script>";
  exit(0);
} else if(isset($_POST['changeTeamContest'])) { //给比赛账号重新分配比赛
  require_once("../include/check_get_key.php");
  $cnt = 0;
  $err_str = $msg = "";
  $new_contest_id = $mysqli->real_escape_string($_POST['new_contest_id']);
  $sql = "SELECT `contest_id`,`title` FROM `contest` WHERE `contest_id` = $new_contest_id AND NOT `practice` AND `user_limit`='Y'";
  $result = $mysqli->query($sql);
  if($result->num_rows==0){
    $err_str=$err_str."编号为{$new_contest_id}的{$MSG_CONTEST}不存在或不是{$MSG_Special}！\\n";
  } else {
    $row = $result->fetch_object();
    foreach($_POST['cid'] as $c){
      $tuser = explode("@", trim($c));
      $user_id = $mysqli->real_escape_string(trim($tuser[0]));
      $contest_id = $mysqli->real_escape_string(trim($tuser[1]));
      if($user_id && $contest_id && $contest_id != $row->contest_id ){
        $sql = "SELECT `user_id` FROM `team` WHERE `user_id`='".$user_id."' AND `contest_id`='$new_contest_id' ";
        $result = $mysqli->query($sql);
        if($result->num_rows != 0){
          $err_str=$err_str."编号为{$new_contest_id}的{$MSG_CONTEST}中存在同名{$MSG_TEAM}，不能将{$user_id}放入该比赛！\\n";
        } else {
          $sql = "UPDATE `team` SET `contest_id` = '$new_contest_id' WHERE `user_id`='".$user_id."' AND `contest_id`='$contest_id'";
          $mysqli->query($sql);
          if ($mysqli->affected_rows>0) $cnt++;
        }
      }
    }
  }
  if($cnt) $msg .= "成功将【{$row->contest_id}】{$row->title} 重新分配给{$cnt}个比赛账号！\\n" . $err_str;
  else $msg .= $err_str;
  if($msg) echo "<script language=javascript>alert('$msg');</script>";
  echo "<script language=javascript>history.go(-1);</script>";
  exit(0);
} else if(isset($_POST['changeClass'])) { //批量调整班级
  require_once("../include/check_get_key.php");
  $cnt = 0;
  $err_str = $msg = "";
  $new_class = $mysqli->real_escape_string($_POST['new_class']);
  if (!class_is_exist($new_class)) {
    $err_str=$err_str."{$MSG_Class}【{$new_class}】不存在！\\n";
  } else if(!isset($_GET['team'])) { //普通用户调整班级
      $ulist = "";
      foreach ($_POST['cid'] as $user_id) {
        $user_id = $mysqli->real_escape_string($user_id);
        if ($ulist) {
          $ulist .= ",'" . $user_id . "'";
        } else $ulist .= "'" . $user_id . "'";
      }
      if($ulist){
        $sql = "UPDATE `users_cache` SET `class`='$new_class' WHERE `user_id` IN ($ulist)";
        $mysqli->query($sql);
        $sql = "UPDATE `users` SET `class`='$new_class' WHERE `user_id` IN ($ulist)";
        $mysqli->query($sql);
        if ($mysqli->affected_rows>0) $cnt = $mysqli->affected_rows;
      }
    } else { //比赛账号调整班级
      foreach ($_POST['cid'] as $c) {
        $tuser = explode("@", trim($c));
        $user_id = $mysqli->real_escape_string(trim($tuser[0]));
        $contest_id = $mysqli->real_escape_string(trim($tuser[1]));
        $sql = "UPDATE `team` SET `class`='$new_class' WHERE `user_id`='".$user_id."' AND `contest_id`='$contest_id'";
        $mysqli->query($sql);
        if ($mysqli->affected_rows>0) $cnt++;
      }
    }
  if($cnt) $msg .= "成功将{$MSG_Class}【{$new_class}】分配给{$cnt}个账号！\\n" . $err_str;
  else $msg .= $err_str;
  if($msg) echo "<script language=javascript>alert('$msg');</script>";
  echo "<script language=javascript>history.go(-1);</script>";
  exit(0);
} else if(isset($_GET['resetpwd'])) { //比赛账号重置密码
  require_once("../include/check_get_key.php");
  $cid = array();
  $report = array();
  if(isset($_GET['cid'])){
        $cid[0] = $_GET['cid'];
  } else $cid = $_POST['cid'];
  $i = 0;
  foreach($cid as $c){
    $tuser = explode("@", trim($c));
    $user_id = $mysqli->real_escape_string(trim($tuser[0]));
    $contest_id = $mysqli->real_escape_string(trim($tuser[1]));
    $report[$i]['user_id'] = $user_id;
    $sql = "SELECT t.`nick`,t.`contest_id`, c.`title`, c.`defunct` FROM `team` AS t ";
    $sql .= " LEFT JOIN `contest` AS c ON t.`contest_id` = c.`contest_id` WHERE t.`user_id`='$user_id' AND t.`contest_id`='$contest_id'";
    $row = $mysqli->query($sql)->fetch_object();
    if(!$row){
      $report[$i]['nick']=" ";
      $report[$i]['contest'] = " ";
      $report[$i]['password']=" ";
      $report[$i]['success'] = false;
    } else {
      $report[$i]['nick']=$row->nick;
      $contest_status = ($row->defunct=='Y')?'<font color=red>【'.$MSG_Reserved.'】</font>':"";
      $report[$i]['contest'] = "【".$row->contest_id."】".$row->title.$contest_status;
      $password=createPwd($user_id, 10);
      $report[$i]['password'] = $password;
      $password=pwGen($password);
      $sql = "UPDATE `team` SET `password`='$password' WHERE `user_id`='$user_id' AND `contest_id`='$contest_id'";
      $mysqli->query($sql);
      if ($mysqli->affected_rows>0) $report[$i]['success'] = true;
      else $report[$i]['success'] = false;
    }
    $i++;
  }
  $title = $MSG_RESET.$MSG_TEAM.$MSG_PASSWORD;
  ?>
  <title><?php echo $html_title.$title ?></title>
<h1><?php echo $title ?></h1>
<hr>
<div  class="am-g am-scrollable-horizontal" style="max-width: 1300px;margin-top: 0px;margin-bottom: 0px;">
<input type="button" name="submit" value="返回" onclick="javascript:history.go(-1);" style="margin-bottom: 20px;">
<table id="passwords" class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;width:600px">
  <thead>
    <tr><td colspan=5>Copy these accounts to distribute</td></tr>
    <tr>
      <th><?php echo $MSG_TEAM ?></th>
      <th><?php echo $MSG_NICK ?></th>
      <th><?php echo $MSG_CONTEST ?></th>
      <th><?php echo $MSG_NewPasswd ?></th>
      <th><?php echo $MSG_STATUS ?></th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach($report as $row){
    echo "<tr>";
    echo "<td>".$row['user_id']."</td>";
    echo "<td>".$row['nick']."</td>";
    echo "<td>".$row['contest']."</td>";
    if($row['success']){
      echo "<td>".$row['password']."</td><td>Success</td>";
    } else  echo "<td></td><td>No such user</td>";
    echo "</tr>";
  }
  ?>
  </tbody>
</table>  
</div>
  <?php
  require_once("admin-footer.php");
  exit(0);
}

//显示资料修改界面 start
if(!isset($_GET['team'])) {
  if(!isset($_POST['team']) && $user_id != $_SESSION['user_id'] && get_order(get_group($cid))<=get_order(get_group(""))){
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  $user_id = $mysqli->real_escape_string($_GET['cid']);
  $sql="SELECT * FROM `users` WHERE `user_id`='$user_id'";
  //echo $sql."<br>";
  $title = $MSG_EDIT.$MSG_USER;
} else {
  $tuser = explode("@", trim($_GET['cid']));
  $user_id = $mysqli->real_escape_string(trim($tuser[0]));
  $contest_id = $mysqli->real_escape_string(trim($tuser[1]));
  $sql="SELECT * FROM `team` WHERE `user_id`='$user_id' AND `contest_id`='$contest_id'";
  $title = $MSG_EDIT.$MSG_TEAM;
}
$result=$mysqli->query($sql);
$row=$result->fetch_object();
$result->free();
if(isset($_GET['team'])) {
  $view_contest = get_contests("");
}
?>
<title><?php echo $html_title.$title ?></title>
<h1><?php echo $title ?></h1>
<hr>
<link rel="stylesheet" href="../plugins/emailAutoComplete/emailAutoComplete.css"/>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;width:600px;">
  <form class="am-form am-form-horizontal" action="user_edit.php" method="post">
    <?php require_once('../include/set_post_key.php');?>
    <input type='hidden' name='cid' value='<?php echo $user_id ?>'>
    <?php
      if(isset($_GET['team'])) {
        echo "<input type='hidden' name='contest_id' value='{$contest_id}'>\n";
        echo "<input type='hidden' name='team' value='{$_GET['team']}'>\n";
        echo "<input type='hidden' name='contest' value='{$_GET['contest']}'>\n";
      } else {
        echo "<input type='hidden' name='defunct' value='{$_GET['defunct']}'>\n";
        if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE) {
          echo "<input type='hidden' name='class' value='{$_GET['class']}'>\n";
        }
      }
    ?>    
    <input type='hidden' name='sort_method' value='<?php if(isset($_GET['sort_method'])) echo $_GET['sort_method'] ?>'>
    <input type='hidden' name='keyword' value='<?php if(isset($_GET['keyword'])) echo $_GET['keyword'] ?>'>
    <input type='hidden' name='page' value='<?php if(isset($_GET['page'])) echo $_GET['page'] ?>'>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_USER_ID ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $user_id?></label>        
      </div>
    </div>
    <?php if(!isset($_GET['team'])) {?>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_STRENGTH ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo round($row->strength)?></label>        
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_LEVEL ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $row->level?></label>        
      </div>
    </div>
    <?php }?>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_NICK ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" maxlength="20" autocomplete="off" placeholder="1-20位汉字、字母、数字" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$" value="<?php echo htmlentities($row->nick)?>" name="nick">
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_SCHOOL ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" placeholder="<?php echo $MSG_SCHOOL ?>" value="<?php echo htmlentities($row->school)?>" name="school">
      </div>
    </div>
    <?php if(!isset($_GET['team'])) {?>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label" style="float:left;">
      <?php echo $MSG_EMAIL ?>:
      </label>
      <div class="am-u-sm-8 parentCls">
        <input class="inputElem" type="email" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->email)?>" name="email">
      </div>
    </div>
    <?php } else {?>
      <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_CONTEST ?>:</label>
      <div class="am-u-sm-8">
          <select name="new_contest_id" class="selectpicker show-tick" data-live-search="true"  data-width= "340px">
            <?php 
            foreach($view_contest as $view_con):
              if($view_con['data']) { ?>
                <optgroup <?php echo "label='{$view_con['type']}' {$view_con['disabled']}"?>>
                <?php foreach ($view_con['data'] as $contest):
                  $contest_status = ($contest['defunct']=='Y')?'【'.$MSG_Reserved.'】':""; ?>
                  <option value="<?php echo $contest['contest_id']?>" <?php if($contest['contest_id'] == $row->contest_id) echo "selected"?>><?php echo "【".$contest['contest_id']."】".$contest['title'].$contest_status?></option>
                <?php endforeach ?>
                </optgroup>
            <?php }
            endforeach  ?>
          </select>
      </div>
    </div>
    <?php } 
      if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ 
        require_once("../include/classList.inc.php");
        $classList = get_classlist(true, "");
        $class = $row->class;
      ?>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_StudentID ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->stu_id)?>" name="stu_id">
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_REAL_NAME ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->real_name)?>" name="real_name">
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Class ?>:</label>
      <div class="am-u-sm-8">
          <select name="new_class" class="selectpicker show-tick" data-live-search="true" data-width="340px">
            <?php 
              foreach ($classList as $c){
                  if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
                  foreach ($c[1] as $cl){
                    if($cl == $class) $selected = "selected"; else $selected ="";
                    echo "<option value='$cl' $selected>$cl</option>\n";
                  }
                  echo "</optgroup>\n";
              }
            ?>
          </select>
      </div>
    </div>
    <?php } 
    if(isset($_GET['team'])) {
    ?>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Seat ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->seat)?>" name="seat">
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Institute ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->institute)?>" name="institute">
      </div>
    </div>
    <?php } ?>
    <div class="am-form-group" style="white-space: nowrap;">
      <div class="am-u-sm-8 am-u-sm-offset-4">
        <input type="submit" value="<?php echo $MSG_SUBMIT?>" name="saveUser" class="am-btn am-btn-success">&nbsp;
        <input type="button" value="<?php echo $MSG_Back ?>"  name="submit" onclick="javascript:history.go(-1);" class="am-btn am-btn-secondary">
      </div>
    </div>
  </form>
</div>

<?php 
  require_once("admin-footer.php")
?>
<script type="text/javascript" src="../plugins/emailAutoComplete/emailAutoComplete.js"></script>
