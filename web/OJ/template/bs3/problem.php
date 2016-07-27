<!DOCTYPE html>

<?php
  /**
   * This file is modified
   * by yybird
   * @2015.07.03
  **/
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php");?>	    
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
	
<?php
if ($pr_flag){
echo "<title>$MSG_PROBLEM $row->problem_id. -- $row->title</title>";
echo "<center><h2>$id: $row->title</h2>";
}else{
$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$id=$row->problem_id;
echo "<title>$MSG_PROBLEM $PID[$pid]: $row->title </title>";
echo "<center><h2>$MSG_PROBLEM $PID[$pid]: $row->title</h2>";
}
echo "<span class=green>$MSG_Time_Limit: </span>$row->time_limit Sec&nbsp;&nbsp;";
echo "<span class=green>$MSG_Memory_Limit: </span>".$row->memory_limit." MB";
if ($row->spj) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
echo "<br><span class=green>$MSG_SUBMIT: </span>".$row->submit."&nbsp;&nbsp;";
echo "<span class=green>$MSG_SOLVED: </span>".$row->accepted."&nbsp;&nbsp;";
echo "<span class=green>$MSG_SCORES: </span>".$row->scores."<br>";
if ($pr_flag){
echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
}else{
echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
}
echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";
echo "[<a href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>]";
if(isset($_SESSION['administrator']) || isset($_SESSION['problem_editor'])){
require_once("include/set_get_key.php");
if (isset($_SESSION['administrator'])) {
?>

[<a href="admin/problem_edit.php?id=<?php echo $id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>]
<?php } ?>
[<a href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>]
<?php
}
echo "</center>";
echo "<h2><b><font color='#0000cd'>$MSG_Description</font></b></h2><div class=content>".$row->description."</div>";
echo "<h2><b><font color='#0000cd'>$MSG_Input</font></b></h2><div class=content>".$row->input."</div>";
echo "<h2><b><font color='#0000cd'>$MSG_Output</font></b></h2><div class=content>".$row->output."</div>";
$sinput=str_replace("<","&lt;",$row->sample_input);
$sinput=str_replace(">","&gt;",$sinput);
$soutput=str_replace("<","&lt;",$row->sample_output);
$soutput=str_replace(">","&gt;",$soutput);
if($sinput ||true) {
echo "<h2><b><font color='#0000cd'>$MSG_Sample_Input</font></b></h2>
<pre class=content><span class=sampledata>".($sinput)."</span></pre>";
}
if($soutput ||true){
echo "<h2><b><font color='#0000cd'>$MSG_Sample_Output</font></b></h2>
<pre class=content><span class=sampledata>".($soutput)."</span></pre>";
}
if ($pr_flag||true)
echo "<h2><b><font color='#0000cd'>$MSG_HINT</font></b></h2>
<div class=content><p>".nl2br($row->hint)."</p></div>";
if ($pr_flag||true)
echo "<h2><b><font color='#0000cd'>Author</font></b></h2>
<div class=content><p><a href='problemset.php?search=$row->author'>".nl2br($row->author)."</a></p></div>";
if ($pr_flag)
echo "<h2><b><font color='#0000cd'>$MSG_Source</font></b></h2>
<div class=content><p><a href='problemset.php?search=$row->source'>".nl2br($row->source)."</a></p></div>";
echo "<center>";
if ($pr_flag){
echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
}else{
echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
}
echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";
echo "[<a href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>]";
if(isset($_SESSION['administrator']) || isset($_SESSION['problem_editor'])){
require_once("include/set_get_key.php");
if (isset($_SESSION['administrator'])) {
?>
[<a href="admin/problem_edit.php?id=<?php echo $id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>]
<?php } ?>
[<a href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>]
<?php
}
echo "</center>";
?>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>	    
  </body>
</html>
