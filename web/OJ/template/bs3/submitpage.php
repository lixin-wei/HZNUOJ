<!DOCTYPE html>
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
 <center>
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
{
$OJ_EDITE_AREA=false;
}
if($OJ_EDITE_AREA){
?>
<script language="Javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>
<script language="Javascript" type="text/javascript">
editAreaLoader.init({
id: "source"
,start_highlight: true
,allow_resize: "both"
,allow_toggle: true
,word_wrap: false
,language: "en"
,syntax: "cpp"
,font_size: "12"
,syntax_selection_allow: "basic,c,cpp,java,pas,perl,php,python,ruby"
,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,syntax_selection,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
});
</script>
<?php }?>
<script src="include/checksource.js"></script>
<form id=frmSolution action="submit.php" method="post"
<?php if($OJ_LANG=="cn"){?>
onsubmit="return checksource(document.getElementById('source').value);"
<?php }?>
>
<?php if (isset($id)){?>
Problem <span class=blue><b><?php echo $id?></b></span>
<input id=problem_id type='hidden' value='<?php echo $id?>' name="id" ><br>
<?php }else{
$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//if ($pid>25) $pid=25;
?>
Problem <span class=blue><b><?php echo chr($pid+ord('A'))?></b></span> of Contest <span class=blue><b><?php echo $cid?></b></span><br>
<input id="cid" type='hidden' value='<?php echo $cid?>' name="cid">
<input id="pid" type='hidden' value='<?php echo $pid?>' name="pid">
<?php }?>
Language:
<!--
<select id="language" name="language" onchange="reloadtemplate(this);" >
-->
<select id="language" name="language"" >
<?php
$lang_count=count($language_ext);
if(isset($_GET['langmask']))
$langmask=$_GET['langmask'];
else
$langmask=$OJ_LANGMASK;
$lang=(~((int)$langmask))&((1<<($lang_count))-1);
if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
else $lastlang=0;
for($i=0;$i<$lang_count;$i++){
if($lang&(1<<$i))
echo"<option value=$i ".( $lastlang==$i?"selected":"").">
".$language_name[$i]."
</option>";
}
?>
</select>
<br>
<textarea style="width:80%" cols=180 rows=20 id="source" name="source"><?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea><br>
<?php echo $MSG_Input?>:<textarea style="width:30%" cols=40 rows=5 id="input_text" name="input_text" ><?php echo $view_sample_input?></textarea>
<?php echo $MSG_Output?>:
<textarea style="width:30%" cols=40 rows=5 id="out" name="out" >SHOULD BE:
<?php echo $view_sample_output?>
</textarea>
<br>
<input id=Submit class="btn btn-info" type=button value="<?php echo $MSG_SUBMIT?>" onclick=do_submit();>
<input id=TestRun class="btn btn-info" type=button value="<?php echo $MSG_TR?>" onclick=do_test_run();><span class="btn" id=result>状态</span>
<input type=reset class="btn btn-danger" value="重置">
</form>
<iframe name=testRun width=0 height=0 src="about:blank"></iframe>
</center>
     </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>	    
 <script>
var sid=0;
var i=0;
var judge_result=[<?php
foreach($judge_result as $result){
echo "'$result',";
}
?>''];
function print_result(solution_id)
{
sid=solution_id;
$("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);
}
function fresh_result(solution_id)
{
sid=solution_id;
var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
var tb=window.document.getElementById('result');
var r=xmlhttp.responseText;
var ra=r.split(",");
// alert(r);
// alert(judge_result[r]);
var loader="<img width=18 src=image/loader.gif>";
var tag="span";
if(ra[0]<4) tag="span disabled=true";
else tag="a";
{
	if(ra[0]==11)
	
	tb.innerHTML="<"+tag+" href='ceinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</"+tag+">";
	else
	tb.innerHTML="<"+tag+" href='reinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</"+tag+">";
}
if(ra[0]<4)tb.innerHTML+=loader;
tb.innerHTML+="Memory:"+ra[1]+"kb&nbsp;&nbsp;";
tb.innerHTML+="Time:"+ra[2]+"ms";
if(ra[0]<4)
window.setTimeout("fresh_result("+solution_id+")",2000);
else
window.setTimeout("print_result("+solution_id+")",2000);
}
}
xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
xmlhttp.send();
}
function getSID(){
var ofrm1 = document.getElementById("testRun").document;
var ret="0";
if (ofrm1==undefined)
{
ofrm1 = document.getElementById("testRun").contentWindow.document;
var ff = ofrm1;
ret=ff.innerHTML;
}
else
{
var ie = document.frames["frame1"].document;
ret=ie.innerText;
}
return ret+"";
}
var count=0;
function do_submit(){
if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
var mark="<?php echo isset($id)?'problem_id':'cid';?>";
var problem_id=document.getElementById(mark);
if(mark=='problem_id')
problem_id.value='<?php echo $id?>';
else
problem_id.value='<?php echo $cid?>';
document.getElementById("frmSolution").target="_self";
<?php if($OJ_LANG=="cn") echo "if(checksource(document.getElementById('source').value))";?>
document.getElementById("frmSolution").submit();
}
var handler_interval;
function do_test_run(){
if( handler_interval) window.clearInterval( handler_interval);
var loader="<img width=18 src=image/loader.gif>";
var tb=window.document.getElementById('result');
tb.innerHTML=loader;
if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
var mark="<?php echo isset($id)?'problem_id':'cid';?>";
var problem_id=document.getElementById(mark);
problem_id.value=-problem_id.value;
document.getElementById("frmSolution").target="testRun";
document.getElementById("frmSolution").submit();
document.getElementById("TestRun").disabled=true;
document.getElementById("Submit").disabled=true;
problem_id.value=-problem_id.value;
count=20;
handler_interval= window.setTimeout("resume();",1000);
}
function resume(){
count--;
var s=document.getElementById('Submit');
var t=document.getElementById('TestRun');
if(count<0){
s.disabled=false;
t.disabled=false;
s.value="<?php echo $MSG_SUBMIT?>";
t.value="<?php echo $MSG_TR?>";
if( handler_interval) window.clearInterval( handler_interval);
}else{
s.value="<?php echo $MSG_SUBMIT?>("+count+")";
t.value="<?php echo $MSG_TR?>("+count+")";
window.setTimeout("resume();",1000);
}
}
function reloadtemplate(lang){
   document.cookie="lastlang="+lang.value;
   //alert(document.cookie);
   var url=window.location.href;
   var i=url.indexOf("sid=");
   if(i!=-1) url=url.substring(0,i-1);
   if(confirm("Do you want to reload template?\n You may lost all code that you've typed here!"))
        document.location.href=url;
}

</script>
  </body>
</html>
