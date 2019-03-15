<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once './include/const.inc.php';
if (isset($_GET['cid'])){
    $user_id = $mysqli->real_escape_string($_SESSION['user_id']);
    $contest_id = $mysqli->real_escape_string($_GET['cid']);
    $sql = "select code,in_date,status from printer_code where user_id = '$user_id' and contest_id = '$contest_id'";
    $result = $mysqli->query($sql);
    $printed_codes = [];
    if ($result){
        while ($row=$result->fetch_object()) {
            array_push($printed_codes, $row);
        }
    }
    $result->free();

}
require_once "template/hznu/contest_code_printer.php";
?>