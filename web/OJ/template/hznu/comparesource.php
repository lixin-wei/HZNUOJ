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

