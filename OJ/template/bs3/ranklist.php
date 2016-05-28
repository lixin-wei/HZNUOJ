<!DOCTYPE html>

<?php
  /**
   * This file is modified
   * by yybird
   * @2015.07.02
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
		<table align=center width=100%>
			<thead>
				<tr>
					<td colspan=3 align=left><form class="form-inline" action=userinfo.php>
						<?php echo $MSG_USER?><input class="form-control" name=user>
						<input type=submit class="form-control" value=Go>
					</form></td>
					<td colspan=10 align=right>
						<b>For All:&nbsp</b>
						<a href=ranklist.php?order_by=s>Level</a>&nbsp&nbsp&nbsp&nbsp
						<b>For HZNU:</b>&nbsp
						<a href=ranklist.php?order_by=ac>AC</a>&nbsp&nbsp
						<a href=ranklist.php?scope=d>Day</a>&nbsp&nbsp
						<a href=ranklist.php?scope=w>Week</a>&nbsp&nbsp
						<a href=ranklist.php?scope=m>Month</a>&nbsp&nbsp
						<a href=ranklist.php?scope=y>Year</a>&nbsp&nbsp
					</td>
				</tr>
				<tr class='toprow'>
					<td width=4% align=center><b><?php echo $MSG_Number?></b></td>
					<td width=10% align=center><b><?php echo $MSG_USER?></b></td>
					<td align=center><b><?php echo $MSG_NICK?></b></td>
					<td width=4% align=center><b><?php echo HZNU ?></b></td>
					<td width=4% align=center><b><?php echo ZJU ?></b></td>
					<td width=4% align=center><b><?php echo HDU ?></b></td>
					<td width=4% align=center><b><?php echo PKU ?></b></td>
					<td width=4% align=center><b><?php echo UVA ?></b></td>
					<td width=4% align=center><b><?php echo CF ?></b></td>
					<td width=4% align=center><b><?php echo Total ?></b></td>
					<!--<td width=5% align=center><b><?php echo $MSG_AC?></b>-->
					<!--<td width=5% align=center><b><?php echo $MSG_SUBMIT?></b>-->
					<!--<td width=10% align=center><b><?php echo $MSG_RATIO?></b>-->
					<td width=9% align=center><b><?php echo $MSG_LEVEL?></b></td>
					<td width=7% align=center><b><?php echo $MSG_STRENGTH?></b></td>
				</tr>
			</thead>
			<tbody>
				<?php
					$cnt=0;
					foreach ($view_rank as $row){
						if ($cnt) echo "<tr class='oddrow'>";
						else echo "<tr class='evenrow'>";
						foreach ($row as $table_cell){
							echo "<td>"."\t".$table_cell."</td>";
						}
						echo "</tr>";
						$cnt=1-$cnt;
					}
				?>
			</tbody>
		</table>
		<?php
			echo "<center>";
			for($i = 0; $i <$view_total ; $i += $page_size) {
				echo "<a href='./ranklist.php?start=" . strval ( $i ).($scope?"&scope=$scope":"") . "'>";
				echo strval ( $i + 1 );
				echo "-";
				echo strval ( $i + $page_size );
				echo "</a>&nbsp;";
				if ($i % 250 == 200)
				echo "<br>";
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
