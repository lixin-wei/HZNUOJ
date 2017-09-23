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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link type="text/css" rel="stylesheet" href="plugins/mergely/mergely.css" />
</head>
<body>
<div class="am-container">
  <div class="am-jumbotron">
    <div id='main'>
        <div id="compare" >
        </div>
    </div><!--end main-->
  </div><!--end wrapper-->
  <!-- Requires jQuery -->
  <script language="javascript" type="text/javascript" src="plugins/jquery/jquery-1.9.0.min.js"></script>
  <!-- Requires CodeMirror 2.16 -->
  <script type="text/javascript" src="plugins/mergely/codemirror.js"></script>
  <link type="text/css" rel="stylesheet" href="plugins/mergely/codemirror.css" />
  <!-- Requires Mergely -->
  <script type="text/javascript" src="plugins/mergely/mergely.js"></script>
  <script type="text/javascript">
      $(document).ready(function () {
          $('#compare').mergely({
              cmsettings: {
                  readOnly: false,
                  lineWrapping: true,
                  editor_height: 800
              }
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
</body>
</html>

