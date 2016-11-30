<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

<?php 
  require_once ("admin-header.php");
  require_once("../include/check_post_key.php");
  require_once ("../include/db_info.inc.php");
  require_once ("../include/problem.php");
?>
<?php // contest_id
  
  $title = $_POST ['title'];
  $problemset= $_POST['problemset'];
  if(!HAS_PRI("edit_".$problemset."_problem")){
  	echo "Permission denied!";
  	exit(0);
  }
  $time_limit = $_POST ['time_limit'];
  $memory_limit = $_POST ['memory_limit'];
  $description = $_POST ['description'];
  $input = $_POST ['input'];
  $output = $_POST ['output'];
  $sample_input = $_POST ['sample_input'];
  $sample_output = $_POST ['sample_output'];
  $test_input = $_POST ['test_input'];
  $test_output = $_POST ['test_output'];
  $hint = $_POST ['hint'];
  $xing_tmp = $_POST['xing'];
  $ming_tmp = $_POST['ming'];
  $xing = $ming = "";
  $strlen = strlen($xing_tmp);
  for ($i=0; $i<$strlen; ++$i) {
    if ($xing_tmp[$i]==' ' || $xing_tmp[$i]=='\n' || $xing_tmp[$i]=='\t' || $xing_tmp[$i]=='\r') continue;
    $xing .= $xing_tmp[$i];
  }
  $xing = strtoupper($xing);
  $strlen = strlen($ming_tmp);
  for ($i=0; $i<$strlen; ++$i) {
    if ($ming_tmp[$i]==' ' || $ming_tmp[$i]=='\n' || $ming_tmp[$i]=='\t' || $ming_tmp[$i]=='\r') continue;
    $ming .= $ming_tmp[$i];
  }
  $ming = ucfirst($ming);
  $author = $xing.", ".$ming;
  if ($author[0] == ',') $author = "";
  if (strlen($author)>=2 && $author[strlen($author)-2] == ',') $author = substr($author, 0, strlen($author)-2);
  $source = $_POST ['source'];
  $spj = $_POST ['spj'];
  if (get_magic_quotes_gpc ()) {
    $title = stripslashes ( $title);
    $problemset = stripslashes($problemset);
    $time_limit = stripslashes ( $time_limit);
    $memory_limit = stripslashes ( $memory_limit);
    $description = stripslashes ( $description);
    $input = stripslashes ( $input);
    $output = stripslashes ( $output);
    $sample_input = stripslashes ( $sample_input);
    $sample_output = stripslashes ( $sample_output);
    $test_input = stripslashes ( $test_input);
    $test_output = stripslashes ( $test_output);
    $hint = stripslashes ( $hint);
    $source = stripslashes ( $source);
    $spj = stripslashes ( $spj);
    $source = stripslashes ( $source );
  }
  //echo "->".$OJ_DATA."<-"; 
  $pid=addproblem($problemset, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $author, $source, $spj, $OJ_DATA );
  $basedir = "$OJ_DATA/$pid";
  mkdir ( $basedir );
  if(strlen($sample_output)&&!strlen($sample_input)) $sample_input="0";
  if(strlen($sample_input)) mkdata($pid,"sample.in",$sample_input,$OJ_DATA);
  if(strlen($sample_output))mkdata($pid,"sample.out",$sample_output,$OJ_DATA);
  if(strlen($test_output)&&!strlen($test_input)) $test_input="0";
  if(strlen($test_input))mkdata($pid,"test.in",$test_input,$OJ_DATA);
  if(strlen($test_output))mkdata($pid,"test.out",$test_output,$OJ_DATA);

  // $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','p$pid')";
  // $mysqli->query($sql);
  $_SESSION["p$pid"]=true;
  
  echo "<a href=quixplorer/index.php?action=list&dir=$pid&order=name&srt=yes>Add More Test Data</a>";
  /*  */
?>
<?php 
  require_once("admin-footer.php")
?>