<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.03.24
  **/
?>

<?php $title="Contest";?>
<?php include "template/hznu/contest_header.php"; ?>
<div class="am-container">
  <hr / style="margin-top:30px;">
  <h3>Contest<?php echo $view_cid?> - <?php echo $view_title ?></h3>
  <pre><?php echo $view_description?></pre>
  <h3 class='am-text-center'>
    <?php // 判断Start Time有没过去，以此确认字体颜色
      if ($view_start_time < date('Y-m-d H:i:s')) {
        $color = "red";
      } else {
        $color = "green";
      }
    ?>
    Start Time: &nbsp;<span style="color:<?php echo $color;?>;"><?php echo $view_start_time?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php // 判断End Time有没过去，以此确认字体颜色
      if ($view_end_time < date('Y-m-d H:i:s')) {
        $color = "red";
      } else {
        $color = "green";
      }
    ?>
    End Time: &nbsp;<span style="color:<?php echo $color;?>"><?php echo $view_end_time?></span>
  </h3>
  <h3 class='am-text-center'>
    Current Time: &nbsp;<span style="color:blue;" id=nowdate><?php echo date("Y-m-d H:i:s")?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    Current Status: &nbsp;<span style="color:red;">
      <?php
        if ($now>$end_time) 
          echo "<span class='am-badge am-badge-danger '>Ended</span>";
        else if ($now<$start_time) 
          echo "<span class='am-badge am-badge-warning '>Not Started</span>";
        else 
          echo "<span class='am-badge am-badge-secondary '>Running</span>";
      ?>&nbsp;&nbsp;
      <?php
        if ($view_private=='0') 
          echo "<span class='am-badge am-badge-success'>Public</font>";
        else 
          echo "&nbsp;&nbsp;<span class='am-badge am-badge-danger'>Private</font>"; 
      ?>
  </span></h3>
  <hr />
  <style type="text/css">
    td {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      text-align: center;
    }
    .table-problem {
      table-layout: fixed;
    }
  </style>
  <table class="am-table am-table-striped table-problem">
    <thead>
      <th style='width: 3%'></th>
      <th class='am-text-center' style='width: 15%'>Problem ID</th>
      <th class='am-text-center' style='width: 50%'>Title</th>
      <th class='am-text-center' style='width: 15%'>Author</th>
      <th class='am-text-center' style='width: 8%'>AC</th>
      <th class='am-text-center' style='width: 9%'>Submssion</th>
    </thead>
    <tbody>
      <?php
        foreach($view_problemset as $row){
          echo "<tr class='am-text-center'>";
          foreach($row as $table_cell){
            echo "<td>";
            echo $table_cell;
            echo "</td>";
          }
          echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
    {
      var x,h,m,s,n,xingqi,y,mon,d;
      var x = new Date(new Date().getTime()+diff);
      y = x.getYear()+1900;
      if (y>3000) y-=1900;
      mon = x.getMonth()+1;
      d = x.getDate();
      xingqi = x.getDay();
      h=x.getHours();
      m=x.getMinutes();
      s=x.getSeconds();
  
      n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout("clock()",1000);
    } 
    clock();
</script>
<?php include "footer.php" ?>
