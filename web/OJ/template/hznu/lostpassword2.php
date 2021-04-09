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

<?php $title=$MSG_LOST_PASSWORD;?>
<?php include "header.php" ?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <br>
      <h3><?php echo $MSG_LOST_PASSWORD."-Step 2" ?></h3>
      <?php echo $MSG_LOSTPASSWORD_HELP2 ?>
    <hr>
    <form action="lostpassword2.php" method="post" class="am-form am-form-horizontal">
      <div class="am-form-group">
        <label for="username" class="am-u-sm-4 am-form-label"><?php echo $MSG_USER_ID ?>: </label>
        <div class="am-u-sm-8">
          <input type="text" name="user_id" id="username" autocomplete="off" value="" style="width:300px;" maxlength="30" required>
        </div>  
      </div> 
      <div class="am-form-group">
        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Securekey ?>: </label>
        <div class="am-u-sm-8">
          <input type="text" name="lost_key" id="pwd" autocomplete="off" value="" style="width:300px;" maxlength="30" required>
        </div>  
      </div>
      <div class="am-form-group">
        <label for="vcode" class="am-u-sm-4 am-form-label"><?php echo $MSG_VCODE ?>: </label>
        <div class="am-u-sm-1"><input name="vcode" type='text' style="width:100px;" maxlength="4" autocomplete="off" required></input></div>
        <div class="am-u-sm-6"><img style='width:100px; height:35px; cursor:pointer;' alt="click to change" src='vcode.php' onclick="this.src='vcode.php#'+Math.random()"></div>
      </div>
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-5 am-u-end">
          <input type="submit" name="submit" value="<?php echo $MSG_RESET.$MSG_PASSWORD ?>" class="am-btn am-btn-primary am-btn-sm am-fl">
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>
<?php include "footer.php" ?>
