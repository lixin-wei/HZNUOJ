<?php

/**
 * This file is created
 * by lixun516@qq.com
 * @2020.02.15
 **/
?>

<?php
require_once("admin-header.php");
if (!HAS_PRI("edit_user_profile")) {
  $view_error = "You don't have the privilege to view this page!";
  require_once("error.php");
  exit(1);
}
require_once("../include/my_func.inc.php");
if (isset($_GET['del'])) { //删除班级信息
  require_once("../include/check_get_key.php");
  $cid = array();
  if (isset($_GET['cid'])) {
    $cid[0] = $_GET['cid'];
  } else if (isset($_POST['cid'])) $cid = $_POST['cid'];
  $clist = "";
  foreach ($cid as $class) {
    $class = $mysqli->real_escape_string($class);
    if ($clist) {
      $clist .= ",'" . $class . "'";
    } else $clist .= "'" . $class . "'";
  }
  if ($clist) { //先将班级名下学生归到“其它”班，再删除班级记录
    $sql = "UPDATE `users` SET `class`='其它' WHERE `class`<>'其它' AND `class` IN ($clist)";
    $mysqli->query($sql);
    $sql = "UPDATE `users_cache` SET `class`='其它' WHERE `class`<>'其它' AND `class` IN ($clist)";
    $mysqli->query($sql);
    $sql = "UPDATE `team` SET `class`='其它' WHERE `class`<>'其它' AND `class` IN ($clist)";
    $mysqli->query($sql);
    $sql = "DELETE FROM `reg_code` WHERE `class_name`<>'其它' AND `class_name` IN  ($clist)"; //删除对应班级的注册码
    $mysqli->query($sql);
    $sql = "DELETE FROM `class_list` WHERE `class_name`<>'其它' AND `class_name` IN  ($clist)";
    $mysqli->query($sql);
  }
  echo "<script language=javascript>history.go(-1);</script>";
  exit(0);
} else if (isset($_POST['add'])) { //增加班级写入数据库
  require_once("../include/check_post_key.php");
  echo "<title>" . $html_title . $MSG_ADD . $MSG_Class_Name . "</title>";
  $year = $mysqli->real_escape_string($_POST['year']);
  $prefix = $mysqli->real_escape_string(htmlentities(trim($_POST['prefix'])));
  $class_num = trim($_POST['class_num']);
  $mode = $_POST['mode'];
  $err_str = "";
  $err_cnt = 0;
  if (!is_numeric($year) || $year > 2500 || ($year < 1900 && $year != 0)) {
    $err_str .= "输入的{$MSG_Enrollment_Year}({$year})不是一个合法的年份 ！\\n";
    $err_cnt++;
  }
  if ($mode == "A" || $mode == "B") {
    if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $prefix)) { //{1,60} 60=3*20，一个utf-8汉字占3字节
      $err_str .= "输入的{$MSG_Prefix}限20个以内的汉字、字母、数字或下划线、加号 ！\\n";
      $err_cnt++;
    }
    if (!preg_match("/^[1-9][0-9]{0,1}$/", $class_num)) {
      $err_str .= "输入的{$MSG_Class}{$MSG_Amount}要求是介于1-99的整数 ！\\n";
      $err_cnt++;
    }
  } 
  if ($err_cnt == 0) {
    // <option value="A">1/2/3/4(最多99个班)</option>
    // <option value="B">A/B/C/D(最多26个班)</option>
    // <option value="C">自定义班级列表</option>
    $class_list_err = array();
    switch ($mode) {
      case 'B':
        if ($class_num > 26) {
          $err_str .= $MSG_Mode . '"A/B/C/D"最多支持26个班的编号！';
          $err_cnt++;
        } else {
          $class_list = array();
          for ($i = 65; $i <= 64 + $class_num; $i++) {
            array_push($class_list, $prefix . chr($i));
          }
        };
        break;
      case 'A':
      default:
        $class_list = array();
        if ($class_num >= 10) {
          $format = "%02d";
        } else {
          $format = "%d";
        }
        for ($i = 1; $i <= $class_num; $i++) {
          array_push($class_list, $prefix . sprintf($format, $i));
        }
        break;
      case "C":
        if (trim($_POST['classes'])) {
          $classes = explode("\n", trim($_POST['classes']));
          $class_list = array();
          foreach ($classes as $c) {
            $c = trim(str_replace("\r", "", $c));
            if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $c)) {
              array_push($class_list_err, "输入的{$MSG_Class_Name} {$c} 不合规，限20个以内的汉字、字母、数字或下划线、加号  ！<br>\n");
            } else array_push($class_list, $mysqli->real_escape_string(htmlentities($c)));
          }
        } else {
          $err_str .= "请填写$MSG_Class{$MSG_LIST}！";
          $err_cnt++;
        }
        break;
    }
  }
  if ($err_cnt > 0) {
    print "<script language='javascript'>\n";
    echo "alert('";
    echo $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);
  }
  $cnt = 0;
  foreach($class_list as $c){
    if (class_is_exist($c)) {
      echo $c . "-有重名，无法写入！<br>\n";
    } else {
      $sql = "INSERT INTO `class_list` VALUES ('" . $c . "', '" . $year . "')";
      $mysqli->query($sql);
      if($year!=0) echo $year . "级 " . $c. "-成功写入！<br>\n";
      else echo "无入学年份 " . $c. "-成功写入！<br>\n";
      $cnt++;
    }
  }
  foreach ($class_list_err as $err) {
    echo $err;
  }
  echo "成功写入{$cnt}个班级。";
  echo "<p><input type='button' name='submit' value='$MSG_Back' onclick='javascript:history.go(-1);' style='margin-bottom: 20px;'>";
  require_once("admin-footer.php");
  exit(0);
} else if (isset($_POST['save'])) { //修改班级资料写入数据库，保存后资料后跳转回班级列表
  require_once("../include/check_post_key.php");

  if (isset($_POST['year'])) $args['year'] = $_POST['year'];
  if (isset($_POST['keyword'])) {
    $_POST['keyword'] = trim($_POST['keyword']);
    $args['keyword'] = urlencode($_POST['keyword']);
  }
  if (isset($_POST['sort_method'])) $args['sort_method'] = $_POST['sort_method'];
  if (isset($_POST['page'])) $args['page'] = $_POST['page'];
  function generate_url($data)
  {
    global $args;
    $link = "class_list.php?";
    foreach ($args as $key => $value) {
      if (isset($data["$key"])) {
        $value = htmlentities($data["$key"]);
        $link .= "&$key=$value";
      } else if ($value) {
        $link .= "&$key=" . htmlentities($value);
      }
    }
    return $link;
  }
  $err_str = "";
  $err_cnt = 0;
  $old_name = trim($mysqli->real_escape_string($_POST['cid']));
  $new_name = htmlspecialchars(trim($mysqli->real_escape_string($_POST['new_name'])));
  if ($new_name == $old_name) {
    $err_str .= "相同的{$MSG_Class_Name}！\\n";
    $err_cnt++;
  } else if (!class_is_exist($old_name)) {
    $err_str .= "原{$MSG_Class_Name}不存在！\\n";
    $err_cnt++;
  } else if (class_is_exist($new_name)) {
    $err_str .= "输入的$MSG_New{$MSG_Class_Name}有重名！\\n";
    $err_cnt++;
  } else if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $new_name)) { //{1,60} 60=3*20，一个utf-8汉字占3字节
    $err_str = $err_str . "输入的{$MSG_Class_Name}限20个以内的汉字、字母、数字或下划线、+ ！\\n";
    $err_cnt++;
  }
  if ($err_cnt > 0) {
    print "<script language='javascript'>\n";
    echo "alert('";
    echo $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);
  }

  $sql = "UPDATE `users` SET `class`='$new_name' WHERE `class`='$old_name'";
  $mysqli->query($sql);
  $sql = "UPDATE `users_cache` SET `class`='$new_name' WHERE `class`='$old_name'";
  $mysqli->query($sql);
  $sql = "UPDATE `team` SET `class`='$new_name' WHERE `class`='$old_name'";
  $mysqli->query($sql);
  if(!get_class_regcode($new_name)){ //更新相关注册码
    $sql = "DELETE FROM `reg_code` WHERE `class_name`='$new_name'";
    $mysqli->query($sql);
    $sql = "UPDATE `reg_code` SET `class_name`='$new_name' WHERE `class_name`='$old_name'";
    $mysqli->query($sql);
  }
  $sql = "UPDATE `class_list` SET `class_name`='$new_name' WHERE `class_name`='$old_name'";
  $mysqli->query($sql);
  if ($mysqli->affected_rows) {
    $result = "编辑成功！";
  } else $result = "No such Class";
  echo "<script language='javascript'>\n";
  echo "alert('$result');";
  echo "window.location.href='" . generate_url("") . "';";
  echo "</script>";
  exit(0);
}

//显示班级修改界面 start
$title = $MSG_EDIT . $MSG_Class_Name;
$view_class = array();
$cid = $mysqli->real_escape_string($_GET['cid']);
$sql = "SELECT * FROM `class_list` WHERE `class_name`='$cid'";
$result = $mysqli->query($sql);
$row = $result->fetch_object();
$view_class['class_name'] = $row->class_name;
$view_class['enrollment_year'] = $row->enrollment_year;
$result->free();

?>
<title><?php echo $html_title . $title ?></title>
<h1><?php echo $title ?></h1>
<hr>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;width:800px;">
  <form class="am-form am-form-horizontal" action="class_edit.php" method="post">
    <?php require_once('../include/set_post_key.php'); ?>
    <input type='hidden' name='cid' value='<?php echo $cid ?>'>
    <input type='hidden' name='page' value='<?php if (isset($_GET['page'])) echo $_GET['page'] ?>'>
    <input type='hidden' name='sort_method' value='<?php if (isset($_GET['sort_method'])) echo $_GET['sort_method'] ?>'>
    <input type='hidden' name='keyword' value='<?php if (isset($_GET['keyword'])) echo $_GET['keyword'] ?>'>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Enrollment_Year ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $view_class['enrollment_year']>0?$view_class['enrollment_year']:"无"; ?></label>
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Old . $MSG_Class_Name ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $view_class['class_name'] ?></label>
      </div>
    </div>
    <div class="am-form-group" style="white-space: nowrap;">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_New . $MSG_Class_Name ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" maxlength="20" id="new_name" name="new_name" value="<?php echo $view_class['class_name'] ?>" placeholder="限20个以内的汉字、字母、数字、下划线及加号" pattern="^[\u4e00-\u9fa5_+a-zA-Z0-9]{1,20}$" required />
      </div>
    </div>
    <div class="am-form-group">
      <div class="am-u-sm-8 am-u-sm-offset-4">
        <input type="submit" value="<?php echo $MSG_SUBMIT ?>" name="save" class="am-btn am-btn-success">&nbsp;
        <input type="button" value="<?php echo $MSG_Back ?>" name="submit" onclick="javascript:history.go(-1);" class="am-btn am-btn-secondary">
      </div>
    </div>
  </form>
</div>
<script language=javascript>
  document.getElementById('new_name').focus();
</script>
<?php
require_once("admin-footer.php")
?>