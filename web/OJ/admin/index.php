<?php
require_once("admin-header.php");
if(isset($OJ_LANG)) require_once("../lang/$OJ_LANG.php");
?>
<html>
<head>
  <title>JudgeOnline Administration</title>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
        <iframe name="main" src="watch.php" width="100%" height="100%" frameborder="0"></iframe>
    </div>
  </div>
</body>
</html>
<script>
  $("li").click(function(){
    $("li").each(function(){
      $(this).removeClass("active");
    });
    $(this).addClass("active");
  });
</script>
<?php 
  require_once("admin-footer.php")
?>