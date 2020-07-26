<?php
    /**
  * This file is modified
  * by yybird
  * @2015.07.01
  **/
?>
<?php

  // 主导航栏
  $MSG_HOME = "主页";
  $MSG_BBS = "讨论版";
  $MSG_PROBLEMSET = "题库";
  $MSG_STATUS = "状态";
  $MSG_RANKLIST = "排名";
  $MSG_CONTEST = "竞赛&作业";
  $MSG_RECENT_CONTEST = "名校联赛";
  $MSG_FAQ = "常见问答";
  $MSG_ACM_MAIL = "杭师ACM邮箱";
  $MSG_COURSE = "课程";
  $MSG_Assist = "辅助";

  // 用户导航栏
  $MSG_LOGIN = "登录";
  $MSG_REGISTER = "注册";
  $MSG_LOGOUT = "注销";
  $MSG_MODIFY_USER = "修改帐号";
  $MSG_MAIL = "短消息";
  $MSG_ADMIN = "管理";
  $MSG_USERINFO = "用户信息";
  $MSG_MY_SUBMISSIONS="我的提交"; 
  $MSG_MY_CONTESTS="我的$MSG_CONTEST"; 
  $MSG_HIDETAG = "隐藏标签";
  $MSG_SHOWTAG = "显示标签";
  $MSG_LOST_PASSWORD="忘记密码";
  $MSG_Securekey="安全口令";
  $MSG_LOSTPASSWORD_HELP1="请填写您的用户名和注册时登记的电子邮箱进行身份验证。";
  $MSG_LOSTPASSWORD_HELP2="请将发到您邮箱中的".$MSG_Securekey."填写到相应位置；如果填写正确通过下一步验证，这个验证码就自动成为您的新密码！";
  $MSG_RESETPASSWORD_SUCC="已将输入的".$MSG_Securekey."设置为您的新密码。 点击<a href='loginpage.php'>这里</a>登录！";
  $MSG_RESETPASSWORD_FAIL="密码重置失败！";

  // 比赛导航栏
  $MSG_BACK_TO_HOME = "返回主页";
  $MSG_PROBLEM = "题目";
  $MSG_STATISTICS = "统计";
  $BACK_TO_CONTEST = "返回";
  
  // problemset.php
  $MSG_SEARCH = "查找";
  $MSG_PROBLEM_ID = "题号";
  $MSG_TITLE = "标题";
  $MSG_SUBMIT = "提交";
  $MSG_SCORE = "分值";
  $MSG_KEYWORDS = "关键词";
  $MSG_TAGS = "标签";
  $MSG_AUTHOR = "提交人";

  //ranklist.php
  $MSG_RANK = "名次";
  $MSG_NICK = "昵称";
  $MSG_SOLVED = "解决";
  $MSG_RATIO = "通过率";
  $MSG_LEVEL = "等级";
  $MSG_STRENGTH = "实力";
  $MSG_RANKTIPS = "此排名非实时数据排名，请访问自己的用户信息页更新数据。";
  $MSG_Wrong = "错误";
  $MSG_Year = "本年";
  $MSG_Month = "本月";
  $MSG_Week = "本周";
  $MSG_Day = "本日";
  $MSG_Update_RANK = "更新排名";

  // contestRank.php
  $MSG_REAL_NAME = "姓名";
  $MSG_USER = "用户";
  $MSG_PENALTY = "累计时间";
  $MSG_DOWNLOAD_RANK = "下载排名";
  $MSG_Normal_Mode = "普通模式";
  $MSG_RealName_Mode = "实名模式";
  
  //其他
  $MSG_SUBMISSIONS = "提交量";
  $MSG_SUBMISSION = "提交";
  $MSG_SUNMITTOTAL = "总提交";
  $MSG_HINTS= "访问量";  
  $MSG_SERVERTIME = "服务器时间";
  $MSG_MAINTAINER = "Maintainer List";
  $MSG_ALL = "全部";
  $MSG_GO = "跳转";
  $MSG_EDIT = "编辑";
  $MSG_DEL = "删除";
  $MSG_MY = "我的";
  $MSG_NO = "没有";
  $MSG_LoginError = "用户名、密码错误，或".$MSG_CONTEST."ID错误、被停用，或账号未激活!";  
  $MSG_Tried = "正在努力的".$MSG_PROBLEM;
  $MSG_Recommended = "推荐".$MSG_PROBLEM;
  $MSG_OldPasswd = "旧密码";
  $MSG_NewPasswd = "新密码";
  $MSG_TOTAL = "合计";
  $MSG_Available = "启用";
  $MSG_Reserved = "停用";
  $MSG_LastEditTime = "最后编辑时间";
  $MSG_Solution = "单个提交代码";
  $MSG_Importance = "优先级";
  $MSG_Content = "内容";
  $MSG_OpenSource ="开放源码";
  $MSG_RankingExcludedUsers = "排名不包括以下用户";
  $MSG_CodeArchive = "代码档案";
  $MSG_Order_by = "排序";
  $MSG_AccessTime = "登录时间";
  $MSG_StudentID = "学号";
  $MSG_Class = "班级";
  $MSG_RegTime = "注册时间";
  $MSG_RegIP = "注册IP";
  $MSG_Institute = "机构";
  $MSG_Seat = "座位";
  $MSG_ChangeTeamContest = "分配".$MSG_CONTEST;
  $MSG_ChangeClass = "分配".$MSG_Class;
  $MSG_Enrollment_Year = "入学年份";
  $MSG_Class_Name = "班级名称";
  $MSG_Stu_List = "学生名单";
  $MSG_Prefix = "专业/前缀";
  $MSG_Amount = "数量";
  $MSG_Mode = "模式";
  $MSG_Not_Empty_Class = "有人班级";
  $MSG_Empty_Class = "无人班级";
  $MSG_Back = "返回";
  $MSG_Customiz = "自定义";
  $MSG_Download = "下载";
  $MSG_IgnoreWS = "忽略空白";
  $MSG_SIM = "相似度";
  $MSG_Alias = "别名";
  $MSG_Top = "固顶";
  
  /*
  下面的代码请不要乱动，以免出问题！！！
  */
  //status.php
  $MSG_Pending="等待";
  $MSG_Pending_Rejudging="等待重判";
  $MSG_Compiling="编译中";
  $MSG_Running_Judging="运行并评判";
  $MSG_Accepted="正确";
  $MSG_Presentation_Error="格式错误";
  $MSG_Wrong_Answer="答案错误";
  $MSG_Time_Limit_Exceed="时间超限";
  $MSG_Memory_Limit_Exceed="内存超限";
  $MSG_Output_Limit_Exceed="输出超限";
  $MSG_Runtime_Error="运行错误";
  $MSG_TEST_RUN="运行完成";
  $MSG_Compile_Error="编译错误";  
  $MSG_Runtime_Click="运行错误(点击看详细)";
  $MSG_Compile_Click="编译错误(点击看详细)";
  $MSG_Compile_OK="编译成功";
  $MSG_Click_Detail="点击看详细";
  $MSG_RESULT="结果";
  $MSG_MEMORY="内存";
  $MSG_TIME="耗时";
  $MSG_LANG="语言";
  $MSG_CODE_LENGTH="代码长度";
  $MSG_SUBMIT_TIME="提交时间";
  $MSG_Manual="人工判题";
  $MSG_OK="确定";
  $MSG_Explain="输入判定原因与提示";
  $MSG_RUNID="提交编号";
  $MSG_FILTER = "过滤";
  $MSG_RESET = "重置";
  
  //problemstatistics.php      
  $MSG_TR="测试运行";  
  $MSG_PD="等待";
  $MSG_PR="等待重判";
  $MSG_CI="编译中";
  $MSG_RJ="运行并评判";
  $MSG_AC="正确";
  $MSG_PE="格式错误";
  $MSG_WA="答案错误";
  $MSG_TLE="时间超限";
  $MSG_MLE="内存超限";
  $MSG_OLE="输出超限";
  $MSG_RE="运行错误";
  $MSG_CE="编译错误";
  $MSG_CO="编译成功";

  //registerpage.php
  $MSG_USER_ID="用户名";
  $MSG_PASSWORD="密码";
  $MSG_REPEAT_PASSWORD="重复密码";
  $MSG_SCHOOL="学校";
  $MSG_EMAIL="电子邮箱";
  $MSG_REG_INFO="注册信息";
  $MSG_VCODE="验证码";
  $MSG_REG_CODE="注册码";
  $MSG_Remain_Num="注册名额";

    //problem.php
  $MSG_NO_SUCH_PROBLEM="题目不可用!";
  $MSG_Description="题目描述"  ;
  $MSG_Input="输入"  ;
  $MSG_Output= "输出" ;
  $MSG_Samples = "样例";
  $MSG_Sample_Input= "样例输入" ;
  $MSG_Sample_Output= "样例输出" ;
  $MSG_HINT= "提示" ;
  $MSG_Source= "来源/分类" ;
  $MSG_Limit = "限制";
  $MSG_Times = "时间";
  $MSG_Time_Limit="时间限制";
  $MSG_Memory_Limit="内存限制";
  $MSG_TestData = "测试数据";
  $MSG_Codes = "查看代码";
  $MSG_SolutionVideo = "帮助视频";
  $MSG_WatchVideo = "点击观看帮助视频";
  $MSG_ToggleSamples = "隐藏/显示";
  $MSG_ShowAfterTrying = "错误几次之后显示本样例";
  $MSG_AddSapmple = "增加一组样例";
  $MSG_RemoveSample = "删除最后一组样例";
  $MSG_SampleDataIsUpdated = "测试样例文件已更新！";
  $MSG_AddMoreTestData = "添加更多的样例数据";
  $MSG_EditOK = "编辑完毕！";
  $MSG_SeeProblem = "查看题目！";
	
  //admin menu
  $MSG_DASHBOARD = "控制面板";
  $MSG_SEEOJ="返回前台";
  $MSG_ADD="添加";
  $MSG_LIST="列表";
  $MSG_NEWS="公告";
  $MSG_TEAMGENERATOR="比赛帐号生成器";
  $MSG_TEAM = "比赛账号";
  $MSG_SETMESSAGE="设置公告";
  $MSG_SETPASSWORD="修改密码";
  $MSG_SET_USER_ID = "修改".$MSG_USER_ID;
  $MSG_REJUDGE="重判题目";
  $MSG_PRIVILEGE="管理权限";
  $MSG_Distribution = $MSG_PRIVILEGE."分配";
  $MSG_SourceCode= "源码" ;
  $MSG_GIVESOURCE="赠送".$MSG_SourceCode;
  $MSG_IMPORT="导入";
  $MSG_EXPORT="导出";
  $MSG_ReOrderProblem="修改原始".$MSG_PROBLEM_ID;
  $MSG_UPDATE_DATABASE="更新数据库";
  $MSG_ONLINE="在线";
  $MSG_Copy = "复制";
  $MSG_Logs = "日志";
  $MSG_Operations = "操作";
  $MSG_Old = "老";
  $MSG_New = "新";
  $MSG_FastTrack = "快速通道";
  $MSG_ChangeClass = "批量分班";
  $MSG_CourseSet = "课程设置";
  $MSG_UpdateScores = "更新题目分值";
  
  //contest
  $MSG_PRIVATE_WARNING="私有比赛或考试作业，您无权查看题目。";
  $MSG_WATCH_RANK="点击这里查看做题排名。";
  $MSG_Public="公开";
  $MSG_Private="私有";
  $MSG_Practice = "练习赛";
  $MSG_Special = "比赛专用(Special)";
  $MSG_Running="运行中";
  $MSG_Start="开始于";
  $MSG_StartTime="开始时间";
  $MSG_EndTime="结束时间";
  $MSG_LockTime="封榜时间";
  $MSG_Locked = "已封榜，答题信息停止更新！";
  $MSG_TotalTime="总赛时";
  $MSG_LeftTime="剩余";
  $MSG_Ended="已结束";
  $MSG_Login="请登录后继续操作";
  $MSG_JUDGER="判题机";
  $MSG_Announcement = "公告";
  $MSG_Type = "类型";
  $MSG_Name = "名称";
  $MSG_ID = "编号";
  $MSG_NearlyEnd = "即将结束，请注意掌握时间！";
  $MSG_notStart = $MSG_CONTEST."尚未开始";
  $MSG_notStart2 = "未开始";
  $MSG_TimeElapsed = "已耗时";
  $MSG_TimeRemaining = "剩余时间";
  $MSG_ContestIsClosed = "竞赛&作业已关闭！";
  $MSG_Creator = "组织者";
  $MSG_IMPORTED = "外部导入";
  $MSG_LockBoard = "封榜";
  $MSG_LockByTime="比赛结束前xx小时封榜";
  $MSG_LockByRate=$MSG_TotalTime."剩余xx%时封榜";
  $MSG_GOLD="金牌数量"; 
  $MSG_SILVER="银牌数量";
  $MSG_BRONZE="铜牌数量";
  
  $MSG_HELP_TeamAccount_forbid = $MSG_TEAM."不能访问此页！";
  $MSG_HELP_TeamAccount_login = "非".$MSG_TEAM."登录勿填！";
  $MSG_HELP_ADD_NEWS="添加首页显示的".$MSG_NEWS."。";
  $MSG_HELP_NEWS_LIST="管理已经发布的".$MSG_NEWS."。";
  $MSG_HELP_USER_LIST="对注册用户停用、启用帐号及编辑相关信息，对于管理员帐号需要先降级为普通用户才能删除或修改密码";
  $MSG_HELP_CLASS_LIST="班级模式下，添加、删除和编辑班级。若删除一个班级，则该班所有普通账号和比赛账号都会归到<b>“其它”</b>班下。";
  $MSG_HELP_CHANGECALSS="班级模式下，给学生批量的重新分班。";
  $MSG_HELP_RegCode_OpenMode="当前系统为开放模式，注册无限制，账号注册后立即激活。";
  $MSG_HELP_RegCode_ComfirmMode="当前系统设定为审核模式，注册无限制，账号注册后需要管理员后台审核激活。";
  $MSG_HELP_RegCode_PwdMode="当前系统设定为密码模式，人员凭后台设置的注册码进行注册，账号注册后立即激活。";
  $MSG_HELP_RegCode_PwdComfirmMode="当前系统设定为密码+审核模式，人员凭后台设置的注册码进行注册，账号注册后还需管理员后台审核激活。";
  $MSG_HELP_ADD_PROBLEM="手动添加新的题目，更多测试数据可以在添加后点击".$MSG_PROBLEM.$MSG_LIST."中的'".$MSG_TestData."'按钮进行批量上传，新建题目<b>默认".$MSG_Reserved."</b>，需点击".$MSG_PROBLEM.$MSG_LIST."中对应的<b>'".$MSG_Reserved."'</b>切换到启用状态。";
  $MSG_HELP_PROBLEM_LIST="管理已有的题目和数据，上传测试数据可以用zip格式压缩不含目录的数据。";
  $MSG_HELP_CONTEST_LIST="管理已有的比赛列表，修改时间和公开/私有等类型，尽量不要在开赛后调整题目列表。";
  $MSG_HELP_TEAMGENERATOR="批量生成大量比赛帐号、密码。小系统不要随便使用，可能产生垃圾帐号，清理比较麻烦。<font color='red'><b>此外，账号生成过程中遇到相同{$MSG_USER_ID}和{$MSG_CONTEST}的{$MSG_USER}，系统将做覆盖处理。</b></font>";
  $MSG_HELP_SETPASSWORD="重设指定用户的密码，对于管理员帐号需要先降级为普通用户才能修改。";
  $MSG_HELP_REJUDGE="可以重判指定的题目、提交或比赛。";
  $MSG_HELP_ADD_PRIVILEGE="给指定用户添加".$MSG_PRIVILEGE."，包括管理员、教师、助教等权限。";
  $MSG_HELP_PRIVILEGE_LIST="查看用户已有的".$MSG_PRIVILEGE."列表、进行删除操作。";
  $MSG_HELP_GIVESOURCE="将导入系统的标程赠与指定帐号，用于训练后辅助未通过的人学习参考。<b>注意此处代码赠与是把代码移动到对方名下而非复制。</b>";
  $MSG_HELP_EXPORT_PROBLEM="将系统中的题目以fps.xml文件的形式导出。";
  $MSG_HELP_IMPORT_PROBLEM="导入从官方群共享或tk.hustoj.com下载到的fps.xml文件，支持ZIP压缩文件批量上传。";
  $MSG_HELP_PROBLEM_STATISTICS="当你AC了一道题，你就有权限查看该题所有的提交代码。";
  $MSG_HELP_PROBLEMSET1="增加、删除、编辑题库，";
  $MSG_HELP_PROBLEMSET2="当题库中有题目时不能做删除操作。";
  $MSG_HELP_CourseSet="添加、删除、编辑课程/章节及相关题目，拖拽节点可以调整顺序，按住Ctrl键或Cmd键（MacOS系统）后拖拽可复制节点。";

  $MSG_HELP_AC="答案正确，请再接再厉。"; 
  $MSG_HELP_PE="答案基本正确，但是格式不对。"; 
  $MSG_HELP_WA="答案不对，仅仅通过样例数据的测试并不一定是正确答案，一定还有你没想到的地方，点击查看系统可能提供的对比信息。"; 
  $MSG_HELP_TLE="运行超出时间限制，检查下是否有死循环，或者应该有更快的计算方法。"; 
  $MSG_HELP_MLE="超出内存限制，数据可能需要压缩，检查内存是否有泄露。"; 
  $MSG_HELP_OLE="输出超过限制，你的输出比正确答案长了两倍，一定是哪里弄错了。"; 
  $MSG_HELP_RE="运行时错误，非法的内存访问，数组越界，指针漂移，调用禁用的系统函数。请点击后获得详细输出";
  $MSG_HELP_CE="编译错误，请点击后获得编译器的详细输出。"; 
  
  $MSG_HELP_MORE_TESTDATA_LATER="更多组测试数据，请在题目添加完成后补充。"; 
  //$MSG_HELP_SPJ="特殊裁判的使用，请参考<a href='https://cn.bing.com/search?q=hustoj+special+judge' target='_blank'>搜索hustoj special judge</a>"; 
  
  //$MSG_WARNING_LOGIN_FROM_DIFF_IP="从不同的ip地址登录";
  //$MSG_LOSTPASSWORD_MAILBOX="请到您邮箱的垃圾邮件文件夹寻找验证码，并填写到这里";
  //$MSG_LOSTPASSWORD_WILLBENEW="如果填写正确，通过下一步验证，这个验证码就自动成为您的新密码！";
  
  //privilege_distribution.php
  $MSG_enter_admin_page = "访问管理页面";
  $MSG_rejudge = $MSG_REJUDGE;
  $MSG_edit_news = $MSG_EDIT.$MSG_NEWS;
  $MSG_HELP_edit_news = "可以添加和编辑".$MSG_NEWS."。";
  $MSG_edit_contest = $MSG_EDIT.$MSG_CONTEST;
  $MSG_HELP_edit_contest1 = "可以添加和编辑".$MSG_CONTEST."。";
  $MSG_HELP_edit_contest2 = "可以查看任何已结束的".$MSG_CONTEST."。";
  $MSG_HELP_edit_contest3 = "可以在未结束的".$MSG_CONTEST."中查看原始".$MSG_PROBLEM_ID."。";
  $MSG_HELP_edit_contest4 = "可以添加、删除、编辑课程辅助中的课程/章节及相关题目。";
  $MSG_download_ranklist = $MSG_DOWNLOAD_RANK;
  $MSG_generate_team ="账号生成";
  $MSG_HELP_generate_team ="批量生成比赛帐号以及批量导入普通账号。";
  $MSG_edit_user_profile = $MSG_EDIT.$MSG_USER;
  $MSG_HELP_edit_user_profile ="可以修改".$MSG_USER_ID."和".$MSG_PASSWORD."，以及增加、删除、编辑班级和班级注册码。";
  $MSG_edit_privilege_group =$MSG_EDIT."用户".$MSG_PRIVILEGE;
  $MSG_HELP_edit_privilege_group ="添加、删除指定用户的".$MSG_PRIVILEGE."。";
  $MSG_edit_privilege_distribution=$MSG_Distribution;
  $MSG_inner_function ="内建功能";
  $MSG_HELP_inner_function ="一些不常用（如题库管理、题目分值更新、题目导入导出）或者较为危险的功能（修改原始题号）等";
  $MSG_edit_xx_problem = "edit_xx_problem";
  $MSG_HELP_edit_xx_problem1 = "可以在xx".$MSG_PROBLEMSET."中".$MSG_ADD."、".$MSG_EDIT.$MSG_PROBLEM."以及查看相关数据。";
  $MSG_see_hidden_xx_problem ="see_hidden_xx_problem";
  $MSG_HELP_see_hidden_xx_problem1 ="可以在".$MSG_PROBLEMSET."页面里查看xx".$MSG_PROBLEMSET."中隐藏的".$MSG_PROBLEM."。";
  $MSG_HELP_see_hidden_xx_problem2 ="包括因为被包含在运行中".$MSG_CONTEST."而被隐藏的".$MSG_PROBLEM."。";
  $MSG_see_hidden_user_info ="查看隐藏的用户信息";
  $MSG_HELP_see_hidden_user_info ="可以在用户信息页面中查看隐藏的信息，包括真名、班级和最近的登录信息。";
  $MSG_see_wa_info_out_of_contest ="查看比赛之外提交代码的错误信息";
  $MSG_HELP_see_wa_info_out_of_contest ="可以查看所有用户在".$MSG_CONTEST."之外提交代码的WA、RE、PE、TEST_RUN、CE等错误信息。";
  $MSG_see_wa_info_in_contest ="查看比赛中提交代码的错误信息";
  $MSG_HELP_see_wa_info_in_contest ="可以查看所有用户在".$MSG_CONTEST."中提交代码的WA、RE、PE、TEST_RUN、CE等错误信息。";
  $MSG_see_source_out_of_contest ="查看比赛之外正常提交的源代码";
  $MSG_HELP_see_source_out_of_contest1 ="可以查看比赛之外正常提交的所有源代码。";
  $MSG_HELP_see_source_out_of_contest2 ="还可以查看比赛之外每个提交的耗时、内存占用、所用语言等信息。";
  $MSG_see_source_in_contest ="查看比赛中提交的源代码";
  $MSG_HELP_see_source_in_contest1 ="可以查看比赛中提交的所有源代码。";
  $MSG_HELP_see_source_in_contest2 ="还可以查看比赛中每个提交的耗时、内存占用、所用语言等信息。";
  $MSG_see_compare ="查看源代码比较";
  $MSG_HELP_see_compare ="可以使用源代码比较功能。";
  $MSG_upload_files ="上传文件";
  $MSG_HELP_upload_files1 = "上传文件时会检查此权限。";
  $MSG_HELP_upload_files2 = "添加和编辑".$MSG_PROBLEM."和".$MSG_CONTEST."时有可能需要此权限。";
  $MSG_watch_solution_video ="查看".$MSG_SolutionVideo;
?>