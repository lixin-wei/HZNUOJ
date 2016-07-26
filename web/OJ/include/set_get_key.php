<?php $_SESSION['getkey']=strtoupper(substr(MD5($_SESSION['user_id'].rand(0,9999999)),0,10));?>
