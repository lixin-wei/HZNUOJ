<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.js"></script>

<?php
$view_marquee_msg="分数不会进行自动更新，需要点击查看用户信息后才会更新。欢迎使用HZNUOJ V1.4 如发现任何bug可以向管理员反馈yybird@hsACM.cn";

?>
<script>
$(document).ready(function(){
  var msg="<marquee  id=broadcast scrollamount=2 scrolldelay=50 onMouseOver='this.stop()' onMouseOut='this.start()' class=toprow><?php echo $view_marquee_msg?></marquee>";
  $(".jumbotron").prepend(msg);
});

</script>

