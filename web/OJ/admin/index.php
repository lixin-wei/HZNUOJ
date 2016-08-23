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
      <div class="col-sm-2">
        <center>
          <h3>DASHBOARD</h3>
        </center>
      </div>
      <div class="col-sm-10">
      </div>
    </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-2 sidebar">
        <center>
            <ul class='nav nav-pills nav-stacked'>
            <?php
              echo "<li class='active'><a href='watch.php' target='main'>See OJ</a></li>";
              if(HAS_PRI("edit_hznu_problem")) echo "<li><a href='problem_add_page.php?type=C' target='main'>Add C Problem</a></li>";
              if(HAS_PRI("edit_hznu_problem")) echo "<li><a href='problem_list.php?type=C' target='main'>C ProblemList</a></li>";
              if(HAS_PRI("rejudge")) echo "<li><a href='rejudge.php' target='main'>Rejudge</a></li>";
              if(HAS_PRI("edit_news")) echo "<li><a href='news_add_page.php' target='main'>Add News</a></li>";
              if(HAS_PRI("edit_news")) echo "<li><a href='news_list.php' target='main'>News List</a></li>";
              //*************slide相关功能暂时被弃用
              //if(HAS_PRI("'AddSlide' ")) echo "<li><a href='slide_add_page.php' target='main'>'AddSlide' </a></li>";
              //if(HAS_PRI("'slideList' ")) echo "<li><a href='slide_list.php' target='main'>'slideList' </a></li>";
              //*************
              if(HAS_PRI("edit_c_problem")) echo "<li><a href='problem_add_page.php' target='main'>Add Problem</a></li>";
              if(HAS_PRI("edit_c_problem")) echo "<li><a href='problem_list.php' target='main'>Problem List</a></li>";
              if(HAS_PRI("edit_contest")) echo "<li><a href='contest_add.php' target='main'>Add Contest</a></li>";
              if(HAS_PRI("edit_contest")) echo "<li><a href='contest_list.php' target='main'>Contest List</a></li>";
              if(HAS_PRI("generate_team")) echo "<li><a href='team_generate.php' target='main'>Team Generator</a></li>";
              //if(HAS_PRI("$MSG_SETMESSAGE")) echo "<li><a href='setmsg.php' target='main'>$MSG_SETMESSAGE</a></li>"; -->
              if(HAS_PRI("edit_user_profile")) echo "<li><a href='change_user_id.php' target='main'>Change User ID</a></li>";
              if(HAS_PRI("edit_user_profile")) echo "<li><a href='changepass.php' target='main'>Change PassWD</a></li>";
              if(HAS_PRI("edit_privilege")) echo "<li><a href='privilege_add.php' target='main'>Add Privilege</a></li>";
              if(HAS_PRI("edit_privilege")) echo "<li><a href='privilege_list.php' target='main'>Privilege List</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='source_give.php' target='main'>Give Source</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='contestrank-solutions.php?cid=1000' target='main'>Export Source</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_export.php' target='main'>Export Problem</a></li>";
              if(HAS_PRI("inner_function")) echo "<li><a href='problem_import.php' target='main'>Import Problem</a></li>";
              if(HAS_PRI("inner_function")) echo "<a href='problem_copy.php' target='main' title='Create your own data'><font color='eeeeee'>CopyProblem</font></a> <br>";
              if(HAS_PRI("inner_function")) echo "<a href='problem_changeid.php' target='main' title='Danger,Use it on your own risk'><font color='eeeeee'>ReOrderProblem</font></a>";
            ?>
            </ul>
        </center>
      </div>

      <div class="col-sm-10 main">
        <iframe name="main" src="watch.php" width="100%" height="100%" frameborder="0"></iframe>
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