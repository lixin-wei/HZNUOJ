<?php 
if(preg_match("/\/admin\/quixplorer\//i", $_SERVER['SCRIPT_NAME'])) {
  $baseDir="../..";//在admin/quixplorer目录下
  $urlbaseDir="..";//在admin/quixplorer目录下
} else {
  $baseDir="..";
  $urlbaseDir=".";
}
?>
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="<?php echo $baseDir ?>/plugins/jquery/jquery-3.1.1.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="<?php echo $baseDir ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $baseDir ?>/plugins/bootstrap/js/bootstrap-filestyle.min.js"></script>
<script src="<?php echo $baseDir ?>/plugins/bootstrap/js/bootstrap-select.min.js"></script>
<?php if($OJ_LANG=="cn") echo "<script src=\"$baseDir/plugins/bootstrap/js/defaults-zh_CN.min.js\"></script>"; ?>
</div><!-- container -->

</body>
</html>
