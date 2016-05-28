<!DOCTYPE html>

<?php
  /**
   * This file is modified!
   * by yybird
   * @2015.07.06
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
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>     
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
    <?php $rank=1; ?>
    <center><h3>Contest RankList -- <?php echo $title?></h3>
    <?php 
      if(isset($_SESSION['administrator'])||isset($_SESSION['source_browser'])) {
    ?>
    [ <a href="contestrank.xls.php?cid=<?php echo $cid?>" ><?php echo $MSG_DOWNLOAD_RANK ?></a> ]
    &nbsp;&nbsp;&nbsp;
    [ <a href="contestrank.xls.php?cid=<?php echo $cid?>" ><?php echo $MSG_DOWNLOAD_RANK ?></a>]
    &nbsp;&nbsp;&nbsp;
    <?php } ?>
    [ <a href="contestrank-auto.php?cid=<?php echo $cid?>"><?php echo "Auto-scrolling" ?></a> ]

    &nbsp;&nbsp;&nbsp;
    <?php  //echo $classSet[0].$classSet[1]; ?>
    [ Choose Class
		  <select id="class">
        <option value="" <?php if ($_GET['class']=="") echo selected; ?> >显示全部</option>
        <option value="null" <?php if ($_GET['class']=="null") echo selected; ?> >其它</option>
        <!-- don't remove "其它" option to for loop, if both null and "null" exist, there will occur two options -->
        <?php 
          $sz = count($classSet);
          //echo $sz;
          for ($i=0; $i<$sz; $i++) {
            if ($classSet[$i]==null || $classSet[$i]=="null") continue; 
            else if ($classSet[$i]=="cs151") $className = "计算机151";
            else if ($classSet[$i]=="cs152") $className = "计算机152";
            else if ($classSet[$i]=="cs153") $className = "计算机153";
            else if ($classSet[$i]=="cs154") $className = "计算机154";
            else if ($classSet[$i]=="se151") $className = "软件工程151";
            else if ($classSet[$i]=="se152") $className = "软件工程152";
            else if ($classSet[$i]=="iot151") $className = "物联网151";
            else if ($classSet[$i]=="iot152") $className = "物联网152";
            else if ($classSet[$i]=="cs141") $className = "计算机141";
            else if ($classSet[$i]=="cs142") $className = "计算机142";
            else if ($classSet[$i]=="cs143") $className = "计算机143";
            else if ($classSet[$i]=="cs144") $className = "计算机144";
            else if ($classSet[$i]=="se141") $className = "软件工程141";
            else if ($classSet[$i]=="se142") $className = "软件工程142";
            else if ($classSet[$i]=="iot141") $className = "物联网141";
        ?>
            <option value="<?php echo $classSet[$i]; ?>" <?php if ($_GET['class']==$classSet[$i]) echo selected; ?> ><?php echo $className; ?></option>
        <?php
          }
        ?>
<!--
				<option value="cs151" <?php if ($_GET['class']=="cs151") echo selected; ?> >计算机151</option>
				<option value="cs152" <?php if ($_GET['class']=="cs152") echo selected; ?>>计算机152</option>
				<option value="cs153" <?php if ($_GET['class']=="cs153") echo selected; ?>>计算机153</option>
				<option value="cs154" <?php if ($_GET['class']=="cs154") echo selected; ?>>计算机154</option>
				<option value="se151" <?php if ($_GET['class']=="se151") echo selected; ?>>软件工程151</option>
				<option value="se152" <?php if ($_GET['class']=="se152") echo selected; ?>>软件工程152</option>
				<option value="iot151" <?php if ($_GET['class']=="iot151") echo selected; ?>>物联网151</option>
-->
			</select>
    ]
    <script type="text/javascript">
      var oSelect=document.getElementById("class");
      oSelect.onchange=function() { //当选项改变时触发
        var valOption=this.options[this.selectedIndex].value; //获取option的value
        var url = window.location.search;
        var cid = url.substr(url.indexOf('=')+1,4);
        // alert(cid);
        var url = window.location.pathname+"?cid="+cid+"&class="+valOption;
        window.location.href = url;
        // var txtOption=this.options[this.selectedIndex].innerHTML; //获取option中间的文本
        // alert(txtOption);
      }
    </script>
<?php
    if($OJ_MEMCACHE){
?>
      <a href="contestrank2.php?cid=<?php echo $cid?>" >Replay</a>
<?php
    }
 ?>
    </center>
    <table id='rank' >
      <thead>
        <tr class=toprow align=center>
          <th class="{sorter:'false'}"><center><?php echo $MSG_RANK ?></center>
            <th><center><?php echo $MSG_USER ?></center></th>
            <!-- <th><center><?php echo $MSG_REAL_NAME ?></center></th> -->
            <th><center><?php echo $MSG_NICK ?></center></th>
            <th><center><?php echo $MSG_SOLVED."&nbsp" ?></center></th>
            <th><center><?php echo $MSG_PENALTY ?></center></th>
<?php
            for ($i=0;$i<$pid_cnt;$i++) // 输出所有题目号作为表头
              echo "<td width='5%'><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
?>
        </tr>
      </thead>
    
<?php
      for ($i=0;$i<$user_cnt;$i++){
        if ($i&1) echo "<tr class=oddrow align=center>\n";
        else echo "<tr class=evenrow align=center>\n";
        echo "<td>";
        $uuid=$U[$i]->user_id;
        $nick=$U[$i]->nick;
        if($nick[0]!="*")
          echo $rank++;
        else
          echo "*";
        $usolved=$U[$i]->solved;
        if(isset($_GET['user_id'])&&$uuid==$_GET['user_id']) echo "<td bgcolor=#ffff77>";
        else echo"<td>";
        echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
        // echo "<td>".$U[$i]->real_name;
        echo "<td><a href=userinfo.php?user=$uuid>".htmlentities($U[$i]->nick,ENT_QUOTES,"UTF-8")."</a>";
        echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
        echo "<td>".sec2str($U[$i]->time);
        for ($j=0;$j<$pid_cnt;$j++){
          $bg_color="eeeeee";
          if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
            $aa=0x33+$U[$i]->p_wa_num[$j]*32;
            $aa=$aa>0xaa?0xaa:$aa;
            $aa=dechex($aa);
            $bg_color="$aa"."ff"."$aa";
            //$bg_color="aaffaa";
            if($uuid==$first_blood[$j]){
              $bg_color="aaaaff";
            }
          }else if(isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
            $aa=0xaa-$U[$i]->p_wa_num[$j]*10;
            $aa=$aa>16?$aa:16;
            $aa=dechex($aa);
            $bg_color="ff$aa$aa";
          }
          echo "<td class=well style='background-color:#$bg_color'>";
          if(isset($U[$i])){
            if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
              echo sec2str($U[$i]->p_ac_sec[$j]);
            if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
              echo "(-".$U[$i]->p_wa_num[$j].")";
          }
        }
        echo "</tr>\n";
      }
      echo "</tbody></table>";
?>
      </div>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>      
    <script type="text/javascript" src="include/jquery.tablesorter.js"></script>
    <script type="text/javascript">
$(document).ready(function()
{
$.tablesorter.addParser({
// set a unique id
id: 'punish',
is: function(s) {
// return false so this parser is not auto detected
return false;
},
format: function(s) {
// format your data for normalization
var v=s.toLowerCase().replace(/\:/,'').replace(/\:/,'').replace(/\(-/,'.').replace(/\)/,'');
//alert(v);
v=parseFloat('0'+v);
return v>1?v:v+Number.MAX_VALUE-1;
},
// set type, either numeric or text
type: 'numeric'
});
$("#rank").tablesorter({
headers: {
4: {
sorter:'punish'
}
<?php
for ($i=0;$i<$pid_cnt;$i++){
echo ",".($i+5).": { ";
echo " sorter:'punish' ";
echo "}";
}
?>
}
});
}
);
</script>
<script>
function getTotal(rows){
var total=0;
for(var i=0;i<rows.length&&total==0;i++){
try{
total=parseInt(rows[rows.length-i].cells[0].innerHTML);
if(isNaN(total)) total=0;
}catch(e){
}
}
return total;
}
function metal(){
var tb=window.document.getElementById('rank');
var rows=tb.rows;
try{
var total=getTotal(rows);
//alert(total);
for(var i=1;i<rows.length;i++){
var cell=rows[i].cells[0];
var acc=rows[i].cells[3];
var ac=parseInt(acc.innerText);
if (isNaN(ac)) ac=parseInt(acc.textContent);
if(cell.innerHTML!="*"&&ac>0){
var r=parseInt(cell.innerHTML);
if(r==1){
cell.innerHTML="Winner";
//cell.style.cssText="background-color:gold;color:red";
cell.className="badge btn-warning";
}
if(r>1&&r<=total*.05+1)
cell.className="badge btn-warning";
if(r>total*.05+1&&r<=total*.20+1)
cell.className="badge";
if(r>total*.20+1&&r<=total*.45+1)
cell.className="badge btn-danger";
if(r>total*.45+1&&ac>0)
cell.className="badge badge-info";
}
}
}catch(e){
//alert(e);
}
}
metal();
</script>
<style>
.well{
   background-image:none;
   padding:1px;
}
td{
   white-space:nowrap;
}
</style>
  </body>
</html>
