<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/header.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/config.php";
$sql="SELECT announcement, title FROM formal_contest WHERE id = $contest_id";
$res=$mysqli->query($sql)->fetch_array();
$announcement = $res['announcement'];
$title = $res['title'];
?>
<div class="am-container" style="padding-top: 20px;">
    <h1>标题：</h1>
    <div>
        <input class="am-form-field am-radius" id="title" value="<?php echo htmlentities($title) ?>" />
    </div>
    <h1>通知内容：</h1>
    <div>
        <textarea class="am-form-field am-radius" id="announcement" cols="120" rows="15"><?php echo htmlentities($announcement)?></textarea>
    </div>
    <h1>密码</h1>
    <input type="password" class="am-form-field am-radius" id="password">
    <hr/>
    <div class="am-text-center">
        <button id="submit" class="am-btn am-btn-primary">提交</button>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/footer.php" ?>

<script type="text/javascript">
    $("#submit").click(function(){
        $.post("ajax/update_contest_info.php", {
            title: $("#title").val(),
            announcement: $("#announcement").val(),
            password: $("#password").val()
        }, function (data) {
            console.log(data);
            alert(data['msg']);
            if (data['result']) {
                location.reload();
            }
        }, "json");
    });
</script>