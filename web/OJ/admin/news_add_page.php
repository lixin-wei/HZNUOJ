  <body leftmargin="30" >
  <?php 
    require_once("../include/db_info.inc.php");
    require_once("admin-header.php");
    if (!HAS_PRI("edit_news")) {
      echo "Permission denied!";
      exit(1);
    }
  ?>
  <?php
    include_once("kindeditor.php") ;
  ?>
    <form method=POST action='news_add.php'>
      <p align=left>Post a News</p>
      <p align=left>Title:<input type=text name=title size=71></p>
      <p align='left'>Importance:
        <select name='importance' style='width:70px'>
          <option value='10' <?php if ($importance==10) echo "selected" ?> >Top</option>
          <option value='3' <?php if ($importance==3) echo "selected" ?> >3</option>
          <option value='2' <?php if ($importance==2) echo "selected" ?> >2</option>
          <option value='1' <?php if ($importance==1) echo "selected" ?> >1</option>
          <option value='0' <?php if ($importance==0) echo "selected" ?> >0</option>
        </select>
      </p>
      <p align=left>Content:<br>
      <textarea class=kindeditor name=content ></textarea></p>
      <input type=submit value=Submit name=submit>
      <?php require_once("../include/set_post_key.php");?>
    </form>
<?php 
  require_once("admin-footer.php")
?>
