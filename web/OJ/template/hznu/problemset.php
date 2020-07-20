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
$title=$MSG_PROBLEMSET;
require_once("header.php");
$args=Array();

//default args
if($OJ!="all")$args['OJ']=$OJ;
if(isset($sort_method)) $args['sort_method']=$sort_method;
if(isset($_GET['search'])) $args['search'] = urlencode(htmlentities($search));
if(isset($page)) $args['page']=$page;
function generate_url($data){
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    global $args;
    $link="problemset.php?";
    foreach ($args as $key => $value) {
        if(isset($data["$key"])){
            $value=htmlentities($data["$key"]);
            $link.="&$key=$value";
        }
        else if($value){
            $link.="&$key=".htmlentities($value);
        }
    }
    return $link;
}
?>


<div class="am-container">
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <?php 	/*把题库、状态、排名分开	
    <ul class="am-nav am-nav-tabs">
      <li class="am-active"><a href="./problemset.php"><?php echo $MSG_PROBLEM ?></a></li>
      <li><a href="./status.php"><?php echo $MSG_STATUS ?></a></li>
      <li><a href="./ranklist.php"><?php echo $MSG_RANKLIST ?></a></li>
    </ul>
	*/ ?>
  </div>
  <!-- 题目查找 start -->
  <div class="am-g">
    <!-- 通过ProblemID查找 start-->
    <div class='am-u-md-4'>
      <form class="am-form am-form-horizontal" action='problem.php'>
        <div class="am-u-sm-7">
          <div class="am-form-group am-form-icon">
            <i class="am-icon-search"></i>
            <input type="text" class="am-form-field" placeholder="  &nbsp;<?php echo $MSG_PROBLEM_ID ?>" name="id">
          </div>
        </div>
        <button type="submit" class="am-u-sm-2 am-btn am-btn-warning "><?php echo $MSG_GO ?></button>
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
            <input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_KEYWORDS ?>" name="search" value="<?php echo $search ?>">
            <input type="hidden" name="OJ" value="<?php echo $args['OJ'] ?>">
          </div>
        </div>
        <button type="submit" class="am-u-sm-3 am-btn am-btn-secondary "><?php echo $MSG_SEARCH ?></button>
      </form>
    </div>
    <!-- 通过关键词查找 end -->
    <!-- by problemset -->
    <div class='am-u-md-4'>
      <form class="am-form am-form-horizontal">
        <div class="am-form-group">
          <label class="am-u-sm-4 am-form-label"><?php echo $MSG_PROBLEMSET ?>:</label>
          <div class="am-u-sm-8">
            <select data-am-selected class='select-problemset' type='text'>
              <option value='all' <?php if(!isset($_GET['OJ'])) echo "selected";?> ><?php echo $MSG_ALL ?></option>
                <?php
                $res = $mysqli->query("SELECT set_name,set_name_show FROM problemset");
                while($row = $res->fetch_array()){
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
  </div>
  <!--random choose END-->
  <!-- 题目查找 end -->
  
  <!-- 页标签 start -->
  <div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php $link = generate_url(Array("page"=>"1"))?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        $page_size=10;
        $page_start=max(ceil($page/$page_size-1)*$page_size+1,1);
        $page_end=min(ceil($page/$page_size-1)*$page_size+$page_size,$view_total_page);
        for ($i=$page_start;$i<$page;$i++){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"));
        echo "<li class='am-active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
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
  <div class="am-avg-md-1 well">
    <table class="am-table am-table-hover am-table-striped am-text-nowrap">
      <thead>
      <tr>
        <th style='width:1%;'>&nbsp;</th>
        <th class='am-text-center' style='width:1%;'><?php echo $MSG_PROBLEM_ID ?></th>
        <th class='am-text-left'><?php echo $MSG_TITLE ?></th>
  <?php if ($show_tag) { ?><th class='am-text-center'><?php echo $MSG_TAGS ?></th><?php } ?>
        <th class='am-text-left' style='width:1%;'><?php echo $MSG_AUTHOR ?></th>
        <th class='am-text-left'><?php echo $MSG_Source ?></th>
        <th class='am-text-left' style='width:1%;'><?php echo $MSG_Accepted."/".$MSG_SUBMIT ?></th>
          <?php
          switch ($args['sort_method']) {
              case 'SCORE_DESC':
                  $score_icon="am-icon-sort-amount-desc";
                  break;
              case 'SCORE_ASCE':
                  $score_icon="am-icon-sort-amount-asc";
                  break;
              default:
                  $score_icon="am-icon-sort";
                  break;
          }
          ?>
        <style type="text/css" media="screen">
          #score:hover{
            cursor: pointer;
          }
        </style>
        <th id="score" class='am-text-left' style='width:1%;'><?php echo $MSG_SCORE ?> <span class="<?php echo $score_icon ?>"></span></th>
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
  <div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php $link = generate_url(Array("page"=>"1"))?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=$page_start;$i<$page;$i++){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"));
        echo "<li class='am-active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
</div>
<!-- 页标签 end -->

<?php
  require_once("footer.php");
  include("js.php");
?>

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
  $sql="SELECT COUNT(problem_id) FROM problem WHERE defunct='N'";
  $cnt_problem=$mysqli->query($sql)->fetch_array()[0];
  //echo "<pre>$cnt_problem</pre>";
  $sql="SELECT problem_id FROM problem WHERE defunct='N' LIMIT ".rand(0,$cnt_problem-1).",1";
  $res=$mysqli->query($sql);
  //echo "<pre>$sql</pre>";
  $id=$res->fetch_array()[0];
  //echo "<pre>$id</pre>";
?>
<script type="text/javascript">
  $("#random_choose").click(function(){
    window.location.href="problem.php?id=<?php echo $id; ?>";
  });
</script>
<!--random choose js END-->

<!-- sort by socre BEGIN -->

<script>
  $("#score").click(function(){
    <?php
    if($args['sort_method']=='SCORE_DESC') $args['sort_method']='SCORE_ASCE';
    else $args['sort_method']='SCORE_DESC';
    ?>
    var link="<?php echo generate_url(Array("page"=>"1")) ?>";
    window.location.href=link;
  });
</script>
<!-- sort by socre END -->