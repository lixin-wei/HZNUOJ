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
  $MSG_RECENT_CONTEST = "近期比赛";
  $MSG_FAQ = "常见问答";
  $MSG_ACM_MAIL = "杭师ACM邮箱";

  // 用户导航栏
  $MSG_LOGIN = "登录";
  $MSG_REGISTER = "注册";
  $MSG_LOGOUT = "注销";
  $MSG_MODIFY_USER = "修改帐号";
  $MSG_MAIL = "短消息";
  $MSG_ADMIN = "管理";

  // 比赛导航栏
  $MSG_BACK_TO_HOME = "返回主页";
  $MSG_PROBLEM = "问题";
  $MSG_STATISTICS = "统计";
  $BACK_TO_CONTEST = "返回比赛";

  // problemset.php
  $MSG_SEARCH = "查找";
  $MSG_PROBLEM_ID = "题号";
  $MSG_TITLE = "标题";
  $MSG_SOURCE = "来源";
  $MSG_SUBMIT = "提交";
  $MSG_SCORE = "分数";

  //ranklist.php
  $MSG_Number = "名次";
  $MSG_NICK = "昵称";
  $MSG_SOVLED = "解决";
  $MSG_RATIO = "比率";
  $MSG_LEVEL = "等级";
  $MSG_STRENGTH = "实力";

  // contestRank.php
  $MSG_RANK = "排名";
  $MSG_REAL_NAME = "真实姓名";
  $MSG_USER = "用户";
  $MSG_PENALTY = "罚时";
  $MSG_DOWNLOAD_RANK = "下载排名";


  /*
  下面的代码请不要乱动，以免出问题！！！
  */
  
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
  $MSG_Compile_Error="编译错误";
  $MSG_Runtime_Click="运行错误(点击看详细)";
  $MSG_Compile_Click="编译错误(点击看详细)";
  $MSG_Compile_OK="编译成功";
  $MSG_Click_Detail="点击看详细";
   $MSG_Manual="人工判题";
   $MSG_OK="确定";
  $MSG_Explain="输入判定原因与提示";
  
  //fool's day
if(date('m')==4&&date('d')==1&&rand(0,100)<10){
        $MSG_Accepted="人品问题-愚人节快乐";
$MSG_Presentation_Error="人品问题-愚人节快乐";
$MSG_Wrong_Answer="人品问题-愚人节快乐";
$MSG_Time_Limit_Exceed="人品问题-愚人节快乐";
$MSG_Memory_Limit_Exceed="人品问题-愚人节快乐";
$MSG_Output_Limit_Exceed="人品问题-愚人节快乐";
$MSG_Runtime_Error="人品问题-愚人节快乐";
$MSG_Compile_Error="人品问题-愚人节快乐";
$MSG_Compile_OK="人品问题-愚人节快乐";
}
    $MSG_TEST_RUN="运行完成";
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
  
  $MSG_RUNID="运行编号";
  
  $MSG_PROBLEM="问题";
  $MSG_RESULT="结果";
  $MSG_MEMORY="内存";
  $MSG_TIME="耗时";
  $MSG_LANG="语言";
  $MSG_CODE_LENGTH="代码长度";
  $MSG_SUBMIT_TIME="提交时间";
  
  
  //registerpage.php
  $MSG_USER_ID="用户名（学号）";
  $MSG_PASSWORD="密码";
  $MSG_REPEAT_PASSWORD="重复密码";
  $MSG_SCHOOL="学校";
  $MSG_EMAIL="电子邮件";
  $MSG_REG_INFO="注册信息";
  $MSG_VCODE="验证码";

    //problem.php
  $MSG_NO_SUCH_PROBLEM="题目不可用!";
  $MSG_Description="题目描述"  ;
  $MSG_Input="输入"  ;
  $MSG_Output= "输出" ;
  $MSG_Sample_Input= "样例输入" ;
  $MSG_Sample_Output= "样例输出" ;
  $MSG_HINT= "提示" ;
  $MSG_Source= "来源" ;
  $MSG_Time_Limit="时间限制";
  $MSG_Memory_Limit="内存限制";
  //admin menu
  $MSG_SEEOJ="查看前台";
  $MSG_ADD="添加";
  $MSG_LIST="列表";
  $MSG_NEWS="新闻";
  $MSG_TEAMGENERATOR="比赛队帐号生成器";
  $MSG_SETMESSAGE="设置公告";
  $MSG_SETPASSWORD="修改密码";
  $MSG_REJUDGE="重判题目";
  $MSG_PRIVILEGE="权限";
  $MSG_GIVESOURCE="转移源码";
  $MSG_IMPORT="导入";
  $MSG_EXPORT="导出";
  $MSG_UPDATE_DATABASE="更新数据库";
  $MSG_ONLINE="在线";
  //contest
  $MSG_PRIVATE_WARNING="私有比赛或考试作业，您无权查看题目。";
  $MSG_WATCH_RANK="点击这里查看做题排名。";
  $MSG_Public="公开";
  $MSG_Private="私有";
  $MSG_Running="运行中";
  $MSG_Start="开始于";
  $MSG_TotalTime="总赛时";
  $MSG_LeftTime="剩余";
  $MSG_Ended="已结束";
  $MSG_Login="请登录后继续操作";
  $MSG_JUDGER="判题机";

?>