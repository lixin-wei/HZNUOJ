<?php
  /**
   * This file is modified
   * by yybird
   * @2016.07.02
  **/
?>

<?php 
  require("admin-header.php");
  if (!HAS_PRI("generate_team")) {
    echo "Permission denied!";
    exit(1);
  }
?>
<?php 
  if(isset($_POST['prefix'])) {
    require_once("../include/check_post_key.php");
    $prefix=$_POST['prefix'];
    require_once("../include/my_func.inc.php");
    if (strlen($prefix) > 30) {
      echo "Prefix is too long!";
      exit(0);
    }
    if (!is_valid_user_name($prefix)){
      echo "Prefix is not valid.";
      exit(0);
    }
    if (!is_numeric($_POST['contest_id'])) {
      echo "Illegal contest ID";
      exit(0);
    }
    if ($_POST['class1']!="其它" && !is_numeric($_POST['class2'])) {
      echo "Illegal class name";
      exit(0);
    }
    $teamnumber=intval($_POST['teamnumber']);
    $pieces = explode("\n", trim($_POST['ulist']));
    $contest_id = intval($_POST['contest_id']);
    $no = 0;
    if ($_POST['class1'] == "其它"){
      if($_POST['class2']){
        $class = $_POST['class2'];
      }
      else{
        $class = "其它";
      }
    }
    else $class = $_POST['class1'].$_POST['class2'];
    $sql = "SELECT MAX(NO) AS start FROM team WHERE prefix='$prefix' AND contest_id='$contest_id'";
    $result = $mysqli->query($sql);
    if ($result) {
      $row = $result->fetch_array();
      $no = $row['start']+1;
    }
    
    if ($teamnumber>0){
      echo "<table border=1>";
      echo "<tr><td colspan=3>Copy these accounts to distribute</td></tr>";
      echo "<tr><td>team_name</td><td>class</td><td>contest_id</td><td>login_id</td><td>password</td></tr>";
      for($i=$no;$i<$no+$teamnumber;$i++){
        $user_id=$prefix.($i<10?('0'.$i):$i);
        $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
        while (is_numeric($password))  $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
        str_replace("I","X",$password);
        str_replace("O","Y",$password);
        str_replace("0","Z",$password);
        str_replace("1","W",$password);
        if(isset($pieces[$i-1])) $nick=$pieces[$i-1];
        else $nick="NULL";
        echo "<tr><td>$nick</td><td>$class</td><td>$contest_id</td><td>$user_id</td><td>$password</td></tr>";
        
        $password=pwGen($password);
                         
        $school=$_POST['school'];
        $sql="INSERT INTO `team`(`user_id`, prefix, NO, `ip`,`accesstime`,`password`,`reg_time`,`nick`,contest_id, class, `school`)"."VALUES('".$user_id."','".$prefix."','".$i."','".$_SERVER['REMOTE_ADDR']."',NOW(),'".$password."',NOW(),'".$nick."','".$contest_id."','".$class."','".$school."')on DUPLICATE KEY UPDATE `ip`='".$_SERVER['REMOTE_ADDR']."',`accesstime`=NOW(),`password`='".$password."',`reg_time`=now(),nick='".$nick."',`school`='".$school."'";
        $mysqli->query($sql) or die($mysqli->error);
      }
      echo  "</table>";
    }
  }
?>
  <title>TeamGenerator</title>
  <h1>TeamGenerator</h1><hr>
  <font size='2px' color='red'>
    使用指南：<br />
    <li>1. School和Users为选填项，其余必填，其中队伍前缀名（Prefix）不能超过30个字符。若填入Users表示指定账号的nick</li>
    <li>2. Class一项，方框中填入班级号，例如计算机151班则选中“计算机”，并在方框中填入151。若选择“其它”，可以自定义班级号，若留空则默认为“其他”，此处分班级创建临时账号是为了方便在contestranklist中分班级进行排名</li>
    <li>3. 若不小心创建了过多的账号，请联系管理员在数据库中进行删除（好像这个功能也只有管理员能用？orz）</li>
  </font>
  <form action='team_generate.php' method=post>
    School:<input type='text' name='school' value='Hangzhou Normal University'>
    Class:
      <select name='class1' style='width:80px'>
        <option value='计算机'>计算机</option>
        <option value='软工'>软工</option>
        <option value='物联网'>物联网</option>
        <option value='其它'>其它</option>
      </select>
      <input type='text' name='class2' style='width:100px'>
    <br />
    Contest ID:<input type='text' name='contest_id' style='width:100px'>
    <br />
    Prefix:<input type='text' name='prefix' value='team' style='width:150px'>
    Generate<input type='input' name='teamnumber' value='50' style='width:60px'>Teams.
    <input type='submit' value='Generate'><br>
    Users:<textarea name="ulist" rows="20" cols="20"><?php if (isset($ulist)) { echo $ulist; } ?></textarea>
    <?php require_once("../include/set_post_key.php");?>
  </form>

<?php 
  require_once("admin-footer.php")
?>
