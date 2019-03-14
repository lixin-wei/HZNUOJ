<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once './include/const.inc.php';
require_once "template/hznu/contest_code_printer.php";
if (isset($_GET['cid'])){
    $cid = intval($_GET['cid']);
    
}
?>