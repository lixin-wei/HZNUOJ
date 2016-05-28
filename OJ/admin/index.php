<?php
require_once("admin-header.php");
if(isset($OJ_LANG)) require_once("../lang/$OJ_LANG.php");
?>
<html>
<head>
  <title>JudgeOnline Administration</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="row">
      <div class="col-md-2">
        <center>
          <h3>DASHBOARD</h3>
        </center>
      </div>
      <div class="col-md-10">
      </div>
    </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 sidebar">
        <center>
          <div class="well sidebar-nav">
            <ul class="nav nav-pills nav-stacked">
              <li><a href="watch.php" target="main"><b><?php echo $MSG_SEEOJ?></b></a></li>
        <?php 
          if ($GE_TA) {
        ?>
            <li><a href="problem_add_page.php?type=C" target="main"><b><?php echo "AddCProblem" ?></b></a></li>
            <li><a href="problem_list.php?type=C" target="main"><b><?php echo "CProblemList" ?></b></a></li>
        <?php 
          }
          if ($GE_T) {
        ?>
            <li><a href="problem_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_PROBLEM?></b></a></li>
            <li><a href="problem_list.php" target="main"><b><?php echo $MSG_PROBLEM.$MSG_LIST?></b></a></li>
            <li><a href="contest_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_CONTEST?></b></a></li>
            <li><a href="contest_list.php" target="main"><b><?php echo $MSG_CONTEST.$MSG_LIST?></b></a></li>
            <li><a href="news_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_NEWS?></b></a></li>
            <li><a href="news_list.php" target="main"><b><?php echo $MSG_NEWS.$MSG_LIST?></b></a>
<!-- slide相关功能暂时被弃用
            <li><a href="slide_add_page.php" target="main"><b><?php echo "AddSlide" ?></b></a>
            <li><a href="slide_list.php" target="main"><b><?php echo "slideList" ?></b></a>
-->
            
            <li><a href="rejudge.php" target="main"><b><?php echo $MSG_REJUDGE?></b></a></li>
        <?php 
          }
          if (isset($_SESSION['administrator'])){
        ?>
            <li><a href="team_generate.php" target="main"><b><?php echo $MSG_TEAMGENERATOR?></b></a></li>
            <!-- <li><a href="setmsg.php" target="main"><b><?php echo $MSG_SETMESSAGE?></b></a></li> -->
            <li><a href="change_user_id.php" target="main"><b><?php echo "ChangeUserID" ?></b></a></li>
            <li><a href="changepass.php" target="main"><b><?php echo $MSG_SETPASSWORD?></b></a></li>
            <li><a href="privilege_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_PRIVILEGE?></b></a></li>
            <li><a href="privilege_list.php" target="main"><b><?php echo $MSG_PRIVILEGE.$MSG_LIST?></b></a></li>
            <li><a href="source_give.php" target="main"><b><?php echo $MSG_GIVESOURCE?></b></a></li>
            <li><a href="contestrank-solutions.php?cid=1000" target="main"><b><?php echo "ExportSource"?></b></a></li>
            <li><a href="problem_export.php" target="main"><b><?php echo $MSG_EXPORT.$MSG_PROBLEM?></b></a></li>
            <li><a href="problem_import.php" target="main"><b><?php echo $MSG_IMPORT.$MSG_PROBLEM?></b></a></li>

        <?php 
          }
        ?>
        <?php 
          if (isset($_SESSION['administrator'])) {
        ?>
            <a href="problem_copy.php" target="main" title="Create your own data"><font color="eeeeee">CopyProblem</font></a> <br>
            <a href="problem_changeid.php" target="main" title="Danger,Use it on your own risk"><font color="eeeeee">ReOrderProblem</font></a>
        <?php 
          }
        ?>
            </ul>
          </div>
        </center>
      </div>

      <div class="col-md-10 main">
        <iframe name="main" src="watch.php" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</body>
</html>
