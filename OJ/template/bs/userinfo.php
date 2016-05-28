<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>

<body>
	
<script type="text/javascript" src="include/wz_jsgraphics.js"></script>
<script type="text/javascript" src="include/pie.js"></script>
<script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script>
    <script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
    <script type="text/javascript">
$(function () {
    var d1 = [];
    var d2 = [];
    <?php 
       foreach($chart_data_all as $k=>$d){
    ?>
        d1.push([<?php echo $k?>, <?php echo $d?>]);
	<?php }?>
    <?php 
       foreach($chart_data_ac as $k=>$d){
    ?>
        d2.push([<?php echo $k?>, <?php echo $d?>]);
	<?php }?>
          //var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

    // a null signifies separate line segments
    var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
    
  $.plot($("#submission"), [ 
    {label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
    {label:"<?php echo $MSG_AC?>",data:d2,bars:{show:true}} ],{
    
        
            xaxis: {
              mode: "time"
              //,    max:(new Date()).getTime()
              //,min:(new Date()).getTime()-100*24*3600*1000
            },
        grid: {
            backgroundColor: { colors: ["#fff", "#333"] }
        }
        });
});
      //alert((new Date()).getTime());
</script>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>

<center>
<table class="table table-striped" id=statics width=70%>
<caption>
<?php echo $user."--".htmlspecialchars($nick)?>
<?php
	echo "<a href=mail.php?to_user=$user>$MSG_MAIL</a>";
?>
</caption>
<tr bgcolor=#D7EBFF><td width=15%><?php echo $MSG_Number?><td width=25% align=center><?php echo $Rank?><td width=70% align=center>Solved Problems List</tr>
<tr bgcolor=#D7EBFF><td><?php echo $MSG_SOVLED?><td align=center><a href='status.php?user_id=<?php echo $user?>&jresult=4'><?php echo $AC?></a>
<td rowspan=14 align=center>
<script language='javascript'>
function p(id){document.write("<a href=problem.php?id="+id+">"+id+" </a>");}
<?php $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id` ASC";	
if (!($result=mysql_query($sql))) echo mysql_error();
while ($row=mysql_fetch_array($result))
	echo "p($row[0]);";
mysql_free_result($result);
?>
</script>
<div id=submission style="width:600px;height:300px" ></div>

  </td>
</tr>
<tr bgcolor=#D7EBFF><td><?php echo $MSG_SUBMIT?><td align=center><a href='status.php?user_id=<?php echo $user?>'><?php echo $Submit?></a></tr>
<?php 
	foreach($view_userstat as $row){
		
		//i++;
		echo "<tr bgcolor=#D7EBFF><td>".$jresult[$row[0]]."<td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a></tr>";
	}
	
	
//}
echo "<tr id=pie bgcolor=#D7EBFF><td>Statistics<td><div id='PieDiv' style='position:relative;height:105px;width:120px;'></div></tr>";

?>
<script language="javascript">
	var y= new Array ();
	var x = new Array ();
	var dt=document.getElementById("statics");
	var data=dt.rows;
	var n;
	for(var i=3;dt.rows[i].id!="pie";i++){
			n=dt.rows[i].cells[0];
			n=n.innerText || n.textContent;
			x.push(n);
			n=dt.rows[i].cells[1].firstChild;
			n=n.innerText || n.textContent;
			//alert(n);
			n=parseInt(n);
			y.push(n);
	}
	var mypie=  new Pie("PieDiv");
	mypie.drawPie(y,x);
	//mypie.clearPie();

</script> 


<tr bgcolor=#D7EBFF><td>School:<td align=center><?php echo $school?></tr>
<tr bgcolor=#D7EBFF><td>Email:<td align=center><?php echo $email?></tr>
</table>
<?php
 if(isset($_SESSION['administrator'])){

	 ?><table border=1><tr class=toprow><td>UserID<td>Password<td>IP<td>Time</tr>
	 <tbody>
			<?php 
			$cnt=0;
			foreach($view_userinfo as $row){
				if ($cnt) 
					echo "<tr class='oddrow'>";
				else
					echo "<tr class='evenrow'>";
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}
				
				echo "</tr>";
				
				$cnt=1-$cnt;
			}
			?>
			</tbody>
			</table>
	 <?php

 }

?>
</center>
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
