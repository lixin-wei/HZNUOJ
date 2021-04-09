<?php
@session_start ();
require_once("../include/db_info.inc.php");
if (!HAS_PRI("inner_function")) {
	echo "You are not allowed to view this page!";
	exit(1);
}
require_once("../include/const.inc.php");
require_once("../include/setlang.php");
function getTestFileIn($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents ( "$OJ_DATA/$pid/" . $testfile . ".in" );
	else
		return "";
}
function getTestFileOut($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents (  );
	else
		return "";
}
function printSampleCases($pid)
{
	global $mysqli;
	$sql = "SELECT * FROM `problem_samples` WHERE `problem_id`='$pid' ORDER BY `sample_id`";
	$res = $mysqli->query($sql) or die($mysqli->error);
	while ($sample_row = $res->fetch_object()) {
		echo "<sample_input show_after=\"$sample_row->show_after\"><![CDATA[" . $sample_row->input . "]]></sample_input>\n";
		echo "<sample_output><![CDATA[" .  $sample_row->output . "]]></sample_output>\n";
		echo "\n";
	}
	$res->free();
	return;
}
function printTestCases($pid, $OJ_DATA){
if(strstr($OJ_DATA,"saestor:"))     {
  // echo "<debug>$pid</debug>";
       $store = new SaeStorage();
           $ret = $store->getList("data", "$pid",100,0 );
            foreach($ret as $file) {
              //          echo "<debug>$file</debug>";
              
              if(!strstr($file,"sae-dir-tag")){
                    
                    $pinfo = pathinfo ( $file );
		if (isset($pinfo ['extension'])
			&& strtolower($pinfo['extension']) == "in" 
			&& strtolower(substr($pinfo['basename'],0,6)) != "sample") {
			$f = basename($pinfo['basename'], "." . $pinfo['extension'] );
			
			$outfile="$pid/" . $f . ".out";
			$infile="$pid/" . $f . ".in";
			if( $store->fileExists ("data",$infile)){
				echo "<test_input><![CDATA[".fixcdata($store->read("data",$infile))."]]></test_input>\n";
			}if($store->fileExists ("data",$outfile)){
				echo "<test_output><![CDATA[".fixcdata($store->read("data",$outfile))."]]></test_output>\n";
			}
//			break;
		}
                    
                    
                    
              }
                    
            }

}else{


	$ret = "";
	$pdir = opendir ( "$OJ_DATA/$pid/" );
	while ( $file = readdir ( $pdir ) ) {
		$pinfo = pathinfo ( $file );
		if (isset($pinfo ['extension'])
			&&strtolower($pinfo['extension']) == "in" 
			&& strtolower(substr($pinfo['basename'],0,6)) != "sample") {
			$ret = basename ( $pinfo ['basename'], "." . $pinfo ['extension'] );
			
			$outfile="$OJ_DATA/$pid/" . $ret . ".out";
			$infile="$OJ_DATA/$pid/" . $ret . ".in";
			if(file_exists($infile)){
				echo "<test_input><![CDATA[".fixcdata(file_get_contents ($infile))."]]></test_input>\n";
			}if(file_exists($outfile)){
				echo "<test_output><![CDATA[".fixcdata(file_get_contents ($outfile))."]]></test_output>\n";
			}
			echo "\n";
		}
	}
	closedir ( $pdir );
	return $ret;
}
}
class Solution{
  var $language="";
  var $source_code=""; 
}
function getSolution($pid,$lang){
	$ret=new Solution();
	global $mysqli, $language_name;

	$sql = "select `solution_id`,`language` from solution where problem_id=$pid and result=4 and language=$lang limit 1";
//	echo $sql;
	$result = $mysqli->query($sql);
	if($result&&$row = $result->fetch_row()) {
		$solution_id=$row[0];
		$ret->language=$language_name[$row[1]];

		$result->free();
		$sql = "select source from source_code where solution_id=$solution_id";
		$result = $mysqli->query ( $sql ) or die ( $mysqli->error );
		if($row = $result->fetch_object()){
			$ret->source_code=$row->source;

		}
		$result->free();
	}
	return $ret;
}
function fixurl($img_url){
   $img_url=htmlspecialchars_decode( $img_url);
   
	if (substr($img_url,0,7)!="http://"){
	  if(substr($img_url,0,1)=="/"){
	     	$ret='http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER["SERVER_PORT"].$img_url;
     }else{
     		$path= dirname($_SERVER['PHP_SELF']);
	      $ret='http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER["SERVER_PORT"].$path."/../".$img_url;
     }
   }else{
   	$ret=$img_url;
   }
   return  $ret;
} 
function image_base64_encode($img_url){
    $img_url=fixurl($img_url);
	if (substr($img_url, 0, 4) != "http") return false;
	$handle = @fopen($img_url, "rb");
	if($handle){
		$contents = stream_get_contents($handle);
		$encoded_img= base64_encode($contents);
		fclose($handle);
		return $encoded_img;
	}else
		return false;
}
function getImages($content){
    preg_match_all("<[iI][mM][gG][^<>]+[sS][rR][cC]=\"?([^ \"\>]+)/?>",$content,$images);
    return $images;
}
function fixcdata($content){
	$content=str_replace("\x1a","",$content);// hustoj原版那边复制过来 remove some strange \x1a [SUB] char from datafile
    return str_replace("]]>","]]]]><![CDATA[>",$content);
}
function fixImageURL(&$html,&$did){
   $images=getImages($html);
   $imgs=array_unique($images[1]);
   foreach($imgs as $img){
		  $html=str_replace($img,fixurl($img),$html); 
		  //print_r($did);
		  if(!in_array($img,$did)){
			  $base64=image_base64_encode($img);
			  if($base64){
				  echo "<img><src><![CDATA[".fixurl($img)."]]></src>";
				  echo "<base64><![CDATA[". $base64 ."]]></base64></img>";
			 }
			 array_push($did,$img);
		 }
   }   	
}

if (isset($_POST ['download'])||isset($_GET['cid'])) {
	$cid = isset($_GET['cid']) ? $_GET['cid'] : $_POST['cid'];
	$sql = "";
   if(isset($_POST ['in'])&&strlen($_POST ['in'])>0){
		require_once("../include/check_post_key.php");
		$_POST['in'] = trim(str_replace("[", "", $_POST['in']));
		$_POST['in'] = trim(str_replace("]", "", $_POST['in']));
		$ins = explode(",", $_POST['in']);
		$in="";
		foreach ($ins as $pid) {
			$pid = intval($pid);
			if ($in) $in .= ",";
			$in .= $mysqli->real_escape_string(trim($pid));
		}
		$sql = "SELECT * FROM problem WHERE problem_id IN ($in)";
		$filename="-$in";
	} else if (isset($_POST['start']) && strlen($_POST['start']) > 0){
		require_once("../include/check_post_key.php");
		$start = intval($_POST['start']);
		$end = intval($_POST['end']);
		$sql = "SELECT * FROM `problem` WHERE `problem_id`>='$start' AND problem_id<='$end'";
		$filename = "-$start-$end";
	} else if (isset($_GET['cid']) || isset($_POST['cid'])  && strlen($cid) > 0) {
		if (isset($_GET['cid'])) {
			require_once("../include/check_get_key.php");
		} else {
			require_once("../include/check_post_key.php");
		}
		$cid = intval($cid);
		$sql= "SELECT `title` FROM `contest` WHERE `contest_id`='$cid'";
		$result = $mysqli->query($sql) or die($mysqli->error);
		if ($row = $result->fetch_object()){
			$filename='-'.$row->title;
		} else {
			echo "<script language=javascript>alert('查不到{$MSG_CONTEST}信息！');</script>";
			echo "<script language=javascript>history.go(-1);</script>";
			exit(0);
		}
		$result->free();
		$sql = "SELECT * FROM `problem` WHERE `problem_id` IN (SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid')";
	}

	$msg = "查不到{$MSG_PROBLEM}信息！";
	if($sql){
		//echo $sql;
		$result = $mysqli->query($sql) or die($mysqli->error);
		if ($result->num_rows!=0) $msg = "";
	}
	if ($msg){
		echo "<script language=javascript>alert('$msg');</script>";
		echo "<script language=javascript>history.go(-1);</script>";
		exit(0);
	}

	//header('Content-Type:   text/xml');
	header("content-type:   application/file");
	header("content-disposition:   attachment;   filename=\"fps-" . $_SESSION['user_id'] . $filename . ".xml\"");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
  
?>
<fps version="1.2" url="https://github.com/zhblue/freeproblemset/">
	<generator name="HZNUOJ" url="https://github.com/wlx65003/HZNUOJ/" />
		<?php while ($row = $result->fetch_object()) { ?>
		<item>
			<title><![CDATA[<?php echo $row->title ?>]]></title>
			<time_limit unit="s"><![CDATA[<?php echo $row->time_limit ?>]]></time_limit>
			<memory_limit unit="MB"><![CDATA[<?php echo $row->memory_limit ?>]]></memory_limit>
			<?php
			echo "\n";
			$did=array();
			fixImageURL($row->description, $did);
			fixImageURL($row->input, $did);
			fixImageURL($row->output, $did);
			fixImageURL($row->hint, $did);
			echo "\n";
			?>
			<description><![CDATA[<?php echo $row->description ?>]]></description>
			<input><![CDATA[<?php echo $row->input ?>]]></input>
			<output><![CDATA[<?php echo $row->output ?>]]></output>
			<?php
			echo "\n";
			printSampleCases($row->problem_id);
			printTestCases($row->problem_id, $OJ_DATA);
			?>
			<hint><![CDATA[<?php echo $row->hint ?>]]></hint>
			<source><![CDATA[<?php echo fixcdata($row->source) ?>]]></source>
			<?php
			for ($lang = 0; $lang < count($language_name); $lang++) {
				$solution = getSolution($row->problem_id, $lang);
				if ($solution->language) { ?>
<solution language="<?php echo $solution->language ?>"><![CDATA[<?php echo fixcdata($solution->source_code) ?>]]></solution>
			<?php 
				echo "\n";
				}
				$pta = array("prepend", "template", "append");
				foreach ($pta as $pta_file) {
					$append_file = "$OJ_DATA/$pid/$pta_file." . $language_ext[$lang];
					if (file_exists($append_file)) { ?>
			 <<?php echo $pta_file ?> language="<?php echo $language_name[$lang] ?>"><![CDATA[<?php echo fixcdata(file_get_contents($append_file)) ?>]]></<?php  echo $pta_file ?>>
				<?php
						echo "\n";
					}
				}
			}
			echo "\n";
			if ($row->spj != 0) {
				$filec = "$OJ_DATA/" . $row->problem_id . "/spj.c";
				$filecc = "$OJ_DATA/" . $row->problem_id . "/spj.cc";
				if (file_exists($filec)) {
					echo "<spj language=\"C\"><![CDATA[";
					echo fixcdata(file_get_contents($filec));
					echo "]]></spj>";
				} elseif (file_exists($filecc)) {
					echo "<spj language=\"C++\"><![CDATA[".fixcdata(file_get_contents($filecc))."]]></spj>";
				}
			}
?>
</item>
<?php }
	$result->free();
	
	echo "</fps>";

}
?>
