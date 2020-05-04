
<?php

if(isset($_POST['pid'])){
  $pid=$_POST['pid'];
}
else{
  exit(0);
}
require_once("./include/db_info.inc.php");
require_once('./include/setlang.php');
$title=$MSG_SolutionVideo." Of Problem $pid";
require_once("template/".$OJ_TEMPLATE."/header.php");

$can_see_video=false;
if(isset($_SESSION['user_id'])){
  $uid=$_SESSION['user_id'];
  $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$pid'";
  $res=$mysqli->query($sql);
  if($res->num_rows>$VIDEO_SUBMIT_TIME) $can_see_video=true;
}
if(!$can_see_video && !HAS_PRI("watch_solution_video")){
  exit(0);
}


//save watch log
$sql="INSERT INTO solution_video_watch_log (video_id,user_id,`time`) VALUES($pid,'$uid',NOW())";
//echo "<pre>$sql</pre>";
$mysqli->query($sql);
?>
<div class="am-container">
  <h1 style="padding-top: 20px;"><?php echo $title ?></h1><hr>
  <div>
    <video src="./upload/video/<?php echo md5($pid)."pfb" ?>.mp4" width=100% controls=1></video>
  </div>
</div>

<?php require_once "template/".$OJ_TEMPLATE."/footer.php"; ?>