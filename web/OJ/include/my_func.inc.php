<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.28
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


  function canSeeSource($sid) {

    global $OJ_AUTO_SHARE;
    global $GE_T, $GE_TA;


    /* 获取solution信息 start */
    $sql="SELECT * FROM `solution` WHERE `solution_id`='".$sid."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_object($result);
    $pid = $row->problem_id;
    $cid = $row->contest_id;
    mysql_free_result($result);
    /* 获取solution信息 end */


    $irc = false; // in running contest
    $idc = false; // in defunct TA contest
    $sql = "SELECT DISTINCT(contest_id) AS cid FROM contest_problem WHERE problem_id='$pid'";
    $result = mysql_query($sql);
    while ($row_cid = mysql_fetch_object($result)) {
      if (is_running($row_cid->cid)) {
        $irc = true;
        $sql = "SELECT defunct_TA FROM contest WHERE contest_id='$row_cid->cid'";
        $result_tmp = mysql_query($sql);
        $row_tmp = mysql_fetch_array($result);
        $idc = $row_tmp->defunct_TA=="Y"?1:0; 
        mysql_free_result($result_tmp);
      }
    }
    mysql_free_result($result);


    /* 判断是否有查看权限 start */
    if (isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE && isset($_SESSION['user_id'])){ // 已经AC该题目，可查看该题代码
      $sql = "SELECT 1 FROM solution WHERE result=4 AND problem_id=$pid AND user_id='".$_SESSION['user_id']."'";
      $rrs = mysql_query($sql);
      $ok = !$irc && (mysql_num_rows($rrs)>0) ;
      mysql_free_result($rrs);
      if ($ok) return true;
    }
    
    if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) return true;  // 是本人，可以查看该代码
    else { // 不是本人的情况下
      if ($irc) { // the problem is in running contest
        return ( $GE_T || isset($_SESSION['source_browser']) || // 权限在教师以上或者有看代码权限
                      (!$GE_T && $GE_TA && !$idc) // 是助教且包含该题的所有运行中的比赛都没屏蔽助教
                    );
      } else if (is_numeric($cid)) { // 没有运行中的比赛包含该题则考察该代码是否在已经结束的比赛中
        $sql = "SELECT defunct_TA, open_source FROM contest WHERE contest_id='$cid'";
        $result = mysql_query($sql);
        $row = mysql_fetch_object($result);
        $open_source = $row->open_source=="Y"?1:0; // 默认值为0
        $defunct_TA = $row->defunct_TA=="Y"?1:0; // 默认值为0
        mysql_free_result($result);
        return  ( (!is_running(intval($cid))  && $open_source) || // 比赛已经结束了且开放源代码查看
                       $GE_T || isset($_SESSION['source_browser']) || // 权限在教师以上或者有看代码权限
                       (!$GE_T && $GE_TA && !$defunct_TA) // 是助教且该比赛没屏蔽助教
                     );
      } else { // 该代码不是在比赛中的
        if ($GE_TA || isset($_SESSION['source_browser'])) return true; // 所有有管理权限的成员均可查看
      }
    }
    /* 判断是否有查看权限 end */

    return false;
  }


  function canSeeWAinfo($sid) {

    global $OJ_SHOW_DIFF;
    global $GE_T, $GE_TA;


    /* 获取solution信息 start */
    $sql="SELECT * FROM `solution` WHERE `solution_id`='".$sid."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_object($result);
    $pid = $row->problem_id;
    $cid = $row->contest_id;
    mysql_free_result($result);
    /* 获取solution信息 end */


    /* 判断是否有查看权限 start */
    if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id'] && $OJ_SHOW_DIFF) return true;  // 是本人
    else { // 不是本人的情况下
      if ($GE_T || isset($_SESSION['source_browser']) )return true;
      if (!$GE_TA) return false;
      if (is_numeric($cid)) { // 比赛中
        $sql = "SELECT defunct_TA FROM contest WHERE contest_id='$cid'";
        $result = mysql_query($sql);
        $row = mysql_fetch_object($result);
        $defunct_TA = $row->defunct_TA=="Y"?1:0; // 默认值为0
        mysql_free_result($result);
        if (is_running(intval($cid)) && $defunct_TA) return false;
        else return true;
      } else { // 该代码不是在比赛中的
        if ($GE_TA || isset($_SESSION['source_browser'])) return true; // 所有有管理权限的成员均可查看
      }
    }
    /* 判断是否有查看权限 end */

    return false;
  }
?>
