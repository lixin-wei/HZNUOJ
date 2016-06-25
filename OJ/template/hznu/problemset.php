<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.06.02
  **/
?>

<?php 
  $title="ProblemSet";
  require_once("header.php");
?>

<!-- 题目查找 start -->
<div class="am-container tt am-g">
  <!-- 通过ProblemID查找 start-->
  <div class='am-u-md-6 am-text-right'>
    <form class="am-form am-form-inline" action='problem.php'>
      <div class="am-form-group am-form-icon">
        <i class="am-icon-search"></i>
        <input type="text" class="am-form-field am-round" placeholder="  &nbsp;Input problem ID" name="id">
      </div>
      <button type="submit" class="am-btn am-btn-warning am-round ">Go</button>
    </form>
  </div>
  <!-- 通过ProblemID查找 end-->
  <!-- 通过关键词查找 start -->
  <div class='am-u-md-6 am-text-left'>
    <form class="am-form am-form-inline">
      <div class="am-form-group am-form-icon">
        <i class="am-icon-binoculars"></i>
        <input type="text" class="am-form-field am-round" placeholder=" &nbsp;Input keywords" name="search">
        <input type="hidden" name="OJ" value="<?php echo $OJ ?>">
      </div>
      <button type="submit" class="am-btn am-btn-secondary am-round ">Search</button>
    </form>
  </div>
  <!-- 通过关键词查找 end -->
</div>
<!-- 题目查找 end -->

<!-- 页标签 start -->
<div class="am-container">
  <ul class="am-pagination am-text-center">
    <li><a href="problemset.php?OJ=<?php echo $OJ ?>&page=<?php echo max($page-1, 1) ?>">&laquo; Prev</a></li>
    <?php 
      //分页
      for ($i=1;$i<=$view_total_page;$i++){
        if($page==$i)
          echo "<li class='am-active'><a href='problemset.php?OJ={$OJ}&page={$i}'>{$i}</a></li>";
        else
          echo "<li><a href='problemset.php?OJ={$OJ}&page={$i}'>{$i}</a></li>";
      }
    ?>
    <li><a href="problemset.php?OJ=<?php echo $OJ ?>&page=<?php echo min($page+1,intval($view_total_page)) ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<!-- 罗列题目 start -->
<div class="am-container">
  <table class="am-table am-table-hover am-table-striped">
    <thead>
      <tr>
        <th style='width:30px'></th>
        <th class='am-text-center' >Prob.ID</th>
        <th class='am-text-center'>Title</th>
        <th class='am-text-center'>Tags</th>
        <th class="am-text-center">Author</th>
        <th class='am-text-center'>Source</th>
        <th class='am-text-center'>AC/Sub</th>
        <th class='am-text-center'>Score</th>
      </tr>
    </thead>
    <tbody>
    <?php
      foreach($view_problemset as $row){
          echo "<tr>";
          foreach($row as $table_cell){
            echo $table_cell;
          }
          echo "</tr>";
        }
    ?>
    </tbody>
  </table>
</div>
<!-- 罗列题目 end -->

<!-- 页标签 start -->
<div class="am-container">
  <ul class="am-pagination am-text-center">
    <li><a href="problemset.php?OJ=<?php echo $OJ ?>&page=<?php echo max($page-1, 1) ?>">&laquo; Prev</a></li>
    <?php 
      //分页
      for ($i=1;$i<=$view_total_page;$i++){
        if($page==$i)
          echo "<li class='am-active'><a href='problemset.php?OJ={$OJ}&page={$i}'>{$i}</a></li>";
        else
          echo "<li><a href='problemset.php?OJ={$OJ}&page={$i}'>{$i}</a></li>";
      }
    ?>
    <li><a href="problemset.php?OJ=<?php echo $OJ ?>&page=<?php echo min($page+1,intval($view_total_page)) ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<?php require_once("footer.php") ?>