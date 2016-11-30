<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>New Problem</title>
</head>
<body leftmargin="30">
<center>
<?php require_once("../include/db_info.inc.php");?>
<?php require_once("admin-header.php");
if (!HAS_PRI("inner_function")){
	echo "You are not allowed to view this page!";
	exit(1);
}
?>
<?php
include_once("../fckeditor/fckeditor.php") ;
?>
<?php require_once("../include/simple_html_dom.php");
  $url=$_POST ['url'];
  if (!$url) $url=$_GET['url'];
  if (strpos($url, "http") === false){
	echo "Please Input like http://acm.hdu.edu.cn/showproblem.php?pid=1000";
	exit(1);
  }
  if (get_magic_quotes_gpc ()) {
	$url = stripslashes ( $url);
  }
  $baseurl=substr($url,0,strrpos($url,"/")+1);
  //echo $baseurl;
  $html = file_get_html($url);
  foreach($html->find('img') as $element)
        $element->src=$baseurl.$element->src;
        
  $element=$html->find('h1',0);
  $title=$element->plaintext;
  
  $element=$html->find('span',0);
  $tlimit=$element->plaintext; 
  $tlimit=substr($tlimit,12);
  $tlimit=substr($tlimit,strpos($tlimit, '/')+1,strpos($tlimit, ' MS') - strpos($tlimit, '/'));
  $mlimit=$element->plaintext;
  $mlimit=substr($mlimit, strpos($mlimit, "Memory"));
  $mlimit=substr($mlimit, strpos($mlimit, '/')+1,strpos($mlimit, ' K') - strpos($mlimit, '/'));
  echo $mlimit;
  $tlimit/=1000;
  $mlimit/=1000;
  
  $element=$html->find('div[class=panel_content]',0);
  $descriptionHTML=$element->outertext;
  $element=$html->find('div[class=panel_content]',1);
  $inputHTML=$element->outertext;
  $element=$html->find('div[class=panel_content]',2);
  $outputHTML=$element->outertext;
  
  $element=$html->find('pre',0);
  $element=$element->find('div',0);
  $sample_input=$element->innertext;
  $element=$html->find('pre',1);
  $element=$element->find('div',0);
  $sample_output=$element->innertext;
?>
<form method=POST action=problem_add.php>
<p align=center><font size=4 color=#333399>Add a Problem</font></p>
<input type=hidden name=problem_id value=New Problem>
<p align=left>Problem Id:&nbsp;&nbsp;New Problem</p>
<p align=left>Title:<input type=text name=title size=71 value="<?php echo $title?>"></p>
<p align=left>Time Limit:<input type=text name=time_limit size=20 value="<?php echo $tlimit?>">S</p>
<p align=left>Memory Limit:<input type=text name=memory_limit size=20 value="<?php echo $mlimit?>">MByte</p>
<p align=left>Description:<br><!--<textarea rows=13 name=description cols=80></textarea>-->
<?php
$description = new FCKeditor('description') ;
$description->BasePath = '../fckeditor/' ;
$description->Height = 300 ;
$description->Width=600;
$description->Value =$descriptionHTML;
$description->Create() ;
?>
</p>
<p align=left>Input:<br><!--<textarea rows=13 name=input cols=80></textarea>-->
<?php
$input = new FCKeditor('input') ;
$input->BasePath = '../fckeditor/' ;
$input->Height = 300 ;
$input->Width=600;
$input->Value = $inputHTML;//'<p></p>' ;
$input->Create() ;
?>
</p>
</p>
<p align=left>Output:<br><!--<textarea rows=13 name=output cols=80></textarea>-->

<?php
$output = new FCKeditor('output') ;
$output->BasePath = '../fckeditor/' ;
$output->Height = 300 ;
$output->Width=600;
$output->Value =$outputHTML;// '<p></p>' ;
$output->Create() ;
?>
</p>
<p align=left>Sample Input:<br><textarea rows=13 name=sample_input cols=80><?php echo $sample_input?></textarea></p>
<p align=left>Sample Output:<br><textarea rows=13 name=sample_output cols=80><?php echo $sample_output?></textarea></p>
<p align=left>Test Input:<br><textarea rows=13 name=test_input cols=80></textarea></p>
<p align=left>Test Output:<br><textarea rows=13 name=test_output cols=80></textarea></p>
<p align=left>Hint:<br>
<?php
$output = new FCKeditor('hint') ;
$output->BasePath = '../fckeditor/' ;
$output->Height = 300 ;
$output->Width=600;
$output->Value = '<p></p>' ;
$output->Create() ;
?>
</p>
<p>SpecialJudge: N<input type=radio name=spj value='0' checked>Y<input type=radio name=spj value='1'></p>
<p align=left>Source:<br><textarea name=source rows=1 cols=70></textarea></p>
<p align=left>contest:
	<select  name=contest_id>
<?php $sql="SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
$result=$mysqli->query($sql);
echo "<option value=''>none</option>";
if ($result->num_rows==0){
}else{
	for (;$row=$result->fetch_object();)
		echo "<option value='$row->contest_id'>$row->contest_id $row->title</option>";
}
?>
	</select>
</p>
<div align=center>
<?php require_once("../include/set_post_key.php");?>
<input type=submit value=Submit name=submit>
</div></form>
<p>
<?php require_once("../oj-footer.php");?>
</body></html>
