<?php
  /**
   * This file is modified
   * by yybird
   * @2016.04.12
  **/
?>

<?php

  function pwGen($password,$md5ed=False) 
  {
    if (!$md5ed) $password=md5($password);
    $salt = sha1(rand());
    $salt = substr($salt, 0, 4);
    $hash = base64_encode( sha1($password . $salt, true) . $salt ); 
    return $hash; 
  }

  function pwCheck($password,$saved) { // 分别问当前密码、数据库中保存的密码
    if (isOldPW($saved)){ // 是否为旧版密码（旧版密码使用MD5加密）
      $mpw = md5($password);
      if ($mpw==$saved) return True;
      else return False;
    }
    $svd=base64_decode($saved);
    $salt=substr($svd,20);
    $hash = base64_encode( sha1(md5($password).$salt, true) . $salt );
    if (strcmp($hash,$saved)==0) return True;
    else return False;
  }

  function isOldPW($password)
  {
    for ($i=strlen($password)-1;$i>=0;$i--)
    {
      $c = $password[$i];
      if ('0'<=$c && $c<='9') continue;
      if ('a'<=$c && $c<='f') continue;
      if ('A'<=$c && $c<='F') continue;
      return False;
    }
    return True;
  }

  function is_valid_user_name($user_name){
    $len=strlen($user_name);
    for ($i=0;$i<$len;$i++){
      if (
        ($user_name[$i]>='a' && $user_name[$i]<='z') ||
        ($user_name[$i]>='A' && $user_name[$i]<='Z') ||
        ($user_name[$i]>='0' && $user_name[$i]<='9') ||
        $user_name[$i]=='_'||
        ($i==0 && $user_name[$i]=='*') 
      );
      else return false;
    }
    return true;
  }

  function sec2str($sec){
    return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
  }

  function is_running($cid){
    require_once("./include/db_info.inc.php");
     $now=strftime("%Y-%m-%d %H:%M",time());
    $sql="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' AND `end_time`>'$now'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $cnt=intval($row[0]);
    mysql_free_result($result);
    return $cnt>0;
  }

  function check_ac($cid,$pid){
    require_once("./include/db_info.inc.php");
    $sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `result`='4' AND `user_id`='".$_SESSION['user_id']."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $ac=intval($row[0]);
    mysql_free_result($result);
    if ($ac>0) return "<font color=green>Y</font>";
    $sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `user_id`='".$_SESSION['user_id']."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $sub=intval($row[0]);
    mysql_free_result($result);
    if ($sub>0) return "<font color=red>N</font>";
    else return "";
  }

  function RemoveXSS($val) {
     // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
     // this prevents some character re-spacing such as <java\0script>
     // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
     $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

     // straight replacements, the user should never need these since they're normal characters
     // this prevents like <IMG SRC=@avascript:alert('XSS')>
     $search = 'abcdefghijklmnopqrstuvwxyz';
     $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $search .= '1234567890!@#$%^&*()';
     $search .= '~`";:?+/={}[]-_|\'\\';
     for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
     }

     // now the only remaining whitespace attacks are \t, \n, and \r
     $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
     $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
     $ra = array_merge($ra1, $ra2);

     $found = true; // keep replacing as long as the previous round replaced something
     while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
           $pattern = '/';
           for ($j = 0; $j < strlen($ra[$i]); $j++) {
              if ($j > 0) {
                 $pattern .= '(';
                 $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                 $pattern .= '|';
                 $pattern .= '|(&#0{0,8}([9|10|13]);)';
                 $pattern .= ')*';
              }
              $pattern .= $ra[$i][$j];
           }
           $pattern .= '/i';
           $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
           $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
           if ($val_before == $val) {
              // no replacements were made, so exit the loop
              $found = false;
           }
        }
     }
     return $val;
  }


?>
