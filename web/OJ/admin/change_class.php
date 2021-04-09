<?php

/**
 * This file is created
 * by lixun516@qq.com
 * @2020.05.06
 **/
?>

<?php require_once("admin-header.php"); ?>
<?php
if (!HAS_PRI("edit_user_profile")) {
    $view_error = "You don't have the privilege to view this page!";
    require_once("error.php");
    exit(1);
}
$view_data = "";
if (isset($_POST['submit'])) {
    require_once("../include/check_post_key.php");
    require_once("../include/my_func.inc.php");
    $data = explode("\n", str_replace("\r", "", $_POST['data']));
    $view_data = "<table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap;'>\n";
    $view_data .= "<thead><tr>\n";
    $view_data .= "<th>$MSG_ID</th>\n";
    $view_data .= "<th>$MSG_USER_ID</th>\n";
    $view_data .= "<th>$MSG_NICK</th>\n";
    $view_data .= "<th>$MSG_New$MSG_Class</th>\n";
    $view_data .= "<th>$MSG_STATUS</th>\n";
    $view_data .= "</tr></thead><tbody>\n";
    $i = 1;
    foreach ($data as $d) {
        $u = explode(" ", preg_replace('/\s{1,}/',' ',trim($d)));
        $u[0] = $mysqli->real_escape_string(trim($u[0]));
        if (!$u[0]) continue;
        if (!class_is_exist("其它")) $mysqli->query("INSERT INTO `class_list` VALUES ('其它', '0')");
        if (!$u[1]) $u[1] = "其它";
        else $u[1] = $mysqli->real_escape_string($u[1]);
        $view_data .= "<tr><td>$i</td><td>$u[0]</td>";
        $sql = "SELECT `nick` FROM `users` WHERE `user_id`='$u[0]'";
        if($row=$mysqli->query($sql)->fetch_array()){
            $view_data .= "<td>$row[0]</td>";
        } else $view_data .= "<td></td>";
        $view_data .= "<td>$u[1]</td>";
        if (!class_is_exist($u[1])) {
            $view_data .= "<td><b>【$u[1]】不存在，请先添加相应班级记录再做分班操作！</b></td>";
        } else {
            if($row){
                $sql = "UPDATE `users` SET `class`='$u[1]' WHERE `user_id`='$u[0]'";
                $mysqli->query($sql);
                if ($mysqli->affected_rows>0) $view_data.= "<td>用户已划归至新班级！</td>";
                else $view_data.= "<td>用户已在班级【$u[1]】,班级信息未修改。</td>";
            } else $view_data.= "<td><b>用户不存在</b></td>";
        }
        $view_data .= "</tr>\n";
        $i++;
    }
    $view_data .= "</tbody></table>\n";
}
?>
<title><?php echo $html_title . $MSG_ChangeClass ?></title>
<h1><?php echo $MSG_ChangeClass ?></h1>
<h4><?php echo $MSG_HELP_CHANGECALSS ?></h4>
<?php
if (!(isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE))
    echo "<font color=red>(班级模式未启用，若有需要请联系管理员开启)</font>";
?>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <li><a href="user_list.php"><?php echo $MSG_USER . $MSG_LIST ?></a></li>
        <li><a href="user_list.php?team=all"><?php echo $MSG_TEAM . $MSG_LIST ?></a></li>
        <li><a href="class_list.php"><?php echo $MSG_Class . $MSG_LIST ?></a></li>
        <li class="am-active"><a href="change_class.php"><?php echo $MSG_ChangeClass ?></a></li>
        <li><a href="reg_code.php"><?php echo $MSG_REG_CODE ?></a></li>
    </ul>
</div>
<form class="form-inline" method="post">
    <?php require_once("../include/set_post_key.php"); ?>
    <div class="am-g">
        <div class="am-form-group am-u-sm-5" style="white-space: nowrap;">
            <input type="submit" value="<?php echo $MSG_SUBMIT ?>" name="submit" class="am-btn am-btn-success">
        </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-5">
            <textarea name="data" rows="30" style="width:500px;" placeholder="*示例：1个<?php echo $MSG_USER_ID ?>和1个<?php echo $MSG_Class_Name ?>以空格间隔为一组数据，每组数据占1行<?php echo "\n" . $MSG_USER_ID . "1 " . $MSG_New . $MSG_Class . "\n" . $MSG_USER_ID . "2 " . $MSG_New . $MSG_Class . "\n" . $MSG_USER_ID . "3 " . $MSG_New . $MSG_Class . "\n若" . $MSG_New . $MSG_Class . "留空，则对应账号划归默认班级“其它”。" ?>" required></textarea>
        </div>
        <div class="am-u-sm-7">
            <?php echo $view_data; ?>
        </div>
    </div>
</form>
<?php require_once("admin-footer.php") ?>