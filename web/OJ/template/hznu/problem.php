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
  else require_once("header.php");
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
      /*background: none repeat scroll 0 0 #8DB8FF;*/
      font-family: Monospace;
      font-size: 18px;
      white-space: pre;
  }
</style>
<!-- Sample Input 和 Sample Output 的背景色 end -->


<div class="am-container">
  <h1 style="text-align:center;margin-top:40px;"><?php echo $row->title?></h1>
  <?php
    if ($show_tag && !isset($_GET['cid'])) { 
  ?> 
      <form id='tagForm' class='am-form am-form-inline' style="text-align:center" action=''>
        <div class="am-form-group">
  <?php
          echo "<span><i class='am-icon-tag'></i> Tags: </span>";
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
        <div class='am-form-group'><span> My tag: &nbsp;</span></div>
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
    Time Limit:&nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo $row->time_limit?> s</span> 
    &nbsp;&nbsp;&nbsp;&nbsp; Memory Limit: &nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo $row->memory_limit?> MB</span></span>
    <?php if($row->spj) echo "<span class='am-badge am-badge-primary'>Special Judge</span>"?>
  </div>
  <div style="text-align:center;">
    Submission：<span class="am-badge am-badge-secondary"><?php echo $row->submit?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    AC：<span class="am-badge am-badge-success"><?php echo $row->accepted?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php
      $score_class = "am-badge-default";
      if ($row->score >= 82) $score_class='am-badge-danger';
      else if ($row->score >= 64) $score_class='am-badge-warning';
      else if ($row->score >= 46) $score_class='am-badge-primary';
      else if ($row->score >= 28) $score_class='am-badge-secondary';
    ?>
    Score：<span class='am-badge <?php echo $score_class ?>'><?php echo $row->score?></span>
  </div>
  <?php
      $sinput=str_replace("<","&lt;",$row->sample_input);
      $sinput=str_replace(">","&gt;",$sinput);
      $soutput=str_replace("<","&lt;",$row->sample_output);
      $soutput=str_replace(">","&gt;",$soutput);

      // 用中文全角空格替换\t，以免在一些浏览器（例如chrome）中出现\t显示不正常的问题
      $sinput=str_replace("\t"," ",$sinput);
      $soutput=str_replace("\t"," ",$soutput);
  ?>
  <br />

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
      <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
    </a>&nbsp;&nbsp;
    <?php
      if (isset($_GET['cid'])) {
    ?>
    <a href="problemstatus.php?<?php echo "cid=".$cid."&id=".$row->problem_id?>" style="color:white"><button type="button" class="am-btn am-btn-sm am-btn-primary ">Status</button></a>&nbsp;&nbsp;
    <?php
      } else {

    ?>
    <a href="problemstatus.php?id=<?php echo $row->problem_id?>" style="color:white"><button type="button" class="am-btn am-btn-sm am-btn-primary ">Status</button></a>&nbsp;&nbsp;
    <?php
      }
      if (HAS_PRI("edit_".$set_name."_problem")) {
    ?>
        <a href="admin/problem_edit.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>" style='color:white'><button type='button' class='am-btn am-btn-sm am-btn-danger '>Edit</button></a>&nbsp;&nbsp;
        <a href='./admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes' style='color:white'><button type='button' class='am-btn am-btn-sm am-btn-warning '>Test Data</button></a>
    <?php
      }
    ?>
  </div>
  <!-- 提交等按钮 end -->

  <h2><b><font color='#0000cd'>Description</font></b></h2>
  <p>
    <?php 
      //编码转义未解决！
      //$tt=htmlspecialchars($row->description);
      echo sss($row->description);
    ?>
  </p>

  <h2><b><font color='#0000cd'>Input</font></b></h2>
  <p>
    <?php echo sss($row->input);?>
  </p>

  <h2><b><font color='#0000cd'>Output</font></b></h2>
  <p>
    <?php echo sss($row->output)?>
  </p>

  <h2><b><font color='#0000cd'>Sample Input</font></b></h2>
  <?php echo "<pre><span class=sampledata>".($sinput)."</span></pre>";?>


  <h2><b><font color='#0000cd'>Sample Output</font></b></h2>
  <?php echo "<pre><span class=sampledata>".($soutput)."</span></pre>";?>

  <h2><b><font color='#0000cd'>Hint</font></b></h2>
  <?php echo "<div>".$row->hint."</div>"; ?>


  <h2><b><font color='#0000cd'>Author</font></b></h2>
  <?php 
    echo "<div><p><a href='problemset.php?search=$row->author'>".nl2br($row->author)."</a></p></div>"; 
  ?>
  <?php
    if (!isset($_GET['cid'])) { // hide source if the problem is in contest
  ?>
  <h2><b><font color='#0000cd'>Source</font></b></h2>
  <?php 
    echo "<div><p><a href='problemset.php?search=$row->source'>".nl2br($row->source)."</a></p></div>";
    } 
  ?>
  <?php
    $video_submit_time=10;
    $can_see_video=false; 
    if(isset($_SESSION['user_id'])){
      $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$real_id' AND result='4'";
      $res=$mysqli->query($sql);
      if($res->num_rows) $can_see_video=true;
      $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$real_id'";
      $res=$mysqli->query($sql);
      if($res->num_rows>$video_submit_time) $can_see_video=true;
    }
  ?>
  <?php if ($can_see_video): ?>
    <h2><b><font color='#0000cd'>Solution Video</font></b></h2>
    <?php

    ?>
    <?php if (file_exists("upload/video/{$real_id}.mp4")): ?>
      <form action="solution_video.php" method="POST">
        <input type="hidden" name="pid" value="<?php echo $real_id ?>" placeholder="">
        <button class="am-btn am-btn-success am-btn-lg">Click To See The Video</button>
      </form>
    <?php else: ?>
      <button disabled="1" class="am-btn am-btn-default am-btn-lg">No Solution Video</button>
    <?php endif ?>
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
      <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
    </a>&nbsp;&nbsp;
    <a href="problemstatus.php?id=<?php echo $row->problem_id?>" style="color:white"><button type="button" class="am-btn am-btn-sm am-btn-primary ">Status</button></a>&nbsp;&nbsp;
    <?php
      if (HAS_PRI("edit_".$set_name."_problem")) {
    ?>
        <a href="admin/problem_edit.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>" style='color:white'><button type='button' class='am-btn am-btn-sm am-btn-danger '>Edit</button></a>&nbsp;&nbsp;
        <a href='./admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes' style='color:white'><button type='button' class='am-btn am-btn-sm am-btn-warning '>Test Data</button></a>
    <?php
      }
    ?>

  </div>
  <!-- 提交等按钮 end -->

</div>
<?php require_once("footer.php"); ?>

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
<!-- highlight.js START-->
<!-- <link href='highlight/styles/github-gist.css' rel='stylesheet' type='text/css'/> -->
<!-- <script src='highlight/highlight.pack.js' type='text/javascript'></script> -->
<!-- <script src='highlight/highlightjs-line-numbers.min.js' type='text/javascript'></script> -->

<link href="//cdn.bootcss.com/highlight.js/9.7.0/styles/github-gist.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/highlight.js/9.7.0/highlight.min.js"></script>
<script src="//cdn.bootcss.com/highlightjs-line-numbers.js/1.1.0/highlightjs-line-numbers.min.js"></script>
<style type="text/css">
  .hljs-line-numbers {
      text-align: right;
      border-right: 1px solid #ccc;
      color: #999;
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
  }
  code{
    background: transparent;
  }
  pre.prettyprint{
    background: transparent;
  }
  .main{
    background-color: red !important;
  }
</style>
<script>
  hljs.initHighlightingOnLoad();
  hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->
<!--auto folding code START-->
<script type="text/javascript">
$(document).ready(function(){
  $("pre code").parent().before("<button class='am-btn am-btn-block am-btn-default am-text-xs'>Toggle Code</button>");
  $("pre code").parent().hide(0);


  $("button").click(function(){
    $(this).next().toggle(100);
  });
});
</script>
<!--auto folding code END-->