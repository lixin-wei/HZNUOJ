<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.28
  **/
?>

<?php 
  require("admin-header.php");
  include_once("kindeditor.php") ;
  include_once("../include/const.inc.php");

  if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
  }
  
  if (isset($_POST['syear'])) { // 如果有POST过来的信息，则获取POST值并更新
    /* 更新部分 start */
    require_once("../include/check_post_key.php");
    $starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
    $endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";

    $title=$mysqli->real_escape_string($_POST['title']);
    $password=$mysqli->real_escape_string($_POST['password']);
    $description=$mysqli->real_escape_string($_POST['description']);
    $private=$mysqli->real_escape_string($_POST['private']);
    $user_limit = $mysqli->real_escape_string($_POST['user_limit']);
    $defunct_TA = $mysqli->real_escape_string($_POST['defunct_TA']);
    $open_source = $mysqli->real_escape_string($_POST['open_source']);
    if (get_magic_quotes_gpc ()) {
        $title = stripslashes ( $title);
        $password = stripslashes ( $password);
    //$description = stripslashes ( $description);
    }

    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t){
      $langmask+=1<<$t;
    } 
    $langmask=((1<<count($language_ext))-1)&(~$langmask);
    echo $langmask; 

    $cid=intval($_POST['cid']);
    $sql = "UPDATE `contest` 
            SET `title`='$title',description='$description',`start_time`='$starttime',`end_time`='$endtime',
                `private`='$private', user_limit='$user_limit', defunct_TA='$defunct_TA', open_source='$open_source',
                `langmask`=$langmask  ,password='$password'
            WHERE `contest_id`=$cid";
    //echo $sql;
    $mysqli->query($sql) or die($mysqli->error);
    $sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
    $mysqli->query($sql);
    $plist=trim($_POST['cproblem']);
    $pieces = explode(',', $plist);
    if (count($pieces)>0 && strlen($pieces[0])>0){
      $sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) 
        VALUES ('$cid','$pieces[0]',0)";
      for ($i=1;$i<count($pieces);$i++)
        $sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
      $mysqli->query("update solution set num=-1 where contest_id=$cid");
      for ($i=0;$i<count($pieces);$i++){
        $sql_2="update solution set num='$i' where contest_id='$cid' and problem_id='$pieces[$i]';";
        $mysqli->query($sql_2);
      }
      //echo $sql_1;
      
      $mysqli->query($sql_1) or die($mysqli->error);
      $sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
      $mysqli->query($sql) or die($mysqli->error);
  
  }
  
/*  $sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
  $mysqli->query($sql);
  $pieces = explode("\n", trim($_POST['ulist']));
  if (count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) 
      VALUES ('".trim($pieces[0])."','c$cid')";
    for ($i=1;$i<count($pieces);$i++)
      $sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
    //echo $sql_1;
    $mysqli->query($sql_1) or die($mysqli->error);
  }*/
  
  echo "<script>window.location.href=\"contest_list.php\";</script>";
  exit();
  /* 更新部分 end */

}else{
  $cid=intval($_GET['cid']);
  $sql="SELECT * FROM `contest` WHERE `contest_id`=$cid";
  $result=$mysqli->query($sql);
  if ($result->num_rows!=1){
    $result->free();
    echo "No such Contest!";
    exit(0);
  }
  $row=$result->fetch_array();
  $starttime=$row['start_time'];
  $endtime=$row['end_time'];
  $private=$row['private'];
  $user_limit = $row['user_limit']=="Y"?'Y':'N';
  $defunct_TA = $row['defunct_TA']=="Y"?'Y':'N';
  $open_souce = $row['open_source']=="Y"?'Y':'N';
  $password=$row['password'];
  $langmask=$row['langmask'];
  $description=$row['description'];
  $title=htmlentities($row['title'],ENT_QUOTES,"UTF-8");
  $result->free();
  $plist="";
  $sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
  $result=$mysqli->query($sql) or die($mysqli->error);
  for ($i=$result->num_rows;$i>0;$i--){
    $row=$result->fetch_row();
    $plist=$plist.$row[0];
    if ($i>1) $plist=$plist.',';
  }
  $ulist="";
  $sql="SELECT `user_id` FROM `privilege` WHERE `rightstr`='c$cid' order by user_id";
  $result=$mysqli->query($sql) or die($mysqli->error);
  for ($i=$result->num_rows;$i>0;$i--){
    $row=$result->fetch_row();
    $ulist=$ulist.$row[0];
    if ($i>1) $ulist=$ulist."\n";
  }
  
  
}
?>

<form method=POST >
  <?php require_once("../include/set_post_key.php");?>
  <p align=center><font size=4 color=#333399>Edit a Contest</font></p>
  <input type=hidden name='cid' value=<?php echo $cid?>>
  <p align=left>Title:<input class=input-xxlarge type=text name=title size=71 value='<?php echo $title?>'></p>
  <p align=left>Start Time:<br>&nbsp;&nbsp;&nbsp;
  Year:<input class=input-mini  type=text name=syear value=<?php echo substr($starttime,0,4)?> size=4 >
  Month:<input class=input-mini  type=text name=smonth value='<?php echo substr($starttime,5,2)?>' size=2 >
  Day:<input class=input-mini  type=text name=sday size=2 value='<?php echo substr($starttime,8,2)?>'>
  Hour:<input class=input-mini  type=text name=shour size=2 value='<?php echo substr($starttime,11,2)?>'>
  Minute:<input class=input-mini  type=text name=sminute size=2 value=<?php echo substr($starttime,14,2)?>></p>
  <p align=left>End Time:<br>&nbsp;&nbsp;&nbsp;

  Year:<input class=input-mini  type=text name=eyear value=<?php echo substr($endtime,0,4)?> size=4 >
  Month:<input class=input-mini  type=text name=emonth value=<?php echo substr($endtime,5,2)?> size=2 >
  Day:<input class=input-mini  type=text name=eday size=2 value=<?php echo substr($endtime,8,2)?>>
  Hour:<input class=input-mini  type=text name=ehour size=2 value=<?php echo substr($endtime,11,2)?>> 
  Minute:<input class=input-mini  type=text name=eminute size=2 value=<?php echo substr($endtime,14,2)?>></p>

  Public/Private:
    <select name='private' style='width:80px'>
      <option value=0 <?php echo $private=='0'?'selected=selected':''?>>Public</option>
      <option value=1 <?php echo $private=='1'?'selected=selected':''?>>Private</option>
    </select>
  Password:<input type=text name=password value="<?php echo htmlentities($password,ENT_QUOTES,'utf-8')?>"><br>
  Team only:
    <select name='user_limit' style='width:50px'>
      <option value='Y' <?php echo $user_limit=='Y'?'selected=selected':''?>>Y</option>
      <option value='N' <?php echo $user_limit=='N'?'selected=selected':''?>>N</option>
    </select>
  Defunct TA:
    <select name='defunct_TA' style='width:50px'>
      <option value='Y' <?php echo $defunct_TA=='Y'?'selected=selected':''?>>Y</option>
      <option value='N' <?php echo $defunct_TA=='N'?'selected=selected':''?>>N</option>
    </select>
  Open source:
    <select name='open_source' style='width:50px'>
      <option value='Y' <?php echo $open_souce=='Y'?'selected=selected':''?>>Y</option>
      <option value='N' <?php echo $open_souce=='N'?'selected=selected':''?>>N</option>
    </select>
  <br />
  Problems:<input class=input-xxlarge type=text size=60 name=cproblem value='<?php echo $plist?>'>
  Language:
    <select name="lang[]"  multiple="multiple"    style="height:220px">
    <?php
      $lang_count=count($language_ext);
      $lang=(~((int)$langmask))&((1<<$lang_count)-1);
      if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
      else $lastlang=0;
      for($i=0;$i<$lang_count;$i++){ 
        echo  "<option value=$i ".( $lang&(1<<$i)?"selected":"").">
              ".$language_name[$i]."
        </option>";
      }
    ?>
     </select>
  <br>
  <p align=left>Description:<br><textarea class="kindeditor" rows=13 name=description cols=80><?php echo htmlentities($description,ENT_QUOTES,"UTF-8")?></textarea>
  Users:<textarea name="ulist" rows="20" cols="20"><?php if (isset($ulist)) { echo $ulist; } ?></textarea>
  <p><input type=submit value=Submit name=submit><input type=reset value=Reset name=reset></p>
</form>
<?php require_once("admin-footer.php");?>

