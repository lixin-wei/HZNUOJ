<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/header.php";
?>
<div class="am-container" style="padding-top: 20px;">
<?php
$Paserdown = new Parsedown();
echo $Paserdown->text($announcement);
?>
</div>



<?php require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/footer.php" ?>