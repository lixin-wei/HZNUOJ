<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.28
  **/
?>

<?php require_once("admin-header.php");?>
<?php
  require_once("../include/db_info.inc.php");
  require_once("../include/const.inc.php");
  if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
  }
  $description="";
  if (isset($_POST['syear'])) {
    require_once("../include/check_post_key.php");
    $starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
    $endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";

    $title=$_POST['title'];
    $private=$_POST['private'];
    $password=$_POST['password'];
    $description=$_POST['description'];
    $user_limit = $_POST['user_limit'];
    $defunct_TA = $_POST['defunct_TA'];
    $open_source = $_POST['open_source'];
    $practice = $mysqli->real_escape_string($_POST['practice']);

    if (get_magic_quotes_gpc ()){
      $title = stripslashes ($title);
      $private = stripslashes ($private);
      $password = stripslashes ($password);
      $description = stripslashes ($description);
    }

    $title=$mysqli->real_escape_string($title);
    $private=$mysqli->real_escape_string($private);
    $password=$mysqli->real_escape_string($password);
    $description=$mysqli->real_escape_string($description);

    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t) {
      $langmask+=1<<$t;
    }
    $sql="INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`, user_limit, defunct_TA, open_source, practice)
          VALUES('$title','$starttime','$endtime','$private',$langmask,'$description','$password', '$user_limit', '$defunct_TA', '$open_source', '$practice')";
  echo $sql;
  $mysqli->query($sql) or die($mysqli->error);
  $cid=$mysqli->insert_id;
  echo "Add Contest ".$cid;
  $sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
  $plist=trim($_POST['cproblem']);
  $pieces = explode(",",$plist );
  if (count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) 
      VALUES ('$cid','$pieces[0]',0)";
    for ($i=1;$i<count($pieces);$i++){
      $sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
    }
    //echo $sql_1;
    $mysqli->query($sql_1) or die($mysqli->error);
    // $sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
    // $mysqli->query($sql) or die($mysqli->error);
  }
  $sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
  $mysqli->query($sql);
/*  $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','m$cid')";
  $mysqli->query($sql);*/
  $_SESSION["m$cid"]=true;
  $pieces = explode("\n", trim($_POST['ulist']));
  if (count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) 
      VALUES ('".trim($pieces[0])."','c$cid')";
    for ($i=1;$i<count($pieces);$i++)
      $sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
    //echo $sql_1;
    $mysqli->query($sql_1) or die($mysqli->error);
  }


  $ex_users=explode("\n",trim($_POST['ex_ulist']));
  foreach ($ex_users as $uid) {
    $uid=trim($uid);
    $sql="INSERT INTO contest_excluded_user (contest_id,user_id) VALUES($cid,'$uid')";
    $mysqli->query($sql);
  }

  
  echo "<script>window.location.href=\"contest_list.php\";</script>";
}
else{
  
   if(isset($_GET['cid'])){
       $cid=intval($_GET['cid']);
       $sql="select * from contest WHERE `contest_id`='$cid'";
       $result=$mysqli->query($sql);
       $row=$result->fetch_object();
       $title=$row->title;
       $result->free();
      $plist="";
      $sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
      $result=$mysqli->query($sql) or die($mysqli->error);
      for ($i=$result->num_rows;$i>0;$i--){
        $row=$result->fetch_row();
        $plist=$plist.$row[0];
        if ($i>1) $plist=$plist.',';
      }
      $result->free();
   }
else if(isset($_POST['problem2contest'])){
     $plist="";
     //echo $_POST['pid'];
     sort($_POST['pid']);
     foreach($_POST['pid'] as $i){        
      if ($plist) 
        $plist.=','.$i;
      else
        $plist=$i;
     }
}else if(isset($_GET['spid'])){
  require_once("../include/check_get_key.php");
       $spid=intval($_GET['spid']);
     
      $plist="";
      $sql="SELECT `problem_id` FROM `problem` WHERE `problem_id`>=$spid ";
      $result=$mysqli->query($sql) or die($mysqli->error);
      for ($i=$result->num_rows;$i>0;$i--){
        $row=$result->fetch_row();
        $plist=$plist.$row[0];
        if ($i>1) $plist=$plist.',';
      }
      $result->free();
}  
  include_once("kindeditor.php") ;
?>
  <title>Add a Contest</title>
  <h1>Add a Contest</h1><hr>
  <form method=POST >
  <p align=left>Title:<input class=input-xxlarge  type=text name=title size=71 value="<?php echo isset($title)?$title:""?>"></p>
  <p align=left>Start Time:<br>&nbsp;&nbsp;&nbsp;
  Year:<input  class=input-mini type=text name=syear value=<?php echo date('Y')?> size=4 >
  Month:<input class=input-mini  type=text name=smonth value=<?php echo date('m')?> size=2 >
  Day:<input class=input-mini type=text name=sday size=2 value=<?php echo date('d')?> >&nbsp;
  Hour:<input class=input-mini    type=text name=shour size=2 value=<?php echo date('H')?>>&nbsp;
  Minute:<input class=input-mini    type=text name=sminute value=00 size=2 ></p>
  <p align=left>End Time:<br>&nbsp;&nbsp;&nbsp;
  Year:<input class=input-mini    type=text name=eyear value=<?php echo date('Y')?> size=4 >
  Month:<input class=input-mini    type=text name=emonth value=<?php echo date('m')?> size=2 >
  
  Day:<input class=input-mini  type=text name=eday size=2 value=<?php echo date('d')+(date('H')+4>23?1:0)?>>&nbsp;
  Hour:<input class=input-mini  type=text name=ehour size=2 value=<?php echo (date('H')+4)%24?>>&nbsp;
  Minute:<input class=input-mini  type=text name=eminute value=00 size=2 ></p>
    <div style="color: #ff775a">
      visit <a href="../faqs.php#p-5" target="_blank">FAQ</a> to know differences between types of contest.
    </div>
  Is Practice:
  <select name='practice' style='width:50px'>
    <option value='1'>Y</option>
    <option value='0' selected>N</option>
  </select>
  Public:
    <select name=private>
      <option value=0>Public</option>
      <option value=1>Private</option>
    </select>
  Password:<input type=text name=password value=""><br />
  Team only(Special):
    <select name='user_limit' style='width:50px'>
      <option value='Y' ?>Y</option>
      <option value='N' selected='selected'>N</option>
    </select>
  Defunct TA:
    <select name='defunct_TA' style='width:50px'>
      <option value='Y'>Y</option>
      <option value='N' selected='selected'>N</option>
    </select>
  Open source:
    <select name='open_source' style='width:50px'>
      <option value='Y'>Y</option>
      <option value='N' selected='selected'>N</option>
    </select>
  <br />
  <?php require_once("../include/set_post_key.php");?>
    <div style="color: green;">Problems can be added later in contest-edit page.</div>
  <p align=left>Description:<br><textarea class=kindeditor rows=13 name=description cols=80></textarea>
    
  <p><input type=submit value=Submit name=submit><input type=reset value=Reset name=reset></p>
  </form>
<?php }
?>

<?php 
  require_once("admin-footer.php")
?>