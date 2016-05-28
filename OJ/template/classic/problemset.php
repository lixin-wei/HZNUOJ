<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>
<script type="text/javascript" src="include/jquery-latest.js"></script> 
<script type="text/javascript" src="include/jquery.tablesorter.js"></script> 
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#problemset").tablesorter(); 
    } 
); 
</script>

<h3 align='center'>
        <?php
    for ($i=1;$i<=$view_total_page;$i++){
		if ($i>1) echo '&nbsp;';
		if ($i==$page) echo "<span class=red>$i</span>";
		else echo "<a href='problemset.php?page=".$i."'>".$i."</a>";
	}
        ?>

</h3><center>
	<table id='problemset' width='90%'>
		<thead>
			<tr align='center' class='evenrow'><td width='5'></td>
			<td width='10%' colspan='1'>
				<form action=problem.php>
					Problem ID<input type='text' name='id' size=5><input type='submit' value='GO' ></form>
			</td>
			<td width='90%' colspan='4'>
				<form><?php echo $MSG_SEARCH?>
				<input type='text' name='search'><input type='submit' value='<?php echo $MSG_SEARCH?>' ></form>
			</td></tr>
			<tr align=center class='toprow'>
				<td width='5'>
				<td style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" width=10%><A><?php echo $MSG_PROBLEM_ID?></A>
				<td width='60%'><?php echo $MSG_TITLE?></td>
				<td width='10%'><?php echo $MSG_SOURCE?></td>
				<td style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='5%'><A><?php echo $MSG_AC?></A></td>
				<td style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='5%'><A><?php echo $MSG_SUBMIT?></A></td>
			</tr>
			</thead>
			<tbody>
			<?php 
			$cnt=0;
			foreach($view_problemset as $row){
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
			</table></center>
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
