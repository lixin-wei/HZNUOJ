<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $view_title?></title>
        <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
        <?php
        if(isset($_GET['id']))
                require_once("oj-header.php");
        else
                require_once("contest-header.php");
       
        ?>
<div id=main>
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
                ,word_wrap: true
                ,language: "en"
                ,syntax: "cpp"  
                        ,font_size: "8"
                ,syntax_selection_allow: "basic,c,cpp,java,pas,perl,php,python,ruby"
                        ,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,syntax_selection,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"          
        });
</script>
<?php }?>
<script src="include/checksource.js">

</script>
<form id=frmSolution action="submit.php" method="post"
<?php if($OJ_LANG=="cn"){?>
 onsubmit="return checksource(document.getElementById('source').value);"
<?php }?>
 >
<?php if (isset($id)){?>
Problem <span class=blue><b><?php echo $id?></b></span>
<input id=problem_id type='hidden'  value='<?php echo $id?>' name="id" ><br>
<?php }else{
$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
if ($pid>25) $pid=25;
?>
Problem <span class=blue><b><?php echo $PID[$pid]?></b></span> of Contest <span class=blue><b><?php echo $cid?></b></span><br>
<input id="cid" type='hidden' value='<?php echo $cid?>' name="cid">
<input id="pid" type='hidden' value='<?php echo $pid?>' name="pid">
<?php }?>
Language:
<select id="language" name="language">
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

<textarea style="width:80%" cols=180 rows=20 id="source" name="source"><?php echo $view_src?></textarea><br>
<?php echo $MSG_Input?>:<textarea style="width:30%" cols=40 rows=5 id="input_text" name="input_text" ><?php echo $view_sample_input?></textarea>
<?php echo $MSG_Output?>:
  <textarea style="width:30%" cols=40 rows=5 id="out" name="out" >SHOULD BE:
<?php echo $view_sample_output?>
</textarea>

<br>

<input id=Submit class="btn btn-info" type=button value="<?php echo $MSG_SUBMIT?>"  onclick=do_submit();>
<input id=TestRun class="btn btn-info"  type=button value="<?php echo $MSG_TR?>" onclick=do_test_run();><span  class="btn"  id=result>状态</span>
<input type=reset  class="btn btn-danger" value="重置">
</form>

<iframe name=testRun width=0 height=0 src="about:blank"></iframe>
</center>
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
     var tb=window.document.getElementById('out');
     var r=xmlhttp.responseText;
     tb.innerHTML=""+r;
    }
  }
xmlhttp.open("GET","status-ajax.php?tr=1&solution_id="+solution_id,true);
xmlhttp.send();
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
//     alert(r);
//     alert(judge_result[r]);
      var loader="<img width=18 src=image/loader.gif>";  
     var tag="span";
     if(ra[0]<4) tag="span disabled=true";
     else tag="a";
     tb.innerHTML="<"+tag+" href='reinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</"+tag+">";
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

if(typeof(eAL) != "undefined"){   eAL.toggle("source");eAL.toggle("source");}


        var mark="<?php echo isset($id)?'problem_id':'cid';?>";
        var problem_id=document.getElementById(mark);
       
        if(mark=='problem_id')
                problem_id.value='<?php echo $id?>';
        else    
                problem_id.value='<?php echo $cid?>';
       
        document.getElementById("frmSolution").target="_self";
        document.getElementById("frmSolution").submit();
     }
     function do_test_run(){
          var loader="<img width=18 src=image/loader.gif>";
          var tb=window.document.getElementById('result');
          tb.innerHTML=loader;
  if(typeof(eAL) != "undefined"){   eAL.toggle("source");eAL.toggle("source");}
        

        var mark="<?php echo isset($id)?'problem_id':'';?>";
        var problem_id=document.getElementById(mark);
        problem_id.value=0;
        document.getElementById("frmSolution").target="testRun";
        document.getElementById("frmSolution").submit();
        document.getElementById("TestRun").disabled=true;
        document.getElementById("Submit").disabled=true;
        count=20;
        window.setTimeout("resume();",1000);
       
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
                window.cleanTimeout();
        }else{
        	s.value="<?php echo $MSG_SUBMIT?>("+count+")";
        	t.value="<?php echo $MSG_TR?>("+count+")";
                window.setTimeout("resume();",1000);
        
        }
  }
</script>
<div id=foot>
        <?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
