#summary config file usage

#labels Featured,Phase-Support

= Introduction =

there are two files that can be used for oj behavior control

**/home/judge/etc/judge.conf** and **/var/www/web/OJ/include/static.php**

## = judge.conf =

**不要直接复制下面的内容到你的judge.conf, judged/judge_client 不支持注释。**

OJ_HOST_NAME=127.0.0.1     #如果用mysql连接读取数据库，数据库的主机地址

OJ_USER_NAME=root          #数据库帐号

OJ_PASSWORD=root           #数据库密码

OJ_DB_NAME=jol             #数据库名称

OJ_PORT_NUMBER=3306        #数据库端口

OJ_RUNNING=2               #judged会启动judge_client判题，这里规定最多同时运行几个judge_client

OJ_SLEEP_TIME=1            #udged通过轮询数据库发现新任务，轮询间隔的休息时间，单位秒

OJ_TOTAL=1                 #老式并发处理中总的judged数量

OJ_MOD=0                   #老式并发处理中，本judged负责处理solution_id按照TOTAL取模后余数为几的任务。

OJ_JAVA_TIME_BONUS=2       #Java等虚拟机语言获得的额外运行时间。

OJ_JAVA_MEMORY_BONUS=64    #Java等虚拟机语言获得的额外内存。

OJ_JAVA_XMS=-Xms64M

OJ_JAVA_XMX=-Xmx128M

OJ_SIM_ENABLE=1            #是否使用sim进行代码相似度的检测,即抄袭检查功能，长期开启此功能会占用大量硬盘存储空间

OJ_HTTP_JUDGE=0            #是否使用HTTP方式连接数据库，如果启用，则前面的HOST_NAME等设置忽略,。

OJ_HTTP_BASEURL=http://127.0.0.1/JudgeOnline  #使用HTTP方式连接数据库的基础地址，就是OJ的首页地址。

OJ_HTTP_USERNAME=IP        #使用HTTP方式所用的用户帐号（REJUDGE权限），该帐号登录时不能启用VCODE图形验证码，但可以登录成功后启用。

OJ_HTTP_PASSWORD=admin     #密码

OJ_REDISENABLE=0

OJ_REDISSERVER=127.0.0.1

OJ_REDISPORT=6379

OJ_REDISAUTH=123456

OJ_REDISQNAME=hustoj

OJ_OI_MODE=0                #是否启用OI信息学奥林匹克竞赛模式，即无论是否出错都继续判剩余的数据，并显示测试数据通过率，而在在ACM比赛模式规则是(值为1)一旦出错就停止运行。

OJ_FULL_DIFF=0              #是否启用RE信息全输出模式，即将每组的in数据、out数据以及对应运行结果的对比信息全部输出到到数据库RE信息表`runtimeinfo`，若不启用（值为0），则只将每组数据的out数据、对应运行结果的对比信息输出，这样可以避免选手根据对比数据刷题。

OJ_SHM_RUN=0                #是否使用/dev/shm的共享内存虚拟磁盘来运行答案，如果启用能提高判题速度，但需要较多内存。

OJ_USE_MAX_TIME=1           #是否使用所有测试数据中最大的运行时间作为最后运行时间，如果不启用则以所有测试数据的总时间作为超时判断依据。

OJ_LANG_SET=0,1,2,3,4,5,6,7,8,9,12,13,14,15,17,18

OJ_COMPILE_CHROOT=1

OJ_TURBO_MODE=0


## = static.php =

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

static  $OJ_DATA = "/home/judge/data";//测试数据所在目录，实际位置。

static  $OJ_ONLINE=false;//是否使用在线监控，需要消耗一定的内存和计算，因此如果并发大建议关闭

static  $OJ_LANG="cn";//设置默认显示的语言，中文为cn，英文为en

static  $OJ_SIM=true;//是否显示相似度检测的结果。

static  $OJ_DICT=true;//是否启用在线英字典

static  $OJ_LANGMASK=979967; //用掩码表示的OJ接受的提交语言，可以被比赛设定覆盖。hustoj原版规则1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language  221184

        //HZNUOJ规则 1开启 0关闭，各个语言从后往前排，在最高位补1，再二进制转成十进制变成掩码,语言顺序见/include/const.inc.php的$language_name数组

        //1 1101111001111111111=1(455679D)=979967 github上down下来默认JavaScript、Obj-C、FreeBasic不开

        //1 1111111111111111111=1(524287D)=1048575 语言全开

        //1 1100000000000000011 = 1(393219D)=917507 只开C C++ python3 GO

        //试验了下最高位1不补好像也没问题

static  $OJ_EDITE_AREA=true; //true: 是否启用高亮语法显示的提交界面，可以在线编程，无须IDE。

static  $OJ_AUTO_SHARE=true; //true: 自动分享代码，启用的话，做出一道题就可以在该题的Status中看其他人的答案。

static  $OJ_CSS="hoj.css";

static  $OJ_SAE=false; //是否是在新浪的云平台运行web部分

static  $OJ_VCODE = true; // 是否开启验证码

static  $OJ_APPENDCODE = true; // 是否启用自动添加代码，启用的话，提交时会参考$OJ_DATA对应题目的目录里是否有append.c、prepend.c一类的文件，

        //有的话会把其中代码附加到对应语言的提交代码之前（prepend.c加到C语言代码之前）或之后（append.c加到C语言代码之后），

        //C++代码是append.cc、prepend.cc等，对应的后缀名见/include/const.inc.php的$language_ext数组，

        //巧妙使用可以指定main函数而要求学生编写main部分调用的函数。

static  $OJ_MEMCACHE=false;//是否使用memcache作为页面缓存，如果不启用则用/cache目录

static  $OJ_MEMSERVER="127.0.0.1";//memcached的服务器地址

static  $OJ_MEMPORT=11211;//memcached的端口

static  $OJ_TEMPLATE="hznu";

static  $OJ_LOGIN_MOD="hustoj";

static  $OJ_SHOW_DIFF=true;//是否显示WA的对比说明

static  $OJ_TEST_RUN = true;//提交界面是否允许测试运行

static  $OJ_BEIAN="";//如果有网站备案号，请填入备案号

static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';

static $ICON_PATH = "image/hznuoj.ico";//设置网站图标

static $BORDER = 500000;

static $LOGIN_DEFUNCT = false;

static $VIDEO_SUBMIT_TIME=3;// can see video after,如果有帮助视频，几次提交错误后能观看视频

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

static $SMTP_SERVER = "smtp.exmail.qq.com";

static $SMTP_SERVER_PORT = 25;

static $SMTP_USER = "forgot@hsacm.com";

static $SMTP_PASS = "hznuojForgot123";
