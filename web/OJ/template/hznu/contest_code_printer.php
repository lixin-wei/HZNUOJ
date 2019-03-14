<?php
  /**
   * Need to modify the interface
   * by jnxxhzz
   * @2019.03.15
  **/
?>
<?php include "template/hznu/contest_header.php"; ?>
<div class="am-container">
    <h2>Code Submiting History</h2>
    <hr />
    <form method="post">
    	<div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10"><textarea name="code_print" id="" rows="13" class="kindeditor"></textarea></div>
      </div>
    <button type="submit" value="submit" class="btn btn-default">Submit</button>
	</form> 
    <?php
    $user_id=$mysqli->real_escape_string($_SESSION['user_id']);
    $contest_id=$mysqli->real_escape_string($_GET['cid']);
    $sql = "select code,in_date from printer_code where user_id = '$user_id' and contest_id = $contest_id";
    $result = $mysqli->query ( $sql );
 	if ($result){
	 	while ($row=$result->fetch_object()) {
	 		$code_write = $row->code;
	 		$code_len = strlen($code_write)."B";
	 		if(strlen($code_write) >= 150) $code_write = substr($code_write,0,150)."...";
	 		$code_write = htmlentities($code_write,ENT_QUOTES,"UTF-8");

	 		echo <<<HTML
	 		<ul class="am-list am-list-static am-list-border">
		      <li>
		        <span class="am-badge am-badge-success">$code_len</span> <span class="am-badge">$row->in_date;</span>
		        
		        $code_write
		        </li></ul>
HTML;

	 	}
	 }
	 $result->free();

?>
    
</div>



<?php include "footer.php" ?>

<?php

 
if(isset($_POST['code_print'])){
    $user_id=$mysqli->real_escape_string($_SESSION['user_id']);
    $contest_id=$mysqli->real_escape_string($_GET['cid']);
    $code=$mysqli->real_escape_string($_POST ['code_print']);
  //  $spj=($spj);
    $sql = "INSERT into `printer_code` (`user_id`,`contest_id`,`code`,`in_date`) VALUES('$user_id','$contest_id','$code',NOW())";
    @$mysqli->query ( $sql ) or die ( $mysqli->error );
 }

?>


