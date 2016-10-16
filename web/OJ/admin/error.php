<?php 
require_once("admin-header.php");

if($view_error==""){
  $view_error="Error! Maybe you don't have the privilege!";
}
echo "<h1>".$view_error."</h1>";
require_once("admin-footer.php");
?>