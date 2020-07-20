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
  require_once("./include/const.inc.php");

    if ($OJ_TEMPLATE == "hznu")
      $judge_color=Array( "am-btn am-btn-secondary am-btn-sm", // Pending
                          "am-btn am-btn-secondary am-btn-sm", // Pending & Rejudging
                          "am-btn am-btn-secondary am-btn-sm", // Running & Judging
                          "am-btn am-btn-secondary am-btn-sm", // Compliing
                          "am-btn am-btn-success am-btn-sm", // AC
                          "am-btn am-btn-danger am-btn-sm", // PE
                          "am-btn am-btn-danger am-btn-sm", // WA
                          "am-btn am-btn-warning am-btn-sm", // TLE
                          "am-btn am-btn-warning am-btn-sm", // MLE
                          "am-btn am-btn-warning am-btn-sm", // OLE
                          "am-btn am-btn-warning am-btn-sm", // RE
                          "am-btn am-btn-primary am-btn-sm", // CE
                          "am-btn am-btn-warning am-btn-sm",
                          "am-btn am-btn-primary am-btn-sm",

                        );
    else if($OJ_TEMPLATE!="classic") 
      $judge_color=Array("btn gray","btn btn-info","btn btn-warning","btn btn-warning","btn btn-success","btn btn-danger","btn btn-danger","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-info");

  //echo $OJ_SHOW_DIFF;
  //分页start
  $page = 1;
  if(isset($_GET['page'])) $page = intval($_GET['page']);
  $page_cnt = 20;

  //分页end  
  
  $lock=false;
  $sql=" WHERE problem_id>0 ";
  //check the cid arg start
  if (isset($_GET['cid'])){	 
    $cid=intval($_GET['cid']);
    $sql=$sql." AND `contest_id`='$cid' and num>=0 ";
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
      $lock_time=$row->lock_time;
      $unlock=$row->unlock;
    }
    switch($unlock){
      case 0: //用具体时间来控制封榜
          $lock_t=$end_time-$lock_time;
          break;
      case 2: //用时间比例来控制封榜
          $lock_t = $end_time - ($end_time - $start_time) * $lock_time / 100;
          break;
    }
    if($unlock != 1 && time()>$lock_t && time()<$end_time){
       $lock=true;
    }else{
       $lock=false;
    }
    //require_once("contest-header.php");
  } else {
    if (isset($_SESSION['contest_id'])){ //不允许比赛用户查看比赛外的排名
      $view_errors= "<font color='red'>$MSG_HELP_TeamAccount_forbid</font>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }
    if(isset($OJ_show_contestSolutionInStatus)&&$OJ_show_contestSolutionInStatus){
      $sql=" WHERE 1 "; //要在主状态页面中显示contest中提交的代码
    } else $sql=" WHERE (contest_id is null OR contest_id=0) ";//不在主状态页面中显示contest中提交的代码
  }
  
  $order_str=" ORDER BY `solution_id` DESC ";
  //check the cid arg end
  // check the top arg
  if (isset($_GET['top'])){
    $top=strval(intval($_GET['top']));
    if ($top>0) $sql=$sql."AND `solution_id`<='".$top."' ";
  }

  // check the problem arg
  $problem_id="";
  if (isset($_GET['problem_id'])&&$_GET['problem_id']!=""){
    
    if(isset($_GET['cid'])){
      $problem_id=$_GET['problem_id'];
      $num=get_id_from_label($problem_id);
      $sql=$sql."AND `num`='".$num."' ";
    }else{
          $problem_id=strval(intval($_GET['problem_id']));
          if ($problem_id!='0'){
                  $sql=$sql."AND `problem_id`='".$problem_id."' ";
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
          }else $user_id="";
  }
  // check the language arg
  if (isset($_GET['language'])) $language=intval($_GET['language']);
  else $language=-1;

  if ($language>count($language_ext) || $language<0) $language=-1;
  if ($language!=-1){
    $sql=$sql."AND `language`='".strval($language)."' ";
  }
  // check the jresult_get arg
  if (isset($_GET['jresult'])) $jresult_get=intval($_GET['jresult']);
  else $jresult_get=-1;

  if ($jresult_get>12 || $result<0) $jresult_get=-1;
  if ($jresult_get!=-1&&!$lock){
    $sql=$sql."AND `result`='".strval($jresult_get)."' ";
  }
  $sql_page = "SELECT count(1) FROM `solution` ".$sql;
  $rows =$mysqli->query($sql_page)->fetch_all(MYSQLI_BOTH) or die($mysqli->error);
  if($rows) $total = $rows[0][0];  
  $view_total_page = ceil($total / $page_cnt); //计算页数
  $view_total_page = $view_total_page>0?$view_total_page:1;
  if ($page > $view_total_page) $page = $view_total_page;
  if ($page < 1) $page = 1;
  $pstart = $page_cnt*$page-$page_cnt;
  $pend = $page_cnt;
  $sql_limit = " limit ".strval($pstart).",".strval($pend);

  if($OJ_SIM){
    //$old=$sql;
    $sql="SELECT * from solution solution left join `sim` sim on solution.solution_id=sim.s_id ".$sql;
    if(isset($_GET['showsim'])&&intval($_GET['showsim'])>0){
          $showsim=intval($_GET['showsim']);
          $sql.=" and sim.sim>=$showsim";
    }
  }else{
	  $sql="select * from `solution` ".$sql;
  }

  //if is rankist query, show all submissions
  if(isset($_GET['ranklist_ajax_query'])){
    $sql_limit="";
  }
  $sql=$sql.$order_str.$sql_limit;

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
       
    if ($row['contest_id']>0 && !isset($cid)) {
       $view_status[$i][1]= "<a target='_blank' href='contestrank.php?cid=".$row['contest_id']."&user_id=".$row['user_id']."#".$row['user_id']."'>".$row['user_id']."</a>";
    } else {
      $view_status[$i][1]= "<a target='_blank' href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>";
    }

    if ($row['contest_id']>0) {
      $view_status[$i][2]= "<div class=center>";
      if(isset($cid)){
        $view_status[$i][2].= "<a target='_blank' href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>".PID($row['num'])."</a>";
      }else{
		$view_status[$i][2].= "<a target='_blank' href='problem.php?id=".$row['problem_id']."'>".$row['problem_id']."</a>";
        $view_status[$i][2].= "&nbsp;(<a target='_blank' href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>".$row['contest_id']."-".PID($row['num'])."</a>)";
	  }
      $view_status[$i][2].="</div>";
    } else{
      $view_status[$i][2]= "<div class=center><a target='_blank' href='problem.php?id=".$row['problem_id']."'>".$row['problem_id']."</a></div>";
    }

    

    $WA_or_PE = (intval($row['result'])==5||intval($row['result'])==6);

    // =========reinfo, includes WA,RE,PE,TSET_RUN===========
	switch($row['result']){
		case 4:
			$MSG_Tips=$MSG_HELP_AC;break;
		case 5:
			$MSG_Tips=$MSG_HELP_PE;break;
		case 6:
			$MSG_Tips=$MSG_HELP_WA;break;
		case 7:
			$MSG_Tips=$MSG_HELP_TLE;break;
		case 8:
			$MSG_Tips=$MSG_HELP_MLE;break;
		case 9:
			$MSG_Tips=$MSG_HELP_OLE;break;
		case 10:
			$MSG_Tips=$MSG_HELP_RE;break;
		case 11:
			$MSG_Tips=$MSG_HELP_CE;break;
		default: $MSG_Tips="";
	}
	
	$mark="";
	if ($row['result']!=4&&isset($row['pass_rate'])&&$row['pass_rate']>0&&$row['pass_rate']<.98)
	    $mark=(100-$row['pass_rate']*100)."%";    //有测试数据通过且没有全通过时显示错误率
    
    // 确认该用户是否可以查看reinfo
    $flag = true;// flag is whether uesr can see memory, time and language info.
    if (isset($_GET['cid'])) {
      $flag = ( isset($_SESSION['user_id'])&&strtolower($row['user_id'])==strtolower($_SESSION['user_id']) ||// himself
                (!is_running(intval($cid))) || // 比赛已经结束了
                is_numeric($row['contest_id']) && HAS_PRI("see_source_in_contest") ||
                !is_numeric($row['contest_id']) && HAS_PRI("see_source_out_of_contest")// if he can see souce code , he can see these info in passing
              ); 
    }
    $info_can_be_read = ( $WA_or_PE || $row['result']==7 || $row['result']==8 || $row['result']==10 || $row['result']==13); // 属于可看类型且
    if (isset($_GET['ranklist_ajax_query'])) $target=" target='_blank' "; else $target="";
    $contest = $cid  ? "&cid=$cid" : "";
    $view_status[$i][3]="<span class='hidden' style='display:none' result='".$row['result']."' ></span>";
    if($lock&&$lock_t<=strtotime($row['in_date'])&&$row['user_id']!=$_SESSION['user_id'] && !HAS_PRI("edit_contest")){
      $view_status[$i][3] = "----";//Unknown
    } else if(intval($row['result'])==11 && can_see_res_info($row["solution_id"])){ //CE
      //only user himself and admin can see CE info.
        $view_status[$i][3] .= "<a href='ceinfo.php?sid=".$row['solution_id']."$contest' class='".$judge_color[$row['result']]."' $target title='".$MSG_Tips."'>".$MSG_Compile_Error.$mark."</a>";
    } else if($info_can_be_read && can_see_res_info($row["solution_id"])){// others WA/PE/RE/TE
      $view_status[$i][3] .= "<a href='reinfo.php?sid=".$row['solution_id']."$contest' class='".$judge_color[$row['result']]."' $target title='".$MSG_Tips."'>".$judge_result[$row['result']].$mark."</a>";
    } else if($OJ_SIM&&$row['sim']>=70&&$row['sim_s_id']!=$row['s_id']) {
        $view_status[$i][3].= "<span class='".$judge_color[$row['result']]."' title='".$MSG_Tips."'>*".$judge_result[$row['result']].$mark."</span>";
        if(HAS_PRI("see_compare")){
          $view_status[$i][3].= "<a href=comparesource.php?left=".$row['sim_s_id']."&right=".$row['solution_id']." $target class='am-btn am-btn-secondary am-btn-sm' >".$row['sim_s_id']."(".$row['sim']."%)</a>";
        } else {
          $view_status[$i][3].= "<span class='am-btn am-btn-secondary am-btn-sm'>".$row['sim_s_id']."(".$row['sim']."%)</span>";
        }
        if(isset($row['sim_s_id']))  $view_status[$i][3].= "<span sid='".$row['sim_s_id']."' class='original'></span>";
    } else {
        //echo $row['result']." ".$judge_result[1]."<br>";
        $view_status[$i][3] .= "<span class='".$judge_color[$row['result']]."' title='".$MSG_Tips."'>".$judge_result[$row['result']].$mark."</span>";
    }
    if(HAS_PRI("rejudge")) {
      $view_status[$i][3].="<form class='http_judge_form form-inline'><input type='hidden' name='sid' value='".$row['solution_id']."'>";
      $view_status[$i][3].="</form>";
    }
          
    
    if ($flag){ 

      if ($row['result']>=4){
        $view_status[$i][4]= "<div id=center class=red>".$row['memory']."KB"."</div>";
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
            $view_status[$i][6].= "/<a href=\"submitpage.php?cid=".$cid."&pid=".$row['num']."&sid=".$row['solution_id']."\" $target>$MSG_EDIT</a>";
          }else{
            $view_status[$i][6].= "/<a href=\"submitpage.php?id=".$row['problem_id']."&sid=".$row['solution_id']."\" $target>$MSG_EDIT</a>";
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
    $view_status[$i][9]= $row['judger'];
  }
  if(!$OJ_MEMCACHE) $result->free();

  
?>
<!-- ranklist ajax query mod START -->
<?php if (isset($_GET['ranklist_ajax_query'])): ?>
  <?php
  if($rows_cnt==0)
    exit(0);
  ?>
  <table id="result-tab" class="am-table am-table-hover am-table-striped" style='white-space: nowrap;'>
    <thead>
      <tr>
        <th><?php echo $MSG_RUNID ?></th>
        <th><?php echo $MSG_USER ?></th>
        <th><?php echo $MSG_PROBLEM_ID ?></th>
        <th><?php echo $MSG_RESULT ?></th>
        <th><?php echo $MSG_MEMORY ?></th>
        <th><?php echo $MSG_TIME ?></th>
        <th><?php echo $MSG_LANG ?></th>
        <th><?php echo $MSG_CODE_LENGTH ?></th>
        <th><?php echo $MSG_SUBMIT_TIME ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach($view_status as $row){
        echo "<tr>";
        echo "<td style='text-align:left'>".$row[0]."</td>";
        echo "<td style='text-align:left'>".$row[1]."</td>";
        echo "<td style='text-align:left'>".$row[2]."</td>";
        echo "<td style='text-align:left'>".$row[3]."</td>";
        echo "<td style='text-align:left'>".$row[4]."</td>";
        echo "<td style='text-align:left'>".$row[5]."</td>";
        echo "<td style='text-align:left'>".$row[6]."</td>";
        echo "<td style='text-align:left'>".$row[7]."</td>";
        echo "<td style='text-align:left'>".$row[8]."</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
<script>
var i = 0;
var judge_result = [<?php echo "'". implode("','", $judge_result) ."'";?>];
var judge_color = [<?php echo "'". implode("','", $judge_color) ."'";?>];
</script>
<script src="template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js?v=0.38"></script>
  <?php exit(0) ?>
<?php endif ?>
<!-- ranklist ajax query mod END -->
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

