<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/header.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/plugins/Parserdown.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/config.php";

$sql="SELECT announcement, title FROM formal_contest WHERE id = $contest_id";
$res=$mysqli->query($sql)->fetch_array();
$announcement = $res['announcement'];
$title = $res['title'];
?>


<style type="text/css" media="screen">
    .big-title{
        background: url("img/title_bk.png");
        height: 300px;
    }
</style>
<div class="big-title">
    <div class="am-g am-text-center" style="padding-top: 75px;">
        <span style="font-size: 30pt;">
            <?php echo $title ?>
            <?php if($is_end) echo "(报名已终止)"; ?>     
        </span>
    </div>
    <div class="am-g" style="margin-top: 60px; width: 70%;">
        <div class="am-u-sm-12">
            <ul class="am-nav am-nav-pills am-nav-justify">
              <li <?php if(basename($_SERVER['SCRIPT_NAME']) == "index.php") echo "class='am-active'"; ?>><a href="index.php">通知公告</a></li>
              <li <?php if(basename($_SERVER['SCRIPT_NAME']) == "register.php") echo "class='am-active'"; ?>><a href="register.php">注册报名</a></li>
            </ul>
        </div>
    </div>
</div>
