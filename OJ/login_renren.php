<?php

function http_request($url,$is_post=False){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($is_post){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"");
    }
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}

require_once("./include/db_info.inc.php");
require_once("./include/my_func.inc.php");

if(array_key_exists('code',$_GET)){
    $code = $_GET['code'];
    $GURL = "https://graph.renren.com/oauth/token?";
    $vars = array(
        'client_id'=>$OJ_RR_AKEY,
        'client_secret'=>$OJ_RR_ASEC,
        'grant_type'=>'authorization_code',
        'redirect_uri'=>$OJ_RR_CBURL,
        'code'=>$code);
    $GURL = $GURL.http_build_query($vars);
    $ret = http_request($GURL,True);
    $data = json_decode($ret);
    if (array_key_exists('user',$data)){
        // register this user and login it
        $uname = "renren_".$data->user->id;
        $nick = "Renren_".$data->user->name;
        $password = $OJ_OPENID_PWD;
        $email = "";
        $school = "";
        // check first
        $sql = "SELECT `user_id` FROM `users` where `user_id`='$uname'";
        $res = mysql_query($sql);
        $row_num = mysql_num_rows($res);
        if ($row_num == 0){
            $sql="INSERT INTO `users`("
                    ."`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`nick`,`school`)"
            ."VALUES('".$uname."','".$email."','".$_SERVER['REMOTE_ADDR']."',NOW(),'".$password."',NOW(),'".$nick."','".$school."')";
           // reg it
           mysql_query($sql);
        }
        // login it
		$_SESSION['user_id']=$uname;
        // redirect it
        header("Location: ./");
    }else{
        echo "Login Expire!";
    }
}
else{
    $CBURL="https://api.weibo.com/oauth2/authorize?client_id={$OJ_WEIBO_AKEY}&response_type=code&redirect_uri=$OJ_WEIBO_CBURL";
    $CBURL="https://graph.renren.com/oauth/authorize?client_id={$OJ_RR_AKEY}&redirect_uri={$OJ_RR_CBURL}&response_type=code";
    header("Location: ".$CBURL);
}
