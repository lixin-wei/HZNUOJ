<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.24
   * last modified
   * by yybird
   * @2016.03.24
  **/
?>

<?php $title = $MSG_STATUS;?>
<?php include "contest_header.php" ?>
<?php
if(isset($_GET['cid'])) $args['cid']=htmlentities($cid);
if(isset($_GET['problem_id'])) $args['problem_id']=htmlentities($problem_id);
if(isset($_GET['user_id'])) $args['user_id']=htmlentities($user_id);
if(isset($_GET['language'])&& $language!=-1) $args['language']=htmlentities($language);
if(isset($_GET['jresult']) && $jresult_get!=-1) $args['jresult']=htmlentities($jresult_get);
if(isset($_GET['showsim'])) $args['showsim']=htmlentities($showsim);
if(isset($page)) $args['page']=$page;
function generate_url($data){
    global $args;
    $link="status.php?";
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
<style type="text/css">
  .pp{
    margin-top: 30px;
  }
</style>

<div class="am-container">
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">   
    </div>
<!-- 搜索框 start -->
    <div class="am-g">
      <div class="am-u-md-12">
        <form action="status.php" id="statusform" method="get" class="am-form am-form-inline" role="form" style="float: left;">
          <div class="am-form-group"><input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_PROBLEM_ID ?>" name="problem_id" value="<?php echo $args['problem_id']?>"></div>
          <div class="am-form-group">
            <input type="text" class="am-form-field" placeholder=" &nbsp;<?php echo $MSG_USER_ID ?>" name="user_id" value="<?php echo $args['user_id']?>">
              <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
          </div>
          <div class="am-form-group">
            <label for="language"><?php echo $MSG_LANG ?>:</label>
            <select class="am-round" id="language" name="language" data-am-selected="{searchBox: 1, maxHeight: 400}">
          <?php
          if (isset($_GET['language'])) $language=$_GET['language'];
          else $language=-1;
          if ($language<0||$language>=count($language_name))
              $language=-1;
          if ($language==-1)
              echo "<option value='-1' selected>$MSG_ALL</option>";
          else
              echo "<option value='-1'>$MSG_ALL</option>";
          $lang_count=count($language_ext);
          for($i=0 ; $i<$lang_count ; ++$i) {
              $j = $language_order[$i];
              if($OJ_LANGMASK & (1<<$j)) {
                  if ($j==$language)
                      echo "<option value=$j selected>$language_name[$j]</option>";
                  else
                      echo "<option value=$j>$language_name[$j]</option>";
              }
          }
          ?>
              </select>
              <span class="am-form-caret"></span>
            </div>
            <div class="am-form-group">
              <label for="jresult"><?php echo $MSG_RESULT ?>:</label>
              <select class="am-round" name="jresult" data-am-selected="{btnWidth: '100px'}">
            <?php
              if (isset($_GET['jresult']))
                $jresult_get=intval($_GET['jresult']);
              else
                $jresult_get=-1;
              if ($jresult_get>=12||$jresult_get<0)
                $jresult_get=-1;
              if ($jresult_get==-1)
                echo "<option value='-1' selected>$MSG_ALL</option>";
              else
                echo "<option value='-1'>$MSG_ALL</option>";
              for ($j=0;$j<12;$j++){
                      $i=($j+4)%12;
                      if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
                      else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>";
                }
            ?>
              </select>
              <span class="am-form-caret"></span>
            </div>
            <?php if ($OJ_SIM) { ?>
            <div class="am-form-group">
            <label for="sim"><?php echo $MSG_SIM ?>:</label>
            <select class="am-round" id="showsim" name="showsim" data-am-selected="{btnWidth: '100px'}" onchange="document.getElementById('statusform').submit();">
                <?php
                echo "
                <option value=0 ".($showsim==0?'selected':'').">All</option>
                <option value=50 ".($showsim==50?'selected':'').">&gt;=50%</option>
                <option value=60 ".($showsim==60?'selected':'').">&gt;=60%</option>
                <option value=70 ".($showsim==70?'selected':'').">&gt;=70%</option>
                <option value=80 ".($showsim==80?'selected':'').">&gt;=80%</option>
                <option value=90 ".($showsim==90?'selected':'').">&gt;=90%</option>
                <option value=100 ".($showsim==100?'selected':'').">100%</option>
                ";
                ?>
            </select>
            <span class="am-form-caret"></span>
          </div>
          <?php } ?>
          <button type="submit" class="am-btn am-btn-secondary"><span class='am-icon-filter'></span> <?php echo $MSG_FILTER ?></button>
          <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
          &nbsp;&nbsp;<button type="submit" class="am-btn am-btn-default"><?php echo $MSG_RESET ?></button>
        </form>
      </div>
    </div>
      <!-- 搜索框 end -->
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
<div class="am-avg-md-1 well">
  <table id="result-tab" class="am-table am-table-hover am-table-striped" >
    <thead>
      <tr>
        <th><?php echo $MSG_RUNID ?></th>
        <th><?php echo $MSG_USER ?></th>
        <th><?php echo $MSG_PROBLEM_ID ?></th>
        <th><?php echo $MSG_RESULT ?></th>
        <th><?php echo $MSG_MEMORY ?></th>
        <th><?php echo $MSG_TIME ?></th>
        <th><?php echo $MSG_LANG ?></th>
        <th><?php echo $MSG_CODE_LENGTH ?></th>
        <th><?php echo $MSG_SUBMIT_TIME ?></th>
        <th><?php echo $MSG_JUDGER ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
                    foreach($view_status as $row){
                      echo "<tr>";
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
<!-- 页标签 end -->
<?php include "footer.php" ?>
<script>
var i = 0;
var judge_result = [<?php echo "'". implode("','", $judge_result) ."'";?>];
var judge_color = [<?php echo "'". implode("','", $judge_color) ."'";?>];
</script>
<script src="template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js?v=0.38"></script>
