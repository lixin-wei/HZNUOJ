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
  static  $VJ_OPEN=false;
  $OJ_HOME=dirname(dirname(__FILE__));//:OJ/
  static  $OJ_NAME="HZNUOJ";
  static  $OJ_ADMIN="root@localhost";
  static  $OJ_DATA = "/home/judge/data";
  static  $OJ_ONLINE=false;
  static  $OJ_LANG="en";
  static  $OJ_SIM=true;
  static  $OJ_DICT=true;
  static  $OJ_LANGMASK=979967; //1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language  221184
  static  $OJ_EDITE_AREA=true; //true: syntax highlighting is active
  static  $OJ_AUTO_SHARE=true; //true: One can view all AC submit if he/she has ACed it onece.
  static  $OJ_CSS="hoj.css";
  static  $OJ_SAE=false; //using sina application engine
  static  $OJ_VCODE = false; // 是否开启验证码
  static  $OJ_APPENDCODE = true; // 是否开启补全代码（用于C语言教学）
  static  $OJ_MEMCACHE=false;
  static  $OJ_MEMSERVER="127.0.0.1";
  static  $OJ_MEMPORT=11211;
  static  $OJ_TEMPLATE="hznu";
  if(isset($_GET['tp'])) $OJ_TEMPLATE=$_GET['tp'];
  static  $OJ_LOGIN_MOD="hustoj";
  static  $OJ_RANK_LOCK_PERCENT=0.2; // 封榜
  static  $OJ_SHOW_DIFF=false;
  static  $OJ_TEST_RUN = true;
  static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';


  static $ICON_PATH = "image/hznuoj.ico";
  static $GOLD_RATE = 0.10; // 金牌比例
  static $SILVER_RATE = 0.20; // 银牌比例
  static $BRONZE_RATE = 0.30; // 铜牌比例
  static $BORDER = 500000;
  static $LOGIN_DEFUNCT = false;
  static $VIDEO_SUBMIT_TIME=3;// can see video after

  /* Email configuration */
  static $SMTP_SERVER = "smtp.exmail.qq.com";
  static $SMTP_SERVER_PORT = 25;
  static $SMTP_USER = "forgot@hsacm.com";
  static $SMTP_PASS = "hznuojForgot123";

  static  $OJ_REG_NEED_CONFIRM=true; //新注册用户需要审核
  static  $OJ_REGISTER=true; //允许注册新用户
?>
