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

<?php 
$title=$MSG_CONTEST;
if(isset($_GET['my'])) $title = $MSG_MY.$MSG_CONTEST;
require_once("header.php");
if(isset($_GET['type'])) $args['type']=htmlentities(trim($_GET['type']));
if(isset($_GET['runstatus'])) $args['runstatus']=htmlentities(trim($_GET['runstatus']));
if(isset($_GET['search'])) $args['search']=urlencode(htmlentities($search));
if(isset($page)) $args['page']=$page;
function generate_url($data){
    global $args, $getMy;
    $link="contest.php?".$getMy;
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
  </div>
  <!-- contest查找 start -->
  <div class="am-g">
  <div class="am-u-md-12">
      <form id="searchform" class="am-form am-form-inline">
        <?php if(isset($_GET['my'])){
			echo "<input type='hidden' name='my' value=''>";
		}?>
    <!-- 通过类型查找 start -->
    <div class="am-form-group">
      <select name="type" data-am-selected="{}" onchange='javascript:document.getElementById("searchform").submit();'>
        <option value='all' <?php if (isset($_GET['type']) && ($_GET['type'] == "" || $_GET['type'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Type ?></option>
        <option value='Public' <?php if (isset($_GET['type']) && $_GET['type'] == "Public" ) echo "selected"; ?>><?php echo $MSG_Public ?></option>
        <option value='Private' <?php if (isset($_GET['type']) && $_GET['type'] == "Private" ) echo "selected"; ?>><?php echo $MSG_Private ?></option>
        <option value='Practice' <?php if (isset($_GET['type']) && $_GET['type'] == "Practice" ) echo "selected"; ?>><?php echo $MSG_Practice ?></option>
        <option value='Special' <?php if (isset($_GET['type']) && $_GET['type'] == "Special" ) echo "selected"; ?>><?php echo $MSG_Special ?></option>
      </select>
    </div>
    <!-- 通过状态查找 end -->
    <div class="am-form-group">
      <select name="runstatus" data-am-selected="{}" onchange='javascript:document.getElementById("searchform").submit();'>
        <option value='all' <?php if (isset($_GET['runstatus']) && ($_GET['runstatus'] == "" || $_GET['runstatus'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_STATUS ?></option>
        <option value='noStart' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "noStart" ) echo "selected"; ?>><?php echo $MSG_notStart2 ?></option>
        <option value='Running' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "Running" ) echo "selected"; ?>><?php echo $MSG_Running ?></option>
        <option value='Ended' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "Ended" ) echo "selected"; ?>><?php echo $MSG_Ended ?></option>
      </select>
    </div>
    <!-- 通过状态查找 end -->
    <!-- 通过关键词查找 start -->
      <div class="am-form-group am-form-icon">
        <i class="am-icon-binoculars"></i>
        <input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_KEYWORDS ?>" name="search" value="<?php echo $search ?>">
      </div>
    <!-- 通过关键词查找 end -->
       <button type="submit" class="am-btn am-btn-secondary"><?php echo $MSG_SEARCH ?></button>
      </form>
      </div>
    </div>
  <!-- contest查找 end -->
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
  <!-- 罗列contest start -->
  <div class="am-avg-md-1 well">
    <table class="am-table am-table-hover  am-table-striped">
      <thead>
      <th class='am-text-left'><?php echo $MSG_ID ?></th>
      <th class='am-text-left'><?php echo $MSG_Name ?></th>
      <th class='am-text-left'><?php echo $MSG_STATUS ?></th>
      <th class='am-text-left'><?php echo $MSG_Type ?></th>
      <th class='am-text-left'><?php echo $MSG_Creator ?></th>
      </thead>
      <tbody>
      <?php
      foreach($view_contest as $row){
          echo "<tr class='am-text-left'>";
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
</div>
<!-- 罗列contest end -->
  
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
<?php include "footer.php" ?>
