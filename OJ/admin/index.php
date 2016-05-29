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
            <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="watch.php" target="main"><?php echo $MSG_SEEOJ?></a></li>
        <?php 
          if ($GE_TA) {
        ?>
            <li><a href="problem_add_page.php?type=C" target="main"><?php echo "AddCProblem" ?></a></li>
            <li><a href="problem_list.php?type=C" target="main"><?php echo "CProblemList" ?></a></li>
        <?php 
          }
          if ($GE_T) {
        ?>
            <li><a href="problem_add_page.php" target="main"><?php echo $MSG_ADD.$MSG_PROBLEM?></a></li>
            <li><a href="problem_list.php" target="main"><?php echo $MSG_PROBLEM.$MSG_LIST?></a></li>
            <li><a href="contest_add.php" target="main"><?php echo $MSG_ADD.$MSG_CONTEST?></a></li>
            <li><a href="contest_list.php" target="main"><?php echo $MSG_CONTEST.$MSG_LIST?></a></li>
            <li><a href="news_add_page.php" target="main"><?php echo $MSG_ADD.$MSG_NEWS?></a></li>
            <li><a href="news_list.php" target="main"><?php echo $MSG_NEWS.$MSG_LIST?></a>
<!-- slide相关功能暂时被弃用
            <li><a href="slide_add_page.php" target="main"><?php echo "AddSlide" ?></a></li>
            <li><a href="slide_list.php" target="main"><?php echo "slideList" ?></a></li>
-->
            
            <li><a href="rejudge.php" target="main"><?php echo $MSG_REJUDGE?></a></li>
        <?php 
          }
          if (isset($_SESSION['administrator'])){
        ?>
            <li><a href="team_generate.php" target="main"><?php echo $MSG_TEAMGENERATOR?></a></li>
            <!-- <li><a href="setmsg.php" target="main"><?php echo $MSG_SETMESSAGE?></a></li> -->
            <li><a href="change_user_id.php" target="main"><?php echo "ChangeUserID" ?></a></li>
            <li><a href="changepass.php" target="main"><?php echo $MSG_SETPASSWORD?></a></li>
            <li><a href="privilege_add.php" target="main"><?php echo $MSG_ADD.$MSG_PRIVILEGE?></a></li>
            <li><a href="privilege_list.php" target="main"><?php echo $MSG_PRIVILEGE.$MSG_LIST?></a></li>
            <li><a href="source_give.php" target="main"><?php echo $MSG_GIVESOURCE?></a></li>
            <li><a href="contestrank-solutions.php?cid=1000" target="main"><?php echo "ExportSource"?></a></li>
            <li><a href="problem_export.php" target="main"><?php echo $MSG_EXPORT.$MSG_PROBLEM?></a></li>
            <li><a href="problem_import.php" target="main"><?php echo $MSG_IMPORT.$MSG_PROBLEM?></a></li>

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
        </center>
      </div>

      <div class="col-md-10 main">
        <iframe name="main" src="watch.php" width="100%" height="90%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</body>
</html>
<script>
  $("li").click(function(){
    $("li").each(function(){
      $(this).removeClass("active");
    });
    $(this).addClass("active");
  });
</script>