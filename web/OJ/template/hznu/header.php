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
   * last modified
   * by zkin
   * @2018.09.07
  **/
?>


<?php // 是否显示tag的判断
  require_once "./include/db_info.inc.php";
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
    <link rel="alternate icon" type="image/jpg" href="<?php echo $ICON_PATH ?>">
    <link rel="stylesheet" href="./plugins/AmazeUI/css/amazeui.min.css"/>
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
      .tt{
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .am-badge{
        font-weight: normal;
      }
    </style>
  </head>
  <body class='am-with-topbar-fixed-top bg' style="padding-top: 40px;">
  <header class="am-topbar-inverse am-topbar-fixed-top">
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-primary am-show-sm-only" data-am-collapse="{target: '#collapse-head'}">
      <span class="am-sr-only">导航切换</span>
      <span class="am-icon-bars"></span>
    </button>
    <div class="am-container" >
      <h1 class="am-topbar-brand">
        <a href="<?php echo $OJ_HOME ?>"><?php echo $OJ_NAME ?></a>
      </h1>
      <div class="am-collapse am-topbar-collapse" id="collapse-head">
        <ul class="am-nav am-nav-pills am-topbar-nav">

        <?php $page_name=basename($_SERVER['SCRIPT_NAME']);?>
          <!-- Contest部分 start -->
          <li <?php
          if($page_name=="contest.php" || $page_name=="recent-contest.php") {
              echo "class='am-active'";
          }
          ?>><a href="./contest.php"><?php echo $MSG_CONTEST ?></a></li>
          <!-- Contest部分 end -->
          <?php 
        if (!isset($_SESSION['contest_id'])) { ?>
           <!-- course部分 start -->
           <li <?php
          if($page_name=="course.php") {
            echo "class='am-active'";
          }
          ?>><a href="./course.php"><?php echo $MSG_COURSE.$MSG_Assist ?></a></li>
          <!-- course部分 end -->

          <!-- category部分 start -->
          <li <?php
          if($page_name=="category.php") {
            echo "class='am-active'";
          }
          ?>><a href="./category.php"><?php echo $MSG_Source ?></a></li>
          <!-- category部分 end -->

          <!-- status部分 start -->
          <li <?php
          if($page_name=="status.php") {
            echo "class='am-active'";
          }
          ?>><a href="./status.php"><?php echo $MSG_STATUS ?></a></li>
          <!-- status部分 end -->
          
          <!-- ranklist部分 start -->
          <li <?php
          if($page_name=="ranklist.php") {
            echo "class='am-active'";
          }
          ?>><a href="./ranklist.php"><?php echo $MSG_RANKLIST ?></a></li>
          <!-- ranklist部分 end -->
          <!-- ProblemSet部分 start -->
          <li <?php
          if($page_name=="problemset.php" || $page_name=="problem.php") {
            echo "class='am-active'";
          }
          ?>><a href="./problemset.php"><?php echo $MSG_PROBLEMSET ?></a></li>
          <!-- ProblemSet部分 end -->
          <?php } ?>


          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="faqs.php"){
			  echo "class='am-active'";}
	      ?>><a href="./faqs.php"><?php echo $MSG_FAQ ?></a></li>    
        </ul>

        <!-- 用户部分 start -->
        <?php
        if (!isset($_SESSION['user_id'])){
			if(!isset($OJ_REGISTER)|| $OJ_REGISTER){
                $reg_link = "<li><a href='./registerpage.php'><span class='am-icon-pencil'></span> $MSG_REGISTER </a></li>";
			}
echo <<<BOT
          <div class="am-topbar-right">
            <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;"> $MSG_LOGIN <span class="am-icon-caret-down"></span></a>
                  <ul class="am-dropdown-content">
                    <li><a href="./loginpage.php"><span class="am-icon-user"></span> $MSG_LOGIN </a></li>	
                    $reg_link
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
                <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'><span class='am-icon-user'></span> {$_SESSION['user_id']} <span class='am-icon-caret-down'></span></a>
                  <ul class="am-dropdown-content">
BOT;
          if (!isset($_SESSION['contest_id'])) {
echo <<<BOT
                    <li><a href="./modifypage.php"><span class="am-icon-eraser"></span> $MSG_MODIFY_USER</a></li>
                    <li><a href="./userinfo.php?user={$_SESSION['user_id']}"><span class="am-icon-info-circle"></span>  $MSG_USERINFO </a></li>
                    <!-- <li><a href="./mail.php"><span class="am-icon-comments"></span> Mail</a></li> -->
                    <li><a href="./status.php?user_id=$user_session"><span class="am-icon-keyboard-o"></span> $MSG_MY_SUBMISSIONS </a></li>            
                    <li><a href="./contest.php?my"><span class="am-icon-leaf"></span> $MSG_MY_CONTESTS </a></li>               
BOT;
          }
          if ($show_tag) echo "<li><a href='./changeTag.php'><span class='am-icon-toggle-on'></span> $MSG_HIDETAG</a></li>";
          else echo "<li><a href='./changeTag.php'><span class='am-icon-toggle-off'></span> $MSG_SHOWTAG</a></li>";
          echo "<li><a href='./logout.php'><span class='am-icon-reply'></span> $MSG_LOGOUT</a></li>";

          if(HAS_PRI('enter_admin_page')){
            echo <<<BOT
              <li><a href="./admin/index.php"><span class="am-icon-cog"></span> $MSG_ADMIN </a></li>
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
