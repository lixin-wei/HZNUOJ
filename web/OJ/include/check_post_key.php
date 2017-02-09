<?php @session_start();
if(isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])) {
    if($_POST['csrf_token'] != $_SESSION['csrf_token']) {
        echo "token incorrect!";
        exit(1);
    }
}
else {
    echo "token incorrect!";
    exit(1);
}
?>
