<?php
  /**
   * This file is modified!
   * by yybird
   * @2016.07.01
  **/
?>
<?php
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  
  ////////////////////////////Common head
  $cache_time=2;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $view_title= "$MSG_STATUS";

  require_once("./include/my_func.inc.php");
  if(isset($OJ_LANG)) require_once("./lang/$OJ_LANG.php");
  require_once("./include/const.inc.php");

    if ($OJ_TEMPLATE == "hznu")
      $judge_color=Array( "am-badge am-badge-secondary am-text-sm", // Pending
                          "am-badge am-badge-secondary am-text-sm", // Pending & Rejudging
                          "am-badge am-badge-secondary am-text-sm", // Running & Judging
                          "am-badge am-badge-secondary am-text-sm", // Compliing
                          "am-badge am-badge-success am-text-sm", // AC
                          "am-badge am-badge-danger am-text-sm", // PE
                          "am-badge am-badge-danger am-text-sm", // WA
                          "am-badge am-badge-warning am-text-sm", // TLE
                          "am-badge am-badge-warning am-text-sm", // MLE
                          "am-badge am-badge-warning am-text-sm", // OLE
                          "am-badge am-badge-warning am-text-sm", // RE
                          "am-badge am-badge-primary am-text-sm", // CE
                          "am-badge am-badge-warning am-text-sm",
                          "am-badge am-badge-primary am-text-sm",

                        );
    else if($OJ_TEMPLATE!="classic") 
      $judge_color=Array("btn gray","btn btn-info","btn btn-warning","btn btn-warning","btn btn-success","btn btn-danger","btn btn-danger","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-info");

  //echo $OJ_SHOW_DIFF;
  $str2="";
  $lock=false;
  $lock_time=date("Y-m-d H:i:s",time());
  $sql="SELECT * FROM `solution` WHERE problem_id>0 ";
  if (isset($_GET['cid'])){
    $cid=intval($_GET['cid']);
    $sql=$sql." AND `contest_id`='$cid' and num>=0 ";
    $str2=$str2."&cid=$cid";
    $sql_lock="SELECT * FROM `contest` WHERE `contest_id`='$cid'";
    $result=$mysqli->query($sql_lock) or die($mysqli->error);
    $rows_cnt=$result->num_rows;
    $start_time=0;
    $end_time=0;
    if ($rows_cnt>0){
      $row=$result->fetch_object();
      $start_time=strtotime($row->start_time);
      $title=$row->title;
      $end_time=strtotime($row->end_time);
      $open_source = $row->open_source=="Y"?1:0; // 默认值为0
      $defunct_TA = $row->defunct_TA=="Y"?1:0; // 默认值为0
    }
    $lock_time=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
    //$lock_time=date("Y-m-d H:i:s",$lock_time);
    $time_sql="";
    //echo $lock.'-'.date("Y-m-d H:i:s",$lock);
    if(time()>$lock_time&&time()<$end_time){
      //$lock_time=date("Y-m-d H:i:s",$lock_time);
      //echo $time_sql;
       $lock=true;
    }else{
       $lock=false;
    }
    //require_once("contest-header.php");
  } else {
    //require_once("oj-header.php");
    $sql="SELECT * FROM `solution` WHERE contest_id is null ";
  }
  $start_first=true;
  $order_str=" ORDER BY `solution_id` DESC ";

  // check the top arg
  if (isset($_GET['top'])){
    $top=strval(intval($_GET['top']));
    if ($top!=-1) $sql=$sql."AND `solution_id`<='".$top."' ";
  }

  // check the problem arg
  $problem_id="";
  if (isset($_GET['problem_id'])&&$_GET['problem_id']!=""){
    
    if(isset($_GET['cid'])){
      $problem_id=$_GET['problem_id'];
      $num=strpos($PID,$problem_id);
      $sql=$sql."AND `num`='".$num."' ";
          $str2=$str2."&problem_id=".$problem_id;
    }else{
          $problem_id=strval(intval($_GET['problem_id']));
          if ($problem_id!='0'){
                  $sql=$sql."AND `problem_id`='".$problem_id."' ";
                  $str2=$str2."&problem_id=".$problem_id;
          }
          else $problem_id="";
    }
  }
  // check the user_id arg
  $user_id="";
  if (isset($_GET['user_id'])){
          $user_id=trim($_GET['user_id']);
          if (is_valid_user_name($user_id) && $user_id!=""){
                  $sql=$sql."AND `user_id`='".$user_id."' ";
                  if ($str2!="") $str2=$str2."&";
                  $str2=$str2."user_id=".$user_id;
          }else $user_id="";
  }
  if (isset($_GET['language'])) $language=intval($_GET['language']);
  else $language=-1;

  if ($language>count($language_ext) || $language<0) $language=-1;
  if ($language!=-1){
    $sql=$sql."AND `language`='".strval($language)."' ";
    $str2=$str2."&language=".$language;
  }
  if (isset($_GET['jresult'])) $result=intval($_GET['jresult']);
  else $result=-1;

  if ($result>12 || $result<0) $result=-1;
  if ($result!=-1&&!$lock){
    $sql=$sql."AND `result`='".strval($result)."' ";
    $str2=$str2."&jresult=".$result;
  }



  if($OJ_SIM){
    $old=$sql;
    $sql="select * from ($sql order by solution_id desc limit 1000) solution left join `sim` on solution.solution_id=sim.s_id WHERE 1 ";
    if(isset($_GET['showsim'])&&intval($_GET['showsim'])>0){
            $showsim=intval($_GET['showsim']);
            $sql="select * from ($old ) solution 
                 left join `sim` on solution.solution_id=sim.s_id WHERE result=4 and sim>=$showsim limit 1000";
            $sql="SELECT * FROM ($sql) `solution`
                    left join(select solution_id old_s_id,user_id old_user_id from solution limit 1000) old
                    on old.old_s_id=sim_s_id WHERE  old_user_id!=user_id and sim_s_id!=solution_id ";
            $str2.="&showsim=$showsim";
    }
    //$sql=$sql.$order_str." LIMIT 20";
  }

  $sql=$sql.$order_str." LIMIT 20";
  //echo $sql;

  if($OJ_MEMCACHE){
    require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
  } else{
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
  }
  $top=$bottom=-1;
  $cnt=0;
  if ($start_first){
    $row_start=0;
    $row_add=1;
  }else{
    $row_start=$rows_cnt-1;
    $row_add=-1;
  }

  $view_status=Array();

  $last=0;
  for ($i=0;$i<$rows_cnt;$i++){
    if($OJ_MEMCACHE) $row=$result[$i];
    else $row=$result->fetch_array();
    //$view_status[$i]=$row;
    if($i==0&&$row['result']<4) $last=$row['solution_id'];

    if ($top==-1) $top=$row['solution_id'];
    $bottom=$row['solution_id'];

    $cnt=1-$cnt;
  
    $view_status[$i][0]=$row['solution_id'];
       
    // if ($row['contest_id']>0) {
    //   $view_status[$i][1]= "<a href='contestrank.php?cid=".$row['contest_id']."&user_id=".$row['user_id']."#".$row['user_id']."'>".$row['user_id']."</a>";
    // } else {
      $view_status[$i][1]= "<a href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>";
    // }

    if ($row['contest_id']>0) {
      $view_status[$i][2]= "<div class=center><a href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>";
      if(isset($cid)){
        $view_status[$i][2].= $PID[$row['num']];
      }else{
        $view_status[$i][2].= $row['problem_id'];
      }
      $view_status[$i][2].="</div></a>";
    } else{
      $view_status[$i][2]= "<div class=center><a href='problem.php?id=".$row['problem_id']."'>".$row['problem_id']."</a></div>";
    }

    

    $WA_or_PE = (intval($row['result'])==5||intval($row['result'])==6);

    // =========reinfo, includes WA,RE,PE,TSET_RUN===========
    // 确认该用户是否可以查看reinfo
    $flag = true;// flag is whether uesr can see memory, time and language info.
    if (isset($_GET['cid'])) {
      $flag = ( isset($_SESSION['user_id'])&&strtolower($row['user_id'])==strtolower($_SESSION['user_id']) ||// himself
                (!is_running(intval($cid)) && $open_source) || // 比赛已经结束了且开放源代码查看
                is_numeric($row['contest_id']) && HAS_PRI("see_source_in_contest") ||
                !is_numeric($row['contest_id']) && HAS_PRI("see_source_out_of_contest")// if he can see souce code , he can see these info in passing
              ); 
    }
    $info_can_be_read = ( $WA_or_PE || $row['result']==10 || $row['result']==13); // 属于可看类型且


    $view_status[$i][3]="";
    
    if(intval($row['result'])==11 && can_see_res_info($row["solution_id"])){ //CE
      //only user himself and admin can see CE info.
        $view_status[$i][3] .= "<a href='ceinfo.php?sid=".$row['solution_id']."' class='".$judge_color[$row['result']]."'  title='$MSG_Click_Detail'>".$MSG_Compile_Error."</a>";
    }
    else if($info_can_be_read && can_see_res_info($row["solution_id"])){// others
      $view_status[$i][3] .= "<a href='reinfo.php?sid=".$row['solution_id']."' class='".$judge_color[$row['result']]."' title='$MSG_Click_Detail'>".$judge_result[$row['result']]."</a>";
    }
    else {
      if(!$lock||$lock_time>$row['in_date']||$row['user_id']==$_SESSION['user_id']){
        if($OJ_SIM&&$row['sim']>80&&$row['sim_s_id']!=$row['s_id']) {
          $view_status[$i][3].= "<span class='".$judge_color[$row['result']]."'>*".$judge_result[$row['result']]."</span>";
          if(HAS_PRI("see_compare"))
            $view_status[$i][3].= "<a href=comparesource.php?left=".$row['sim_s_id']."&right=".$row['solution_id']."  class='am-badge am-badge-secondary am-text-sm'  target=original>".$row['sim_s_id']."(".$row['sim']."%)</a>";
          else
            $view_status[$i][3].= "<span class='am-badge am-badge-secondary am-text-sm'>".$row['sim_s_id']."</span>";
          if(isset($_GET['showsim'])&&isset($row[13]))
            $view_status[$i][3].= "$row[13]";
        } else {
          //echo $row['result']." ".$judge_result[1]."<br>";
          $view_status[$i][3] .= "<span class='".$judge_color[$row['result']]."'>".$judge_result[$row['result']]."</span>";
          
        }
      } else {
        echo "<td>----";
      }
    }
    if ($row['result']!=4&&isset($row['pass_rate'])&&$row['pass_rate']>0&&$row['pass_rate']<.98)
      $view_status[$i][3].="<span class='am-badge am-badge-secondary am-text-sm'>". (100-$row['pass_rate']*100)."%</span>";
    if(isset($_SESSION['http_judge'])) {
      $view_status[$i][3].="<form class='http_judge_form form-inline'><input type=hidden name=sid value='".$row['solution_id']."'>";
      $view_status[$i][3].="</form>";
    }
          
    
    if ($flag){ 

      if ($row['result']>=4){
        $view_status[$i][4]= "<div id=center class=red>".$row['memory']."kB"."</div>";
        $view_status[$i][5]= "<div id=center class=red>".$row['time']."ms"."</div>";
        //echo "=========".$row['memory']."========";
      }else{
        $view_status[$i][4]= "---";
        $view_status[$i][5]= "---";
      }
      //echo $row['result'];

      if (isset($_SESSION['user_id'])&&strtolower($row['user_id'])==strtolower($_SESSION['user_id']) || // 是本人提交的
          (is_numeric($row['contest_id']) && !is_running($row['contest_id']) && $open_source) || // solution在比赛中，比赛结束了且开放了源代码查看
          is_numeric($row['contest_id']) && HAS_PRI("see_source_in_contest") ||
          !is_numeric($row['contest_id']) && HAS_PRI("see_source_out_of_contest")
        ) { // 可以查看代码的情况
        $view_status[$i][6]= "<a target='_blank' href=showsource.php?id=".$row['solution_id'].">".$language_name[$row['language']]."</a>";
        if($row["problem_id"]>0){
          if (isset($cid)) {
            $view_status[$i][6].= "/<a target='_blank' href=\"submitpage.php?cid=".$cid."&pid=".$row['num']."&sid=".$row['solution_id']."\">Edit</a>";
          }else{
            $view_status[$i][6].= "/<a target='_blank' href=\"submitpage.php?id=".$row['problem_id']."&sid=".$row['solution_id']."\">Edit</a>";
          }
        }
      } else { // 不能查看代码的情况
        $view_status[$i][6]=$language_name[$row['language']];
      }
      $view_status[$i][7]= $row['code_length']." B";
    } else { // 如果在正在进行的比赛中且无查看代码权限
      $view_status[$i][4]="----";
      $view_status[$i][5]="----";
      $view_status[$i][6]="----";
      $view_status[$i][7]="----";
    }
    $view_status[$i][8]= $row['in_date'];
    //$view_status[$i][9]= $row['judger'];
  }
  if(!$OJ_MEMCACHE) $result->free();

  
?>

<?php
/////////////////////////Template
if (isset($_GET['cid']))
  require("template/".$OJ_TEMPLATE."/conteststatus.php");
else
  require("template/".$OJ_TEMPLATE."/status.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

