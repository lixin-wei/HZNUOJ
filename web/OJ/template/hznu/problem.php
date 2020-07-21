<?php
/**
 * This file is created
 * by yybird
 * @2016.03.23
 * last modified
 * by yybird
 * @2016.05.25
 * by D_Star
 * @2016.08.xx
 **/
?>


<?php $title=$view_title;?>
<?php
if (isset($_GET['OJ'])) $OJ = $_GET['OJ'];
else $OJ = "HZNU";

if ($_GET['cid']) require_once("contest_header.php");
else {
    require_once("header.php");
}
function sss($str){
    $after = preg_replace( '/<[^<]+?>/' ,'FUCK$0FUCK', $str);
    $after = preg_replace( '/(?<!FUCK)</' ,'&lt;', $after);
    $after = preg_replace( '/FUCK(?=<)/' ,'', $after);
    $after = preg_replace( '/>(?!FUCK)/' ,'&gt;', $after);
    $after = preg_replace( '/(?<=>)FUCK/' ,'', $after);
    return $after;
}
?>


<!-- Sample Input 和 Sample Output 的背景色 start -->
<style type="text/css">
  .sampledata {
    /*background: none repeat scroll 0 0 rgba(0,0,0,.075);*/
    font-family: Monospace;
    white-space: pre-wrap;
    font-size: 10pt;
  }
  .sample-outer {
    /* border: 1px solid; */
    margin-bottom: 20px;
    border-bottom: 1px solid #ccc;
  }
  .sample-bg {
    background: #f0efef;
    color: #7e2222;
    /* border-top: 1px solid; */
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 5px;
    line-height: 1;
  }
  .sample-title {
    background: white;
    border: 1px solid #ccc;
    padding-left: 5px;
    padding-right: 5px;
  }
</style>
<!-- Sample Input 和 Sample Output 的背景色 end -->


<div class="am-container">
  <?php
  //the header of problem page
  /*if(!isset($_GET['cid'])) {
    echo <<<HTML
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
      <li class="am-active"><a href="./problemset.php">$MSG_PROBLEM</a></li>
      <li><a href="./status.php">$MSG_STATUS</a></li>
      <li><a href="./ranklist.php">$MSG_RANKLIST</a></li>
    </ul>
  </div>
HTML;
  }
  else */
  if(isset($_GET['cid'])){
    echo "<ul class=\"am-nav am-nav-tabs\" style='margin-top: 30px;'>";
    //跳过不存在题目的题号
    $sql = "SELECT `num` FROM contest_problem a 
    inner join (select problem_id from `problem`) b 
    on a.problem_id = b.problem_id 
    WHERE contest_id = $cid and num >=0 order by num" ;
    $result=$mysqli->query($sql) or die($mysqli->error);
    $pid_nums=$result->fetch_all(MYSQLI_BOTH);
    $result->free();
	foreach($pid_nums as $num){
		$label = PID($num[0]);
        $class = ($num[0] == $pid)? "am-active": "";
		echo <<<HTML
  <li class="$class"><a href="problem.php?cid=$cid&pid=$num[0]">$label</a></li>
HTML;
	}
    echo "</ul>";
  }
  ?>
  <h1 style="text-align:center;margin-top:40px;">
      <?php
      echo $row->title;
      ?>
    <!-- is contest problem -->
      <?php
      if($has_accepted) {
          echo "<span class='am-badge am-badge-success am-text-lg'><i class='am-icon-check'></i>$MSG_AC</span>";
      }
      $now=time();
      ?>
      <?php if ($is_practice || isset($_GET['cid']) && ($now>$end_time || HAS_PRI("edit_contest"))): ?>
        <span class="am-badge am-badge-primary am-text-lg">
      <a href="problem.php?id=<?php echo $real_id ?>" style="color: white;">
        <?php echo $real_id ?>
      </a>
    </span>
      <?php endif ?>
  </h1>
    <?php
    if ($show_tag && !isset($_GET['cid'])) {
    ?>
  <form id='tagForm' class='am-form am-form-inline' style="text-align:center" action=''>
    <div class="am-form-group">
        <?php
        echo "<span><i class='am-icon-tag'></i> $MSG_TAGS: </span>";
        for ($i=0; $i<count($tag); ++$i) {
            if ($i == 0) echo "&nbsp;&nbsp;<span class='am-badge am-badge-danger'>".$tag[$i]."</span>";
            else if ($i == 1) echo "&nbsp;&nbsp;<span class='am-badge am-badge-warning'>".$tag[$i]."</span>";
            else if ($i == 2) echo "&nbsp;&nbsp;<span class='am-badge am-badge-primary'>".$tag[$i]."</span>";
            else if ($i == 3) echo "&nbsp;&nbsp;<span class='am-badge am-badge-secondary'>".$tag[$i]."</span>";
            else if ($i == 4) echo "&nbsp;&nbsp;<span class='am-badge am-badge-success'>".$tag[$i]."</span>";
            else echo "&nbsp;&nbsp;<span class='am-badge am-badge-default'>".$tag[$i]."</span>";
        }
        ?>
    </div>
      <?php
      if ($is_solved) {
          ?>
        &nbsp;&nbsp;
        <div class='am-form-group'><span><?php echo  $MSG_MY.$MSG_TAGS ?>: &nbsp;</span></div>
        <div class='am-form-group'>
          <input class='col-sm-9' type='text' style="width:80px;height:20px;font-size:10px" value='<?php echo $my_tag; ?>' name='myTag'></input>
        </div>
        <div class='am-form-group'>
          <button type='button' id='tagSubmit' style='border:none;background-color:transparent;'><i class='am-icon-check' ></i></button>
        </div>
        <input type='hidden' value='<?php echo $id ?>' name='id'></input>
          <?php
      }
      echo "</form>";
      }
      ?>
    
    
    <div style="text-align:center;">
      <?php echo $MSG_Time_Limit ?>:&nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo round($row->time_limit) ?> s</span>
      &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $MSG_Memory_Limit ?>: &nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo $row->memory_limit?> MB</span></span>
        <?php if($row->spj) echo "<span class='am-badge am-badge-primary'>Special Judge</span>"?>
    </div>
    <div style="text-align:center;">
      <?php echo $MSG_SUBMISSION ?>：<span class="am-badge am-badge-secondary"><?php echo $submit_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $MSG_Accepted ?>：<span class="am-badge am-badge-success"><?php echo $ac_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
      <?php if(!isset($contest_score)):?>
        <?php
        $score_class = "am-badge-default";
        if ($row->score >= 82) $score_class='am-badge-danger';
        else if ($row->score >= 64) $score_class='am-badge-warning';
        else if ($row->score >= 46) $score_class='am-badge-primary';
        else if ($row->score >= 28) $score_class='am-badge-secondary';
        ?>
        <?php echo $MSG_SCORE ?>：<span class='am-badge <?php echo $score_class ?>'><?php echo round($row->score) ?></span>
      <?php else:?>
        <?php echo $MSG_SCORE ?>：<span class='am-badge am-badge-success'><?php echo round($contest_score) ?></span>
      <?php endif;?>
      
    </div>
    <br />
    <!-- 提交等按钮 start -->
    <?php
    if(isset($_GET['cid'])){
      $edit_target="target='_blank'";
    } else $edit_target="";
    ?>
    <div class="am-text-center">
      <a href="
      <?php
      if ($pr_flag){
          echo "submitpage.php?id=$id";
      }else{
          echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
      }
      ?>
    " style="color:white">
        <button type="button" class="am-btn am-btn-sm am-btn-success "><?php echo $MSG_SUBMIT ?></button>
      </a>
        <?php
        if(!isset($_GET['cid']) || $is_practice==1) {
            echo<<<HTML
            <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
              <button type="button" class="am-btn am-btn-sm am-btn-primary ">
                $MSG_Codes
              </button>
            </a>
HTML;
        }
        if (HAS_PRI("edit_".$set_name."_problem")) {
            echo<<<HTML
          <a href="./admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white' $edit_target>
            <button type='button' class='am-btn am-btn-sm am-btn-danger '>
              $MSG_EDIT
            </button>
          </a>
          <a href="./admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white' target="_blank">
            <button type='button' class='am-btn am-btn-sm am-btn-warning '>
              $MSG_TestData
            </button>
          </a>

HTML;
        }
        ?>
    </div>
    <!-- 提交等按钮 end -->
      <?php
      $str=sss($row->description);
      if($str) {
        //编码转义未解决！
          //$tt=htmlspecialchars($row->description);
          echo <<<HTML
          <h2>$MSG_Description</h2>
          <p>
          $str
          </p>
HTML;
      }
      ?>
      <?php
      $str=sss($row->input);
      if($str) {
          echo <<<HTML
          <h2>$MSG_Input</h2>
          <p>
          $str
          </p>
HTML;
      }
      ?>
    
      <?php
      $str=sss($row->output);
      if($str) {
          echo <<<HTML
          <h2>$MSG_Output</h2>
          <p>
          $str
          </p>
HTML;
      }
      ?>

      <?php
      $html_samples="";
      foreach ($samples as $sample) {
          $text_input=htmlentities($sample['input']);
          $text_output=htmlentities($sample['output']);
          if($sample['show_after']){
              $html_samples.= <<<HTML
                <div style='color: grey;'>
                  Show after trying {$sample['show_after']} times:
                </div>
HTML;
          }
          if($text_input || $text_output) {
              $html_samples.= <<<HTML
                <div class="sample-outer">
                  <div class="sample-title">$MSG_Sample_Input:</div>
                  <div class="sample-bg"><span class="sampledata">$text_input</span></div>
                  <div class="sample-title">$MSG_Sample_Output:</div>
                  <div class="sample-bg"><span class="sampledata">$text_output</span></div>
                </div>
HTML;
          }
      }
      $str=sss($html_samples);
      if($str) {
          echo <<<HTML
          <h2>$MSG_Samples</h2>
          <p>
          $str
          </p>
HTML;
      }
      ?>
    
    
      <?php
      $str=sss($row->hint);
      if($str) {
          echo <<<HTML
          <h2>$MSG_HINT</h2>
          <p>
          $str
          </p>
HTML;
      }
      ?>
    
      <?php
      $str=sss($row->author);
      if($str) {
          echo <<<HTML
          <h2>$MSG_AUTHOR</h2>
            <div><p>
              <a href='problemset.php?search=$row->author'>$str</a>
            </p></div>
HTML;
      }
      ?>
  
      <?php
      if (!isset($_GET['cid'])) { // hide source if the problem is in contest
          $str=sss($row->source);
            $view_source = "<div pid='".$row->problem_id."' fd='source' class='center'>\n";
            $view_source .= show_category($str,"default");
            if(HAS_PRI("edit_".$set_name."_problem")) {
              $view_source .="<span><span class='am-icon-plus' pid='$row->problem_id' style='cursor: pointer;' onclick='problem_add_source(this,\"$row->problem_id\");'></span></span>&nbsp;\n";
            }
            $view_source .= "</div>";
            if($str || HAS_PRI("edit_".$set_name."_problem")) {
              echo <<<HTML
                <h2>$MSG_Source</h2>
                <div><p>
                $view_source
                </p></div>
HTML;
          }
      }
      ?>
    
      <?php if ($can_see_video || HAS_PRI("watch_solution_video")): ?>
        <h2><?php echo $MSG_SolutionVideo ?></h2>
          <?php if (file_exists("upload/video/".md5($real_id)."pfb.mp4")): ?>
          <form action="solution_video.php" method="POST">
            <input type="hidden" name="pid" value="<?php echo $real_id ?>" placeholder="">
            <button class="am-btn am-btn-success am-btn-lg"><?php echo $MSG_WatchVideo ?></button>
          </form>
          <?php else: ?>
          <button disabled="1" class="am-btn am-btn-default am-btn-lg"><?php echo $MSG_NO.$MSG_SolutionVideo ?></button>
          <?php endif ?>
        <div style="display: block; color: grey; padding-bottom: 20px;">
          *if you see this button, it means you've submited more than <?php echo $VIDEO_SUBMIT_TIME ?> times.
        </div>
      <?php endif ?>
  
    <!-- 提交等按钮 start -->
    <div class="am-text-center">
      <a href="
      <?php
      if ($pr_flag){
          echo "submitpage.php?id=$id";
      }else{
          echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
      }
      ?>
    " style="color:white">
        <button type="button" class="am-btn am-btn-sm am-btn-success "><?php echo $MSG_SUBMIT ?></button>
      </a>
        <?php
        if(!isset($_GET['cid']) || $is_practice==1) {
            echo<<<HTML
            <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
              <button type="button" class="am-btn am-btn-sm am-btn-primary ">
                $MSG_Codes
              </button>
            </a>
HTML;
        }
        if (HAS_PRI("edit_".$set_name."_problem")) {
            echo<<<HTML
          <a href="./admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white' $edit_target>
            <button type='button' class='am-btn am-btn-sm am-btn-danger '>
              $MSG_EDIT
            </button>
          </a>
          <a href="./admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white' target="_blank">
            <button type='button' class='am-btn am-btn-sm am-btn-warning '>
              $MSG_TestData
            </button>
          </a>

HTML;
        }
        ?>
    </div>
    <!-- 提交等按钮 end -->

</div>
<?php
  require_once("footer.php");
  include("js.php");
?>

<!-- ajax for adding user's own tag -->
<script>
    $("#tagSubmit").click(function(){
        $.ajax({
            url: 'addTag.php',
            type: 'post',
            data: $("#tagForm").serialize(),
            async: false,
            success: function(data,status){
                //alert(data+"\r\n"+status);
                window.location.reload();
            },
        });
    });
</script>
