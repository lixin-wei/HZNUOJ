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
<hr/>
<!-- 题目查找 start -->
<div class="am-container tt am-g">
  <!-- 通过ProblemID查找 start-->
  <div class='am-u-md-4'>
    <form class="am-form am-form-horizontal" action='problem.php'>
      <div class="am-u-sm-7">
        <div class="am-form-group am-form-icon">
          <i class="am-icon-search"></i>
          <input type="text" class="am-form-field" placeholder="  &nbsp;Problem ID" name="id">
        </div>
      </div>
      <button type="submit" class="am-u-sm-2 am-btn am-btn-warning ">Go</button>
      <a class="am-u-sm-3 am-btn am-btn-success" id="random_choose">Lucky?</a>
    </form>
  </div>
  <!-- 通过ProblemID查找 end-->
  <!-- 通过关键词查找 start -->
  <div class='am-u-md-4'>
    <form class="am-form am-form-horizontal">
      <div class="am-u-sm-9">
        <div class="am-form-group am-form-icon">
          <i class="am-icon-binoculars"></i>
          <input type="text" class="am-form-field" placeholder=" &nbsp;Keywords" name="search">
          <input type="hidden" name="OJ" value="<?php echo $OJ ?>">
        </div>
      </div>
      <button type="submit" class="am-u-sm-3 am-btn am-btn-secondary ">Search</button>
    </form>
  </div>
  <!-- 通过关键词查找 end -->
  <!-- by problemset -->
  <div class='am-u-md-4'>
    <form class="am-form am-form-horizontal">
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label">ProblemSet:</label>
        <div class="am-u-sm-8">
          <select data-am-selected class='select-problemset' type='text'>
            <option value='all' <?php if(!isset($_GET['OJ'])) echo "selected";?> >All</option>
            <?php
            $res = mysql_query("SELECT set_name,set_name_show FROM problemset");
            while($row = mysql_fetch_array($res)){
              echo "<option value='$row[0]' ";
              if($_GET['OJ']==$row[0]) echo "selected";
              echo ">$row[1]</option>";
            }
            ?>
          </select>
        </div>
      </div>
    </form>
  </div>
  <!-- by problemset -->
  <!--random choose START-->
  <div class="am-u-md-1">
    
  </div>
  <!--random choose END-->
</div>
<!-- 题目查找 end -->
<hr/>
<!-- 页标签 start -->
<div class="am-container">
  <ul class="am-pagination am-text-center">
    <li><a href="problemset.php?<?php if($OJ!='all')echo "OJ=".$OJ; ?>&page=<?php echo max($page-1, 1) ?>">&laquo; Prev</a></li>
    <?php 
      //分页
      for ($i=1;$i<=$view_total_page;$i++){
        $link="problemset.php?";
        if($OJ!="all")$link.="&OJ=$OJ";
        if($i!=1)$link.="&page=$i";
        if($page==$i)
          echo "<li class='am-active'><a href='$link'>{$i}</a></li>";
        else
          echo "<li><a href='$link'>{$i}</a></li>";
      }
    ?>
    <li><a href="problemset.php?<?php if($OJ!='all')echo "OJ=".$OJ; ?>&page=<?php echo min($page+1,intval($view_total_page)) ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<!-- 罗列题目 start -->
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
<div class="am-container">
  <table class="am-table am-table-hover am-table-striped am-text-nowrap table-problem">
    <thead>
      <tr>
        <th style='width:2%;'></th>
        <th class='am-text-center' style='width:8%;'>Prob.ID</th>
        <th class='am-text-center' style='width:35%;'>Title</th>
        <th class='am-text-center' style='width:18%;'>Tags</th>
        <th class='am-text-center' style='width:10%;'>Author</th>
        <th class='am-text-center' style='width:25%;'>Source</th>
        <th class='am-text-center' style='width:8%;'>AC/Sub</th>
        <th class='am-text-center' style='width:8%;'>Score</th>
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
    <li><a href="problemset.php?<?php if($OJ!='all')echo "OJ=".$OJ; ?>&page=<?php echo max($page-1, 1) ?>">&laquo; Prev</a></li>
    <?php 
      //分页
      for ($i=1;$i<=$view_total_page;$i++){
        $link="problemset.php?";
        if($OJ!="all")$link.="&OJ=$OJ";
        if($i!=1)$link.="&page=$i";
        if($page==$i)
          echo "<li class='am-active'><a href='$link'>{$i}</a></li>";
        else
          echo "<li><a href='$link'>{$i}</a></li>";
      }
    ?>
    <li><a href="problemset.php?<?php if($OJ!='all')echo "OJ=".$OJ; ?>&page=<?php echo min($page+1,intval($view_total_page)) ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<?php require_once("footer.php") ?>

<!-- problem selector js START-->
<script type="text/javascript">
  $(".select-problemset").change(function(){
    var set_name = $(this).val();
    var url = window.location.pathname;
    if(set_name!="all") url = url+"?OJ="+set_name;
    window.location.href = url;
  });
</script>
<!-- problem selector js END-->

<!--random choose js START-->
<?php
  $cnt_problem=mysql_fetch_array(mysql_query("SELECT COUNT(problem_id) FROM problem WHERE defunct='N'"))[0];
  //echo "<pre>$cnt_problem</pre>";
  $sql="SELECT problem_id FROM problem WHERE defunct='N' LIMIT ".rand(0,$cnt_problem-1).",1";
  $res=mysql_query($sql);
  //echo "<pre>$sql</pre>";
  $id=mysql_fetch_array($res)[0];
  //echo "<pre>$id</pre>";
?>
<script type="text/javascript">
  $("#random_choose").click(function(){
    window.location.href="problem.php?id=<?php echo $id; ?>";
  });
</script>
<!--random choose js END-->