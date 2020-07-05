<?php require_once ("admin-header.php");
require_once("../include/check_post_key.php");
if (!HAS_PRI("inner_function")){
	echo "You are not allowed to view this page!";
	exit(1);
}
?>
<?php 
	function image_save_file($filepath ,$base64_encoded_img){
		$fp=fopen($filepath ,"wb");
		fwrite($fp,base64_decode($base64_encoded_img));
		fclose($fp);
	}
require_once("../include/db_info.inc.php");
require_once("../include/problem.php");
require_once("../include/setlang.php");
require_once("../include/const.inc.php");

function import_addSample($id,$sample_id,$sample_input,$sample_output,$show_after,$spj){
	
	global $mysqli;

    $sample_input=preg_replace("/(\r\n)/","\n",$sample_input);
    $sample_output=preg_replace("/(\r\n)/","\n",$sample_output);
    // if($sample_input=="" && $sample_output=="") return "Error";
 
    
    $sample_input=$mysqli->real_escape_string($sample_input);
    $sample_output=$mysqli->real_escape_string($sample_output);
    $sql=<<<SQL
		INSERT INTO problem_samples (
			problem_id,
			sample_id,
			input,
			output,
			show_after
		)
		VALUES
		($id, '$sample_id', "$sample_input", "$sample_output", '$show_after')
SQL;
    //echo "$sql";
    $mysqli->query($sql);
	
	//echo "Sample data file Updated!<br>";
}

function getLang($language)
{
	global $language_name;
	for($i=0;$i<count($language_name);$i++){
		//echo "$language=$language_name[$i]=".($language==$language_name[$i]);
		if($language==$language_name[$i]){
			return $i;
		}

	}
	return $i;
}

function submitSolution($pid,$solution,$language)
{
	global $mysqli, $OJ_DATA, $language_ext, $OJ_APPENDCODE;
	$language = getLang($language);
	$len=mb_strlen($solution,'utf-8');
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,result)
	VALUES('$pid','".$_SESSION['user_id']."',NOW(),'$language','127.0.0.1','$len',14)";

	$mysqli->query ( $sql );
	$insert_id = $mysqli->insert_id;
	$solution=$mysqli->real_escape_string($solution);
	//echo "submiting$language.....";
	$sql = "INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$solution')";
	$mysqli->query ( $sql );

	$sql = "INSERT INTO `source_code_user`(`solution_id`,`source`)VALUES('$insert_id','$solution')";
	$mysqli->query($sql);
	$sql = "UPDATE `solution` SET `result`=1 WHERE `solution_id`='$insert_id'";
	$mysqli->query($sql);
}
function getValue($Node, $TagName) {

	return $Node->$TagName;
}
function getAttribute($Node, $TagName,$attribute) {
	return $Node->children()->$TagName->attributes()->$attribute;
}
function hasProblem($title){
	// require_once("../include/db_info.inc.php");
	global $mysqli;
	$md5=md5($title);
	$sql="select 1 from problem where md5(title)='$md5'"; 
	$result=$mysqli->query ( $sql );
	$rows_cnt=$result->num_rows;
	$result->free();
	//echo "row->$rows_cnt";			
	return  ($rows_cnt>0);

}

function mkpta($pid, $pends, $node)
{
	global $language_ext, $OJ_DATA;
	foreach ($pends as $pend) {
		$language = $pend->attributes()->language;
		$lang = getLang($language);
		$file_ext = $language_ext[$lang];
		$file_name = "$node.$file_ext";
		mkdata($pid,$file_name,$pend,$OJ_DATA);
	}
}
function import_fps($tempfile)
{
	global $mysqli, $OJ_DATA, $OJ_SAE;
	$xmlDoc=simplexml_load_file($tempfile, 'SimpleXMLElement', LIBXML_PARSEHUGE);
	$searchNodes = $xmlDoc->xpath ( "/fps/item" );
	$spid=0;
	foreach($searchNodes as $searchNode) {
		$title = getValue($searchNode, 'title');
		echo "<b>$title</b>&nbsp;&nbsp;";

		$time_limit = getValue($searchNode, 'time_limit');
    	$unit=getAttribute($searchNode,'time_limit','unit');
    	//echo $unit;
		if(strtolower($unit)=='ms') $time_limit/=1000;
		
		$memory_limit = getValue ( $searchNode, 'memory_limit' );
		$unit=getAttribute($searchNode,'memory_limit','unit');
		if(strtolower($unit)=='kb') $memory_limit/=1024;
		
		$description = getValue ( $searchNode, 'description' );
		$input = getValue ( $searchNode, 'input' );
		$output = getValue ( $searchNode, 'output' );
		$hint = getValue ( $searchNode, 'hint' );
		$source = getValue ( $searchNode, 'source' );
		
		$solutions = $searchNode->children()->solution;
		
		$spjcode = getValue ( $searchNode, 'spj' );
		$spj = trim($spjcode)?1:0;
		if(!hasProblem($title )){
			$pid=addproblem($_POST["problemset"],$title, $time_limit, $memory_limit, $description, $input, $output, $hint, "", $source, $spj, $OJ_DATA);
			if($spid==0) $spid=$pid;
			echo $pid;
			$basedir = "$OJ_DATA/$pid";
			mkdir ( $basedir );
        //  	if(!isset($OJ_SAE)||!$OJ_SAE){
				$sample_list = array();
				$samples = $searchNode->children()->sample_input;
				$testno = 0;
				foreach ($samples as $Node) {
					$sample_list[$testno]['show_after'] = $Node->attributes()['show_after'];
					$sample_list[$testno]['input'] = $Node;
					$testno++;
				}
				$samples = $searchNode->children()->sample_output;
				$testno = 0;
				foreach ($samples as $Node) {
					$sample_list[$testno]['output'] = $Node;
					$testno++;
				}
				unset($samples);
				$testno = 0;
				foreach($sample_list as $sample){
					if (!$sample['show_after']) $sample['show_after'] = 0;
					import_addSample($pid, $testno, $sample['input'], $sample['output'], $sample['show_after'], $spj);
					mkdata($pid, "sample" . $testno . ".in", $sample['input'], $OJ_DATA);
					mkdata($pid, "sample" . $testno . ".out", $sample['output'], $OJ_DATA);
					$testno++;
				}
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
						
						$src=$mysqli->real_escape_string($src);
						$newpath=$mysqli->real_escape_string($newpath);
						$sql="update problem set description=replace(description,'$src','$newpath') where problem_id=$pid";  
						$mysqli->query ( $sql );
						$sql="update problem set input=replace(input,'$src','$newpath') where problem_id=$pid";  
						$mysqli->query ( $sql );
						$sql="update problem set output=replace(output,'$src','$newpath') where problem_id=$pid";  
						$mysqli->query ( $sql );
						$sql="update problem set hint=replace(hint,'$src','$newpath') where problem_id=$pid";  
						$mysqli->query ( $sql );
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
			unset($solutions);
			$pta = $searchNode->children()->prepend;
			mkpta($pid, $pta, "prepend");
			$pta = $searchNode->children()->template;
			mkpta($pid, $pta, "template");
			$pta = $searchNode->children()->append;
			mkpta($pid, $pta, "append");
		}else{
			echo "<br><span class=red>$title is already in this OJ/题目重名</span>";		
		}
		
	}
	unlink ( $tempfile );
}
function get_extension($file)
{
	$info = pathinfo($file);
	return $info['extension'];
}
function fixData($file_content){
	//去掉节点值中<![CDATA[]]>标签之外位置上的换行符、tab、空格以及其他无关数据，避免无意义的缩进
	$file_content=preg_replace ( "/>([^>]*?)<\!\[CDATA/", "><![CDATA", $file_content );
	$file_content=preg_replace ( "/\]\]>([^<]*?)<\//", "]]></", $file_content );
	return $file_content;
}
?>
<title><?php echo $html_title . $MSG_IMPORT . $MSG_PROBLEM ?></title>
<h1><?php echo $MSG_IMPORT . $MSG_PROBLEM ?></h1>
<h4><?php echo $MSG_HELP_IMPORT_PROBLEM ?></h4>
<hr>
<label><?php echo $MSG_IMPORT . $MSG_PROBLEM ?>...</label><br>
<?php
if ($_FILES["fps"]["error"] > 0) {
	echo "Error: " . $_FILES["fps"]["error"] . "File size is too big, change in PHP.ini<br />";
} else {
	// 	echo "Upload: " . $_FILES["fps"]["name"] . "<br />";
	// 	echo "Type: " . $_FILES["fps"]["type"] . "<br />";
	// 	echo "Size: " . ($_FILES["fps"]["size"] / 1024) . " KB<br />";
	// 	echo "Stored in: " . $tempfile;
	if(get_extension($_FILES["fps"]["name"])=="zip"){
		echo "zip file , only fps/xml files in root dir are supported. / <b>只支持fps/xml文件存储在zip压缩包根目录下。</b><br>";
		$resource = zip_open($_FILES["fps"]["tmp_name"]);
		$tempfile=tempnam("/tmp", "fps");
		while ($dir_resource = zip_read($resource)) {
		   if (zip_entry_open($resource,$dir_resource)) {
			$file_name = $path.zip_entry_name($dir_resource);
			$file_path = substr($file_name,0,strrpos($file_name, "/"));
			if(!is_dir($file_name)){
			  $file_size = zip_entry_filesize($dir_resource);
			  $file_content = zip_entry_read($dir_resource,$file_size);
			  file_put_contents($tempfile,fixData($file_content));
			  import_fps($tempfile);
			}
			zip_entry_close($dir_resource);
		   }
	   }
	   zip_close($resource);
	 }else{
		$tempfile=tempnam("/tmp", "fps");
		$myfile=fopen($_FILES["fps"]["tmp_name"],"r");
		$file_size=filesize($_FILES["fps"]["tmp_name"]);
		$file_content=fread($myfile,$file_size);
		file_put_contents($tempfile,fixData($file_content));
		fclose($mylife);
		import_fps($tempfile);
	 }
	 unlink($_FILES ["fps"]["tmp_name"]);
	 echo "<br><input type='button' name='submit' value='返回' onclick='javascript:history.go(-1);' style='margin-bottom: 20px;'>";
}
  require_once("admin-footer.php")
?>
