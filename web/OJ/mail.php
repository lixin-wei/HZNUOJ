<?php
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
    require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
    require_once('./include/my_func.inc.php');

	 require_once('./include/setlang.php');
	$view_title=$MSG_MAIL;
 $to_user="";
$title="";
if (isset($_GET['to_user'])){
	$to_user=htmlspecialchars($_GET['to_user']);
}
if (isset($_GET['title'])){
	$title=htmlspecialchars($_GET['title']);
}

if (!isset($_SESSION['user_id'])){
	echo "<a href=loginpage.php>$MSG_Login</a>";
	require_once("oj-footer.php");
	exit(0);
}
require_once("./include/db_info.inc.php");
require_once("./include/const.inc.php");
if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
		if(file_exists("./faqs.$OJ_LANG.php")){
			$OJ_FAQ_LINK="faqs.$OJ_LANG.php";
		}
}
echo "<title>$MSG_MAIL</title>";



//view mail
$view_content=false;
if (isset($_GET['vid'])){
	$vid=intval($_GET['vid']);
	$sql="SELECT * FROM `mail` WHERE `mail_id`=".$vid."
								and to_user='".$_SESSION['user_id']."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	$to_user=$row->from_user;
	$view_title=$row->title;
	$view_content=$row->content;

	mysql_free_result($result);
	$sql="update `mail` set new_mail=0 WHERE `mail_id`=".$vid;
	mysql_query($sql);

}
//send mail page
//send mail
if(isset($_POST['to_user'])){
	$to_user = $_POST ['to_user'];
	$title = $_POST ['title'];
	$content = $_POST ['content'];
	$from_user=$_SESSION['user_id'];
	if (get_magic_quotes_gpc ()) {
		$to_user = stripslashes ( $to_user);
		$title = stripslashes ( $title);
		$content = stripslashes ( $content );
	}
	$title = RemoveXSS( $title);
	$to_user=mysql_real_escape_string($to_user);
	$title=mysql_real_escape_string($title);
	$content=mysql_real_escape_string($content);
	$from_user=mysql_real_escape_string($from_user);
	$sql="select 1 from users where user_id='$to_user' ";
	$res=mysql_query($sql);
	if ($res&&mysql_num_rows($res)<1){
			mysql_free_result($res);
			$view_title= "No Such User!";

	}else{
		if($res)mysql_free_result($res);
		$sql="insert into mail(to_user,from_user,title,content,in_date)
						values('$to_user','$from_user','$title','$content',now())";

		if(!mysql_query($sql)){
			$view_title=  "Not Mailed!";
		}else{
			$view_title=  "Mailed!";
		}
	}
}
//list mail
	$sql="SELECT * FROM `mail` WHERE to_user='".$_SESSION['user_id']."'
					order by mail_id desc";
	$result=mysql_query($sql) or die(mysql_error());
$view_mail=Array();
$i=0;
for (;$row=mysql_fetch_object($result);){
	$view_mail[$i][0]=$row->mail_id;
	if ($row->new_mail) $view_mail[$i][0].= "<span class=red>New</span>";
	$view_mail[$i][1]="<a href='mail.php?vid=$row->mail_id'>".
			$row->from_user.":".$row->title."</a>";
	$view_mail[$i][2]=$row->in_date;
	$i++;
}
mysql_free_result($result);


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/mail.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

