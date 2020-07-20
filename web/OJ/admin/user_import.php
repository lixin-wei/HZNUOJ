<?php
/**
 * User: d-star
 * Date: 4/16/17
 * Time: 6:52 PM
 */
require_once "admin-header.php";
require_once("../include/my_func.inc.php");
if (!HAS_PRI("generate_team")) {
    echo "Permission denied!";
    exit(1);
}
if (isset($_POST['add'])) {
    require_once("../include/check_post_key.php");
    $err_str = "";
    $err_cnt = 0;
    if (!trim($_POST['user_id'])) {
        $err_str = $err_str . "{$MSG_USER_ID}{$MSG_LIST}为必填项！\\n";
        $err_cnt++;
    }
    if ($err_cnt > 0) {
        print "<script language='javascript'>\n";
        echo "alert('";
        echo $err_str;
        print "');\n history.go(-1);\n</script>";
        exit(0);
    }
    $user_id = explode("\n", trim($_POST['user_id']));
    $stu_id = explode("\n", trim($_POST['stu_id']));
    $school = explode("\n", trim($_POST['school']));
    $class = explode("\n", trim($_POST['class']));
    $real_name = explode("\n", trim($_POST['real_name']));
    $nick = explode("\n", trim($_POST['nick']));
    $email = explode("\n", trim($_POST['email']));
    $password = explode("\n", trim($_POST['password']));
    $report = array();
    foreach ($user_id as $key => $value) {
        $user_id[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $user_id[$key])));
        $report[$key]['user_id'] = $user_id[$key];       
        $password[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $password[$key])));
        if (!$password[$key]) $password[$key] = createPwd($user_id[$key], 10);
        $report[$key]['password'] = $password[$key];
        $nick[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $nick[$key])));
        if (!$nick[$key]) $nick[$key] = $user_id[$key];
        $report[$key]['nick'] = $nick[$key];
        $email[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $email[$key])));
        $report[$key]['email'] = $email[$key];
        $school[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $school[$key])));
        $report[$key]['school'] = $school[$key];
        if (!trim($class[$key])) $class[$key] = "其它";
        if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
            $class[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $class[$key])));
            $report[$key]['class'] = $class[$key];
            $real_name[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $real_name[$key])));
            $report[$key]['real_name'] = $real_name[$key];
            $stu_id[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $stu_id[$key])));
            $report[$key]['stu_id'] = $stu_id[$key];
        }
        $report[$key]['status'] = "Success";
        if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $user_id[$key])) {
            $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_USER_ID}不合规，限3-20位以内的英文字母和数字！";
        } else {
            $sql = "SELECT COUNT(*) FROM `users` WHERE `user_id` = '{$user_id[$key]}'";
            if ($mysqli->query($sql)->fetch_array()[0] > 0) {
                $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_USER_ID}有重名！";
            } else if (!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{1,60}$/", $nick[$key])) {
                $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_NICK}不合规，限20个以内的汉字、字母、数字或下划线！";
            } else if ($email[$key] && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $email[$key])) {
                $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_EMAIL}不合规，格式不对 ！";
            } else if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
                if (!class_is_exist($class[$key])) {
                    $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_Class}【{$class[$key]}】不存在！";
                } else if (!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{0,60}$/", $real_name[$key])) {
                    $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_REAL_NAME}不合规，限20个以内的汉字、字母、数字或下划线！";
                } else if (!preg_match("/^[a-zA-Z0-9]{0,20}$/", $stu_id[$key])) {
                    $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_StudentID}不合规，限20位以内的字母+数字或者纯数字的学号！";
                }
            }
        }
        if ($report[$key]['status'] == "Success") {
            $pass_hash = pwGen($password[$key]);
            $sql = <<<SQL
                    INSERT INTO users (
                        `user_id`,
                        `stu_id`,
                        `school`,
                        `class`,
                        `real_name`,
                        `nick`,
                        `email`,
                        `password`,
                        `ip`,
                        `reg_time`
                    )
                    VALUES
                    (
                        '{$user_id[$key]}',
                        '{$stu_id[$key]}',
                        '{$school[$key]}',
                        '{$class[$key]}',
                        '{$real_name[$key]}',
                        '{$nick[$key]}',
                        '{$email[$key]}',
                        '$pass_hash',
                        '{$_SERVER['REMOTE_ADDR']}',
                        NOW()
                    )
SQL;
            //echo "<pre>$sql</pre>";
            $mysqli->query($sql);
            if ($mysqli->affected_rows <= 0) {
                $report[$key]['status'] = "<font color='red'><b>Fail</b></font>，Unknow Error!";
            }
        }
    }
?>
<title><?php echo $html_title . $MSG_USER . $MSG_IMPORT ?></title>
<h1><?php echo $MSG_USER . $MSG_IMPORT ?></h1>
<hr>
  <div class="am-g" style="max-width: 1300px;margin-top: 0px;margin-bottom: 0px;">
    <input type="button" name="submit" value="返回" onclick="javascript:history.go(-1);" style="margin-bottom: 20px;">
    <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;width:600px">
      <thead>
        <tr>
          <td colspan="<?php echo (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) ? 10 : 7 ?>">Copy these accounts to distribute</td>
        </tr>
        <tr>
          <th><?php echo $MSG_USER_ID ?></th>
          <th><?php echo $MSG_PASSWORD ?></th>
		  <th><?php echo $MSG_NICK ?></th>
		  <th><?php echo $MSG_EMAIL ?></th>
		  <th><?php echo $MSG_SCHOOL ?></th>
          <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
			<th><?php echo $MSG_Class ?></th>
			<th><?php echo $MSG_REAL_NAME ?></th>
			<th><?php echo $MSG_StudentID ?></th>
          <?php } ?>
          <th><?php echo $MSG_STATUS ?></th>          
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
<title><?php echo $html_title . $MSG_USER . $MSG_IMPORT ?></title>
<h1><?php echo $MSG_USER . $MSG_IMPORT ?></h1>
<hr>
<form class="am-form am-form-horizontal" method="post">
    <?php require_once("../include/set_post_key.php"); ?>
    <div class="am-g" style="max-width: 1300px;margin-left: 20px;">
        <div class="am-g">
            <div class="am-form-group am-u-sm-5" style="white-space: nowrap;">
                <input type="submit" value="<?php echo $MSG_SUBMIT ?>" name="add" class="am-btn am-btn-success">
            </div>
        </div>
        <div class="am-g">
            <div class="am-u-sm-2">
                <font color='red'><b>*</b></font>&nbsp;<label class="am-form-label"><?php echo $MSG_USER_ID ?>:</label><textarea name="user_id" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_USER_ID ?>占1行<?php echo "\n" . $MSG_USER_ID . "1\n" . $MSG_USER_ID . "2\n" . $MSG_USER_ID . "3\n每个限3-20位以内的英文字母和数字" ?>" required></textarea>
            </div>
            <div class="am-u-sm-2">
                <font color='red'><b>*</b></font>&nbsp;<label class="am-form-label"><?php echo $MSG_PASSWORD ?>:</label><textarea name="password" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_PASSWORD ?>占1行<?php echo "\n" . $MSG_PASSWORD . "1\n" . $MSG_PASSWORD . "2\n" . $MSG_PASSWORD . "3\n" . "若行数不足系统将生成随机{$MSG_PASSWORD}补足差额。" ?> "></textarea>
            </div>
            <div class="am-u-sm-2">
                <label class="am-form-label"><?php echo $MSG_NICK ?>:</label><textarea name="nick" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_NICK ?>占1行<?php echo "\n" . $MSG_NICK . "1\n" . $MSG_NICK . "2\n" . $MSG_NICK . "3\n每个限20个以内的汉字、字母、数字或下划线，若行数不足，剩余" . $MSG_USER . "将使用“" . $MSG_USER_ID . "”作" . $MSG_NICK . "。" ?> "></textarea>
            </div>
            <div class="am-u-sm-2">
                <label class="am-form-label"><?php echo $MSG_EMAIL ?>:</label><textarea name="email" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_EMAIL ?>占1行<?php echo "\n" . $MSG_EMAIL . "1\n" . $MSG_EMAIL . "2\n" . $MSG_EMAIL . "3\n若行数不足，剩余" . $MSG_USER . "的此项信息将空白。" ?> "></textarea>
            </div>
            <div class="am-u-sm-2 am-u-end">
                <label class="am-form-label"><?php echo $MSG_SCHOOL ?>:</label><textarea name="school" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_SCHOOL ?>占1行<?php echo "\n" . $MSG_SCHOOL . "1\n" . $MSG_SCHOOL . "2\n" . $MSG_SCHOOL . "3\n若行数不足，剩余" . $MSG_USER . "的此项信息将空白。" ?> "></textarea>
            </div>
        </div>
        <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
            <div class="am-g">
                <div class="am-u-sm-2">
                    <label class="am-form-label"><?php echo $MSG_Class ?>:</label><textarea name="class" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_Class_Name ?>占1行<?php echo "\n" . $MSG_Class_Name . "1\n" . $MSG_Class_Name . "2\n" . $MSG_Class_Name . "3\n若行数不足，剩余" . $MSG_USER . "将归到班级“其它”。" ?> "></textarea>
                </div>
                <div class="am-u-sm-2">
                    <label class="am-form-label"><?php echo $MSG_REAL_NAME ?>:</label><textarea name="real_name" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_REAL_NAME ?>占1行<?php echo "\n" . $MSG_REAL_NAME . "1\n" . $MSG_REAL_NAME . "2\n" . $MSG_REAL_NAME . "3\n每个限20个以内的汉字、字母、数字或下划线，若行数不足，剩余" . $MSG_USER . "的此项信息将空白。" ?> "></textarea>
                </div>
                <div class="am-u-sm-2 am-u-end">
                    <label class="am-form-label"><?php echo $MSG_StudentID ?>:</label><textarea name="stu_id" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_StudentID ?>占1行<?php echo "\n" . $MSG_StudentID . "1\n" . $MSG_StudentID . "2\n" . $MSG_StudentID . "3\n每个限20位以内的字母或数字，若行数不足，剩余" . $MSG_USER . "的此项信息将空白。" ?> "></textarea>
                </div>
            </div>
        <?php } ?>
    </div>
</form>
<?php
require_once "admin-footer.php";
?>
