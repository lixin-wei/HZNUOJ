
<?php

if(isset($_POST['pid'])){
    $pid=$_POST['pid'];
}
else{
    exit(0);
}
$title="Solution Video Of Problem $pid";
require_once "template/hznu/header.php";
$video_submit_time=10;
$can_see_video=false;
if(isset($_SESSION['user_id'])){
  $uid=$_SESSION['user_id'];
  $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$pid' AND result='4'";
  $res=$mysqli->query($sql);
  if($res->num_rows) $can_see_video=true;

  $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$pid'";
  $res=$mysqli->query($sql);
  if($res->num_rows>$video_submit_time) $can_see_video=true;
}
if(!$can_see_video){
  exit(0);
}

?>
<div class="am-container">
  <h1 style="padding-top: 20px;">Solution Video Of Problem <?php echo $pid ?></h1><hr>
  <?php if ($can_see_video): ?>
  <div>
    <video src="/OJ/upload/video/<?php echo $pid ?>.mp4" width=100% controls=1></video>
  </div>
  <?php endif ?>
</div>

<?php require_once "template/hznu/footer.php"; ?>