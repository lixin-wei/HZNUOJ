<?php
  /**
   * This file is created
   * by yybird
   * @2016.02.29
   * last modified
   * by yybird
   * @2016.02.29
  **/
?>

<?php


  /*
   * 该文件会将oj和vj中所有与$origin用户相关的信息替换为$dest
   * 用处是更换user_id
   */


  require_once('../include/db_info.inc.php');
  require_once("admin-header.php");
  require_once("../include/my_func.inc.php");
  if (!HAS_PRI("edit_user_profile")) {
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  if (isset($_POST['origin']) && isset($_POST['dest'])) {
    require_once("../include/check_post_key.php");
    $origin = $_POST['origin'];
    $dest = $_POST['dest'];
    $exist = 0;
    if(get_order(get_group($origin))<=get_order(get_group(""))){
      $view_error="You can't edit this user!";
      require_once("error.php");
      exit(1);
    }	
    // OJ中判断老用户是否存在 start
	$sql = "SELECT user_id FROM users WHERE user_id='$origin'";
    $result = $mysqli->query($sql);
	if($result->num_rows == 0){
	   echo "老用户名".$origin."不存在！";
	} else {
		$sql = "SELECT user_id FROM users WHERE user_id='$dest'";
		$result = $mysqli->query($sql);
		$exist += $result->num_rows;
	
		if(isset($VJ_OPEN) && $VJ_OPEN==true){// 连接转入vjudge start
			$mysqli->close();
			$mysqli = new mysqli($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,"vhoj");
			if ($mysqli->connect_errno) die('Could not connect: ' . $mysqli->error);
			$mysqli->query("set names utf8");
		
			// VJ中判断用户是否存在
			$sql = "SELECT C_USERNAME FROM t_user WHERE C_USERNAME='$dest'";
			$result = $mysqli->query($sql);
			$exist += $result->num_rows;
			if ($exist != 0) {
			  echo "新用户名".$dest."已存在！";
			} else {
			  $sql = "UPDATE t_user SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
			  $mysqli->query($sql);
			  $sql = "UPDATE t_submission SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
			  $mysqli->query($sql);
			
			  // 连接转回OJ	
			  $mysqli->close();
			  $mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASS,"jol");
			  if ($mysqli->connect_errno) die('Could not connect: ' . $mysqli->error);
			  $mysqli->query("set names utf8");
			}
		} //连接转入vjudg end
		// 判断新用户是否存在 start
		if ($exist != 0) {
		  echo "新用户名".$dest."已存在！";
		} else {
		  $sql = "UPDATE loginlog SET user_id='$dest' WHERE user_id='$origin' and password not like '%team account%'";
		  $mysqli->query($sql);
		  $sql = "UPDATE mail SET to_user='$dest' WHERE to_user='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE mail SET from_user='$dest' WHERE from_user='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE message SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE news SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE privilege SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE reply SET author_id='$dest' WHERE author_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE solution SET user_id='$dest' WHERE user_id='$origin'";
		  $sql .= " AND solution_id not in (SELECT solution.solution_id FROM solution, team WHERE solution.user_id='$origin' AND solution.contest_id = team.contest_id AND solution.user_id = team.user_id ";
		  $mysqli->query($sql);
		  $sql = "UPDATE topic SET author_id='$dest' WHERE author_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE users SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE tag SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  $sql = "UPDATE contest SET user_id='$dest' WHERE user_id='$origin'";
		  $mysqli->query($sql);
		  //$sql = "UPDATE contest_discuss SET user_id='$dest' WHERE user_id='$origin'";
		  //$mysqli->query($sql);
		  //$sql = "UPDATE printer_code SET user_id='$dest' WHERE user_id='$origin'";
		  //$mysqli->query($sql);
		  //$sql = "UPDATE hit_log SET user_id='$dest' WHERE user_id='$origin' and password like '%team account%'";
		  //$mysqli->query($sql);
		  //$sql = "UPDATE solution_video_watch_log SET user_id='$dest' WHERE user_id='$origin'";
		  //$mysqli->query($sql);
		  echo "done.";
		} // 判断新用户是否存在 end
	}// OJ中判断老用户是否存在 end
  }
?>
<title><?php echo $html_title.$MSG_SET_USER_ID?></title>
  <h1><?php echo $MSG_SET_USER_ID?></h1>
<hr>
<form class="form-inline" method='post' action='change_user_id.php'>
  <p><?php echo $MSG_Old.$MSG_USER_ID?> : <input class="form-control" type='text' name='origin'></p>
  <p><?php echo $MSG_New.$MSG_USER_ID?> : <input class="form-control" type='text' name='dest'></p>
  <?php require_once("../include/set_post_key.php");?>
  <input class="btn btn-default" type='submit' value='<?php echo $MSG_SUBMIT ?>'>
</form>
<?php 
  require_once("admin-footer.php")
?>