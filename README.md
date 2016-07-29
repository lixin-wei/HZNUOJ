**HZNUOJ is based on [HUSTOJ](https://github.com/zhblue/hustoj)**

Site address: [acm.hznu.edu.cn](http://acm.hznu.edu.cn)

# Features
HZNUOJ deeply modified the web client, and add more features.

1. Brand new UI and more JS effect, use amazeUI framework.
2. Tag system, help users more easily to find the problem they want.
3. ...

# Plans
1. Perfect the tag system, make it more friendly to use.
    * A more friendly tag-add interface.
    * Search by tag.
    * Tag can only use particular words.
    * ...
2. Reconstruct the problemset system.
3. Reconstruct the privilege system. 


# Installation
Ubuntu 14.04 is recomended, installer may not work well in later version.

Simply clone the repository, then run judger/install/install.sh.

Then you need to modify apache settings, change the default sever dir to /var/www/web/OJ.

And for security concern, I've deleted web/OJ/include/db_info.inc.php from all commits, so you may need to create it yourself.

Here is the template of this file.

```php
<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php @session_start();

   // ini_set("display_errors","Off");

  static  $DB_HOST="";
  static  $DB_VJHOST="";
  static  $DB_NAME="";
  static  $DB_VJNAME="";
  static  $DB_USER="";
  static  $DB_VJUSER="";
  static  $DB_PASS="";
  static  $DB_VJPASS="";

  static  $OJ_NAME="HZNUOJ";
  static  $OJ_HOME="./";
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
  
  // 管理权限
  $GE_A = isset($_SESSION['administrator']); // 权限在管理员及以上
  $GE_T = isset($_SESSION['administrator']) || isset($_SESSION['teacher']); // 权限在教师以上
  $GE_TA = isset($_SESSION['administrator']) || isset($_SESSION['teacher']) || isset($_SESSION['teacher_assistant']); // 权限在助教及以上

  //if(date('H')<5||date('H')>21||isset($_GET['dark'])) $OJ_CSS="dark.css";
  if (isset($_SESSION['OJ_LANG'])) $OJ_LANG=$_SESSION['OJ_LANG'];
  
  if($OJ_SAE) {
    $OJ_DATA = "saestor://data/";
    //  for sae.sina.com.cn
    mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    $DB_NAME = SAE_MYSQL_DB;
  } else {
    //for normal install
    if((mysql_connect($DB_HOST,$DB_USER,$DB_PASS)) == null) 
      die('Could not connect: ' . mysql_error());
  }
  // use db
  mysql_query("set names utf8");
  //if(!$OJ_SAE)mysqli_set_charset("utf8");
  
  if(!mysql_select_db($DB_NAME))
    die('Can\'t use foo : ' . mysql_error());
  //sychronize php and mysql server
  date_default_timezone_set("PRC");
  mysql_query("SET time_zone ='+8:00'");

?>
```
