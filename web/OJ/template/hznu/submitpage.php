<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.23
   * last modified
   * by yybird
   * @2016.04.12
  **/
?>

<?php
  $title="Submit";
  if(isset($_GET['id']))
    require_once("header.php");
  else
    require_once("contest_header.php");
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
<script src="include/checksource.js"></script>
<script src="include/jquery-latest.js"></script>
<div class="am-container">
  <form id=frmSolution action="submit.php" method="post">
    <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
    <?php
    if (isset($id)) {
      echo "<h3 align='center' style='margin-top:40px; color:red;'>Problem---$id</h3>";
      echo "<input id=problem_id type='hidden'  value='<?php echo $id?>' name='id' >";
    }else{
      $PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      if ($pid>25) $pid=25;
      echo "<h3 align='center' style='margin-top:40px; color:red;'>Problem $PID[$pid] of Contest $cid</h3>";
      echo "<input id='cid' type='hidden' value='$cid' name='cid'>";
      echo "<input id='pid' type='hidden' value='$pid' name='pid'>";
    }
    ?>
    <hr />
    <div align="center">
      <span>Language:</span>
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
      <br /><br />
      <textarea style="width:80%" cols=180 rows=20 id="source" name="source"><?php echo $view_src?></textarea><br>
      <input type="submit" id='Submit' value="Submit" class="am-btn am-btn-success" onclick=do_submit();>
      <input type=reset  class="am-btn am-btn-danger" value="Reset">
    </div>
  </form>
</div>
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
        <?php if($OJ_LANG=="cn") echo "if(checksource(document.getElementById('source').value))";?>
        document.getElementById("frmSolution").submit();
     }
     var  handler_interval;
function do_test_run(){ 
     if( handler_interval) window.clearInterval( handler_interval);
          var loader="<img width=18 src=image/loader.gif>";
          var tb=window.document.getElementById('result');
          tb.innerHTML=loader;
  if(typeof(eAL) != "undefined"){   eAL.toggle("source");eAL.toggle("source");}
        

        var mark="<?php echo isset($id)?'problem_id':'cid';?>";
        var problem_id=document.getElementById(mark);
        problem_id.value=0;
        document.getElementById("frmSolution").target="testRun";
        document.getElementById("frmSolution").submit();
        document.getElementById("TestRun").disabled=true;
        document.getElementById("Submit").disabled=true;
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
</script>
<?php require_once("footer.php")?>