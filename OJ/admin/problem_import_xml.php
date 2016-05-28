<?php require_once ("admin-header.php");
require_once("../include/check_post_key.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
?>
<?php function image_save_file($filepath ,$base64_encoded_img){
	$fp=fopen($filepath ,"wb");
	fwrite($fp,base64_decode($base64_encoded_img));
	fclose($fp);
}
require_once ("../include/problem.php");

function submitSolution($pid,$solution,$language)
{
	
	require ("../include/db_info.inc.php");
	if(isset($OJ_LANG)){
		require("../lang/$OJ_LANG.php");
	}	
	require ("../include/const.inc.php");

	for($i=0;$i<count($language_name);$i++){
		//echo "$language=$language_name[$i]=".($language==$language_name[$i]);
		if($language==$language_name[$i]){
			$language=$i;
			//echo $language;
			break;
		}
		
	}
	
	$len=mb_strlen($solution,'utf-8');
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
	VALUES('$pid','".$_SESSION['user_id']."',NOW(),'$language','127.0.0.1','$len')";
	
	mysql_query ( $sql );
	$insert_id = mysql_insert_id ();
	$solution=mysql_real_escape_string($solution);
	//echo "submiting$language.....";
	$sql = "INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$solution')";
	mysql_query ( $sql );

}
?>
Import Free Problem Set ... <br>

<?php
function getValue($Node, $TagName) {
	
	return $Node->$TagName;
}
function getAttribute($Node, $TagName,$attribute) {
	return $Node->children()->$TagName->attributes()->$attribute;
}
function hasProblem($title){
	require("../include/db_info.inc.php");
	$md5=md5($title);
	$sql="select 1 from problem where md5(title)='$md5'";  
	$result=mysql_query ( $sql );
	$rows_cnt=mysql_num_rows($result);		
	mysql_free_result($result);
	//echo "row->$rows_cnt";			
	return  ($rows_cnt>0);

}

if ($_FILES ["fps"] ["error"] > 0) {
	echo "Error: " . $_FILES ["fps"] ["error"] . "File size is too big, change in PHP.ini<br />";
} else {
	$tempfile = $_FILES ["fps"] ["tmp_name"];
//	echo "Upload: " . $_FILES ["fps"] ["name"] . "<br />";
//	echo "Type: " . $_FILES ["fps"] ["type"] . "<br />";
//	echo "Size: " . ($_FILES ["fps"] ["size"] / 1024) . " Kb<br />";
//	echo "Stored in: " . $tempfile;
	
	//$xmlDoc = new DOMDocument ();
	//$xmlDoc->load ( $tempfile );
	//$xmlcontent=file_get_contents($tempfile );
	$xmlDoc=simplexml_load_file($tempfile, 'SimpleXMLElement', LIBXML_PARSEHUGE);
	$searchNodes = $xmlDoc->xpath ( "/fps/item" );
	$spid=0;
	foreach($searchNodes as $searchNode) {
		//echo $searchNode->title,"\n";

		$title =$searchNode->title;
		
		$time_limit = $searchNode->time_limit;
    	$unit=getAttribute($searchNode,'time_limit','unit');
    	//echo $unit;
		if($unit=='ms') $time_limit/=1000;
		
		$memory_limit = getValue ( $searchNode, 'memory_limit' );
		$unit=getAttribute($searchNode,'memory_limit','unit');
		if($unit=='kb') $memory_limit/=1024;
		
		$description = getValue ( $searchNode, 'description' );
		$input = getValue ( $searchNode, 'input' );
		$output = getValue ( $searchNode, 'output' );
		$sample_input = getValue ( $searchNode, 'sample_input' );
		$sample_output = getValue ( $searchNode, 'sample_output' );
//		$test_input = getValue ( $searchNode, 'test_input' );
//		$test_output = getValue ( $searchNode, 'test_output' );
		$hint = getValue ( $searchNode, 'hint' );
		$source = getValue ( $searchNode, 'source' );
		
		$solutions = $searchNode->children()->solution;
		
		$spjcode = getValue ( $searchNode, 'spj' );
		$spj = trim($spjcode)?1:0;
		if(!hasProblem($title )){
			$pid=addproblem ( $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA );
			if($spid==0) $spid=$pid;
			$basedir = "$OJ_DATA/$pid";
			mkdir ( $basedir );
			if(strlen($sample_input)) mkdata($pid,"sample.in",$sample_input,$OJ_DATA);
			if(strlen($sample_output)) mkdata($pid,"sample.out",$sample_output,$OJ_DATA);
        //  	if(!isset($OJ_SAE)||!$OJ_SAE){
				$testinputs=$searchNode->children()->test_input;
				$testno=0;
			
				foreach($testinputs as $testNode){
					//if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".in",$testNode,$OJ_DATA);
				}
				$testinputs=$searchNode->children()->test_output;
				$testno=0;
				foreach($testinputs as $testNode){
					//if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".out",$testNode,$OJ_DATA);
				}
       // }
			$images=($searchNode->children()->img);
			$did=array();
			$testno=0;
			foreach($images as $img){
			//	
				$src=getValue($img,"src");
				if(!in_array($src,$did)){
						$base64=getValue($img,"base64");
						$ext=pathinfo($src);
						$ext=strtolower($ext['extension']);
						if(!stristr(",jpeg,jpg,png,gif,bmp",$ext)){
							$ext="bad";
							exit(1);
						}
						$testno++;
						$newpath="../upload/pimg".$pid."_".$testno.".".$ext;
						if($OJ_SAE) $newpath="saestor://web/upload/pimg".$pid."_".$testno.".".$ext;
						 
						image_save_file($newpath,$base64);
						$newpath=dirname($_SERVER['REQUEST_URI'] )."/../upload/pimg".$pid."_".$testno.".".$ext;
						if($OJ_SAE) $newpath=$SAE_STORAGE_ROOT."upload/pimg".$pid."_".$testno.".".$ext;
						
						$src=mysql_real_escape_string($src);
						$newpath=mysql_real_escape_string($newpath);
						$sql="update problem set description=replace(description,'$src','$newpath') where problem_id=$pid";  
						mysql_query ( $sql );
						$sql="update problem set input=replace(input,'$src','$newpath') where problem_id=$pid";  
						mysql_query ( $sql );
						$sql="update problem set output=replace(output,'$src','$newpath') where problem_id=$pid";  
						mysql_query ( $sql );
						$sql="update problem set hint=replace(hint,'$src','$newpath') where problem_id=$pid";  
						mysql_query ( $sql );
						array_push($did,$src);
				}
				
			}
			
			if(!isset($OJ_SAE)||!$OJ_SAE){
				if($spj) {
					$basedir = "$OJ_DATA/$pid";
					$fp=fopen("$basedir/spj.cc","w");
					fputs($fp, $spjcode);
					fclose($fp);
					system( " g++ -o $basedir/spj $basedir/spj.cc  ");
					if(!file_exists("$basedir/spj") ){
						$fp=fopen("$basedir/spj.c","w");
						fputs($fp, $spjcode);
						fclose($fp);
						system( " gcc -o $basedir/spj $basedir/spj.c  ");
						if(!file_exists("$basedir/spj")){
							echo "you need to compile $basedir/spj.cc for spj[  g++ -o $basedir/spj $basedir/spj.cc   ]<br> and rejudge $pid";
						
						}else{
							
							unlink("$basedir/spj.cc");
						}
					
					
					}
				}
			}
			foreach($solutions as $solution) {
				$language =$solution->attributes()->language;
				submitSolution($pid,$solution,$language);
			
			}
		}else{
			echo "<br><span class=red>$title is already in this OJ</span>";		
		}
		
	}
	unlink ( $tempfile );
	if($spid>0){
		require_once("../include/set_get_key.php");
		echo "<br><a class=blue href=contest_add.php?spid=$spid&getkey=".$_SESSION['getkey'].">Use these problems to create a contest.</a>";
	 }
}

?>
