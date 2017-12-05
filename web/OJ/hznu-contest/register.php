<?php
require_once "./header.php";
?>
<div class="am-container" style="padding-top: 50px;">
	<div class="am-g" style="margin-bottom: 20px;">
		<ul class="am-nav am-nav-tabs am-nav-justify">
			<li class="am-active" id="tab-list"><a href="#">已报名列表</a></li>
			<li id="tab-register"><a href="#" >我要报名</a></li>
		</ul>
	</div>
	<div id="content-list">
		<div class="am-g">
			<?php
			$sql="SELECT COUNT(*) FROM hznu_contest_user WHERE year = $year";
			$res=$mysqli->query($sql);
			$register_cnt=$res->fetch_array()[0];
			?>
			报名总人数: <?php echo $register_cnt ?>
			<table class="am-table" style="margin-top: 10px;">
				<thead>
					<tr>
						<th>学院</th>
						<th>学号</th>
						<th>班级</th>
						<th>姓名</th>
						<th>注册时间</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql="SELECT institute, stu_id, class, name, register_time, anonymous FROM hznu_contest_user WHERE year = $year ORDER BY register_time DESC";
				$res=$mysqli->query($sql);
				while($row=$res->fetch_array()){
					echo "<tr>";
					echo "<td>".htmlentities($row['institute'])."</td>";
					if($row['anonymous']) echo "<td>****</td>";
					else echo "<td>".htmlentities($row['stu_id'])."</td>";
					echo "<td>".htmlentities($row['class'])."</td>";
					if($row['anonymous']) echo "<td>****</td>";
					else echo "<td>".htmlentities($row['name'])."</td>";
					echo "<td>".htmlentities($row['register_time'])."</td>";
					echo "</tr>";
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="content-register" style="display: none;">
		<div class="am-g" style="max-width: 800px;">
			<form class="am-form" action="contest_register.php" method="post" data-am-validator id="form_register">
        <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
				<div class="am-form-group">
					<label for="institute">学院</label>
					<select name="institute" id="institute" required>
						<option value="人文学院">人文学院</option>
						<option value="体育与健康学院">体育与健康学院</option>
						<option value="医学院">医学院</option>
						<option value="外国语学院">外国语学院</option>
						<option value="政治与社会学院">政治与社会学院</option>
						<option value="教育学院">教育学院</option>
						<option value="文化创意学院">文化创意学院</option>
						<option value="材料与化学化工学院">材料与化学化工学院</option>
						<option value="杭州国际服务工程学院" selected="">杭州国际服务工程学院</option>
						<option value="理学院">理学院</option>
						<option value="生命与环境科学学院">生命与环境科学学院</option>
						<option value="经亨颐学院">经亨颐学院</option>
						<option value="经济与管理学院">经济与管理学院</option>
						<option value="美术学院">美术学院</option>
						<option value="阿里巴巴商学院">阿里巴巴商学院</option>
						<option value="沈钧儒法学院">沈钧儒法学院</option>
					</select>
				</div>
				<div class="am-form-group">
					<label for="stu_id">学号</label>
					<input type="text" class="js-pattern-number" id="stu_id" name="stu_id" placeholder="请输入学号" required minlength="10">
				</div>
				<div class="am-form-group">
					<label for="class">班级</label>
					<input type="text" class="" id="class" name="class" placeholder="请输入班级" required minlength="4">
				</div>
				<div class="am-form-group">
					<label for="name">姓名</label>
					<input type="text" class="" id="name" name="name" placeholder="请输入姓名" required minlength="2">
				</div>
				<div class="am-form-group">
					<label for="phone">手机号码 (此项不会公布在已报名列表中)</label>
					<input type="text" class="" id="phone" name="phone" placeholder="请输入手机号码" required minlength="11" maxlength="11">
				</div>
				<div class="am-checkbox">
					<input type="checkbox" name="anonymous" id="anonymous" value="yes"> 匿名
				</div>
				<div class="am-text-center" style="margin: 20px 0;" id="register_submit">
					<button type="submit" class="am-btn am-btn-primary" id="submit">确认提交</button>
					<button type="submit" class="am-btn am-btn-success" id="query">咨询注册结果</button>
				</div>
				<div class="am-text-center am-text-secondary">
					*咨询注册结果供匿名报名者使用，重新填入你注册时的信息，点击咨询注册结果，即可咨询是否注册成功，后台会比对你的学号、学院、班级和姓名，只有全部匹配才会提示注册成功。
				</div>
			</form>
		</div>
	</div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/footer.php" ?>

<script>
	var aim="contest_register.php";
	$("#tab-list").click(function(){
		$(this).addClass("am-active");
		$("#tab-register").removeClass("am-active");
		$("#content-list").fadeIn();
		$("#content-register").hide();
	});
	$("#tab-register").click(function(){
		$(this).addClass("am-active");
		$("#tab-list").removeClass("am-active");
		$("#content-list").hide();
		$("#content-register").fadeIn();
	});
	$("#submit").click(function(){
		aim="contest_register.php";
	});
	$("#query").click(function(){
		aim="register_query.php";
	});
	$("#form_register").validator({
		submit: function(){
			if(this.isFormValid()){
				$.ajax({
					type: "POST",
					url: aim,
					data: {
						institute: $("#institute").val(),
						stu_id: $("#stu_id").val(),
						class: $("#class").val(),
						name: $("#name").val(),
						anonymous: $("#anonymous").is(":checked"),
						phone: $("#phone").val(),
					},
					context: this,
					success: function(data){
						alert(data);
						if(aim=="contest_register.php" && data=="注册成功!")
							window.location.reload();
				    },
				    complete: function(){
				    	console.log("ajax complete!");
				    },
				    error: function(xmlrqst,info){
				    	console.log(info);
				    }
				})
			}
			return false;
		}
	});
</script>