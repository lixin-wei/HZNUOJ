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

<?php $title="Login";?>
<?php include "header.php" ?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered" style="max-width: 600px;">
    <br>
      <h3>Login Page</h3>
    <hr>
    <form action="login.php" method="post" class="am-form am-form-horizontal">
      <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
      <div class="am-form-group">
        <label for="username" class="am-u-sm-4 am-form-label">User ID: </label>
        <div class="am-u-sm-8">
          <input type="text" name="user_id" id="username" value="" placeholder="Your user ID" style="width:100%;">
        </div>  
      </div>
      <div class="am-form-group">
        <label for="pwd" class="am-u-sm-4 am-form-label">Password: </label>
        <div class="am-u-sm-8">
          <input type="password" name="password" id="pwd" value="" placeholder="Your password" style="width:100%;">
        </div>  
      </div>
      <div class="am-form-group">
        <label for="pwd" class="am-u-sm-4 am-form-label">Contest ID: </label>
        <div class="am-u-sm-8">
          <input type="text" name="contest_id" id="contest_id" value="" placeholder="Don't input if you are not team account" style="width:100%;">
        </div>
      </div>
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-8 am-u-end" style="text-align: center">
            <div style="display: inline-block;">
              <input type="submit" name="submit" value="Login" class="am-btn am-btn-primary am-btn-sm am-fl" style="width: 100px;">
            </div>
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>
<?php include "footer.php" ?>
