<?php
$OJ_CACHE_SHARE=true;
$cache_time=10;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");


$view_title= $MSG_CONTEST.$MSG_RANKLIST;
$title="";

class TM {
    var $solved=0;
    var $time=0;
    var $score;
    var $p_wa_num;
    var $p_ac_sec;
    var $p_pass_rate;
    var $user_id;
    var $nick;
    var $real_name;
    var $stu_id;
    var $class;
    var $try_after_lock;
    function TM__construct(){
        $this->score = 0;
        $this->solved=0;
        $this->time=0;
        $this->try_after_lock=array();
        $this->p_wa_num=array(0);
        $this->p_ac_sec=array(0);
        $this->p_pass_rate=array(0);
    }
    function Add($pid,$sec,$res){
        global $problem_score;
        if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0) return;
        if ($res==-1){ //Try times after locking
            $this->try_after_lock[$pid]++;
            return;
        }
        if ($res * 100 < 99) {
            if (isset($this->p_pass_rate[$pid])) {
              if ($res > $this->p_pass_rate[$pid]) {
                $this->score -= $this->p_pass_rate[$pid] * $problem_score[$pid];;
                $this->p_pass_rate[$pid] = $res;
                $this->score += $this->p_pass_rate[$pid] * $problem_score[$pid];;
              }
            } else {
              $this->p_pass_rate[$pid] = $res;
              $this->score += $res * $problem_score[$pid];;
            }
            if (isset($this->p_wa_num[$pid])) {
              $this->p_wa_num[$pid]++;
            } else {
              $this->p_wa_num[$pid] = 1;
            }
        } else {
          $this->p_ac_sec[$pid] = $sec;
          $this->solved++;
          if (!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid] = 0;
          if (isset($this->p_pass_rate[$pid])) $this->score -= $this->p_pass_rate[$pid] * $problem_score[$pid];
          $this->score += $problem_score[$pid];
          $this->time += $sec + $this->p_wa_num[$pid] * 1200;
        }
    }
}

function s_cmp($A,$B){
    if ($A->score!=$B->score) return $A->score<$B->score;
    else if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
}


$real_name_mode = false;
if(isset($_GET['real_name_mode']) && HAS_PRI("see_hidden_user_info")) {
  $real_name_mode = true;
}
// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);


//get problem score list
$sql = <<<SQL
SELECT
    num, score 
FROM
    contest_problem 
WHERE
    contest_id = $cid 
ORDER BY
    num
SQL;

$problem_score = array();
$res = $mysqli->query($sql);
while($row = $res->fetch_array()) {
  $problem_score[$row['num']] = intval($row['score']);
}

$sql="SELECT user_id FROM contest_excluded_user WHERE contest_id=$cid";
$res=$mysqli->query($sql);
$is_excluded=array();
while($uid=$res->fetch_array()[0]){
    $is_excluded[$uid]=true;
}


$sql="SELECT `start_time`,`title`,`end_time`,user_limit,lock_time,`unlock`,first_prize,second_prize,third_prize FROM `contest` WHERE `contest_id`='$cid'";
//$result=$mysqli->query($sql) or die($mysqli->error);
//$rows_cnt=$result->num_rows;
if($OJ_MEMCACHE){
    require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
} else {
    $result = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}

$start_time=0;
$end_time=0;
$user_limit = 0;
if ($rows_cnt>0){
    //      $row=$result->fetch_array();
    $row=$result[0];
    $start_time=strtotime($row['start_time']);
    $end_time=strtotime($row['end_time']);
    $title=$row['title'];
    $user_limit = $row['user_limit']=="Y"?1:0;
}

if ($start_time==0){
    $view_errors= "No Such Contest";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
if ($start_time>time()){
    $view_errors= "Contest Not Started!";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
$unlock=$row['unlock'];
switch($unlock){
    case 0: //用具体时间来控制封榜
        $lock=$end_time-$row['lock_time'];
        break;
    case 2: //用时间比例来控制封榜
        $lock = $end_time - ($end_time - $start_time) * $row['lock_time'] / 100;
        break;
}

$first_prize=$row['first_prize'];
$second_prize=$row['second_prize'];
$third_prize=$row['third_prize'];



//跳过不存在题目的题号
$sql = "SELECT `num` FROM contest_problem a 
            inner join (select problem_id from `problem`) b 
            on a.problem_id = b.problem_id 
            WHERE contest_id = $cid and num >=0 order by num" ;
$result=$mysqli->query($sql) or die($mysqli->error);
$pid_cnt=$result->num_rows;
$pid_nums=$result->fetch_all(MYSQLI_BOTH);

/* 获取班级列表 start */
$classSet = Array();
if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
    if (!$user_limit) {
        $sql = "SELECT
                DISTINCT(class)
                FROM
                (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                    left join users
                on users.user_id=solution.user_id
                ORDER BY class";
    } else {
        $sql = "SELECT
                DISTINCT(class)
                FROM
                (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                RIGHT JOIN (SELECT * FROM team WHERE contest_id='$cid') team
                on team.user_id=solution.user_id
                ORDER BY class";
    }
    $result = $mysqli->query($sql) or die($mysqli->error);    
    while ($row=$result->fetch_object()) $classSet[] = $row->class;
    $result->free();
}
/* 获取班级列表 end */

/* origin sql
$sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name
                FROM
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
*/


$cls = $mysqli->real_escape_string($_GET['class']); // class
switch($cls){
    case "":
        $sql_filter = " ";
        break;
    case "null":
        if (!$user_limit) $sql_filter = " WHERE users.class='null' or users.class is null or users.class='其它'";
        else$sql_filter = " team.class='null' or team.class is null or team.class='其它'";
        break;
    default:
        if (!$user_limit) $sql_filter = " WHERE users.class='$cls' ";
        else $sql_filter = " WHERE team.class='$cls'";
}

if (!$user_limit)
    $sql = "SELECT
              users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class,users.stu_id,solution.pass_rate
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              INNER join users
              on users.user_id=solution.user_id ".$sql_filter." ORDER BY users.user_id,in_date";
else $sql = "SELECT 
          team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class,team.stu_id,team.real_name,solution.pass_rate
        FROM
          (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
          INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
          on team.user_id=solution.user_id ".$sql_filter." ORDER BY team.user_id,in_date";
/* 获取查询的SQL语句 end */

/* 执行查询 start */
if($OJ_MEMCACHE){
    // require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
} else {
    $result = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}
/* 执行查询 end */


/* 获取查询结果 start */
$user_cnt=0;
$user_name='';
$U=array();
$U[$user_cnt]=new TM();
$U[0]->solved=-1;

for ($i=0; $i<$rows_cnt; $i++){
    $row=$result[$i];
    $n_user=$row['user_id'];
    if (strcmp($user_name,$n_user)){
        $user_cnt++;
        $U[$user_cnt]=new TM();
        $U[$user_cnt]->user_id=$row['user_id'];
        $U[$user_cnt]->nick=$row['nick'];
        $U[$user_cnt]->real_name = $row['real_name'];
        $U[$user_cnt]->stu_id = $row['stu_id'];
        $U[$user_cnt]->class = $row['class'];
        $user_name=$n_user;
    }
    if($unlock != 1 && time() < $end_time && $lock < strtotime($row['in_date']))
        $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,-1);//Unknown
    else
        $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,$row['pass_rate']);
}

/* 获取查询结果 start */

//echo $U[0]->solved;
usort($U,"s_cmp");

//firstblood 找每题第一个解决的人
$first_blood=array();
foreach($pid_nums as $num){
    $sql="SELECT user_id from solution where contest_id=$cid and result=4 and num=$num[0] order by in_date limit 1";
    $result=$mysqli->query($sql);
    $row_cnt=$result->num_rows;
    $row=$result->fetch_array();
    if($row_cnt==1){
        $first_blood[$num[0]]=$row['user_id'];
    }else{
        $first_blood[$num[0]]="";
    }
    
}
?>
<?php if (isset($_GET['download_ranklist']) && HAS_PRI("download_ranklist")): ?>
  <style type="text/css" media="screen">
    .text{
      mso-number-format:"\@";
    }
    .excel_table{
      white-space: nowrap;
      table-layout: fixed;
    }
    .pcell{
      min-width: 150px;
    }
  </style>
    <?php
    echo "<meta http-equiv='Content-type' content='text/html;charset=UTF-8' /> ";
    header ( "content-type:   application/excel" );
    header("Content-Disposition: attachment; filename=\"contest$cid". "_". $title."_OI.xls" . "\"");
    header("Content-Type: application/force-download");
    echo "<center><h3>Contest OI RankList -- $title</h3></center>";
    echo "<table border=1 align='center' class='excel_table'><tr>";
    if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE)  echo "<td>$MSG_REAL_NAME<td>Student ID<td>Class";
    echo "<td>$MSG_RANK<td>$MSG_USER<td>$MSG_NICK<td>$MSG_SCORE<td>$MSG_SOLVED<td>$MSG_PENALTY";
    foreach($pid_nums as $num)
        echo "<td>".PID($num[0])."</td>";
    echo "</tr>";
    // getMark($U,$mark_start,$mark_end,$mark_sigma);
    $rank=1;
    for ($i=0;$i<$user_cnt;$i++){
        echo "<tr align=left>";
        if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
            echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->real_name."</td>";
            echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->stu_id."</td>";
            echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->class."</td>";
        }
        echo "<td>";
        if($is_excluded[$U[$i]->user_id]){
            echo "*";
        } else {
            echo "$rank";
            $rank++;
        }
        echo "</td>";
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->user_id."</td>";
        if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
            $U[$i]->nick=iconv("utf8","gbk",$U[$i]->nick);
        }
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->nick."</td>";
        echo "<td>".$U[$i]->score."</td>";
        echo "<td>".$U[$i]->solved."</td>";
        echo "<td>".$U[$i]->time."</td>";
        
        //echo $U[$i]->mark>0?intval($U[$i]->mark):0;
        foreach($pid_nums as $num){
            echo "<td class='pcell' style='mso-number-format:\"\\@\"'>";
            if(isset($U[$i])){
                if (isset($U[$i]->p_ac_sec[$num[0]])&&$U[$i]->p_ac_sec[$num[0]]>0)
                    echo sec2str($U[$i]->p_ac_sec[$num[0]]);
                else if (isset($U[$i]->p_wa_num[$num[0]])&&$U[$i]->p_wa_num[$num[0]]>0)
                    echo "(+"+$U[$i]->p_pass_rate[$num[0]]*$problem_score[$num[0]]+")";
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    header("Connection: close");
    exit(0);
    ?>
<?php endif ?>

<?php
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/contestrank-oi.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>