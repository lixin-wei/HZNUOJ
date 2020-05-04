<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.23
   * last modified
   * by yybird
   * @2016.03.23
  **/
?>
<?php
$title=$MSG_FAQ;
require_once("header.php");
require_once "./plugins/Parserdown.php";
?>
<style>
  .red {
    color: red;
  }
  .green {
    color: green;
  }
  .box{
    border: 1px solid #eee;
    padding: 30px;
    margin: 25px 0 15px 0;
    box-shadow: 2px 2px 10px 0 #ccc;
  }
  .qa {
    padding-top: 10px;
    padding-bottom: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
  }
  #title-index {
    font-size: 95%;
  }
  @media screen and (max-width:640px){
    #content {
      display: none;
    }
  }
</style>
<div class="am-container">
  <h1 style="margin-top: 50px;">HZNU Online Judge FAQ</h1>
  <hr>
  <div class="am-g">
    <div class="am-u-md-3" id="content">
      <div class="box" data-am-sticky="{top:60}">
        <span style="font-size: larger; margin-bottom: 20px;">Content</span>
        <ul id="title-index" class="am-list">
        </ul>
      </div>
    </div>
  
    <div class="am-u-md-9">
      <div class="box" id="markdown">
          <?php
          $Paserdown = new Parsedown();
          $sql = "SELECT * FROM faqs";
          $markdown = $mysqli->query($sql)->fetch_array()['content'];
          echo $Paserdown->text($markdown);
          ?>
      </div>
    </div>
  </div>
</div><!--end container-->
<?php require_once("footer.php");?>
<script>
  
  //auto generate title index
  $ul = $("#title-index");
  var i = 1;
  $("h2").each(function () {
      $(this).attr("id","p-"+i);
      $ul.append("<li id=\"index-" + i + "\"><a href=\"#\" class=\"am-text-truncate\">" + $(this).html() + "</a></li>");
      i++;
  });
  $("li[id^=index]").click(function () {
      n = $(this).attr("id").substring(6);
      $('html, body').animate({
          scrollTop: $("#p-"+n).offset().top - 80
      }, 500);
  });
  $("#markdown>table").addClass("am-table am-table-striped am-table-bordered");
  $("#markdown>h2").append("<hr/>");
</script>
<!-- highlight.js END-->