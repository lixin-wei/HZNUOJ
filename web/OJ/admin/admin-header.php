<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.28
  **/
?>

<?php @session_start();?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">

</head>
<body>


<?php 
  require_once("../include/db_info.inc.php");
  if (!HAS_PRI('enter_admin_page')) {
    echo "<h1><a href='../loginpage.php'>Premission Denied! Please Log in!</a></h1>";
    exit(0);
  }
  $can_see_problem=false;
  $can_see_all_problems=true;
  $res=mysql_query("SELECT * FROM problemset");
  while($row=mysql_fetch_array($res)){
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
      padding-top: 70px;
    }
  </style>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class='navbar-brand' href='index.php'>DASHBOARD</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
        <?php
          echo "<li><a href='index.php'>See OJ</a></li>";
          $html_li="";
          if($can_see_problem){
            $html_li .= "<li><a href='problem_add_page.php'>Add Problem</a></li>";
            $html_li .= "<li><a href='problem_list.php'>Problem List</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Problems<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          if(HAS_PRI("rejudge")) echo "<li><a href='rejudge.php'>Rejudge</a></li>";
          //*************slide相关功能暂时被弃用
          //if(HAS_PRI("'AddSlide' ")) echo "<li><a href='slide_add_page.php'>'AddSlide' </a></li>";
          //if(HAS_PRI("'slideList' ")) echo "<li><a href='slide_list.php'>'slideList' </a></li>";
          //*************

          $html_li="";
          if(HAS_PRI("edit_news")){
            $html_li .= "<li><a href='news_add_page.php'>Add News</a></li>";
            $html_li .= "<li><a href='news_list.php'>News List</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li="";
          if(HAS_PRI("edit_contest")){
            $html_li .= "<li><a href='contest_add.php'>Add Contest</a></li>";
            $html_li .= "<li><a href='contest_list.php'>Contest List</a></li>";
          }
          if(HAS_PRI("generate_team"))
            $html_li .= "<li><a href='team_generate.php'>Team Generator</a></li>";
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contest<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li="";
          if(HAS_PRI("edit_user_profile")){
            $html_li .= "<li><a href='change_user_id.php'>Change User ID</a></li>";
            $html_li .= "<li><a href='changepass.php'>Change PassWD</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }
          $html_li="";
          $html_li .= "<li><a href='privilege_list.php'>Privilege List</a></li>";
          if(HAS_PRI("edit_privilege_group")){
            $html_li .= "<li><a href='privilege_add.php'>Add Privilege</a></li>";
          }
          $html_li .= "<li><a href='privilege_distribution.php'>Privilege Distribution</a></li>";
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Privilege<span class="caret"></span></a>
              <ul class="dropdown-menu">
                $html_li
              </ul>
            </li>
sss;
          }

          $html_li="";
          if(HAS_PRI("inner_function")){
            $html_li .= "<li><a href='source_give.php'>Give Source</a></li>";
            $html_li .= "<li><a href='contestrank-solutions.php?cid=1000'>Export Source</a></li>";
            $html_li .= "<li><a href='problem_export.php'>Export Problem</a></li>";
            $html_li .= "<li><a href='problem_import.php'>Import Problem</a></li>";
            $html_li .= "<li><a href='problem_copy.php' title='Create your own data'>CopyProblem</a></li>";
            $html_li .= "<li><a href='problem_changeid.php' title='Danger,Use it on your own risk'>ReOrderProblem</a></li>";
          }
          if($html_li!=""){
            echo<<<sss
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Inner Function<span class="caret"></span></a>
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
  <div class="container-fluid">