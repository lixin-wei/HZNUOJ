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
  static  $OJ_LANG="cn";
  static  $OJ_SIM=true;
  static  $OJ_DICT=true;
  static  $OJ_LANGMASK=979967; //1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language  221184
                //1开启 0关闭，各个语言从后往前排，在最高位补1，再二进制转成十进制变成掩码,语言顺序见/include/const.inc.php						  
							  //1 1101111001111111111=1(455679D)=979967 github上down下来默认JavaScript(nodejs) Obj-C FreeBasic(fbc)不开
							  //1 1111111111111111111=1(524287D)=1048575 语言全开
							  //1 1100000000000000111 = 1(393223D)=917511 只开gcc g++ fpc python3 GO
							  //试验了下最高位1不补好像也没问题
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
  static  $OJ_SHOW_DIFF=false;//是否显示WA的对比说明
  static  $OJ_TEST_RUN = true;//提交界面是否允许测试运行
  static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';


  static $ICON_PATH = "image/hznuoj.ico";
  static $GOLD_RATE = 0.10; // 金牌比例
  static $SILVER_RATE = 0.20; // 银牌比例
  static $BRONZE_RATE = 0.30; // 铜牌比例
  static $BORDER = 500000;
  static $LOGIN_DEFUNCT = false;
  static $VIDEO_SUBMIT_TIME=3;// can see video after

  static  $OJ_REG_NEED_CONFIRM=true; //新注册用户需要审核
  static  $OJ_REGISTER=true; //允许注册新用户
  static  $OJ_NEED_CLASSMODE=true;//班级模式，包括显示班级、学号、真名

  /* Email configuration */
  static $SMTP_SERVER = "smtp.exmail.qq.com";
  static $SMTP_SERVER_PORT = 25;
  static $SMTP_USER = "forgot@hsacm.com";
  static $SMTP_PASS = "hznuojForgot123";
?>
