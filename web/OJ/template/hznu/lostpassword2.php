<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.12
   * last modified
   * by yybird
   * @2016.06.02
  **/
?>

<?php $title="Lost Password";?>
<?php include "header.php" ?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <br>
      <h3>Lost Password</h3>
    <hr>
    <form action="lostpassword2.php" method="post" class="am-form am-form-horizontal">
      <div class="am-form-group">
        <label for="username" class="am-u-sm-4 am-form-label">User ID: </label>
        <div class="am-u-sm-8">
          <input type="text" name="user_id" id="username" value="" placeholder="Your user ID" style="width:300px;">
        </div>  
      </div> 
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label">Key: </label>
        <div class="am-u-sm-8">
          <input type="text" name="lost_key" id="pwd" value="" placeholder="Key sended to your email" style="width:300px;">
        </div>  
      </div>
<!--       <div class="am-form-group">
  <label for="pwd" class="am-u-sm-4 am-form-label">Verify Code: </label>
  <div class="am-u-sm-8">
    <input name="vcode" size=4 type='text' style="width:300px;"><img alt="click to change" src='vcode.php' onclick="this.src='vcode.php#'+Math.random()">*</input>
    <input type="password" name="password" id="pwd" value="" placeholder="Your Email" style="width:300px;">
  </div>  
</div> -->
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-5 am-u-end">
          <input type="submit" name="submit" value="Confirm" class="am-btn am-btn-primary am-btn-sm am-fl">
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>
<?php include "footer.php" ?>
