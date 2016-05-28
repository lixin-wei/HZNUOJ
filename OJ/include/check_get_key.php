<?php
if ($_SESSION['getkey']!=$_GET['getkey']){
?>
<script language=javascript>
        history.go(-1);
</script>
<?php 
	exit(1);
}
else{
   unset($_SESSION['getkey']);
}
?>
