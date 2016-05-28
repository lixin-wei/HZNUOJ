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
<?php 
            }
?>
<?php
            foreach($chart_data_ac as $k=>$d){
?>
              d2.push([<?php echo $k?>, <?php echo $d?>]);
<?php 
            }
?>
            //var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];
            // a null signifies separate line segments
            var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
            $.plot($("#submission"), [
              {label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
              {label:"<?php echo $MSG_AC?>",data:d2,bars:{show:true}} ],{
              xaxis: {
                mode: "time"
                //, max:(new Date()).getTime()
                //,min:(new Date()).getTime()-100*24*3600*1000
              },
              grid: { backgroundColor: { colors: ["#fff", "#333"] } }
            });
            });
            //alert((new Date()).getTime());
        </script>
        <center>
          <table class="table table-striped" id='statics' width=70%>
            <caption>
<?php
              $prefix = substr($level, 0,6);
              $honor = "";
              if ($prefix == "斗之") $prefix = "斗之气";
              if ($prefix == "大斗") $prefix = "大斗师";
              if ($prefix=="斗王" || $prefix=="斗皇" || $prefix=="斗宗" || $prefix=="斗尊" || $prefix=="斗圣" || $prefix=="斗帝") {
                $honor .= "强者";
              }
?>
              <?php echo "<b>[ <font color='$color'>$prefix</font><font color='red'> $honor</font> ] &nbsp&nbsp".$user."--".htmlspecialchars($nick)."</b>" ?>
              <?php echo "&nbsp&nbsp&nbsp"; ?>
              <?php echo "<a href=mail.php?to_user=$user>$MSG_MAIL</a>"; ?>
              <?php echo "&nbsp&nbsp&nbsp"; ?>
              <?php if (isset($_SESSION['administrator']) || isset($_SESSION['source_browser'])) echo "<font color=red>(右侧信息仅管理员可见)</font> &nbsp Real Name: &nbsp<font color=red>".$real_name."</font>"."&nbsp&nbsp&nbsp Class: &nbsp<font color=red>".$class."</font>"; ?>
            </caption>
            <tr>
              <td width=10%><?php echo $MSG_Number?></td>
              <td width=20% align=center><?php echo $Rank?></td>
              <td align=center>Solved Problems List</td>
            </tr>
            <tr>
              <td><?php echo $MSG_SOLVED ?></td>
              <td align=center>
                HZNU: <a href='status.php?user_id=<?php echo $user?>&jresult=4'><?php echo $AC?></a><br />
                ZJU: <?php echo $ZJU?><br />
                HDU: <?php echo $HDU?><br />
                CF: <?php echo $CF?>
              </td>
              <td rowspan=14 align=center>
                <script language='javascript'>
                  function p(id) { 
                    document.write("<a href=problem.php?id="+id+">"+id+" </a>"); 
                  }
                  function pvj(pid, oj, vjid) {
                    if (oj=='CodeForces') oj = 'CF';
                    document.write("<a href='http://vj.hsacm.com/problem/viewProblem.action?id="+vjid+"'>"+oj+pid+" </a>&nbsp");
                  }
                  function hznu() {
                    document.write("<h4>HZNUOJ:<br></h4>");
                  }
                  function vjudge() {
                    document.write("<h4>Other OJ:<br></h4>");
                  }
                  function blank() {
                    document.write("<hr>");
                  }
<?php 
                  echo "hznu();";
                  // 查找HZNUOJ题目
                  $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id` ASC";
                  if (!($result=mysql_query($sql)))
                    echo mysql_error();
                  while ($row=mysql_fetch_array($result))
                    echo "p($row[0]);";
                  mysql_free_result($result);


                  echo "blank();";
                  echo "vjudge();";

                  // 查找vjudge上的题目
                  $connvj = mysql_connect($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,true);
                  if (!$connvj) die('Could not connect: ' . mysql_error());
                  mysql_select_db("vhoj", $connvj);
                  mysql_query("set names utf8");
                  $sql = "SELECT DISTINCT C_ORIGIN_PROB,C_ORIGIN_OJ,C_PROBLEM_ID FROM t_submission WHERE C_USERNAME='$user_mysql' ORDER BY C_ORIGIN_OJ,C_ORIGIN_PROB";
                  if (!($result=mysql_query($sql)))
                    echo mysql_error();
                  while ($row=mysql_fetch_object($result)) {
                    echo "pvj('$row->C_ORIGIN_PROB','$row->C_ORIGIN_OJ','$row->C_PROBLEM_ID');";
                  }
?>
                </script>

                <div id=submission style="width:600px;height:300px" ></div>
              </td>
            </tr>
            <tr>
              <td><?php echo $MSG_LEVEL; ?></td>
              <td align=center><font color='<?php echo $color?>'><b><?php echo $level; ?></b></font></td>
            </tr>
            <tr>
              <td><?php echo $MSG_STRENGTH; ?></td>
              <td align=center><?php echo round($strength); ?></td>
            </tr>
            <tr>
              <td><?php echo $MSG_SUBMIT?></td>
              <td align=center><a href='status.php?user_id=<?php echo $user?>'><?php echo $Submit?></a></td>
            </tr>
<?php
        foreach($view_userstat as $row){
          echo "
            <tr >
              <td>".$jresult[$row[0]]."</td>
              <td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a></td>
            </tr>";
        }
?>
            <tr id='pie'>
              <td>Statistics<td>
              <div id='PieDiv' style='position:relative;height:105px;width:120px;'></div>
            </tr>
  <script language="javascript">
  var y= new Array ();
  var x = new Array ();
  var dt=document.getElementById("statics");
  var data=dt.rows;
  var n;
  for(var i=5;dt.rows[i].id!="pie";i++){
  n=dt.rows[i].cells[0];
  n=n.innerText || n.textContent;
  x.push(n);
  n=dt.rows[i].cells[1].firstChild;
  n=n.innerText || n.textContent;
  //alert(n);
  n=parseInt(n);
  y.push(n);
  }
  var mypie= new Pie("PieDiv");
  mypie.drawPie(y,x);
  //mypie.clearPie();
  </script>
<tr ><td>School:<td align=center><?php echo $school?></tr>
<tr ><td>Email:<td align=center><?php echo $email?></tr>
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
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>	    
  </body>
</html>
