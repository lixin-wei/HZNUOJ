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

<?php $title="Modify Info";?>
<?php require_once("header.php") ?>
<div class="am-container">
  <h1 style="margin-top:40px; margin-bottom: 0px;">Modify Page</h1>
  <hr>
  <form class="am-form am-form-horizontal" action="modify.php" method="post">
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">User ID:</label>
      <div class="am-u-sm-8">
        <label class="am-form-label"><?php echo $_SESSION['user_id']?></label>
        <?php require_once('./include/set_post_key.php');?>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">Nick Name:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->nick)?>" name="nick">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Old Password:
      </label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="opassword">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-3 am-u-sm-offset-1 am-form-label">New Password:</label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="npassword">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-3 am-u-sm-offset-1 am-form-label">Repeat Password:</label>
      <div class="am-u-sm-8">
        <input type="password" style="width:340px;" name="rptpassword">
      </div>
    </div> 
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">School:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->school)?>" name="school">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">Student ID:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->stu_id)?>" name="stu_id">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">Class:</label>
      <div class="am-u-sm-8">
        <select name="class" style="width:340px;">
        <?php
          foreach ($classList as $i) {
        ?>
            <option value="<?php echo $i; ?>" <?php if ($row->class==$i) echo selected; ?> ><?php echo $i; ?></option>
        <?php
          }
        ?>
        </select>
      </div>
    </div>   
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">Real Name:</label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->real_name)?>" name="real_name">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Email:
      </label>
      <div class="am-u-sm-8">
        <input type="text" style="width:340px;" value="<?php echo htmlentities($row->email)?>" name="email">
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-2 am-u-sm-offset-2 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Show Tag:
      </label>
      <div class="am-u-sm-8" style='padding-top:12px'>
        <input type="checkbox" <?php if ($row->tag == 'Y') echo "checked='checked'" ?> name="tag">
      </div>
    </div> 
    <div class="am-form-group">
      <div class="am-u-sm-8 am-u-sm-offset-4">
        <input type="submit" value="Modify" name="submit" class="am-btn am-btn-success">
      </div>
    </div>
  </form>
</div>



<?php require_once("footer.php") ?>