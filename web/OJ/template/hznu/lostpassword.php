<?php
/**
 * This file is created
 * by yybird
 * @2016.05.12
 * last modified
 * by yybird
 * @2016.05.12
 **/
?>

<?php $title="Lost Password";?>
<?php include "header.php" ?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <br>
    <h3>Lost Password</h3>
    <hr>
    <form action="lostpassword.php" method="post" class="am-form am-form-horizontal">
      <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
      <div class="am-form-group">
        <label for="username" class="am-u-sm-4 am-form-label">User ID: </label>
        <div class="am-u-sm-8">
          <input type="text" name="user_id" id="username" value="" placeholder="Your user ID" style="width:300px;">
        </div>
      </div>
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label">Email: </label>
        <div class="am-u-sm-8">
          <input type="text" name="email" id="pwd" value="" placeholder="Your Email" style="width:300px;">
        </div>
      </div>
      <div class="am-form-group">
        <label for="pwd" class="am-u-sm-4 am-form-label">Verify Code: </label>
        <div class="am-u-sm-4"><input name="vcode" size=4 type='text' style="width:150px;"></input></div>
        <div class="am-u-sm-4"><img style='width:100px;height:35px'alt="click to change" src='vcode.php' onclick="this.src='vcode.php#'+Math.random()"></div>
      </div>
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-5 am-u-end">
          <input type="submit" name="submit" value="Send Mail" class="am-btn am-btn-primary am-btn-sm am-fl">
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>
<?php include "footer.php" ?>
