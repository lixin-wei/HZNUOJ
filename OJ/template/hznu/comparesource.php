<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.05.26
  **/
?>

<?php 
  $title="Compare Source Code";
?>
<div class="am-container">
  <div class="am-jumbotron">
    <div id='main'>
      <link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
      <link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 
      <script src='highlight/scripts/shCore.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
      <script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
      <script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>
      <script language='javascript'> 
        SyntaxHighlighter.config.bloggerMode = false;
        SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
        SyntaxHighlighter.all();
      </script>
      <!-- Requires jQuery -->
      <script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script> 
      <!-- Requires CodeMirror 2.16 -->
      <script type="text/javascript" src="mergely/codemirror.js"></script>
      <link type="text/css" rel="stylesheet" href="mergely/codemirror.css" />
      <!-- Requires Mergely -->
      <script type="text/javascript" src="mergely/mergely.js"></script>
      <link type="text/css" rel="stylesheet" href="mergely/mergely.css" />
      <script type="text/javascript">
        $(document).ready(function () {
          $('#compare').mergely({
            cmsettings: { readOnly: false, lineWrapping: true }
          });
          $.ajax({
            type: 'GET', async: true, dataType: 'text',
            url: 'getsource.php?id=<?php echo intval($_GET['left'])?>',
            success: function (response) {
              $('#compare').mergely('lhs', response);
            }
          });
          $.ajax({
            type: 'GET', async: true, dataType: 'text',
            url: 'getsource.php?id=<?php echo intval($_GET['right'])?>',
            success: function (response) {
              $('#compare').mergely('rhs', response);
            }
          });
        });
      </script>
      <div id="mergely-resizer">
        <div id="compare" >
      </div>
    </div>
  </div><!--end main-->
</div><!--end wrapper-->

