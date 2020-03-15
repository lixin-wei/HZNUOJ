<?php require_once("admin-header.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
?>
<?php if (isset($_POST['do'])) {
  require_once("../include/check_post_key.php");
  $from = $mysqli->real_escape_string($_POST['from']);
  $to = $mysqli->real_escape_string($_POST['to']);
  $start = intval($_POST['start']);
  $end = intval($_POST['end']);
  $sql = "update `solution` set `user_id`='$to' where `user_id`='$from' and problem_id>=$start and problem_id<=$end and result=4";
  //echo $sql;
  $mysqli->query($sql);
  echo $mysqli->affected_rows . " source file given!";
}
?>
<title><?php echo $html_title . $MSG_GIVESOURCE ?></title>
<h1><?php echo $MSG_GIVESOURCE ?></h1>
<h4><?php echo $MSG_HELP_GIVESOURCE ?></h4>
<hr>
<form class="am-form am-form-horizontal" method=post>
  <div class="am-g" style="max-width:600px;margin-left: 20px;">
    <div class="am-g" style="margin-top: 20px; white-space: nowrap;">
      <label class="am-form-label am-u-sm-2">
        <font color='red'><b>*</b></font>&nbsp;From:
      </label>
      <input class="am-u-sm-3" type="text" size="10" style="width:200px;" name="from" value="zhblue" required>
      <label class="am-form-label am-u-sm-2">
        <font color='red'><b>*</b></font>&nbsp;To:
      </label>
      <input class="am-u-sm-3 am-u-end" type="text" size="10" style="width:200px;" name="to" value="standard" required>
    </div>
    <div class="am-g" style="margin-top: 20px; white-space: nowrap;">
      <label class="am-form-label am-u-sm-2">
        <font color='red'><b>*</b></font>&nbsp;start <?php echo $MSG_PROBLEM_ID ?>:
      </label>
      <input class="am-u-sm-3" type="number" style="width:200px;" name="start" required>
      <label class="am-form-label am-u-sm-2">
        <font color='red'><b>*</b></font>&nbsp;end <?php echo $MSG_PROBLEM_ID ?>:
      </label>
      <input class="am-u-sm-3 am-u-end" type="number" style="width:200px;" name="to" required>
    </div>
    <div class="am-u-sm-12" style="margin-top: 20px;">
      <?php require_once("../include/set_post_key.php"); ?>
      <input type="submit" name="do" value="Give My Source To Him">
    </div>
  </div>
</form>
<?php
require_once("admin-footer.php")
?>
