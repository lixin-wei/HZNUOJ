<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
	require_once("./db_info.inc.php");
	if(isset($OJ_LANG)){
		require_once("../lang/$OJ_LANG.php");
	}else{
		require_once("./lang/en.php");
	}
    function checkmail(){
		
		$sql="SELECT count(1) FROM `mail` WHERE 
				new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
		$result=mysql_query($sql);
		if(!$result) return false;
		$row=mysql_fetch_row($result);
		$retmsg="<span id=red>(".$row[0].")</span>";
		mysql_free_result($result);
		return $retmsg;
	}
	$profile="";
		if (isset($_SESSION['user_id'])){
				$sid=$_SESSION['user_id'];
				$profile.= "<i class=icon-user></i><a href=./modifypage.php>$MSG_USERINFO</a>&nbsp;<a href='./userinfo.php?user=$sid'><span id=red>$sid</span></a>";
				$mail=checkmail();
				if ($mail)
					$profile.= "&nbsp;<i class=icon-envelope></i><a href=./mail.php>$mail</a>";
        $profile.="&nbsp;<a href='./status.php?user_id=$sid'><span id=red>Recent</span></a>";
                                
				$profile.= "&nbsp;<a href=./logout.php>$MSG_LOGOUT</a>&nbsp;";
			}else{
                if ($OJ_WEIBO_AUTH){
				    $profile.= "<a href=./login_weibo.php>$MSG_LOGIN(WEIBO)</a>&nbsp;";
                }
                if ($OJ_RR_AUTH){
				    $profile.= "<a href=./login_renren.php>$MSG_LOGIN(RENREN)</a>&nbsp;";
                }
                if ($OJ_QQ_AUTH){
            $profile.= "<a href=./login_qq.php>$MSG_LOGIN(QQ)</a>&nbsp;";
                }
				$profile.= "<a href=./loginpage.php>$MSG_LOGIN</a>&nbsp;";
				if($OJ_LOGIN_MOD=="hustoj"){
					$profile.= "<a href=./registerpage.php>$MSG_REGISTER</a>&nbsp;";
				}
			}
			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])){
           $profile.= "<a href=./admin/>$MSG_ADMIN</a>&nbsp;";
			
			}
		?>
document.write("<?php echo ( $profile);?>");
