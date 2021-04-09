<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.28
  **/
?>

<?php if(!session_id()) @session_start();?>
<?php
if(preg_match("/\/admin\/quixplorer\//i", $_SERVER['SCRIPT_NAME'])) {
  $baseDir="../..";//在admin/quixplorer目录下
  $urlbaseDir="..";//在admin/quixplorer目录下
} else {
  $baseDir="..";
  $urlbaseDir=".";
}
require_once("$baseDir/include/db_info.inc.php");
?>
<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/jpg" href="<?php echo $baseDir ?>/<?php echo $GLOBALS["ICON_PATH"] ?>">
    <link rel="stylesheet" href="<?php echo $baseDir ?>/plugins/AmazeUI/css/amazeui.min.css"/>
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="<?php echo $baseDir ?>/plugins/bootstrap/css/bootstrap.min.css">
  <link href="<?php echo $baseDir ?>/plugins/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
</head>
<body>
<?php 
  require_once("$baseDir/include/setlang.php");
  $html_title = $MSG_DASHBOARD."--";
  global $mysqli;
  if (!HAS_PRI('enter_admin_page')) {
    echo "<h1><a href='$baseDir/loginpage.php'>Premission Denied! Please Log in!</a></h1>";
    exit(0);
  }
  $can_see_problem=false;
  $can_see_all_problems=true;
  $res=$mysqli->query("SELECT * FROM problemset");
  while($row=$res->fetch_array()){
    if(HAS_PRI("edit_".$row['set_name']."_problem")){
      $can_see_problem=true;
    }
    else{
      $can_see_all_problems=false;
    }
  }
?>
  <style type="text/css">
    body{
      padding-top: 55px;
    }
  </style>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class='navbar-brand' style="margin-left: 150px;" href='<?php echo $urlbaseDir ?>/index.php'><?php echo $MSG_DASHBOARD ?></a>
      </div>
    <div class="am-container" style="max-width: 2000px;">
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
        <?php
          echo "<li><a href='$baseDir'>$MSG_SEEOJ</a></li>";
          $html_li="";
          if($can_see_problem){
            $html_li .= "<li><a href='$urlbaseDir/problem_list.php'>$MSG_PROBLEM$MSG_LIST</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/problem_edit.php?new_problem'>$MSG_ADD$MSG_PROBLEM</a></li>";            
          }
          if(HAS_PRI("edit_contest")) $html_li.="<li><a href='$urlbaseDir/course.php'>$MSG_CourseSet</a></li>";
          if(HAS_PRI("inner_function")) {
            $html_li.="<li><a href='$urlbaseDir/problem_set.php'>$MSG_PROBLEMSET$MSG_LIST</a></li>";
            $html_li.="<li><a href='$urlbaseDir/updateScores.php'>$MSG_UpdateScores</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_PROBLEMSET<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          if(HAS_PRI("rejudge")) echo "<li><a href='$urlbaseDir/rejudge.php'>$MSG_REJUDGE</a></li>";
          //*************slide相关功能暂时被弃用
          //if(HAS_PRI("'AddSlide' ")) echo "<li><a href='$urlbaseDir/slide_add_page.php'>'AddSlide' </a></li>";
          //if(HAS_PRI("'slideList' ")) echo "<li><a href='$urlbaseDir/slide_list.php'>'slideList' </a></li>";
          //*************

          $html_li="";
          if(HAS_PRI("edit_news")){
            $html_li .= "<li><a href='$urlbaseDir/news_add_page.php'>$MSG_ADD$MSG_NEWS</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/news_list.php'>$MSG_NEWS$MSG_LIST</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/edit_faq.php'>$MSG_EDIT$MSG_FAQ </a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_NEWS<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li="";
          if(HAS_PRI("edit_contest")){
            $html_li .= "<li><a href='$urlbaseDir/contest_list.php'>$MSG_CONTEST$MSG_LIST</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/contest_add.php'>$MSG_ADD$MSG_CONTEST</a></li>";
          }
          if(HAS_PRI("generate_team")){
            $html_li .= "<li><a href='$urlbaseDir/team_generate.php'>$MSG_TEAMGENERATOR</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/team_import.php'>$MSG_TEAM$MSG_IMPORT</a></li>";
          }
            
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_CONTEST<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li = "<li><a href='$urlbaseDir/user_list.php'>$MSG_USER$MSG_LIST</a></li>";
          $html_li .= "<li><a href='$urlbaseDir/class_list.php'>$MSG_Class$MSG_LIST</a></li>";
          if(HAS_PRI("edit_user_profile")){
            $html_li .= "<li><a href='$urlbaseDir/change_class.php'>$MSG_ChangeClass</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/change_user_id.php'>$MSG_SET_USER_ID</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/changepass.php'>$MSG_SETPASSWORD</a></li>";
          }
          if(HAS_PRI("generate_team")){
              $html_li .= "<li><a href='$urlbaseDir/reg_code.php'>$MSG_REG_CODE</a></li>";
              $html_li .= "<li><a href='$urlbaseDir/user_import.php'>$MSG_USER$MSG_IMPORT</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_USER<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }
          $html_li="";
          $html_li .= "<li><a href='$urlbaseDir/privilege_list.php'>$MSG_PRIVILEGE$MSG_LIST</a></li>";
          if(HAS_PRI("edit_privilege_group")){
            $html_li .= "<li><a href='$urlbaseDir/privilege_add.php'>$MSG_ADD$MSG_PRIVILEGE</a></li>";
          }
          $html_li .= "<li><a href='$urlbaseDir/privilege_distribution.php'>$MSG_Distribution</a></li>";
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_PRIVILEGE<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li="";
          if(HAS_PRI("inner_function")){
            $html_li .= "<li><a href='$urlbaseDir/problem_set.php'>$MSG_PROBLEMSET$MSG_LIST</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/updateScores.php'>$MSG_UpdateScores</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/source_give.php'>$MSG_GIVESOURCE</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/problem_export.php'>$MSG_EXPORT$MSG_PROBLEM</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/problem_import.php'>$MSG_IMPORT$MSG_PROBLEM</a></li>";
            $html_li .= "<li><a href='$urlbaseDir/problem_changeid.php' title='Danger,Use it on your own risk'>$MSG_ReOrderProblem</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">$MSG_inner_function<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }
        ?>
        </ul> <!-- nav navbar-nav -->
      </div><!-- collapse navbar-collapse -->
    </div>
  </nav>
  <div class="am-container" style="margin-left: 150px;margin-bottom: 30px;max-width: 1300px;width: 100%;">
