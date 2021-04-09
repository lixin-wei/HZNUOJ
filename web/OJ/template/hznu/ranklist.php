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

<?php $title=$MSG_RANKLIST;?>
<?php 
include "header.php";
if(isset($_GET['prefix']) && trim($_GET['prefix'])!="") $args['prefix']=htmlentities($prefix);
if(isset($_GET['class']) && trim($_GET['class']) != "all") $args['class']=urlencode(htmlentities($class_get));
if(isset($page)) $args['page']=$page;
function generate_url($data){
    global $args;
    $link="ranklist.php?";
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
<style>
  .am-form-inline > .am-form-group {
    margin-left: 15px;
  }
  .am-form-inline {
    margin-bottom: 1.5rem;
  }
</style>
<div class='am-container'>
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
  <?php 
	/*把题库、状态、排名分开
    <ul class="am-nav am-nav-tabs">
      <li><a href="./problemset.php"><?php echo $MSG_PROBLEMSET ?></a></li>
      <li><a href="./status.php"><?php echo $MSG_STATUS ?></a></li>
      <li class="am-active"><a href="./ranklist.php"><?php echo $MSG_RANKLIST ?></a></li>
    </ul>
	*/ ?>
  </div>
  <div class='am-g'>
    <!-- 用户查找 start -->
    <div class='am-u-md-6'>
      <form class="am-form am-form-inline" id="searchform">
        <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
        <div class='am-form-group'>
          <select data-am-selected="{searchBox: 1, maxHeight: 400}" id='class' name='class' style='width:110px' onchange='javascript:document.getElementById("searchform").submit();'>
            <option value='all' <?php if (isset($_GET['class']) && $_GET['class']=="" || !isset($_GET['class'])) echo "selected"; ?>> <?php echo $MSG_ALL. $MSG_Class ?></option>
          <?php
            foreach($classSet as $class) {
              if (isset($_GET['class']) && $_GET['class']==$class["class"]) $selected = "selected"; else $selected = "";
              echo "<option value='".$class["class"]."' ".$selected.">".$class["grade"].$class["class"]."</option>";
            }
          ?>
          </select>
        </div>
        <?php } ?>
        <div class="am-form-group am-form-icon">
          <i class="am-icon-search"></i>
          <input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_Input.$MSG_USER_ID ?>" name="prefix" id="prefix" value='<?php echo $args['prefix'] ?>'>
        </div>
        <?php if ($args['scope']!="") echo "<input type='hidden' name='scope' value='{$args['scope']}'>"; ?>
        <button type="submit" class="am-btn am-btn-secondary "><?php echo $MSG_SEARCH ?></button>
      </form>
    </div>
    <!-- 用户查找 end -->

  </div>
  <div class="am-avg-md-1" style="margin-top: 10px; margin-bottom: 10px;">
    <ul class="am-nav am-nav-tabs">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <li <?php if($args['scope']=="") echo "class='am-active'"; ?>><a href="ranklist.php"><?php echo $MSG_ALL ?></a></li>
        <li <?php if($args['scope']=="y") echo "class='am-active'"; ?>><a href="ranklist.php?scope=y"><?php echo $MSG_Year ?></a></li>
        <li <?php if($args['scope']=="m") echo "class='am-active'"; ?>><a href="ranklist.php?scope=m"><?php echo $MSG_Month ?></a></li>
        <li <?php if($args['scope']=="w") echo "class='am-active'"; ?>><a href="ranklist.php?scope=w"><?php echo $MSG_Week ?></a></li>
        <li <?php if($args['scope']=="d") echo "class='am-active'"; ?>><a href="ranklist.php?scope=d"><?php echo $MSG_Day ?></a></li>
        <?php if (HAS_PRI("edit_user_profile")) echo "<li><a href='./admin-tools/updateRank2.php?silent'>$MSG_Update_RANK</a></li>";?>
        <li><a style="color: grey; text-align: center;"><?php echo $MSG_RANKTIPS ?></a></li>
    </ul>
</div>
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
  <div class="am-avg-md-1 well" style="font-size: normal;">
  <style type="text/css" media="screen">
    #ac,#level,#passrate {
        cursor: pointer;
    }
</style>
    <table class="am-table am-table-hover am-table-striped" style="white-space: nowrap;">
      <!-- 表头 start -->
      <thead>
      <tr>
        <th class='am-text-left'><?php echo $MSG_RANK ?></th>
        <th class='am-text-left'><?php echo $MSG_USER ?></th>
        <th class='am-text-left'><?php echo $MSG_NICK ?></th>
        <th class='am-text-left' id='ac'><?php echo $MSG_SOLVED ?>&nbsp;<span class="<?php echo $ac_icon ?>"></span></th>
        <th class='am-text-left'><?php echo $MSG_SUBMIT ?></th>
        <th class='am-text-left' id='passrate'><?php echo $MSG_RATIO ?>&nbsp;<span class="<?php echo $pass_icon ?>"></th>
        <th class='am-text-left' id='level'><?php echo $MSG_LEVEL ?>&nbsp;<span class="<?php echo $level_icon ?>"></span</th>
        <th class='am-text-left'><?php echo $MSG_STRENGTH ?></th>
      </tr>
      </thead>
      <!-- 表头 end -->
      
      <!-- 列出排名 start -->
      <tbody>
      <?php
      foreach($view_rank as $row){
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
      <!-- 列出排名 end -->
    
    </table>
  </div>

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

<?php include "footer.php" ?>
<!-- sort by ac、level BEGIN -->
<script>
    <?php $args['sort_method'] = $ac; ?>
    $("#ac").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $level; ?>
    $("#level").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $pass; ?>
    $("#passrate").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
</script>
<!-- sort by ac、level END -->
