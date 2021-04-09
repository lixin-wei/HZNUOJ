<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.24
   * last modified
   * by yybird
   * @2016.05.12
  **/
?>

<?php $title=$MSG_REGISTER;?>
<?php
  include "header.php";
?>
<link rel="stylesheet" href="./plugins/emailAutoComplete/emailAutoComplete.css"/>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h1 style="margin-top:40px; margin-bottom: 0px;"><?php echo $MSG_REG_INFO ?></h1>
    <hr>
    <form class="am-form am-form-horizontal" action="register.php" method="post"  data-am-validator >
    <?php include_once "./include/set_post_key.php"?>
    <div class="am-form-group">
      <label for="username" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_USER_ID ?>:
      </label>
      <div class="am-u-sm-8">
        <input type="text" name="user_id" id="username" value="" placeholder="3-20位只能包含字母和数字的用户名" style="width:300px;" minlength="3" maxlength="20" pattern="^[a-zA-Z0-9]{3,20}$" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="pwd" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_PASSWORD ?>:
      </label>
      <div class="am-u-sm-8">
        <input type="password" name="password" id="pwd" value="" minlength="6" maxlength="22" placeholder="设定6-22位密码" style="width:300px;"
          pattern="^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="rname" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_REPEAT_PASSWORD ?>:
      </label>
      <div class="am-u-sm-8">
        <input type="password" id="rname" name="rptpassword" value="" style="width:300px;" placeholder="<?php echo $MSG_REPEAT_PASSWORD ?>"
          data-equal-to="#pwd" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="nc" class="am-u-sm-4 am-form-label"><?php echo $MSG_NICK ?>: </label>
      <div class="am-u-sm-8">
        <input name="nick" type="text" id="nc" placeholder="限20个以内的汉字/字母/数字/下划线" style="width:300px;" value="" maxlength="20" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$"/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="school" class="am-u-sm-4 am-form-label"><?php echo $MSG_SCHOOL ?>: </label>
      <div class="am-u-sm-8">
        <input type="text" id="school" name="school" value="" style="width:300px;" maxlength="20" placeholder="限20个以内的汉字/字母/数字" pattern="^[\u4e00-\u9fa5a-zA-Z0-9]{1,20}$"/>
      </div>
    </div>
    <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Class ?>:</label>
      <div class="am-u-sm-8">
        <select name="class" data-am-selected="{searchBox: 1, maxHeight: 400, btnWidth:'300px'}">
        <?php 
          foreach ($classList as $c){
              if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
              foreach ($c[1] as $cl){
                echo "<option value='$cl'>$cl</option>\n";
              }
              echo "</optgroup>\n";
          }
        ?>
        </select>
      </div>
    </div>
    <div class="am-form-group">
      <label for="school" class="am-u-sm-4 am-form-label"><?php echo $MSG_StudentID ?>: </label>
      <div class="am-u-sm-8">
        <input type="text" id="stu_id" name="stu_id"value="" style="width:300px;" maxlength="20" placeholder="20位以内的字母+数字的学号" pattern="^[a-zA-Z0-9]{1,20}$"/>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-4 am-form-label"><?php echo $MSG_REAL_NAME ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" id='real_name' style="width:300px;" value="" name="real_name" maxlength="10" placeholder="20字以内的中文或英文姓名" pattern="^[\u4e00-\u9fa5a-zA-Z]{1,20}$">
      </div>
    </div>
    <?php } ?>
    <div class="am-form-group">
      <label for="email" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_EMAIL ?>:
      </label>
      <div class="am-u-sm-8 parentCls">
        <input class="inputElem" type="email" id="email" value="" name="email" style="width:300px;" placeholder="<?php echo $MSG_EMAIL?>" autocomplete="off" 
          required/>
      </div>
    </div>
    <?php if (isset($OJ_REG_NEED_CONFIRM) && ($OJ_REG_NEED_CONFIRM=="pwd" || $OJ_REG_NEED_CONFIRM=="pwd+confirm")) { ?>
    <div class="am-form-group">
      <label for="regcode" class="am-u-sm-4 am-form-label"><font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_REG_CODE ?>: </label>
		<div class="am-u-sm-8">
		<input name="regcode" type='password' style="width:300px;" size=4 required></input></div>
    </div>
    <?php } ?>
    <?php if (isset($OJ_VCODE)&&$OJ_VCODE) { ?>
    <div class="am-form-group">
      <label for="vcode" class="am-u-sm-4 am-form-label"><font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_VCODE ?>: </label>
		<div class="am-u-sm-1">
		<input name="vcode" type='text' style="width:150px;" size=4 maxlength="4" autocomplete="off" required></input></div>
        <div class="am-u-sm-5">
        <img style='width:100px; height:35px; cursor:pointer;' alt="click to change" src='vcode.php' onclick="this.src='vcode.php#'+Math.random()"></div>
    </div>
    <?php } ?>
    <div class="am-from-group">
      <div class="am-cf am-u-sm-offset-4 am-u-sm-3 am-u-end">
            <input type="submit" name="submit" value="<?php echo $MSG_REGISTER ?>" class="am-btn am-btn-primary am-btn-sm am-fl am-btn-block">
          </div>
        </div>
    </div>
    </form>
  </div>
  <br>
</div>
<?php include "footer.php" ?>
<script type="text/javascript" src="./plugins/emailAutoComplete/emailAutoComplete.js"></script>