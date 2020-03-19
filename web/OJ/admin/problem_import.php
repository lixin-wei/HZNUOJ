<?php function writable($path)
{
  $ret = false;
  $fp = fopen($path . "/testifwritable.tst", "w");
  $ret = !($fp === false);
  fclose($fp);
  unlink($path . "/testifwritable.tst");
  return $ret;
}
require_once("admin-header.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
$maxfile = min(ini_get("upload_max_filesize"), ini_get("post_max_size"));

?>
<title><?php echo $html_title . $MSG_IMPORT . $MSG_PROBLEM ?></title>
<h1><?php echo  $MSG_IMPORT . $MSG_PROBLEM ?></h1>
<h4><?php echo $MSG_HELP_IMPORT_PROBLEM ?></h4>
<hr>
Import FPS data ,please make sure you file is smaller than [<?php echo $maxfile ?>] <br />
or set upload_max_filesize and post_max_size in PHP.ini<br />
if you fail on import big files[10M+],try enlarge your [memory_limit] setting in php.ini.<br>
<?php
$show_form = true;
if (!isset($OJ_SAE) || !$OJ_SAE) {
  if (!writable($OJ_DATA)) {
    echo "<br><font color='red'>You need to add <b>\"$OJ_DATA\"</b> into your open_basedir setting of php.ini,<br>
          or you need to execute:<br>
             <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
          you can't use import function at this time.<br></font>";
    $show_form = false;
  }
  //mkdir("../upload");
  if (!writable("../upload")) {

    echo "<font color='red'>../upload is not writable, <b>chmod 770 && chgrp -R www-data </b> to it.<br></font>";
    $show_form = false;
  }
}
if ($show_form) {
?>
  <br>
  <form class="form-horizontal" action='problem_import_xml.php' method="post" enctype="multipart/form-data">
    <?php require_once("../include/set_post_key.php"); ?>
    <div class="am-g" style="max-width:600px;margin-left: 20px;">
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_PROBLEMSET ?></label>
        <div class="col-sm-5 col-sm-end">
          <select class="form-control selectpicker" name="problemset">
            <?php
            $res = $mysqli->query("SELECT * FROM problemset");
            while ($row = $res->fetch_array()) {
              if (HAS_PRI("edit_" . $row['set_name'] . "_problem")) {
                echo "<option value=" . $row['set_name'];
                if (isset($row->problemset) && $row['set_name'] == $row->problemset) {
                  echo " selected='true'";
                }
                echo ">";
                echo $row['set_name_show'];
                echo "</oition>";
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_PROBLEM ?></label>
        <div class="col-sm-5 col-sm-end">
          <input type="file" name="fps" id="fps" accept=".xml,.zip" required>
        </div>
      </div>
      <div class="form-group">
        <div class="am-u-sm-6 col-sm-end am-u-sm-offset-3">
          <input type="submit" value="<?php echo $MSG_IMPORT ?>" style="margin-top: 10px;">
        </div>
      </div>
  </form>
<?php

}

?>
<br>
免费题目<a href="https://github.com/zhblue/freeproblemset/tree/master/fps-examples" target="_blank">下载</a><br>
更多题目请到 <a href="http://tk.hustoj.com/problemset.php?search=free" target="_blank">TK 题库免费专区</a>。
<?php
require_once("admin-footer.php")
?>
