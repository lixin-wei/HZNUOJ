<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.06.02
  **/
?>

<?php $title=$MSG_MODIFY_USER;?>
<?php require_once("header.php") ?>
<link rel="stylesheet" href="./plugins/emailAutoComplete/emailAutoComplete.css"/>
<div class="am-container">
  <h1 style="margin-top:40px; margin-bottom: 0px;"><?php echo $MSG_MODIFY_USER ?></h1>
  <hr>
  <form class="am-form am-form-horizontal" action="modify.php" method="post">
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_USER_ID ?>:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $_SESSION['user_id']?></label>
        <?php require_once('./include/set_post_key.php');?>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_NICK ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" maxlength="20" placeholder="限20个以内的汉字、字母、数字或下划线" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$" value="<?php echo htmlentities($row->nick)?>" name="nick">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_OldPasswd ?>:
      </label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="opassword" placeholder="输入6-22位的旧密码" maxlength="22" pattern="^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$" required>
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-3 am-u-sm-offset-1 am-form-label"><?php echo $MSG_NewPasswd ?>:</label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="npassword" minlength="6" maxlength="22" placeholder="设定6-22位的新密码" pattern="^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-3 am-u-sm-offset-1 am-form-label"><?php echo $MSG_REPEAT_PASSWORD ?>:</label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="rptpassword" placeholder="<?php echo $MSG_REPEAT_PASSWORD ?>">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_SCHOOL ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" maxlength="20" placeholder="限20个以内的汉字、字母、数字" value="<?php echo htmlentities($row->school)?>" name="school" pattern="^[\u4e00-\u9fa5a-zA-Z0-9]{1,20}$">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
      <font color='red'><b>*</b></font><?php echo $MSG_EMAIL ?>:
      </label>
      <div class="am-u-sm-8 parentCls">
        <input class="inputElem" type="email" style="width:340px;" value="<?php echo htmlentities($row->email)?>" name="email" autocomplete="off" required>
      </div>
    </div>
    <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
    <div class="am-text-center am-u-sm-8 am-u-sm-offset-4" style="margin-bottom: 15px;">
      <div style="width: 340px; color: grey; ">--The following items are set by admins--</div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_StudentID ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->stu_id)?>" name="stu_id" disabled>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_REAL_NAME ?>:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->real_name)?>" name="real_name" disabled>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label"><?php echo $MSG_Class ?>:</label>
      <div class="am-u-sm-8">
          <input type="text" style="width:340px;" value="<?php echo htmlentities($row->class)?>" name="class" disabled>
      </div>
    </div>
    <?php } ?>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;<?php echo $MSG_SHOWTAG?>:
      </label>
      <div class="am-u-sm-8" style='padding-top:12px'>
        <input type="checkbox" <?php if ($row->tag == 'Y') echo "checked='checked'" ?> name="tag">
      </div>
    </div>
    <div class="am-form-group">
      <div class="am-u-sm-8 am-u-sm-offset-4">
        <input type="submit" value="<?php echo $MSG_SUBMIT?>" name="submit" class="am-btn am-btn-success">
      </div>
    </div>
  </form>
</div>
<?php require_once("footer.php") ?>
<script type="text/javascript" src="./plugins/emailAutoComplete/emailAutoComplete.js"></script>
