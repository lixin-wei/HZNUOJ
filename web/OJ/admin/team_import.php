<?php
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
	$cid = $_POST['contest_id'];
	$sql = "SELECT `contest_id`, `title`, `defunct` FROM `contest` WHERE `contest_id`='$cid' AND NOT `practice` AND `user_limit`='Y'";
	$contest = $mysqli->query($sql)->fetch_object();
	if (!$contest) {
		$err_str = $err_str . "编号为{$contest_id}的{$MSG_CONTEST}不存在或不是{$MSG_Special}！\\n";
		$err_cnt++;
	}
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
	$institute = explode("\n", trim($_POST['institute']));
	$class = explode("\n", trim($_POST['class']));
	$real_name = explode("\n", trim($_POST['real_name']));
	$nick = explode("\n", trim($_POST['nick']));
	$seat = explode("\n", trim($_POST['seat']));
	$report = array();
	foreach ($user_id as $key => $value) {
		$user_id[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $user_id[$key])));
		$report[$key]['user_id'] = $user_id[$key];
		$nick[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $nick[$key])));
		if (!$nick[$key]) $nick[$key] = $user_id[$key];
		$report[$key]['nick'] = $nick[$key];
		$contest_status = ($contest->defunct == 'Y') ? '<font color=red>【' . $MSG_Reserved . '】</font>' : "";
    	$report[$key]['contest'] = "【" . $contest->contest_id . "】" . $contest->title . $contest_status;
		$password = createPwd($user_id[$key], 10);
		$report[$key]['password'] = $password;
		$seat[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $seat[$key])));
		$report[$key]['seat'] = $seat[$key];
		$institute[$key] = $mysqli->real_escape_string(trim(str_replace("\r", "", $institute[$key])));
		$report[$key]['institute'] = $institute[$key];
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
		if(!preg_match("/^[a-zA-Z0-9]{3,20}$/", $user_id[$key])) {
			$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_USER_ID}不合规，限3-20位以内的英文字母和数字！";
		} else if(!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{1,60}$/", $nick[$key])) {
			$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_NICK}不合规，限20个以内的汉字、字母、数字或下划线！";
		} else if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
			if (!class_is_exist($class[$key])){				
				$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_Class}【{$class[$key]}】不存在！";
			} else if(!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{0,60}$/", $real_name[$key])) {
				$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_REAL_NAME}不合规，限20个以内的汉字、字母、数字或下划线！";
			} else if(!preg_match("/^[a-zA-Z0-9]{0,20}$/", $stu_id[$key])) {
				$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，{$MSG_StudentID}不合规，限20位以内的字母+数字或者纯数字的学号！";
			}
		}
		if ($report[$key]['status'] == "Success") {
			$password = pwGen($password);
			$sql = <<<SQL
			INSERT INTO team (
				`contest_id`,
				`user_id`,
				`stu_id`,
				`institute`,
				`class`,
				`real_name`,
				`nick`,
				`seat`,
				`PASSWORD`,
				`ip`,
				`reg_time`
			)
			VALUES
			(
				$cid,
				'{$user_id[$key]}',
				'{$stu_id[$key]}',
				'{$institute[$key]}',
				'{$class[$key]}',
				'{$real_name[$key]}',
				'{$nick[$key]}',
				'{$seat[$key]}',
				'$password',
				'{$_SERVER['REMOTE_ADDR']}',
				NOW()
			) ON DUPLICATE KEY UPDATE  
				`stu_id`='{$stu_id[$key]}',
				`institute`='{$institute[$key]}',
				`class`='{$class[$key]}',
				`real_name`='{$real_name[$key]}',
				`nick`='{$nick[$key]}',
				`seat`='{$seat[$key]}',
				`PASSWORD`='$password',
				`ip`='{$_SERVER['REMOTE_ADDR']}',
				`reg_time`=NOW()
SQL;
			//echo "<pre>$sql</pre>";
			$mysqli->query($sql);
			if ($mysqli->affected_rows <= 0) {
				$report[$key]['status'] = "<font color='red'><b>Fail</b></font>，Unknow Error!";
			}
		}
	}
?>
<title><?php echo $html_title . $MSG_TEAM . $MSG_IMPORT ?></title>
<h1><?php echo $MSG_TEAM . $MSG_IMPORT ?></h1>
<h4><?php echo $MSG_HELP_TEAMGENERATOR ?></h4>
<hr>
  <div class="am-g" style="max-width: 1300px;margin-top: 0px;margin-bottom: 0px;">
    <input type="button" name="submit" value="返回" onclick="javascript:history.go(-1);" style="margin-bottom: 20px;">
    <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;width:600px">
      <thead>
        <tr>
          <td colspan="<?php echo (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) ? 10 : 7 ?>">Copy these accounts to distribute</td>
        </tr>
        <tr>
          <th><?php echo $MSG_TEAM ?></th>
		  <th><?php echo $MSG_NICK ?></th>
		  <th><?php echo $MSG_CONTEST ?></th>
		  <th><?php echo $MSG_PASSWORD ?></th>
		  <th><?php echo $MSG_Seat ?></th>
		  <th><?php echo $MSG_Institute ?></th>
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

<title><?php echo $html_title . $MSG_TEAM . $MSG_IMPORT ?></title>
<h1><?php echo $MSG_TEAM . $MSG_IMPORT ?></h1>
<h4><?php echo $MSG_HELP_TEAMGENERATOR ?></h4>
<hr>
<form class="am-form am-form-horizontal" method="post">
	<?php require_once("../include/set_post_key.php"); ?>
	<div class="am-g" style="max-width: 1300px;margin-left: 20px;">
		<div class="am-g">
			<div class="am-form-group am-u-sm-5" style="white-space: nowrap;">
				<label class="am-form-label">
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
				</select>&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" value="<?php echo $MSG_SUBMIT ?>" name="add" class="am-btn am-btn-success">
			</div>
		</div>
		<div class="am-g">
			<div class="am-u-sm-2">
				<font color='red'><b>*</b></font>&nbsp;<label class="am-form-label"><?php echo $MSG_USER_ID ?>:</label><textarea name="user_id" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_USER_ID ?>占1行<?php echo "\n" . $MSG_TEAM . "1\n" . $MSG_TEAM . "2\n" . $MSG_TEAM . "3\n每个限3-20位以内的英文字母和数字" ?>" required></textarea>
			</div>
			<div class="am-u-sm-2">
				<label class="am-form-label"><?php echo $MSG_NICK ?>:</label><textarea name="nick" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_NICK ?>占1行<?php echo "\n" . $MSG_NICK . "1\n" . $MSG_NICK . "2\n" . $MSG_NICK . "3\n每个限20个以内的汉字、字母、数字或下划线，若行数不足，剩余" . $MSG_TEAM . "将使用“" . $MSG_USER_ID . "”作" . $MSG_NICK . "。" ?> "></textarea>
			</div>
			<div class="am-u-sm-2">
				<label class="am-form-label"><?php echo $MSG_Seat ?>:</label><textarea name="seat" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_Seat ?>占1行<?php echo "\n" . $MSG_Seat . "1\n" . $MSG_Seat . "2\n" . $MSG_Seat . "3\n若行数不足，剩余" . $MSG_TEAM . "的此项信息将空白。" ?> "></textarea>
			</div>
			<div class="am-u-sm-2  am-u-end">
				<label class="am-form-label"><?php echo $MSG_Institute ?>:</label><textarea name="institute" rows="10" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_Institute ?>占1行<?php echo "\n" . $MSG_Institute . "1\n" . $MSG_Institute . "2\n" . $MSG_Institute . "3\n若行数不足，剩余" . $MSG_TEAM . "的此项信息将空白。" ?> "></textarea>
			</div>
		</div>
		<?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
			<div class="am-g">
				<div class="am-u-sm-2">
					<label class="am-form-label"><?php echo $MSG_Class ?>:</label><textarea name="class" rows="9" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_Class_Name ?>占1行<?php echo "\n" . $MSG_Class_Name . "1\n" . $MSG_Class_Name . "2\n" . $MSG_Class_Name . "3\n若行数不足，剩余" . $MSG_TEAM . "将归到班级“其它”。" ?> "></textarea>
				</div>
				<div class="am-u-sm-2">
					<label class="am-form-label"><?php echo $MSG_REAL_NAME ?>:</label><textarea name="real_name" rows="9" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_REAL_NAME ?>占1行<?php echo "\n" . $MSG_REAL_NAME . "1\n" . $MSG_REAL_NAME . "2\n" . $MSG_REAL_NAME . "3\n每个限20个以内的汉字、字母、数字或下划线，若行数不足，剩余" . $MSG_TEAM . "的此项信息将空白。" ?> "></textarea>
				</div>
				<div class="am-u-sm-2 am-u-end">
					<label class="am-form-label"><?php echo $MSG_StudentID ?>:</label><textarea name="stu_id" rows="9" style="width:205px;" placeholder="*示例：1个<?php echo $MSG_StudentID ?>占1行<?php echo "\n" . $MSG_StudentID . "1\n" . $MSG_StudentID . "2\n" . $MSG_StudentID . "3\n每个限20位以内的字母或数字，若行数不足，剩余" . $MSG_TEAM . "的此项信息将空白。" ?> "></textarea>
				</div>
			</div>
		<?php } ?>
	</div>
</form>
<?php
require_once "admin-footer.php";
?>
