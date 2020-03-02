<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.24
   * last modified
   * by yybird
   * @2016.05.25
  **/
?>
<?php 
  if (is_numeric($cid) && !isset($_GET['normal_mod'])) {
      $_GET['cid']=$cid;
      require_once("contest_header.php");
  }else require_once("header.php");
  require_once("include/const.inc.php");
?>
<div class="am-container">
  <!-- Main component for a primary marketing message or call to action -->
  <div class="jumbotron">


  <style type="text/css">
    .solution-info {
      display: inline-block;
      margin: 5px;
    }
  </style>
    <?php
      if ($ok==true){
        $res_class="danger";
        $time="-";
        $memory="-";
        if($sresult==4){//AC
          $res_class="success";
          $time=$stime."ms";
          $memory=$smemory."KB";
        }
        else if($sresult<=3){//pending or rejudging or compling or judging
          $res_class="default";
        }

        echo "<hr>";
        echo "<div class='am-text-center'>";
        echo "<div class='solution-info'>";
        echo "$MSG_PROBLEM_ID: ";
        if (is_numeric($cid)){
          $p_lable=PID($num);
          echo "<span class='am-badge am-badge-secondary am-text-sm'><a href='problem.php?cid=$cid&pid=$num' style='color: white;'>$p_lable</a>";
        }
        else echo "<span class='am-badge am-badge-primary am-text-sm'><a href='problem.php?id=$pid' style='color: white;'>$pid</a>";
        echo "</span>";
        echo "</div>";
        $html_sup = "";
        $html_link = "";
        if($is_temp_user) {
          $html_sup = "<sup title='this is a temporary user in a special contest'><a href='/OJ/contest.php?cid=$cid' style='color: white;'>$cid</a></sup>";
        }
        else {
          $html_link = "href='/OJ/userinfo.php?user=$suser_id'";
        }
        echo <<<HTML
          <div class='solution-info'>
            $MSG_RESULT: <span class='am-badge am-badge-$res_class am-text-sm'>$judge_result[$sresult]</span>
          </div>
          <div class='solution-info'>
            $MSG_TIME: <span class='am-badge am-badge-warning am-text-sm'>$time</span>
          </div>
          <div class='solution-info'>
            $MSG_MEMORY: <span class='am-badge am-badge-warning am-text-sm'>$memory</span>
          </div>
          <div class='solution-info'>
            $MSG_AUTHOR: <span class='am-badge am-badge-primary am-text-sm'>
            <a $html_link target='_blank' style='color: white;'>$suser_id</a>{$html_sup}
            </span>
          </div>
HTML;
        if(isset($_GET['normal_mod']) && $cid) {
          echo <<<HTML
          <div class='solution-info'>
          $MSG_CONTEST: <span class='am-badge am-badge-primary am-text-sm'>
HTML;
          if($ctitle){
            $ctitle = "【{$cid}】$ctitle";
            echo "<a href='contest.php?cid=$cid' style='color: white;'>$ctitle</a>";
          } else echo $cid;
          echo "</span></div>\n";
        }
        echo "</div><hr>\n";

          // ****mail function currently stashed
          // if($view_user_id!=$_SESSION['user_id'])
          //   echo "<a href='mail.php?to_user=$view_user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
          $brush=strtolower($language_name[$slanguage]);
        if ($brush=='pascal') $brush='delphi';
        if ($brush=='obj-c') $brush='c';
        if ($brush=='freebasic') $brush='vb';
        if ($brush=='swift') $brush='csharp';
        echo "<pre style='font-size:14px; background-color: transparent;'><code style='background-color: transparent;'>";
        echo htmlentities(str_replace("\r\n","\n",$view_source),ENT_QUOTES,"utf-8");
        echo "</code></pre>";

      } else {
        echo "<div am-text-center><h2>I am sorry, You could not view this code!</h2></div>";
      }
    ?>
  </div>
</div> <!-- /container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<?php include "footer.php" ?>
<!-- highlight.js START-->
<!-- <link href='highlight/styles/github-gist.css' rel='stylesheet' type='text/css'/> -->
<!-- <script src='highlight/highlight.pack.js' type='text/javascript'></script> -->
<!-- <script src='highlight/highlightjs-line-numbers.min.js' type='text/javascript'></script> -->

<link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet">
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>
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
</style>
<script>
  hljs.initHighlightingOnLoad();
  hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->
