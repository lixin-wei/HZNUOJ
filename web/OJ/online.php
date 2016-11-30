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
		
if (HAS_PRI("see_hidden_user_info")){

		
		if(isset($_GET['search'])){

			$sql="SELECT * FROM `loginlog`";
			$search=trim($mysqli->real_escape_string($_GET['search']));
			if ($search!='')
				$sql=$sql." WHERE ip like '%$search%' ";
			 else
				$sql=$sql." where user_id<>'".$_SESSION['user_id']."' ";
			$sql=$sql."  order by `time` desc LIMIT 0,50";

		$result=$mysqli->query($sql) or die($mysqli->error);
		$i=0;
	
		for (;$row=$result->fetch_row();){
				
				$view_online[$i][0]= "<a href='userinfo.php?user=".$row[0]."'>".$row[0]."</a>";
				$view_online[$i][1]=$row[1];
				$view_online[$i][2]=$row[2];
				$view_online[$i][3]=$row[3];
				
				$i++;
		}
	
		$result->free();
		}

}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/online.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
