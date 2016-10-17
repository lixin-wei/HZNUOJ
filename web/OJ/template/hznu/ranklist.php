<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.04.15
  **/
?>

<?php $title="Ranklist";?>
<?php include "header.php" ?>

<div class='am-container'>
  <hr>
  <div class='am-g'>
    <!-- 用户查找 start -->
    <div class='am-u-md-6'>
      <form class="am-form am-form-inline" action='userinfo.php'>
        <div class='am-form-group'>
          <select data-am-selected="{searchBox: 1}" id='class' style='width:110px'>
            <option value='all' <?php if (isset($_GET['class']) && $_GET['class']=="" || !isset($_GET['class'])) echo "selected"; ?>>全部</option>
          <?php
            foreach($classSet as $class) {
              $selected = "";
              $class=substr($class, 5);
              if (isset($_GET['class']) && $_GET['class']==$class) $selected = "selected";
              echo "<option value='".$class."' ".$selected.">".$class."</option>";
            }
          ?>

          </select>
          <!-- 选择班级后自动跳转页面的js代码 start -->
          <script type="text/javascript">
            var oSelect=document.getElementById("class");
            oSelect.onchange=function() { //当选项改变时触发
              var valOption=this.options[this.selectedIndex].value; //获取option的value
              var url = window.location.search;
              var cid = url.substr(url.indexOf('=')+1,4);
              var url = window.location.pathname+"?class="+valOption;
              window.location.href = url;
            }
          </script>
          <!-- 选择班级后自动跳转页面的js代码 end -->
        </div>
        &nbsp;&nbsp;&nbsp;
        <div class="am-form-group am-form-icon">
          <i class="am-icon-search"></i>
          <input type="text" class="am-form-field" placeholder=" &nbsp;Input user ID" name="user">
        </div>
        <button type="submit" class="am-btn am-btn-warning ">Go</button>
      </form>
    </div>
    <!-- 用户查找 end -->

    <!-- 排序模块 start -->
<!--     <div class='am-u-md-6 am-text-right am-text-middle'>
      <b>For All:&nbsp</b>
      <a href=ranklist.php?order_by=s>Level</a>&nbsp&nbsp&nbsp&nbsp
      <b>For HZNU:</b>&nbsp
      <a href=ranklist.php?order_by=ac>AC</a>&nbsp&nbsp
      <a href=ranklist.php?scope=d>Day</a>&nbsp&nbsp
      <a href=ranklist.php?scope=w>Week</a>&nbsp&nbsp
      <a href=ranklist.php?scope=m>Month</a>&nbsp&nbsp
      <a href=ranklist.php?scope=y>Year</a>&nbsp&nbsp
    </div> -->
    <!-- 排序模块 end -->
  </div>

  <table class="am-table">

    <!-- 表头 start -->
    <thead>
      <tr>
        <th class='am-text-center'>Rank</th>
        <th class='am-text-center'>User</th>
        <th class='am-text-center'>Nick</th>
        <th class='am-text-center'>HZNU</th>
        <th class='am-text-center'>ZJU</th>
        <th class='am-text-center'>HDU</th>
        <th class='am-text-center'>PKU</th>
        <th class='am-text-center'>UVA</th>
        <th class='am-text-center'>CF</th>
        <th class='am-text-center'>Total</th>
        <th class='am-text-center' style='width=100px'>Level</th>
        <th class='am-text-center'>DouQi</th>
      </tr>
    </thead>
    <!-- 表头 end -->

    <!-- 列出排名 start -->
    <tbody>
      <?php 
        foreach($view_rank as $row){
          echo "<tr>";
          foreach($row as $table_cell){
            echo "<td align='center'>";
            echo $table_cell;
            echo "</td>";
          }
          echo "</tr>";
        }
      ?>
    </tbody>
    <!-- 列出排名 end -->

  </table>
</div>


<!-- 页标签 start -->
<div class="am-container">
  <ul class="am-pagination am-text-center">
    <li><a href="ranklist.php?start=<?php echo $start-30<0?0:$start-30; echo '&'.$filter_url; ?>">&laquo; Prev</a></li>
      <?php
        if (intval($view_total/$page_size)+1 > 7) {
          $i = max(0,$start-3*$page_size);
          if ($start> 4*$page_size-1) {
            echo "<li><a href='./ranklist.php?start=0".$filter_url. "'>1</a></li>";
            if ((intval($start/$page_size)+1)-4 > 1) echo "&nbsp;......&nbsp;&nbsp;";
          }
          for(; $i<min($view_total,$start+4*$page_size); $i+=$page_size) {
            if (intval($i/$page_size)+1 == intval($start/$page_size)+1)
              echo "<li class='am-active'><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
            else
              echo "<li><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
            echo strval(intval($i/$page_size)+1);
            echo "</a></li>";
          }
          if ($i < $view_total) {
            if (intval(($i)/$page_size)+1 < intval(($view_total-1)/$page_size+1)) echo "&nbsp;&nbsp;......&nbsp;";
            echo "<li><a href='./ranklist.php?start=".(intval($view_total/$page_size)*$page_size).$filter_url. "'>".intval(($view_total-1)/$page_size+1)."</a></li>";
          }
        } else for($i=0; $i<$view_total; $i+=$page_size) {
          if (intval($i/$page_size)+1 == intval($start/$page_size)+1)
            echo "<li class='am-active'><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
          else
            echo "<li><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
          echo strval(intval($i/$page_size)+1);
          echo "</a></li>";
        }
      ?>
    <li><a href="ranklist.php?start=<?php echo $start+30<$view_total?$start+30:$start; echo '&'.$filter_url; ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<?php include "footer.php" ?>
