<?php
require_once("admin-header.php");
require_once "../include/my_func.inc.php";
?>
  <title>JudgeOnline <?php echo $MSG_DASHBOARD ?></title>
  <div class="am-container">
    <div class="row">
    	<h1>Welcome To Administration Page</h1><hr>
    	Your group is: <b><?php echo get_group($_SESSION['user_id']); ?></b><br>
    	<a href="./privilege_distribution.php">See what you can do here.</a>
      <section class="am-panel am-panel-default" style="width:400px;margin-top:20px;">
          <header class="am-panel-hd">
            <h3 class="am-panel-title"><b><?php echo $MSG_FastTrack?></b></h3>
           </header>
          <main class="am-panel-bd">
            <table class="am-table am-text-middle">              
              <?php 
                if($can_see_problem){
                  echo "<tr>\n<td><a href='./problem_edit.php?new_problem'>$MSG_ADD$MSG_PROBLEM</a></td>\n";
                  echo "<td><a href='./problem_list.php'>$MSG_PROBLEM$MSG_LIST</a></td>\n</tr>\n";
                  }
                if(HAS_PRI("edit_contest")){
                    echo "<tr>\n<td><a href='./contest_add.php'>$MSG_ADD$MSG_CONTEST</a></td>\n";
                    echo "<td><a href='./contest_list.php'>$MSG_CONTEST$MSG_LIST</a></td>\n</tr>\n";
                  }
                echo "<tr>\n<td><a href='./user_list.php'>$MSG_USER$MSG_LIST</a></td>\n";
                if(HAS_PRI("edit_user_profile")){
                  echo "<td><a href='./changepass.php'>$MSG_SETPASSWORD</a></td>\n</tr>\n";
                } else {
                  echo "<td>&nbsp;</td>\n</tr>\n";
                }
                if(HAS_PRI("inner_function")){
                  echo "<tr>\n<td><a href='./problem_import.php'>$MSG_IMPORT$MSG_PROBLEM</a></td>\n";
                  echo "<td><a href='./problem_export.php'>$MSG_EXPORT$MSG_PROBLEM</a></td>\n</tr>\n";
                }
              ?>
            </table>
            </main>
       </section>
    </div>
  </div>
<?php 
  require_once("admin-footer.php")
?>
