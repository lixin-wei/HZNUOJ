<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.24
   * last modified
   * by yybird
   * @2016.05.26
  **/
?>
<?php
if(isset($_GET['cid'])){
  $warnning_percent=90;

  $sql="SELECT UNIX_TIMESTAMP(start_time), UNIX_TIMESTAMP(end_time) FROM contest WHERE contest_id='{$_GET['cid']}'";
  $res=$mysqli->query($sql);
  $contest_time=$res->fetch_array();
  $contest_len=$contest_time[1]-$contest_time[0];
  $now=time();
  $bar_percent=0;
  $is_started=false;
  if($now>=$contest_time[0])$is_started=true;
  $dur=$now-$contest_time[0];
  if($dur>=$contest_len)$dur=$contest_len;
  $bar_percent=$dur/$contest_len*100;



  $bar_color="am-progress-bar-success";
  if($bar_percent==100)$bar_color="am-progress-bar-secondary";
  else if($bar_percent>=$warnning_percent)$bar_color="am-progress-bar-danger";

  if(!$is_started){
    $bar_percent=100;
    $bar_color="am-progress-bar-secondary";
  }

  $sql="SELECT title FROM contest WHERE contest_id='{$_GET['cid']}'";
  $res=$mysqli->query($sql);
  $contest_title=$res->fetch_array()[0];
  $title=$contest_title;
}
?>
<!doctype html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $OJ_NAME."--".$title?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/png" href="image/hznuoj.ico">
   <!--  <link rel="stylesheet" href="AmazeUI/css/amazeui.min.css"/> -->
    <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"/>
    <style type="text/css">
      .blog-footer {
        padding: 10px 0;
        text-align: center;
      }
      .am-container {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        max-width: 1400px;
      }
      .am-badge {
        font-weight: normal;
      }
    </style>
  </head>
<body>
<?php 
    if(isset($_GET['cid']))
      $cid=intval($_GET['cid']);
    if (isset($_GET['pid']))
      $pid=intval($_GET['pid']);
?>
<header class="am-topbar-inverse am-topbar-fixed-top">
  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-primary am-show-sm-only" data-am-collapse="{target: '#collapse-head'}">
    <span class="am-sr-only">导航切换</span>
    <span class="am-icon-bars"></span>
  </button>
  <div class="am-container">
    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <ul class="am-nav am-nav-pills am-topbar-nav">
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="index.php"){echo "class='am-active'";} ?>><a href="./">Home</a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contest.php"){echo "class='am-active'";} ?>><a href='./contest.php?cid=<?php echo $cid?>'>Problem</a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contestrank.php"){echo "class='am-active'";} ?>><a href='./contestrank.php?cid=<?php echo $cid?>'>Ranklist</a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="status.php"){echo "class='am-active'";} ?>><a href='./status.php?cid=<?php echo $cid?>'>Status</a></li>
        <li><a href='./contest.php'>Contest</a></li>
      </ul>
        <!-- 用户部分 start -->
        <?php
        if (!isset($_SESSION['user_id'])){
echo <<<BOT
          <div class="am-topbar-right">
            <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">Login<span class="am-icon-caret-down"></span></a>
                  <ul class="am-dropdown-content">
                    <li><a href="loginpage.php"><span class="am-icon-user"></span> Login</a></li>
                    <li><a href="registerpage.php"><span class="am-icon-pencil"></span> Register</a></li>
                  </ul>
              </li>
            </ul>
        </div>
BOT;
        }else{
          $user_session = $_SESSION['user_id'];
echo <<<BOT
          <div class="am-topbar-right">
               <ul class="am-nav am-nav-pills am-topbar-nav">
                <li class="am-dropdown" data-am-dropdown>
                  <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'><span class='am-icon-user'></span> {$_SESSION['user_id']}<span class='am-icon-caret-down'></span></a>
                    <ul class="am-dropdown-content">
                      <li><a href="modifypage.php"><span class="am-icon-eraser"></span> Modify Info</a></li>
                      <li><a href="userinfo.php?user={$_SESSION['user_id']}"><span class="am-icon-info-circle"></span> User Info</a></li>
                      <!-- <li><a href="mail.php"><span class="am-icon-comments"></span> Mail</a></li> -->
                      <li><a href="status.php?user_id=$user_session"><span class="am-icon-leaf"></span> Recent</a></li>
                      <li><a href="logout.php"><span class="am-icon-reply"></span> Logout</a></li>
BOT;

          if(HAS_PRI('enter_admin_page')){
            echo <<<BOT
              <li><a href="admin/index.php"><span class="am-icon-cog"></span> Admin</a></li>
                      </ul>
                  </li>
                </ul>
          </div>
BOT;
          }else{
            echo <<<BOT
                      </ul>
                  </li>
                </ul>
          </div>
BOT;
          }
        }
        ?>
        <!-- 用户部分 end -->
    </div>
  </div>
</header>
<style type="text/css" media="screen">
  .text-bold{
    font-weight: bold;
  }
</style>
<hr/>
<div class="am-container" style="margin-top: 20px;">
  <div class="am-g" style="padding-bottom: 7px;">
    <div class="am-u-sm-3">
      <span class="text-bold">Start: </span>
      <span><?php echo date("M, d, o h:i:s",$contest_time[0]) ?></span>
    </div>
    <div class="am-u-sm-6 am-text-center">
      <span class="text-bold" style="font-size: large;"><?php echo $contest_title ?></span>
    </div>
    <div class="am-u-sm-3 am-text-right">
      <span class="text-bold">End: </span>
      <span><?php echo date("M, d, o h:i:s",$contest_time[1]) ?></span>
    </div>
  </div>

  <div class="am-progress am-progress-striped am-active" id="contest-bar" style="margin-bottom: 0;">
    <div class="am-progress-bar <?php echo $bar_color ?>" style="width: <?php echo $bar_percent ?>%" id="contest-bar-progress">
      <?php if (!$is_started): ?>
        Contest hasn't started now!
      <?php endif ?>
    </div>
  </div>

  <?php if ($is_started): ?>
  <div class="am-g">
    <div class="am-u-sm-6">
      <span class="text-bold">Time elapsed: </span>
      <span id="time_elapsed"></span>
    </div>
    <div class="am-u-sm-6 am-text-right">
      <span class="text-bold">Time remaining: </span>
      <span id="time_remaining"></span>
    </div>
  </div>
  <?php endif ?>

</div>
<?php if ($is_started): ?>
<hr/>
<?php endif ?>