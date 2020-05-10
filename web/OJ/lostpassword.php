<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.02
 **/
?>

<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Welcome To Online Judge";
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
if (isset($_POST['user_id'])) {
    require_once "include/check_post_key.php";
    $lost_user_id=$mysqli->real_escape_string(trim($_POST['user_id']));
    $lost_email=$mysqli->real_escape_string(trim($_POST['email']));
    $vcode=trim($_POST['vcode']);
    if($lost_user_id&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
        echo "<script language='javascript'>\n";
        echo "alert('$MSG_VCODE$MSG_Wrong !');\n";
        echo "history.go(-1);\n";
        echo "</script>";
        exit(0);
    }
    if(get_magic_quotes_gpc()){
        $lost_user_id=stripslashes($lost_user_id);
        $lost_email=stripslashes($lost_email);
    }
    $sql="SELECT `email` FROM `users` WHERE `user_id`='$lost_user_id'";
    $result=$mysqli->query($sql);
    $row = $result->fetch_array();
    $result->free();
    if($row && $row['email']==$lost_email && strpos($lost_email,'@')){
        $_SESSION['lost_user_id']=$lost_user_id;
        $_SESSION['lost_key']=createPwd();
        
        require_once "include/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = $SMTP_SERVER;//SMTP服务器
        $smtpserverport = $SMTP_SERVER_PORT;//SMTP服务器端口
        $smtpusermail = $SMTP_USER;//SMTP服务器的用户邮箱
        $smtpemailto = $row['email'];//发送给谁
        $smtpuser = $SMTP_USER;//SMTP服务器的用户帐号
        $smtppass = $SMTP_PASS;//SMTP服务器的用户密码
        $mailtitle = $OJ_NAME."登录密码重置--系统邮件请勿回复";//邮件主题
        $mailcontent = "$lost_user_id:\n您好！\n您在".$OJ_NAME."系统选择了密码重置服务,为了验证您的身份,请将下面16位".$MSG_Securekey."输入密码重置页面以确认身份:\n";
        $mailcontent .= "┌──────────────────┐\n	".$_SESSION['lost_key']."\n└──────────────────┘\n\n";
        $mailcontent .= "这个".$MSG_Securekey."也将成为您重置成功后的新密码！\n\n".$OJ_NAME."\n".date("Y-m-d H:i",time());//邮件内容
        $mailtype = "TXT";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug =false;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
        require("template/".$OJ_TEMPLATE."/lostpassword2.php");
    } else {
        $view_errors= "没有对应的{$MSG_USER_ID}和$MSG_EMAIL";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
} else {
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/lostpassword.php");
/////////////////////////Common foot
}?>
