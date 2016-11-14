<?php
$title="杭州师范大学程序设计竞赛";
require_once "header.php";
?>


<style type="text/css" media="screen">
	.big-title{
		background: url("img/title_bk.png");
		height: 300px;
	}
</style>
<div class="big-title">
	<div class="am-g am-text-center" style="padding-top: 75px;">
		<span style="font-size: 30pt;">第十届杭州师范大学程序设计竞赛</span>
	</div>
	<div class="am-g" style="margin-top: 60px; width: 70%;">
		<div class="am-u-sm-12">
			<ul class="am-nav am-nav-pills am-nav-justify">
			  <li class="am-active"><a href="#">通知与注意事项</a></li>
			  <li><a href="register.php">注册与报名</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="am-container" style="padding-top: 20px;">
<?php
$sql="SELECT content FROM contest_hznu_2016_news";
$res=$mysqli->query($sql);
$content=$res->fetch_array()[0];
echo $content;
?>
</div>



<?php require_once "../template/hznu/footer.php" ?>