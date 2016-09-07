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
  <!--
  <link rel=stylesheet href='../include/hoj.css' type='text/css'>
  -->
  <script src="../template/bs3/jquery.min.js"></script>
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>

<script>
  $("document").ready(function (){
    $("form").append("<div id='csrf' />");
    $("#csrf").load("../csrf.php");
  });
</script>
<?php 
  require_once("../include/db_info.inc.php");
  if (!HAS_PRI('enter_admin_page')) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
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
        ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Problems<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php 
              if(HAS_PRI("edit_c_problem")) echo "<li><a href='problem_add_page.php?type=C'>Add C Problem</a></li>";
              if(HAS_PRI("edit_c_problem")) echo "<li><a href='problem_list.php?type=C'>C ProblemList</a></li>";
              if(HAS_PRI("edit_hznu_problem")) echo "<li><a href='problem_add_page.php'>Add Problem</a></li>";
              if(HAS_PRI("edit_hznu_problem")) echo "<li><a href='problem_list.php'>Problem List</a></li>";
            ?>
            </ul>
          </li>
        <?php
          if(HAS_PRI("rejudge")) echo "<li><a href='rejudge.php'>Rejudge</a></li>";
          //*************slide相关功能暂时被弃用
          //if(HAS_PRI("'AddSlide' ")) echo "<li><a href='slide_add_page.php'>'AddSlide' </a></li>";
          //if(HAS_PRI("'slideList' ")) echo "<li><a href='slide_list.php'>'slideList' </a></li>";
          //*************
        ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php
              if(HAS_PRI("edit_news")) echo "<li><a href='news_add_page.php'>Add News</a></li>";
              if(HAS_PRI("edit_news")) echo "<li><a href='news_list.php'>News List</a></li>";
            ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contest<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php
              if(HAS_PRI("edit_contest")) echo "<li><a href='contest_add.php'>Add Contest</a></li>";
              if(HAS_PRI("edit_contest")) echo "<li><a href='contest_list.php'>Contest List</a></li>";
              if(HAS_PRI("generate_team")) echo "<li><a href='team_generate.php'>Team Generator</a></li>";
            ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php
              if(HAS_PRI("edit_user_profile")) echo "<li><a href='change_user_id.php'>Change User ID</a></li>";
              if(HAS_PRI("edit_user_profile")) echo "<li><a href='changepass.php'>Change PassWD</a></li>";
            ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Privilege<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php
              if(HAS_PRI("edit_privilege")) echo "<li><a href='privilege_add.php'>Add Privilege</a></li>";
              if(HAS_PRI("edit_privilege")) echo "<li><a href='privilege_list.php'>Privilege List</a></li>";
            ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Inner Function<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php
              if(HAS_PRI("inner_function")) echo "<li><a href='source_give.php'>Give Source</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='contestrank-solutions.php?cid=1000'>Export Source</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_export.php'>Export Problem</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_import.php'>Import Problem</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_copy.php' title='Create your own data'>CopyProblem</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_changeid.php' title='Danger,Use it on your own risk'>ReOrderProblem</a></li>";
            ?>
            </ul>
          </li>
        </ul> <!-- nav navbar-nav -->
      </div><!-- collapse navbar-collapse -->
    </div>
  </nav>
  <div class="container-fluid">