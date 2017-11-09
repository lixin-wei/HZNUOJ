<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.23
   * last modified
   * by yybird
   * @2016.04.12
  **/
?>

<?php
  $title="Submit";
  if(isset($_GET['id']))
    require_once("header.php");
  else
    require_once("contest_header.php");
  require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/const.inc.php";
?>
<div class="am-container" style="padding-top: 20px;">
  <?php
  if(isset($_GET['cid'])) {
      $title = "Submit solution: Problem {$PID[$pid]}";
  }
  else {
      $title = "Submit solution: Problem $id";
  }
  echo "<h1>$title</h1>"
  ?>
  <hr/>
  <div class="am-g">
    <form id="submit_form" action="/OJ/submit.php" method="post">
      <?php require_once $_SERVER["DOCUMENT_ROOT"]."/OJ/include/set_post_key.php" ?>
      <div class="am-u-md-10 am-u-md-centered">
        <div class="am-g am-text-center" style="margin-bottom: 20px;">
          <div class="am-u-md-6">
            <label for="language">Language: </label>
            <select id="language" name="language" data-am-selected="{searchBox: 1, maxHeight: 400}">
                <?php
                $lang_count=count($language_ext);
                if(isset($contest_langmask))
                    $langmask=$contest_langmask;
                else
                    $langmask=$OJ_LANGMASK;
                $lang=((int)$langmask)&((1<<($lang_count))-1);
                if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
                else $lastlang=0;
                for($i=0;$i<$lang_count;$i++){
                    $j = $language_order[$i];
                    if($lang&(1<<$j))
                        echo"<option value=$j ".( $lastlang==$j?"selected":"").">
                                ".$language_name[$j]."
                         </option>";
                }
                ?>
            </select>
          </div>
          <div class="m-u-md-6">
            <label for="language">Theme: </label>
            <select id="theme" name="theme" data-am-selected>
              <option value="xcode">Bright</option>
              <option value="monokai">Dark</option>
            </select>
          </div>
        </div>
        <div id="editor" style="wdith:100%; height: 500px; border: 1px solid #F0F0F0;"><?php if(isset($view_src))echo htmlentities($view_src); ?></div>
        <input type="hidden" id="source" name="source">
          <?php
          if(isset($_GET['cid'])) {
              echo "<input type='hidden' id='source' name='cid' value='$cid'>";
              echo "<input type='hidden' id='source' name='pid' value='$pid'>";
          }
          else {
              echo "<input type='hidden' id='source' name='id' value='$id'>";
          }
          ?>
      </div>
      <div class="am-g am-text-center" style="margin-top: 20px;">
        <button class="am-btn am-btn-success">Submit</button>
      </div>
    </form>
  </div>
</div>
<?php require_once("footer.php") ?>
<script src="/OJ/plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/theme-xcode.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/theme-monokai.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-c_cpp.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-pascal.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-java.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-ruby.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-batchfile.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-python.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-php.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-perl.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-csharp.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-objectivec.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-scheme.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-lua.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-javascript.js" type="text/javascript" charset="utf-8"></script>
<script src="/OJ/plugins/ace/mode-golang.js" type="text/javascript" charset="utf-8"></script>
<script>
    language_mod = ["c_cpp","c_cpp","pascal","java","ruby","batchfile","python","php","perl","csharp","objectivec","plain_text","scheme","c_cpp","c_cpp","lua","javascript","golang","python"];
    var editor = ace.edit("editor");
    var $obj_select_lang = $("#language");
    var lang = $obj_select_lang.val();
    editor.getSession().setMode("ace/mode/"+language_mod[lang]);
    editor.setTheme("ace/theme/xcode");
    $("#submit_form").submit(function () {
        $("#source").val(editor.getValue());
        return true;
    });
    
    $obj_select_lang.change(function () {
        lang = $(this).val();
        editor.getSession().setMode("ace/mode/"+language_mod[lang]);
    });
    
    $("#theme").change(function () {
        editor.setTheme("ace/theme/"+$(this).val());
    });
</script>
