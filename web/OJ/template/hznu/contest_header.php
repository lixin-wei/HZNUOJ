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
 // 是否显示tag的判断
require_once "include/db_info.inc.php";
if(!isset($mysqli))exit(0);
$show_tag = false;
if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
  $uid = $_SESSION['user_id'];
  $sql = "SELECT tag FROM users WHERE user_id='$uid'";
  $result = $mysqli->query($sql);
  $row_h = $result->fetch_array();
  $result->free();
  if ($row_h['tag'] == "Y") $show_tag = true;
} else if (isset($_SESSION['tag'])) {
  if ($_SESSION['tag'] == "N") $show_tag = false;
  else $show_tag = true;
}
if ($show_tag) $_SESSION['tag'] = "Y";
else $_SESSION['tag'] = "N";

if(isset($_GET['cid'])){
  $warnning_percent=90;
  $cid =  $mysqli->real_escape_string($_GET['cid']);
  $sql="SELECT UNIX_TIMESTAMP(start_time) AS start_time, UNIX_TIMESTAMP(end_time) AS end_time,`unlock`,lock_time,title FROM contest WHERE contest_id='$cid'";
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
  $unlock=$contest_time['unlock'];
  switch($unlock){
      case 0: //用具体时间来控制封榜
          $view_lock_time=$contest_time['end_time'] - $contest_time['lock_time'];
          break;
      case 2: //用时间比例来控制封榜
          $view_lock_time=$contest_time['end_time'] - ($contest_time['end_time'] - $contest_time['start_time']) * $contest_time['lock_time'] / 100;
          break;
  }
  $contest_title=$contest_time['title'];
  $title.="<".$contest_title.">";
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
    <link rel="alternate icon" type="image/png" href="<?php echo $ICON_PATH ?>">
    <link rel="stylesheet" href="plugins/AmazeUI/css/amazeui.min.css"/>
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"/> -->
    <style type="text/css">
	 .well{
    display: block;
    padding: 1rem;
    margin: 1rem 0;
    /*font-size: 1.3rem;*/
    line-height: 1.6;
    word-break: break-all;
    word-wrap: break-word;
    color: #555;
    background-color: #f8f8f8;
    border: 1px solid #dedede;
    border-radius: 0;
 }
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
<body style="padding-top: 50px;">
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
      <?php if (!isset($_SESSION['contest_id'])) { ?>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="index.php"){echo "class='am-active'";} ?>><a class="am-icon-chevron-left" href="./contest.php"> <?php echo $BACK_TO_CONTEST ?></a></li>
      <?php } ?>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contest.php" || basename($_SERVER['SCRIPT_NAME'])=="problem.php"){echo "class='am-active'";} ?>><a href='./contest.php?cid=<?php echo $cid?>'><?php echo $MSG_PROBLEM ?></a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="status.php"){echo "class='am-active'";} ?>><a href='./status.php?cid=<?php echo $cid?>'><?php echo $MSG_STATUS ?></a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contestrank.php"){echo "class='am-active'";} ?>><a href='./contestrank.php?cid=<?php echo $cid?>'><?php echo $MSG_RANKLIST ?></a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contestrank-oi.php"){echo "class='am-active'";} ?>><a href='./contestrank-oi.php?cid=<?php echo $cid?>'>OI <?php echo $MSG_RANKLIST ?></a></li>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="conteststatistics.php"){echo "class='am-active'";} ?>><a href='./conteststatistics.php?cid=<?php echo $cid?>'><?php echo $MSG_STATISTICS ?></a></li>
        <?php if(isset($OJ_show_PrinterAndDiscussInContest)&&$OJ_show_PrinterAndDiscussInContest){?>
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contest_code_printer.php"){echo "class='am-active'";} ?>><a href='./contest_code_printer.php?cid=<?php echo $cid?>'>Printer</a></li>     
        <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contest_discuss.php"){echo "class='am-active'";} ?>><a href='./contest_discuss.php?cid=<?php echo $cid?>'>Discuss</a></li>   
        <?php }?>
      </ul>
        <!-- 用户部分 start -->
        <?php
        if (!isset($_SESSION['user_id'])){
echo <<<BOT
          <div class="am-topbar-right">
            <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;"> $MSG_LOGIN <span class="am-icon-caret-down"></span></a>
                  <ul class="am-dropdown-content">
                    <li><a href="loginpage.php"><span class="am-icon-user"></span> $MSG_LOGIN</a></li>
                    <li><a href="registerpage.php"><span class="am-icon-pencil"></span> $MSG_REGISTER</a></li>
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
                  <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'><span class='am-icon-user'></span> {$_SESSION['user_id']} <span class='am-icon-caret-down'></span></a>
                    <ul class="am-dropdown-content">
BOT;
                    if (!isset($_SESSION['contest_id'])) {
echo <<<BOT
                      <li><a href="modifypage.php"><span class="am-icon-eraser"></span> $MSG_MODIFY_USER</a></li>
                      <li><a href="userinfo.php?user={$_SESSION['user_id']}"><span class="am-icon-info-circle"></span> $MSG_USERINFO</a></li>
                      <!-- <li><a href="mail.php"><span class="am-icon-comments"></span> Mail</a></li> -->
                      <li><a href="status.php?user_id=$user_session"><span class="am-icon-keyboard-o"></span> $MSG_MY_SUBMISSIONS</a></li>
					  <li><a href="./contest.php?my"><span class="am-icon-leaf"></span> $MSG_MY_CONTESTS </a></li> 
BOT;
          if ($show_tag) echo "<li><a href='./changeTag.php'><span class='am-icon-toggle-on'></span> $MSG_HIDETAG</a></li>";
          else echo "<li><a href='./changeTag.php'><span class='am-icon-toggle-off'></span> $MSG_SHOWTAG</a></li>";
          }
		  echo "<li><a href='./logout.php'><span class='am-icon-reply'></span> $MSG_LOGOUT</a></li>";
          if(HAS_PRI('enter_admin_page')){
            echo <<<BOT
              <li><a href="admin/index.php"><span class="am-icon-cog"></span> $MSG_ADMIN</a></li>
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
<div class="am-container" style="margin-top: 20px;">
  <div class="am-g" style="padding-bottom: 7px;">
    <div class="am-u-sm-3">
      <span class="text-bold"><?php echo $MSG_StartTime ?>: </span>
      <span><?php echo date("Y-m-d, H:i:s",$contest_time[0]) ?></span>
    </div>
    <div class="am-u-sm-6 am-text-center">
      <span class="text-bold" style="font-size: large;"><?php echo $contest_title ?></span>
    </div>
    <div class="am-u-sm-3 am-text-right">
      <span class="text-bold"><?php echo $MSG_EndTime ?>: </span>
      <span><?php echo date("Y-m-d, H:i:s",$contest_time[1]) ?></span>
    </div>
  </div>

  <div class="am-progress am-progress-striped am-active" id="contest-bar" style="margin-bottom: 0;">
    <div class="am-progress-bar <?php echo $bar_color ?>" style="width: <?php echo $bar_percent ?>%" id="contest-bar-progress">
      <?php if (!$is_started)
         echo $MSG_notStart;
     ?>
    </div>
  </div>

  <?php if ($is_started): ?>
  <div class="am-g">
    <div class="am-u-sm-4">
      <span class="text-bold"><?php echo $MSG_TimeElapsed ?>: </span>
      <span id="time_elapsed"></span>
    </div>
    <div class="am-u-sm-4 am-text-center">
    <?php if ($unlock!=1) { ?>
      <span class="text-bold"><?php echo $MSG_LockTime ?>: </span>
      <span><?php echo date("Y-m-d, H:i:s",$view_lock_time) ?></span>
    <?php }?>
    </div>
    <div class="am-u-sm-4 am-text-right">
      <span class="text-bold"><?php echo $MSG_TimeRemaining ?>: </span>
      <span id="time_remaining"></span>
    </div>
  </div>
  <?php endif ?>
    <?php if(HAS_PRI("edit_contest")) {
        echo <<<HTML
        <div align="center" style="margin-top: 5px;">
          <span class="am-badge am-badge-success am-text-lg">
            <a href="./admin/contest_edit.php?cid={$_GET['cid']}" style="color: white;">$MSG_EDIT</a>
          </span>
        </div>
HTML;
    }
    ?>
</div>