<?php require_once("admin-header.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
require_once("../include/my_func.inc.php");
?>
<title><?php echo $html_title . $MSG_EXPORT . $MSG_PROBLEM ?></title>
<h1><?php echo  $MSG_EXPORT . $MSG_PROBLEM ?></h1>
<h4><?php echo $MSG_HELP_EXPORT_PROBLEM ?></h4>
<hr>
<form action='problem_export_xml.php' class="am-form am-form-horizontal" method='post'>
  <div class="am-g" style="max-width:600px;margin-left: 20px;">
    <div class="am-g" style="margin-top: 20px; white-space: nowrap;">
      <label class="am-form-label am-u-sm-2">1.<?php echo $MSG_PROBLEM_ID ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IN:</label>
      <input class="am-u-sm-3 am-u-end" type="text" placeholder="1000,1020" style="width:450px;" name="in">
    </div>
    <div class="am-g" style="margin-top: 20px; white-space: nowrap;">
      <label class="am-form-label am-u-sm-2">2.<?php echo $MSG_PROBLEM_ID ?>&nbsp;&nbsp;From:</label>
      <input class="am-u-sm-3" type="number" min="1000" placeholder="1000" style="width:200px;" name="start">
      <label class="am-form-label am-u-sm-1">To:</label>
      <input class="am-u-sm-3 am-u-end" type="number" min="1000" placeholder="1000" style="width:200px;" name="end">
    </div>
    <div class="am-g" style="margin-top: 20px; white-space: nowrap;">
      <label class="am-form-label am-u-sm-2">3.<?php echo $MSG_CONTEST ?>:</label>
      <select name="cid" class="selectpicker show-tick" data-live-search="true" data-width="450px"  data-size="8" data-title="选择一个<?php echo $MSG_CONTEST ?>">
          <option value='' selected></option>
          <?php
          $view_contest = get_contests(array("Special" => true, "Private" => true, "Public" => true, "Practice" => true));
          foreach ($view_contest as $view_con) {
              if ($view_con['data']) { ?>
                  <optgroup <?php echo "label='{$view_con['type']}' {$view_con['disabled']}" ?>>
                      <?php foreach ($view_con['data'] as $contest) :
                          $contest_status = ($contest['defunct'] == 'Y') ? '【' . $MSG_Reserved . '】' : ""; ?>
                          <option value="<?php echo $contest['contest_id'] ?>" <?php if ($contest['contest_id'] == $row->contest_id) echo "selected" ?>><?php echo "【" . $contest['contest_id'] . "】" . $contest['title'] . $contest_status ?></option>
                      <?php endforeach ?>
                  </optgroup>
          <?php }
          }  ?>
      </select>&nbsp;
    </div>
    <div class="am-u-sm-12" style="margin-top: 20px;">
      <?php require_once("../include/set_post_key.php"); ?>
      <input type="submit" name="download" value="<?php echo $MSG_EXPORT.$MSG_Download ?>"><br>
* IF using IN,From-To will not working.<br>
* From-To will working when empty IN.<br>
* IN can go with "," seperated problem_ids like [1000,1020].<br>
* 注：因系统差异，HZNUOJ导出的题目若要导入到hustoj，sample样例数据只能导入一组，多余一组的sample样例数据会被抛弃。
    </div>
  </div>
</form>

<?php 
  require_once("admin-footer.php")
?>
