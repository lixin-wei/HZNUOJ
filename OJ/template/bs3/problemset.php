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
<h3 align='center'>
<?php
  for ($i=1;$i<=$view_total_page;$i++){
    if ($i>1) echo '&nbsp;';
    if ($i==$page) echo "<span class=red>$i</span>";
    else echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."&OJ=".$OJ."'>".$i."</a>";
    if ($i%25 == 0) echo "<br>";
  }
?>
</h3><center>
<table>
<tr align='center' class='evenrow'><td width='5'></td>
<td  colspan='1'>
<?php
  if (basename($_SERVER['PHP_SELF']) == "problemset.php") {
?>
    <form class=form-inline action=problem.php>
      <input class="form-control search-query" type='text' name='id'  placeholder="Problem ID">
      <button class="form-control" type='submit' >Go</button>
    </form>
<?php
  }
?>
</td>
<td  colspan='1'>
<form class="form-search form-inline" action=<?php basename($_SERVER['PHP_SELF']) ?>>
  <input type="hidden" name="OJ" value=<?php echo $OJ ?>>
  <input type="text" name="search" class="form-control search-query" placeholder="Keywords Title or Source">
  <button type="submit" class="form-control"><?php echo $MSG_SEARCH?></button>
</form>
</td></tr>
</table>
<?php
  $space = "";
  for ($i=0; $i<30; $i++) $space.="&nbsp";
?>
<table id='problemset' width='90%'class='table table-striped'>
<thead>
<tr align=center class='toprow'>
<th width='5'></th>
<th width='5%'><center><?php echo $MSG_PROBLEM_ID?></center></th>
<th><?php echo $space.$MSG_TITLE?></th>
<th width='20%'><center><?php echo $MSG_SOURCE?></center></th>
<th style="cursor:hand" width='4%' ><center><?php echo $MSG_AC?></center></th>
<th style="cursor:hand" width='5%' ><center><?php echo $MSG_SUBMIT?></center></th>
<th style="cursor:hand" width='6%' ><center><?php echo $MSG_SCORES?></center></th>
</tr>
</thead>
<tbody>
<?php

  $cnt=0;
  $prob_num = count($view_problemset);
  if ($OJ=="CodeForces" || $OJ=="UVA") {
    for ($i=(intval($page)-1)*100; $i<intval($page)*100; $i++) {
      for ($j=0; $j<7; $j++) {
        echo "<td>";
        echo "\t".$view_problemset[$i][$j];
        echo "</td>";
      }
      echo "</tr>";
      $cnt=1-$cnt;
      if ($i == $prob_num) break;
    }
  } else {
    //echo $view_problemset[0][4];
  /*  $row_num = count($view_problemset);
  //  echo $row_num;
    for ($i=0; $i<$row_num; $i++) {
      if ($cnt) echo "<tr class='oddrow'>";
      else echo "<tr class='evenrow'>";
      for ($j=0; $j<7; $j++) {
        echo "<td>";
        echo "\t".$view_problemset[$i][$j];
        echo "</td>";
      }
      echo "</tr>";
      $cnt=1-$cnt;
    }*/
      
    foreach($view_problemset as $row){
      if ($cnt) echo "<tr class='oddrow'>";
      else echo "<tr class='evenrow'>";
      foreach($row as $table_cell){
        echo "<td>";
        echo "\t".$table_cell;
        echo "</td>";
      }
      echo "</tr>";
      $cnt=1-$cnt;
    }
  }
?>

</tbody>
</table></center>
<h3 align='center'>
<?php
  for ($i=1;$i<=$view_total_page;$i++){
    if ($i>1) echo '&nbsp;';
    if ($i==$page) echo "<span class=red>$i</span>";
    else echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."&OJ=".$OJ."'>".$i."</a>";
    if ($i%25 == 0) echo "<br>";
  }
?>
</h3><center>
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
$("#problemset").tablesorter();
}
);
</script>
</body>
</html>
