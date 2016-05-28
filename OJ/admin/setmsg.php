<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	$fp=fopen($OJ_SAE?"saestor://web/msg.txt":"msg.txt","w");
	fputs($fp, stripslashes($_POST['msg']));
	fclose($fp);
	echo "Update At ".date('Y-m-d h:i:s');
}


$msg=file_get_contents($OJ_SAE?"saestor://web/msg.txt":"msg.txt");

?>
	<b>Set Message</b>
	<form action='setmsg.php' method='post'>
		<textarea name='msg' rows=25 class="input input-xxlarge" ><?php echo $msg?></textarea><br>
		<input type='hidden' name='do' value='do'>
		<input type='submit' value='change'>
		<?php require_once("../include/set_post_key.php");?>
	</form>
	
<?php require_once('../oj-footer.php');
?>
