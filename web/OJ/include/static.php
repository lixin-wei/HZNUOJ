<?php
  /**
   * This file is created
   * by D_Star @2016.08.22
  **/
?>
<?php  

  static  $DB_HOST="localhost";
  static  $DB_VJHOST="172.17.151.3";
  static  $DB_NAME="jol";
  static  $DB_VJNAME="vhoj";
  static  $DB_USER="root";
  static  $DB_VJUSER="root";
  static  $DB_PASS="root";
  static  $DB_VJPASS="root";

  $OJ_HOME=dirname(dirname(__FILE__));//:OJ/
  static  $OJ_NAME="HZNUOJ";
  static  $OJ_ADMIN="root@localhost";
  static  $OJ_DATA = "/home/judge/data";
  static  $OJ_BBS="discuss3"; //"bbs" for phpBB3 bridge or "discuss" for mini-forum
  static  $OJ_ONLINE=false;
  static  $OJ_LANG="en";
  static  $OJ_SIM=true;
  static  $OJ_DICT=true;
  static  $OJ_LANGMASK=228352; //1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language  221184
  static  $OJ_EDITE_AREA=true; //true: syntax highlighting is active
  static  $OJ_AUTO_SHARE=true; //true: One can view all AC submit if he/she has ACed it onece.
  static  $OJ_CSS="hoj.css";
  static  $OJ_SAE=false; //using sina application engine
  static  $OJ_VCODE = false; // 是否开启验证码
  static  $OJ_APPENDCODE = true; // 是否开启补全代码（用于C语言教学）
  static  $OJ_MEMCACHE=false;
  static  $OJ_MEMSERVER="127.0.0.1";
  static  $OJ_MEMPORT=11211;
  static  $SAE_STORAGE_ROOT="http://hustoj-web.stor.sinaapp.com/";
  static  $OJ_TEMPLATE="hznu";
  if(isset($_GET['tp'])) $OJ_TEMPLATE=$_GET['tp'];
  static  $OJ_LOGIN_MOD="hustoj";
  static  $OJ_RANK_LOCK_PERCENT=0; // 封榜
  static  $OJ_SHOW_DIFF=false;
  static  $OJ_TEST_RUN = true;
  static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';

  /* weibo config here */
  static  $OJ_WEIBO_AUTH=false;
  static  $OJ_WEIBO_AKEY='1124518951';
  static  $OJ_WEIBO_ASEC='df709a1253ef8878548920718085e84b';
  static  $OJ_WEIBO_CBURL='http://192.168.0.108/OJ/login_weibo.php';

  /* qq config here */
  static  $OJ_QQ_AUTH=false;
  static  $OJ_QQ_AKEY='1124518951';
  static  $OJ_QQ_ASEC='df709a1253ef8878548920718085e84b';
  static  $OJ_QQ_CBURL='192.168.0.108';

  static $ICON_PATH = "/icon.jpg";
  static $VJ_URL = "http://vj.hsacm.com";
  static $GOLD_RATE = 0.10; // 金牌比例
  static $SILVER_RATE = 0.20; // 银牌比例
  static $BRONZE_RATE = 0.30; // 铜牌比例
  static $BORDER = 500000;
  static $LOGIN_DEFUNCT = false;

?>
