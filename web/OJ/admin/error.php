<?php 
require_once("admin-header.php");

if($view_error==""){
  $view_error="Error! Maybe you don't have the privilege!";
}
echo "<center><h1>".$view_error."</h1></center>";
require_once("admin-footer.php");
?>