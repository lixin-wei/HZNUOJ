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

if(isset($_GET['del'])) { //删除账号
    require_once("../include/check_get_key.php");
    $cid = $mysqli->real_escape_string($_GET['cid']);
    if(!isset($_GET['team'])) { //删除普通用户
      if(!IS_ADMIN($cid)){ //用户是非管理员才能删除
        //不清除用户的登录日志`loginlog`、访问日志`hit_log`、往来消息'mail'、发布的公告'news'、提交的代码`solution`
        //以及`reply`、`topic`、`message`、`contest_discuss`、`printer_code`、`solution_video_watch_log`表的相关记录。
        //$sql = "DElETE FROM `loginlog` WHERE `user_id`='$cid' and `password` NOT LIKE '%team account%'";
        //$mysqli->query($sql); 删除普通账号的登录日志
        $sql = "DElETE FROM `privilege` WHERE `user_id`='$cid'";
        $mysqli->query($sql); //删除非管理员的权限
        $sql = "DElETE FROM `tag` WHERE `user_id`='$cid'";
        $mysqli->query($sql); //删除用户的标签
        $sql = "DElETE FROM `users` WHERE `user_id`='$cid'";
        $mysqli->query($sql); //删除用户记录
      }
    } else { //删除比赛临时用户
        //不清除比赛用户的登录日志`loginlog`、访问日志`hit_log`、提交的代码`solution`
        //以及`reply`、`topic`、`message`、`contest_discuss`、`printer_code`、`solution_video_watch_log`表的相关记录。
        //$sql = "DElETE FROM `loginlog` WHERE `user_id`='$cid' and `password` LIKE '%team account%'";
        //$mysqli->query($sql); 删除比赛账号的登录日志
        $cid = array();
        if(isset($_GET['cid'])){
          $cid[0] = $_GET['cid'];
        } else if(isset($_POST['cid'])) $cid = $_POST['cid'];
        $i = 0;
        $ulist="";
        foreach($cid as $user_id){
          $user_id = $mysqli->real_escape_string($user_id);
          if($ulist) {
            $ulist.=",'".$user_id ."'";
          } else $ulist.="'".$user_id ."'";
        }
        if($ulist){ //删除比赛用户记录
          $sql = "DElETE FROM `team` WHERE `user_id`in ($ulist)";
          $mysqli->query($sql);
        }
    }
    echo "<script language=javascript>history.go(-1);</script>";
    exit(0);
} else if (isset($_POST['cid']) && !isset($_GET['resetpwd'])){ //用户资料写入数据库，保存后资料后跳转回用户列表
  $user_id=trim($mysqli->real_escape_string($_POST['cid']));
  require_once("../include/my_func.inc.php");
  if(!isset($_POST['team']) && $user_id != $_SESSION['user_id'] && get_order(get_group($user_id))<=get_order(get_group(""))){
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  require_once("../include/check_post_key.php");
  if(isset($_POST['team'])) $args['team']=$_POST['team'];
  if(isset($_POST['sort_method'])) $args['sort_method']=$_POST['sort_method'];else $args['sort_method']="";
  if(isset($_POST['keyword'])) $args['keyword']=$_POST['keyword'];
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
  $stu_id=trim($mysqli->real_escape_string($_POST['stu_id']));
  $real_name=trim($mysqli->real_escape_string($_POST['real_name']));
  $class=trim($mysqli->real_escape_string($_POST['class']));
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
  if(!isset($_POST['team'])) { //普通用户sql    
    $email=trim($mysqli->real_escape_string($_POST['email']));
    $len=strlen($email);
    if ($len>100){
      $err_str=$err_str."输入的电子邮箱地址过长！\\n";
      $err_cnt++;
    }
    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $_POST['email'])) {
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
    $contestid=trim($mysqli->real_escape_string($_POST['contestid']));
    $seat=trim($mysqli->real_escape_string($_POST['seat']));
    $institute=trim($mysqli->real_escape_string($_POST['institute']));
    $sql = "SELECT `title` FROM `contest` WHERE `contest_id`=$contestid AND NOT `practice` AND `user_limit`='Y'";
    $result = $mysqli->query($sql);
    if($result->num_rows == 0){
      $err_str=$err_str."编号为{$contestid}的{$MSG_CONTEST}不存在或不是{$MSG_Special}！";
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
    $result->free();    
    $sql="UPDATE `team` SET "
    ."`nick`='".($nick)."',"
    ."`school`='".($school)."',"
    ."`contest_id`='".($contestid)."',";
    if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ 
      $sql.="`stu_id`='".($stu_id)."',"
      ."`real_name`='".($real_name)."',"
      ."`class`='".($class)."',";
    }    
    $sql.="`seat`='".($seat)."',"
    ."`institute`='".($institute)."' "
    ."WHERE `user_id`='".$user_id."'";
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
  if ($mysqli->affected_rows) {
    $result="编辑成功！";
  } else $result="No such user";
  echo "<script language='javascript'>\n";
  echo "alert('$result');";
	echo "window.location.href='".generate_url("")."';";
  echo "</script>";
  exit(0);
} else if(isset($_GET['resetpwd'])) { //比赛账号重置密码
  require_once("../include/check_get_key.php");
  require_once("../include/my_func.inc.php");
  $cid = array();
  $report = array();
  if(isset($_GET['cid'])){
        $cid[0] = $_GET['cid'];
  } else if(isset($_POST['cid']))  $cid = $_POST['cid'];
  $i = 0;
  foreach($cid as $user_id){
    $user_id = $mysqli->real_escape_string($user_id);
    $report[$i]['user_id'] = $user_id;
    $sql = "SELECT a.`nick`,a.`contest_id`, `contest`.`title`,`contest`.`defunct` FROM `team` as a ";
    $sql .= " LEFT JOIN `contest` ON a.`contest_id` = `contest`.`contest_id` WHERE a.`user_id`='$user_id'";
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
      $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
      while (is_numeric($password))  $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
      str_replace("I","X",$password);
      str_replace("O","Y",$password);
      str_replace("0","Z",$password);
      str_replace("1","W",$password);
      $report[$i]['password'] = $password;
      $password=pwGen($password);
      $sql = "UPDATE `team` SET `password`='$password' WHERE `user_id`='$user_id'";
      $mysqli->query($sql);
      if ($mysqli->affected_rows) $report[$i]['success'] = true;
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
<input type="submit" name="submit" value="返回" onclick="javascript:history.go(-1);" style="margin-bottom: 20px;">
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
$cid = $mysqli->real_escape_string($_GET['cid']);
if(!isset($_GET['team'])) {
  require_once("../include/my_func.inc.php");
  if(!isset($_POST['team']) && $user_id != $_SESSION['user_id'] && get_order(get_group($cid))<=get_order(get_group(""))){
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  $sql="SELECT * FROM `users` WHERE `user_id`='$cid'";
  $title = $MSG_EDIT.$MSG_USER;
} else {
  $sql="SELECT * FROM `team` WHERE `user_id`='$cid'";
  $title = $MSG_EDIT.$MSG_TEAM;
}
$result=$mysqli->query($sql);
$row=$result->fetch_object();
$result->free();
if(isset($_GET['team'])) {
  $view_contest = array();

  $sql="SELECT `contest_id`,`title`,`defunct` FROM `contest` WHERE NOT `practice` AND `user_limit`='Y' ORDER BY contest_id DESC";//类型优先级2
  $result=$mysqli->query($sql);
  $view_contest['Special']['type'] = $MSG_Special;
  $view_contest['Special']['data'] = $result->fetch_all(MYSQLI_ASSOC);

  $sql="SELECT `contest_id`,`title`,`defunct` FROM `contest` WHERE NOT `practice` AND `user_limit`='N' AND `private` ORDER BY contest_id DESC";//类型优先级3
  $result=$mysqli->query($sql);
  $view_contest['Private']['type'] = $MSG_Private;
  $view_contest['Private']['data'] = $result->fetch_all(MYSQLI_ASSOC);

  $sql="SELECT `contest_id`,`title`,`defunct` FROM `contest` WHERE NOT `practice` AND `user_limit`='N' AND NOT `private` ORDER BY contest_id DESC";//类型优先级3
  $result=$mysqli->query($sql);
  $view_contest['Public']['type'] = $MSG_Public;
  $view_contest['Public']['data'] = $result->fetch_all(MYSQLI_ASSOC);

  $sql="SELECT `contest_id`,`title`,`defunct` FROM `contest` WHERE `practice` ORDER BY contest_id DESC";//类型优先级1
  $result=$mysqli->query($sql);
  $view_contest['Practice']['type'] = $MSG_Practice;
  $view_contest['Practice']['data'] = $result->fetch_all(MYSQLI_ASSOC);
  
  $result->free();
}
?>
<title><?php echo $html_title.$title ?></title>
<h1><?php echo $title ?></h1>
<hr>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;width:600px;">
  <form class="am-form am-form-horizontal" action="user_edit.php" method="post">
    <?php require_once('../include/set_post_key.php');?>
    <input type='hidden' name='cid' value='<?php echo $cid ?>'>
    <input type='hidden' name='page' value='<?php if(isset($_GET['page'])) echo $_GET['page'] ?>'>
    <input type='hidden' name='sort_method' value='<?php if(isset($_GET['sort_method'])) echo $_GET['sort_method'] ?>'>
    <input type='hidden' name='keyword' value='<?php if(isset($_GET['keyword'])) echo $_GET['keyword'] ?>'>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_USER_ID ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $cid?></label>        
      </div>
    </div>
    <?php if(!isset($_GET['team'])) {?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_STRENGTH ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo round($row->strength)?></label>        
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_LEVEL ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $row->level?></label>        
      </div>
    </div>
    <?php }?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_NICK ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" maxlength="20" autocomplete="off" placeholder="1-20位汉字、字母、数字" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$" value="<?php echo htmlentities($row->nick)?>" name="nick">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_SCHOOL ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" placeholder="<?php echo $MSG_SCHOOL ?>" value="<?php echo htmlentities($row->school)?>" name="school">
      </div>
    </div>
    <?php if(!isset($_GET['team'])) {?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label" style="float:left;">
      <?php echo $MSG_EMAIL ?>:
      </label>
      <div class="am-u-sm-8">
        <input type="email" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->email)?>" name="email">
      </div>
    </div>
    <?php } else {?>
      <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_CONTEST ?>:</label>
      <div class="am-u-sm-8">
          <select name="contestid" class="selectpicker show-tick" data-live-search="true"  data-width= "340px">
            <?php 
            foreach($view_contest as $view_con):
              if($view_con['data']) { ?>
                <optgroup <?php echo "label='{$view_con['type']}' ";  if($view_con['type']!=$MSG_Special) echo "disabled"?>>
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
        $class = $row->class;
        if ($class=="null" or  $class=="" ) $class = "其它";
      ?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_StudentID ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->stu_id)?>" name="stu_id">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_REAL_NAME ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->real_name)?>" name="real_name">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Class ?>:</label>
      <div class="am-u-sm-8">
          <select name="class" class="selectpicker show-tick" data-live-search="true" data-width="340px">
            <?php foreach ($classList as $c):?>
              <option value="<?php echo $c?>" <?php if($c == $class) echo "selected"?>><?php echo $c?></option>
            <?php endforeach ?>
          </select>
      </div>
    </div>
    <?php } 
    if(isset($_GET['team'])) {
          echo "<input type='hidden' name='team' value='{$_GET['team']}'>";
    ?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Seat ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->seat)?>" name="seat">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Institute ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" autocomplete="off" value="<?php echo htmlentities($row->institute)?>" name="institute">
      </div>
    </div>
    <?php } ?>
    <div class="am-form-group">
      <div class="am-u-sm-8 am-u-sm-offset-4">
        <input type="submit" value="<?php echo $MSG_SUBMIT?>" name="submit" class="am-btn am-btn-success">
      </div>
    </div>
  </form>
</div>

<?php 
  require_once("admin-footer.php")
?>