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
	<center>
	<?php
	if($view_content)
	echo "<center>
	<table>
			<tr>
				<td class=blue>$to_user:".htmlspecialchars(str_replace("\n\r","\n",$view_title))." </td>
			</tr>
			<tr><td><pre>". htmlspecialchars(str_replace("\n\r","\n",$view_content))."</pre>		
				</td></tr>
    </table></center>";
	
	?>
   <table><form method=post action=mail.php>
	<tr>
		<td>  To:<input name=to_user size=10 value="<?php echo $to_user?>">
			Title:<input name=title size=20 value="<?php echo $title?>">
		    <input type=submit value=<?php echo $MSG_SUBMIT?>></td>
	</tr>
	<tr><td> 
		<textarea name=content rows=10 cols=50></textarea>
	  
	 </td></tr>
	</form>
   </table>
   <table border=1>
   <tr><td>Mail ID<td>From:Title<td>Date</tr>
   <tbody>
			<?php 
			$cnt=0;
			foreach($view_mail as $row){
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
</center> 
	 
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
