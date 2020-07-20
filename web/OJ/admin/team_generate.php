<?php

/**
 * This file is modified
 * by yybird
 * @2016.07.02
 **/
?>

<?php
require("admin-header.php");
if (!HAS_PRI("generate_team")) {
  echo "Permission denied!";
  exit(1);
}
?>
<?php
require_once("../include/my_func.inc.php");
if (isset($_POST['add'])) {
  require_once("../include/check_post_key.php");
  $school = trim($mysqli->real_escape_string($_POST['school']));
  $class = trim($mysqli->real_escape_string($_POST['class']));
  $contest_id = trim($mysqli->real_escape_string($_POST['contest_id']));
  $prefix = trim($mysqli->real_escape_string($_POST['prefix']));
  $user_num = trim($mysqli->real_escape_string($_POST['user_num']));
  $nick_list = explode("\n", trim($_POST['nicklist']));

  $err_str = "";
  $err_cnt = 0;
  if (strlen($school) > 100) {
    $err_str = $err_str . "school is too long!\\n";
    $err_cnt++;
  }
  if($prefix == "all"){ //前缀不能是all，会影响用户列表页面的筛选
    $err_str = $err_str . "{$MSG_Prefix}不能设定为“all”！\\n";
    $err_cnt++;
  }
  if (!$class) $class = "其它";
  if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE && !class_is_exist($class)) {
    $err_str = $err_str . "{$MSG_Class}【{$class}】不存在！\\n";
    $err_cnt++;
  }
  $sql = "SELECT `contest_id`, `title`, `defunct` FROM `contest` WHERE `contest_id`='$contest_id' AND NOT `practice` AND `user_limit`='Y'";
  $contest = $mysqli->query($sql)->fetch_object();
  if (!$contest) {
    $err_str = $err_str . "编号为{$contest_id}的{$MSG_CONTEST}不存在或不是{$MSG_Special}！\\n";
    $err_cnt++;
  }
  if (!preg_match("/^[a-zA-Z0-9]{1,20}$/", $prefix)) {
    $err_str = $err_str . "{$MSG_Prefix}不合规，限20个以内的字母、数字！\\n";
    $err_cnt++;
  }
  if (!preg_match("/^[1-9][0-9]{0,1}$/", $user_num)) {
    $err_str = $err_str . "{$MSG_Amount}不合规，要求介于1-99的整数 ！\\n";
    $err_cnt++;
  }
  if ($err_cnt > 0) {
    print "<script language='javascript'>\n";
    echo "alert('";
    echo $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);
  }
  $no = 0;
  $sql = "SELECT MAX(`NO`) AS start FROM `team` WHERE `prefix`='$prefix' AND `contest_id`='$contest_id'";
  $result = $mysqli->query($sql);
  if ($result) {
    $row = $result->fetch_array();
    $no = $row['start'] + 1;
  }
  $report = array();
  for ($i = $no; $i < $no + $user_num; $i++) {
    $user_id = $prefix . ($i < 10 ? ('0' . $i) : $i);
    $report[$i]['user_id'] = $user_id;
    $nick = $nick_list[$i - $no] ? $nick_list[$i - $no] : $user_id;
    $report[$i]['nick'] = $nick;
    if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) $report[$i]['class'] = $class;
    $contest_status = ($contest->defunct == 'Y') ? '<font color=red>【' . $MSG_Reserved . '】</font>' : "";
    $report[$i]['contest'] = "【" . $contest->contest_id . "】" . $contest->title . $contest_status;
    $password = createPwd($user_id, 10);
    $report[$i]['password'] = $password;
    $password = pwGen($password);
    $sql = "INSERT INTO `team`(`user_id`, `prefix`, `NO`, `ip`,`password`,`reg_time`,`nick`,`contest_id`, `class`, `school`)";
    $sql .= " VALUES('" . $user_id . "','" . $prefix . "','" . $i . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $password . "',NOW(),'" . $nick . "','" . $contest_id . "','" . $class . "','" . $school . "')";
    $sql .= " ON DUPLICATE KEY UPDATE `ip`='" . $_SERVER['REMOTE_ADDR'] . "',`password`='" . $password . "',`reg_time`=now(),nick='" . $nick . "',`school`='" . $school . "',`class`='" . $class . "'";
    $mysqli->query($sql) or die($mysqli->error);
  }
?>
  <title><?php echo $html_title . $MSG_TEAMGENERATOR ?></title>
  <h1><?php echo $MSG_TEAMGENERATOR ?></h1>
  <h4><?php echo $MSG_HELP_TEAMGENERATOR ?></h4>
  <hr>
  <div class="am-g" style="max-width: 1300px;margin-top: 0px;margin-bottom: 0px;">
    <input type="button" name="submit" value="返回" onclick="javascript:history.go(-1);" style="margin-bottom: 20px;">
    <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;width:600px">
      <thead>
        <tr>
          <td colspan="<?php echo (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) ? 5 : 4 ?>">Copy these accounts to distribute</td>
        </tr>
        <tr>
          <th><?php echo $MSG_TEAM ?></th>
          <th><?php echo $MSG_NICK ?></th>
          <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
          <th><?php echo $MSG_Class ?></th>
          <?php } ?>
          <th><?php echo $MSG_CONTEST ?></th>
          <th><?php echo $MSG_PASSWORD ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($report as $row) {
          echo "<tr>\n";
          foreach ($row as $x){
            echo "<td>" . $x . "</td>\n";
          }
          echo "</tr>\n";
        }
        ?>
      </tbody>
      </table>
  </div>
<?php
  require_once("admin-footer.php");
  exit(0);
}
?>

<title><?php echo $html_title . $MSG_TEAMGENERATOR ?></title>
<h1><?php echo $MSG_TEAMGENERATOR ?></h1>
<h4><?php echo $MSG_HELP_TEAMGENERATOR ?></h4>
<hr>
<form class="am-form am-form-horizontal" method="post">
  <div class="am-g" style="max-width: 1300px;margin-left: 20px;">
    <font size='2px' color='red'>
      使用指南：<br />
      <ol>
        <li><?php echo $MSG_SCHOOL ?>和<?php echo $MSG_NICK ?>为选填项，其余必填，此处分<?php echo $MSG_Class ?>创建<?php echo $MSG_TEAM ?>是为了方便在contestranklist比赛排名页中按班级进行排名；</li>
        <li><?php echo $MSG_TEAM ?>前缀名（Prefix）不能超过20个字符，此外若填入<?php echo $MSG_NICK ?>表示指定账号的nick</li>
        <li>若不小心创建了过多的账号，请登录管理员账号在 <?php echo $MSG_USER . $MSG_LIST ?>-><?php echo $MSG_TEAM ?> 中删除。</li>
      </ol>
    </font>
    <div class="am-u-sm-4">
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label" style="white-space: nowrap;">
          <?php echo $MSG_SCHOOL ?>:
        </label>
        <input type="text" value="<?php echo (isset($OJ_NAME) && $OJ_NAME == "HZNUOJ") ? "Hangzhou Normal University" : "" ?>" name="school" style="width:250px;" />
      </div>
      <?php
      if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        require_once("../include/classList.inc.php");
        $classList = get_classlist(true, "");
      ?>
        <div class="am-form-group">
          <label class="am-u-sm-4 am-form-label" style="white-space: nowrap;">
          <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_Class ?>:
          </label>
          <select name="class" class="selectpicker show-tick" data-live-search="true" data-width="250px" required>
            <?php
            foreach ($classList as $c) {
              if ($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
              foreach ($c[1] as $cl) {
                echo "<option value='$cl' ";
                if ($cl=="其它") echo "selected";
                echo ">$cl</option>\n";
              }
              if ($c[0]) echo "</optgroup>\n";
            }
            ?>
          </select>
        </div>
      <?php } ?>
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label" style="white-space: nowrap;">
          <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_CONTEST ?>:
        </label>
        <select name="contest_id" class="selectpicker show-tick" data-live-search="true" data-width="250px" data-title="选择一个<?php echo $MSG_CONTEST ?>" required>
          <option value='' selected></option>
          <?php
          $view_contest = get_contests("");
          foreach ($view_contest as $view_con) {
            if ($view_con['data']) { ?>
              <optgroup <?php echo "label='{$view_con['type']}' {$view_con['disabled']}" ?>>
                <?php foreach ($view_con['data'] as $contest) {
                  $contest_status = ($contest['defunct'] == 'Y') ? '【' . $MSG_Reserved . '】' : ""; ?>
                  <option value="<?php echo $contest['contest_id'] ?>" <?php if ($contest['contest_id'] == $row->contest_id) echo "selected" ?>><?php echo "【" . $contest['contest_id'] . "】" . $contest['title'] . $contest_status ?></option>
                <?php } ?>
              </optgroup>
          <?php
            }
          }
          ?>
          ?>
        </select>
      </div>
      <div class=" am-form-group">
        <label class="am-u-sm-4 am-form-label" style="white-space: nowrap;">
          <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_Prefix ?>:
        </label>
        <input type="text" value="" name="prefix" style="width:250px;" maxlength="20" placeholder="20位以内的前缀，只能字母/数字" pattern="^[a-zA-Z0-9]{1,20}$" required />
      </div>
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label">
          <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_Amount ?>:
        </label>
        <input type="number" style="width:250px;" name="user_num" min="1" max="99" value="40" required />
      </div>
      <div class="am-form-group" style="white-space: nowrap;">
        <div class="am-u-sm-8 am-u-sm-offset-4">
          <input type="submit" value="<?php echo $MSG_SUBMIT ?>" name="add" class="am-btn am-btn-success">
        </div>
      </div>
    </div>
    <div class="am-u-sm-4 am-u-end">
      <div class=" am-form-group">
      <label class="am-u-sm-4 am-form-label">
        <?php echo $MSG_NICK ?>:
      </label>
      <textarea name="nicklist" rows="13" style="width:250px;" placeholder="*示例：一个<?php echo $MSG_NICK ?>占一行<?php echo "\n" . $MSG_NICK . "1\n" . $MSG_NICK . "2\n" . $MSG_NICK . "3\n" . "若行数不足，剩余" . $MSG_TEAM . "将使用“" . $MSG_USER_ID . "”作昵称。"?> "></textarea>
    </div>
  </div>
  <?php require_once("../include/set_post_key.php"); ?>
</form>

<?php
require_once("admin-footer.php")
?>