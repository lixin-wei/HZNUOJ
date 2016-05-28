<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
	$debug = true;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./include/online.php');
	$on = new online();
	$view_title= "Welcome To Online Judge";
	require_once('./include/iplocation.php');
	$users = $on->getAll();
	$ip = new IpLocation();
?>



<?php 
$view_online=Array();
		
if (isset($_SESSION['administrator'])){

		
		if(isset($_GET['search'])){

			$sql="SELECT * FROM `loginlog`";
			$search=trim(mysql_real_escape_string($_GET['search']));
			if ($search!='')
				$sql=$sql." WHERE ip like '%$search%' ";
			 else
				$sql=$sql." where user_id<>'".$_SESSION['user_id']."' ";
			$sql=$sql."  order by `time` desc LIMIT 0,50";

		$result=mysql_query($sql) or die(mysql_error());
		$i=0;
	
		for (;$row=mysql_fetch_row($result);){
				
				$view_online[$i][0]= "<a href='userinfo.php?user=".$row[0]."'>".$row[0]."</a>";
				$view_online[$i][1]=$row[1];
				$view_online[$i][2]=$row[2];
				$view_online[$i][3]=$row[3];
				
				$i++;
		}
	
		mysql_free_result($result);
		}

}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/online.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
