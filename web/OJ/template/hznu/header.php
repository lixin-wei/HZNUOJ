<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.05.26
   * last modified
   * by wlx
   * @2016.05.24
  **/
?>


<?php // 是否显示tag的判断
  require_once("include/db_info.inc.php");

  $show_tag = true;
  if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
    $uid = $_SESSION['user_id'];
    $sql = "SELECT tag FROM users WHERE user_id='$uid'";
    $result = mysql_query($sql);
    $row_h = mysql_fetch_array($result);
    mysql_free_result($result);
    if ($row_h['tag'] == "N") $show_tag = false;
  } else if (isset($_SESSION['tag'])) {
    if ($_SESSION['tag'] == "N") $show_tag = false;
    else $show_tag = true;
  }

  if ($show_tag) $_SESSION['tag'] = "Y";
  else $_SESSION['tag'] = "N";
?>


<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $OJ_NAME."--".$title?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/jpg" href="{$ICON_PATH}">
    <link rel="stylesheet" href="AmazeUI/css/amazeui.min.css"/>

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
      .tt{
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .am-badge{
        font-weight: normal;
      }
    </style>
  </head>
  <body class='am-with-topbar-fixed-top bg'>
  <header class="am-topbar-inverse am-topbar-fixed-top">
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-primary am-show-sm-only" data-am-collapse="{target: '#collapse-head'}">
      <span class="am-sr-only">导航切换</span>
      <span class="am-icon-bars"></span>
    </button>
    <div class="am-container" >
      <h1 class="am-topbar-brand">
        <a href="index.php">HZNUOJ</a>
      </h1>
      <div class="am-collapse am-topbar-collapse" id="collapse-head">
        <ul class="am-nav am-nav-pills am-topbar-nav">
          
          <!-- ProblemSet部分 start -->
          <li class='am-dropdown' data-am-dropdown>
            <a href="#" class="am-dropdown-toggle" >ProblemSet <span class="am-icon-caret-down"></span></a>
            <ul class="am-dropdown-content ">
              <li><a href="problemset.php">All</a></li>
              <?php
              $___res = mysql_query("SELECT set_name,set_name_show FROM problemset");
              while($___row = mysql_fetch_array($___res)){
                echo "<li><a href='problemset.php?OJ=$___row[0]'>$___row[1]</a></li>";
              }
              ?>
            </ul>
          </li>
          <!-- ProblemSet部分 end -->

          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="status.php"){echo "class='am-active'";} ?>><a href="status.php">Status</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="ranklist.php"){echo "class='am-active'";} ?>><a href="ranklist.php">Ranklist</a></li>
          
          <!-- Contest部分 start -->
          <li class='am-dropdown' data-am-dropdown>
            <a href="#" class="am-dropdown-toggle" ><?php echo $MSG_CONTEST ?>&nbsp;<span class="am-icon-caret-down"></span></a>
            <ul class="am-dropdown-content ">
              <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="contest.php"){echo "class='am-active'";} ?>><a href="contest.php">Local</a></li>
              <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="recent-contest.php"){echo "class='am-active'";} ?>><a href="recent-contest.php">Remote</a></li>
            </ul>
          </li>
          <!-- Contest部分 end -->

          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="faqs.php"){echo "class='am-active'";} ?>><a href="faqs.php">F.A.Qs</a></li>
          <li><a href="../bbs/" target='_blank'>BBS</a></li>
          <li><a href="<?php echo $VJ_URL; ?>" target='_blank'>vjudge</a></li>
        </ul>

        <!-- 用户部分 start -->
        <?php
        if (!isset($_SESSION['user_id'])){
echo <<<BOT
          <div class="am-topbar-right">
            <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">Login <span class="am-icon-caret-down"></span></a>
                  <ul class="am-dropdown-content">
                    <li><a href="loginpage.php"><span class="am-icon-user"></span> Login</a></li>
                    <li><a href="registerpage.php"><span class="am-icon-pencil"></span> Register</a></li>
BOT;
                  if ($show_tag) echo "<li><a href='changeTag.php'><span class='am-icon-toggle-on'></span> Hide Tag</a></li>";
                  else echo "<li><a href='changeTag.php'><span class='am-icon-toggle-off'></span> Show Tag</a></li>";
echo <<<BOT
                  </ul>
              </li>
            </ul>
        </div>
BOT;
        } else {
          $user_session = $_SESSION['user_id'];
echo <<<BOT
          <div class="am-topbar-right">
             <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'><span class='am-icon-user'></span> {$_SESSION['user_id']}<span class='am-icon-caret-down'></span></a>
                  <ul class="am-dropdown-content">
BOT;
          if (!isset($_SESSION['contest_id'])) {
echo <<<BOT
                    <li><a href="modifypage.php"><span class="am-icon-eraser"></span> Modify Info</a></li>
                    <li><a href="userinfo.php?user={$_SESSION['user_id']}"><span class="am-icon-info-circle"></span> User Info</a></li>
                    <!-- <li><a href="mail.php"><span class="am-icon-comments"></span> Mail</a></li> -->
                    <li><a href="status.php?user_id=$user_session"><span class="am-icon-leaf"></span> Recent</a></li>                
BOT;
          }
          if ($show_tag) echo "<li><a href='changeTag.php'><span class='am-icon-toggle-on'></span> Hide Tag</a></li>";
          else echo "<li><a href='changeTag.php'><span class='am-icon-toggle-off'></span> Show Tag</a></li>";
          echo "<li><a href='logout.php'><span class='am-icon-reply'></span> Logout</a></li>";
          
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
