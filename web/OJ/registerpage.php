<?php
////////////////////////////Common head
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	if (isset($OJ_REGISTER) && !$OJ_REGISTER) {
		echo "<script language='javascript'>\n";
		echo "alert('System do not allow register');\n history.go(-1);\n</script>";
		exit(0);
	}
	if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
		require_once('./include/classList.inc.php');
		$classList = get_classlist(true, "");
	}
	$view_title= "Welcome To Online Judge";
	
///////////////////////////MAIN	
/*	
	$view_news="";
	$sql=	"SELECT * "
			."FROM `news` "
			."WHERE `defunct`!='Y'"
			."ORDER BY `importance` ASC,`time` DESC "
			."LIMIT 5";
	$result=$mysqli->query($sql);//$mysqli->real_escape_string($sql));
	if (!$result){
		$view_news= "<h3>No News Now!</h3>";
		$view_news.= $mysqli->error;
	}else{
		$view_news.= "<table width=96%>";
		
		while ($row=$result->fetch_object()){
			$view_news.= "<tr><td><td><big><b>".$row->title."</b></big>-<small>[".$row->user_id."]</small></tr>";
			$view_news.= "<tr><td><td>".$row->content."</tr>";
		}
		$result->free();
		$view_news.= "<tr><td width=20%><td>This <a href=http://cm.baylor.edu/welcome.icpc>ACM/ICPC</a> OnlineJudge is a GPL product from <a href=http://code.google.com/p/hustoj>hustoj</a></tr>";
		$view_news.= "</table>";
	}
$view_apc_info="";

if(function_exists('apc_cache_info')){
	 $_apc_cache_info = apc_cache_info(); 
		$view_apc_info =_apc_cache_info;
}
*/
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/registerpage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
