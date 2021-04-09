<?php if(!session_id()) session_start();
require_once("include/const.inc.php");
if (!isset($_SESSION['user_id'])){
	$view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
require_once("include/check_post_key.php");
require_once("include/db_info.inc.php");
require_once("include/my_func.inc.php");
  $now=strftime("%Y-%m-%d %H:%M",time());
$user_id=$_SESSION['user_id'];
if (isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	$sql="SELECT `problem_id` from `contest_problem` 
				where `num`='$pid' and contest_id=$cid";
}else{
	$id=intval($_POST['id']);
	if(HAS_PRI("see_hidden_".get_problemset($id)."_problem"))
		$sql="SELECT `problem_id` from `problem` where `problem_id`='$id'";
	else
		$sql=<<<SQL
SELECT problem_id FROM `problem`
WHERE
  `problem_id`=$id
  AND `defunct`='N'
  AND `problem_id` NOT IN (
    SELECT `problem_id` FROM `contest_problem`
    WHERE
      `contest_id` IN(
        SELECT `contest_id` FROM `contest`
        WHERE
          `end_time`>NOW()
          AND start_time <NOW()
          AND practice = 0
      )
  )
SQL;

}

//echo $sql;
$res=$mysqli->query($sql);
if ($res&&$res->num_rows<1&&!((isset($cid)&&$cid<=0) || (isset($id)&&$id<=0))){
		$res->free();
		$view_errors=  "<span class='am-text-danger'>Problem Not Available!</span>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
}
$res->free();


$test_run=false;
if (isset($_POST['id'])) {
	$id=intval($_POST['id']);
        $test_run=($id<=0);
}else if (isset($_POST['pid']) && isset($_POST['cid'])&&$_POST['cid']!=0){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
        $test_run=($cid<0);
	if($test_run) $cid=-$cid;
	// check user if private
	$sql="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<='$now' AND `end_time`>'$now'";
	$result=$mysqli->query($sql);
	$rows_cnt=$result->num_rows;
	if ($rows_cnt!=1){
		echo "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
		$result->free();
		exit(0);
	}else{
		$row=$result->fetch_array();
		$isprivate=intval($row[0]);
		$result->free();
		if ($isprivate==1&&!isset($_SESSION['c'.$cid])){
			$sql="SELECT count(*) FROM `privilege` WHERE `user_id`='$user_id' AND `rightstr`='c$cid'";
			$result=$mysqli->query($sql) or die ($mysqli->error); 
			$row=$result->fetch_array();
			$ccnt=intval($row[0]);
			$result->free();
			if ($ccnt==0&&!HAS_PRI("edit_contest")){
				$view_errors= "You are not invited!\n";
				require("template/".$OJ_TEMPLATE."/error.php");
				exit(0);
			}
		}
	}
	$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid' AND `num`='$pid'";
	$result=$mysqli->query($sql);
	$rows_cnt=$result->num_rows;
	if ($rows_cnt!=1){
		$view_errors= "No Such Problem!\n";
		require("template/".$OJ_TEMPLATE."/error.php");
		$result->free();
		exit(0);
	}else{
		$row=$result->fetch_object();
		$id=intval($row->problem_id);
		if($test_run) $id=-$id;
		$result->free();
	}
}else{
       $id=0;
/*
	$view_errors= "No Such Problem!\n";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
*/
       $test_run=true;
}
$language=intval($_POST['language']);
if ($language>count($language_name) || $language<0) $language=0;
$language=strval($language);


$source=$_POST['source'];
$input_text=$_POST['input_text'];
if(get_magic_quotes_gpc()){
	$source=stripslashes($source);
	$input_text=stripslashes($input_text);

}

$input_text=preg_replace ( "/(\r\n)/", "\n", $input_text );
$source=$mysqli->real_escape_string($source);
$input_text=$mysqli->real_escape_string($input_text);
$source_user=$source;
if($test_run) $id=-$id;

//use append Main code
//上传文件的编码不一定是UTF-8，读取数据(包含中文的情况下)插入数据库会出错，因此先把文件编码转为UTF-8并返写
$prepend_file="$OJ_DATA/$id/prepend.$language_ext[$language]";
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($prepend_file)){
	require_once("./include/problem.php");
    $source=$mysqli->real_escape_string(convert2UTF8($OJ_DATA,$id,pathinfo($prepend_file)['basename'])."\n").$source;
}

$append_file="$OJ_DATA/$id/append.$language_ext[$language]";
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($append_file)){
	require_once("./include/problem.php");
    $source.=$mysqli->real_escape_string("\n".convert2UTF8($OJ_DATA,$id,pathinfo($append_file)['basename']));
}
//end of append 

if($test_run) $id=0;

$len=strlen($source);
//echo $source;



setcookie('lastlang',$language,time()+360000);

$ip=$_SERVER['REMOTE_ADDR'];

if ($len<2){
	$view_errors="Code too short!<br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
if ($len>65536){
	$view_errors="Code too long!<br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}

// last submit
$submit_interval_limit=3;
$now=strftime("%Y-%m-%d %X",time()-$submit_interval_limit);
$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' and in_date>'$now' order by `in_date` desc limit 1";
$res=$mysqli->query($sql);
if ($res->num_rows>0){
	$view_errors="You should not submit more than twice in $submit_interval_limit seconds.....<br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}


if(($OJ_LANGMASK)&(1<<$language)){
	$store_id=0;
	if(isset($_SESSION['store_id'])) $store_id=$_SESSION['store_id'];

	if (!isset($pid)){
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
	}else{
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
	}
	$mysqli->query($sql);
	$insert_id=$mysqli->insert_id;
	$sql="INSERT INTO `source_code_user`(`solution_id`,`source`)VALUES('$insert_id','$source_user')";
	$mysqli->query($sql);

	$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
	$mysqli->query($sql);

	if($test_run){
		$sql="INSERT INTO `custominput`(`solution_id`,`input_text`)VALUES('$insert_id','$input_text')";
		$mysqli->query($sql);
	}
	//echo $sql;
}


	 $statusURI=strstr($_SERVER['REQUEST_URI'],"submit",true)."status.php";
	 if (isset($cid)) 
	    $statusURI.="?cid=$cid";
	    
        $sid="";
        if (isset($_SESSION['user_id'])){
                $sid.=session_id().$_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER["REQUEST_URI"])){
                $sid.=$statusURI;
        }
   // echo $statusURI."<br>";
  
        $sid=md5($sid);
        $file = "cache/cache_$sid.html";
    //echo $file;  
    if($OJ_MEMCACHE){
		$mem = new Memcache;
                if($OJ_SAE)
                        $mem=memcache_init();
                else{
                        $mem->connect($OJ_MEMSERVER,  $OJ_MEMPORT);
                }
        $mem->delete($file,0);
    }
	else if(file_exists($file)) 
	     unlink($file);
    //echo $file;
    
  $statusURI="status.php?user_id=".$_SESSION['user_id'];
  if (isset($cid))
	    $statusURI.="&cid=$cid";
	 
   if(!$test_run){
   	header("Location: $statusURI");
   }
   else{
     $para = "user_id={$_SESSION['user_id']}";
     if(isset($cid)) {
       $para .= "&cid=$cid";
     }
     echo "<script>window.location.href='./status.php?$para'</script>";
   }
?>
