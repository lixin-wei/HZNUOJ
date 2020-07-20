<?php
  /**
   * This file is created
   * by D_Star @2016.08.22
  **/
?>
<?php  

  static  $DB_HOST="localhost";//数据库的服务器地址
  static  $DB_NAME="jol";//数据库名
  static  $DB_USER="root";//数据库用户名
  static  $DB_PASS="root";//数据库密码

  static  $DB_VJHOST="172.17.151.3";
  static  $DB_VJNAME="vhoj";
  static  $DB_VJUSER="root";
  static  $DB_VJPASS="root";
  static  $VJ_OPEN=false;

  static  $OJ_HOME="/OJ/";//OJ的首页地址
  static  $OJ_NAME="HZNUOJ";//OJ的名字，将取代页面标题等位置HZNUOJ字样。
  static  $OJ_ADMIN="root@localhost";//管理员email
  static  $OJ_DATA="/home/judge/data";//测试数据所在目录，实际位置。
  static  $OJ_ONLINE=false;//是否使用在线监控，需要消耗一定的内存和计算，因此如果并发大建议关闭
  static  $OJ_LANG="en";//设置默认显示的语言，中文为cn，英文为en
  static  $OJ_SIM=true;//是否显示相似度检测的结果。
  static  $OJ_DICT=true;//是否启用在线英字典
  static  $OJ_LANGMASK=717823; //用掩码表示的OJ接受的提交语言，可以被比赛设定覆盖。hustoj原版规则1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language  221184
                //HZNUOJ规则 1开启 0关闭，各个语言从后往前排，在最高位补1，再二进制转成十进制变成掩码,语言顺序见/include/const.inc.php的$language_name数组
                //1 1111111111111111111=1(524287D)=1048575 语言全开
                //1 1101111001111111111=1(455679D)=979967 github上down下来默认JavaScript、Obj-C、FreeBasic不开
                //1 0101111001111111111=1(193535D)=717823 为方便和hustoj对接，关闭18Python3；Python2、Python3可以通过系统配置切换，具体参看wiki/maintainer-manual.md
							  //1 0000000000001000011=1(67D)=524355 只开C C++ python
							  //试验了下最高位1不补好像也没问题
  static  $OJ_EDITE_AREA=true; //true: 是否启用高亮语法显示的提交界面，可以在线编程，无须IDE。
  static  $OJ_AUTO_SHARE=true; //true: 自动分享代码，启用的话，做出一道题就可以在该题的Status中看其他人的答案。
  static  $OJ_CSS="hoj.css";
  static  $OJ_SAE=false; //是否是在新浪的云平台运行web部分
  static  $OJ_VCODE=true; // 是否开启验证码
  static  $OJ_APPENDCODE=true; // 是否启用自动添加代码，启用的话，提交时会参考$OJ_DATA对应题目的目录里是否有append.c、prepend.c一类的文件，
                                 //有的话会把其中代码附加到对应语言的提交代码之前（prepend.c加到C语言代码之前）或之后（append.c加到C语言代码之后），
                                 //C++代码是append.cc、prepend.cc等，对应的后缀名见/include/const.inc.php的$language_ext数组，
                                 //巧妙使用可以指定main函数而要求学生编写main部分调用的函数。
  static  $OJ_MEMCACHE=false;//是否使用memcache作为页面缓存，如果不启用则用/cache目录
  static  $OJ_MEMSERVER="127.0.0.1";//memcached的服务器地址
  static  $OJ_MEMPORT=11211;//memcached的端口
  static  $OJ_TEMPLATE="hznu";
  static  $OJ_LOGIN_MOD="hustoj";
  static  $OJ_SHOW_DIFF=true;//是否显示WA的对比说明
  static  $OJ_TEST_RUN=false;//提交界面是否允许测试运行
  static  $OJ_BEIAN="";//如果有网站备案号，请填入备案号
  static $OJ_OPENID_PWD='8a367fe87b1e406ea8e94d7d508dcf01';

  static $ICON_PATH="image/hznuoj.ico";//设置网站图标
  static $BORDER=500000;
  static $LOGIN_DEFUNCT=false;
  static $VIDEO_SUBMIT_TIME=3;// can see video after

  static  $OJ_REGISTER=true; //允许注册新用户
  static  $OJ_REG_NEED_CONFIRM="pwd"; //新注册用户模式，共四种模式
                                   //开放模式，值为"off"，注册无限制，账号注册后立即激活
                                   //审核模式，值为"on"，注册无限制，账号注册后需要管理员后台审核激活
                                   //密码模式，值为"pwd"，凭后台设置的班级+注册码进行注册（注册码可设定注册次数），账号注册后立即激活
                                   //密码+审核模式，值为"pwd+confirm"，基础功能同密码模式，但是账号注册后并不是立即激活，而是还需管理员后台审核激活
  static  $OJ_NEED_CLASSMODE=true;//是否开启班级模式，包括显示班级、学号、真名
  static  $OJ_show_PrinterAndDiscussInContest=true;//是否在比赛页面显示Code Printer和Discuss的链接
  static  $OJ_show_contestSolutionInStatus=false;//是否在status.php中显示contest中提交的代码

  /* Email configuration */
  static $SMTP_SERVER="smtp.exmail.qq.com";
  static $SMTP_SERVER_PORT=25;
  static $SMTP_USER="forgot@hsacm.com";
  static $SMTP_PASS="hznuojForgot123";
?>
