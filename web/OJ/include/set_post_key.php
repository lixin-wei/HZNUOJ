<?php @session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = MD5(rand(0,999999)."asdjlkajs@@!#@!#");
}
?>
<input type=hidden name="csrf_token" value="<?php echo $_SESSION['csrf_token']?>">
