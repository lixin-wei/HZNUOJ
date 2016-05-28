<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.03.23
  **/
?>

<?php $title="ContestSet";?>
<?php include "header.php" ?>

<div class="am-container tt">

  <h3 class='am-text-center'>Server Time：<span id='nowdate'></span></h3>
  <hr />
  <table class="am-table">
    <thead>
      <th class='am-text-center'>ID</th>
      <th class='am-text-center'>Name</th>
      <th class='am-text-center'>Status</th>
      <th class='am-text-center'>Private</th>
    </thead>
    <tbody>
      <?php
        foreach($view_contest as $row){
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
