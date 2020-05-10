<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php $title=$MSG_LOGIN;?>
<?php include "header.php" ?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered" style="max-width: 600px;">
    <br>
      <h2><?php echo $MSG_LOGIN ?></h2>
    <hr>
    <form action="login.php" method="post" class="am-form am-form-horizontal">
      <?php include_once "./include/set_post_key.php"?>
      <div class="am-form-group">
        <label for="username" class="am-u-sm-4 am-form-label"><?php echo $MSG_USER_ID ?>: </label>
        <div class="am-u-sm-8">
          <input type="text" name="user_id" id="username" value="" style="width:100%;" required>
        </div>  
      </div>
      <div class="am-form-group">
        <label for="pwd" class="am-u-sm-4 am-form-label"><?php echo $MSG_PASSWORD ?>: </label>
        <div class="am-u-sm-8">
          <input type="password" name="password" id="pwd" value="" style="width:100%;" required>
        </div>  
      </div>
       <?php if($OJ_VCODE): ?>
        <div class="am-form-group">
      <label for="vcode" class="am-u-sm-4 am-form-label"><font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_VCODE ?>: </label>
      <div class="am-u-sm-1">
		<input name="vcode" type='text' style="width:100px;" size=4 maxlength="4" autocomplete="off" required></input></div>
        <div class="am-u-sm-5">
        <img style='width:100px; height:35px; cursor:pointer;' alt="click to change" src='vcode.php' onclick="this.src='vcode.php#'+Math.random()"></div>
    </div>
    <?php endif ?>
      <div class="am-form-group">
        <label for="pwd" class="am-u-sm-4 am-form-label"><?php echo $MSG_CONTEST ?>ID: </label>
        <div class="am-u-sm-8">
          <input type="text" name="contest_id" id="contest_id" value=""autocomplete="off"  placeholder="<?php echo $MSG_HELP_TeamAccount_login ?>" style="width:100%;">
        </div>
      </div>
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-8 am-u-end" style="text-align: center">
            <div class="am-u-sm-6" style="display: inline-block;">
              <input type="submit" name="submit" value="<?php echo $MSG_LOGIN ?>" class="am-btn am-btn-primary am-btn-sm am-fl" style="width: 100px;"> 
            </div>
            <div class="am-u-sm-6" style="display: inline-block;">
              <a class="am-btn am-btn-warning am-btn-sm am-fl" href="lostpassword.php"><?php echo $MSG_LOST_PASSWORD ?></a>
            </div>
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>
<?php include "footer.php" ?>
