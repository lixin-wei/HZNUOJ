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
if(isset($_GET['order_by']) && trim($_GET['order_by'])!="") $args['order_by']=htmlentities($order_by);
if(isset($_GET['scope']) && trim($_GET['scope'])!="") $args['scope']=htmlentities($scope);
if(isset($_GET['class']) && trim($_GET['class']) != "all") $args['class']=htmlentities($class_get);
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
      <li><a href="/OJ/problemset.php"><?php echo $MSG_PROBLEMSET ?></a></li>
      <li><a href="/OJ/status.php"><?php echo $MSG_STATUS ?></a></li>
      <li class="am-active"><a href="/OJ/ranklist.php"><?php echo $MSG_RANKLIST ?></a></li>
    </ul>
	*/ ?>
  </div>
  <div class='am-g'>
    <!-- 用户查找 start -->
    <div class='am-u-md-6'>
      <form class="am-form am-form-inline">
        <!-- <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">-->
        <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
        <div class='am-form-group'>
          <select data-am-selected="{searchBox: 1, maxHeight: 400}" id='class' name='class' style='width:110px'>
            <option value='all' <?php if (isset($_GET['class']) && $_GET['class']=="" || !isset($_GET['class'])) echo "selected"; ?>> <?php echo $MSG_ALL ?></option>
          <?php
            foreach($classSet as $class) {
              $selected = "";
              $class=substr($class, 5);
              if (isset($_GET['class']) && $_GET['class']==$class) $selected = "selected";
              echo "<option value='".$class."' ".$selected.">".$class."</option>";
            }
          ?>

          </select>
              
        </div>
        <?php } ?>
        <div class="am-form-group am-form-icon">
          <i class="am-icon-search"></i>
          <input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_Input.$MSG_USER_ID ?>" name="prefix" id="prefix" value='<?php echo $args['prefix'] ?>'>
        </div>
        <button type="submit" class="am-btn am-btn-secondary "><?php echo $MSG_SEARCH ?></button>
        <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
          <!-- 选择班级后自动跳转页面的js代码 start -->
          <script type="text/javascript">
            var oSelect=document.getElementById("class");
			var prefix=document.getElementById("prefix").value;
            oSelect.onchange=function() { //当选项改变时触发
              var valOption=this.options[this.selectedIndex].value; //获取option的value
              var url = window.location.search;
              var cid = url.substr(url.indexOf('=')+1,4);
              var url = window.location.pathname+"?class="+valOption+"&prefix="+prefix;
              window.location.href = url;
            }
          </script>
          <!-- 选择班级后自动跳转页面的js代码 end -->   
         <?php } ?> 
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
  <div class="am-g" style="color: grey; text-align: center;">
    <div>
      <?php echo $MSG_RANKTIPS ?>
    </div>
  </div>
  <div class="am-avg-md-1 well" style="font-size: normal;">
    <table class="am-table am-table-hover am-table-striped">
      
      <!-- 表头 start -->
      <thead>
      <tr>
        <th class='am-text-left'><?php echo $MSG_RANK ?></th>
        <th class='am-text-left'><?php echo $MSG_USER ?></th>
        <th class='am-text-left'><?php echo $MSG_NICK ?></th>
        <th class='am-text-left'><?php echo $MSG_SOLVED ?></th>
        <th class='am-text-left'><?php echo $MSG_SUBMIT ?></th>
        <th class='am-text-left'><?php echo $MSG_RATIO ?></th>
        <th class='am-text-left' style='width:100px'><?php echo $MSG_LEVEL ?></th>
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
    <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=1;$i<=$view_total_page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            if($page==$i)
                echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
            else
                echo "<li><a href=\"$link\">{$i}</a></li>";
        }
      ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<?php include "footer.php" ?>
