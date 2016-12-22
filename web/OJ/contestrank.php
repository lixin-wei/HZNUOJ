<?php
/**
 * This file is modified!
 * by yybird
 * @2016.05.24
 **/
?>

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
    var $p_wa_num;
    var $p_ac_sec;
    var $is_unknown;
    var $user_id;
    var $nick;
    var $real_name;
    var $stu_id;
    var $class;
    var $try_after_lock;
    function TM(){
        $this->solved=0;
        $this->time=0;
        $this->try_after_lock=array();
        $this->p_wa_num=array(0);
        $this->p_ac_sec=array(0);
        $this->is_unknown=array(0);
    }
    function Add($pid,$sec,$res){
        if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0) return;
        if ($res==-1){ //Try times after locking
            $this->try_after_lock[$pid]++;
        }
        if ($res<=3){ //unknown status
            $this->is_unknown[$pid]=true;
        }
        if ($res!=4){
            if($res!=-1){
                if(isset($this->p_wa_num[$pid])){
                    $this->p_wa_num[$pid]++;
                }
                else{
                    $this->p_wa_num[$pid]=1;
                }
            }
        } else { // AC
            $this->p_ac_sec[$pid]=$sec;
            $this->solved++;
            if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
            $this->time+=$sec+$this->p_wa_num[$pid]*1200;
        }
    }
}

function s_cmp($A,$B){
    if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);


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
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt = $result->num_rows;
    else $rows_cnt=0;
}

$start_time=0;
$end_time=0;
$user_limit = 0;
if ($rows_cnt>0){
    //      $row=$result->fetch_array();
    if($OJ_MEMCACHE) $row=$result[0];
    else $row=$result->fetch_array();
    $start_time=strtotime($row['start_time']);
    $end_time=strtotime($row['end_time']);
    $title=$row['title'];
    $user_limit = $row['user_limit']=="Y"?1:0;
}

if(!$OJ_MEMCACHE) $result->free();
if ($start_time==0){
    $view_errors= "No Such Contest";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
if ($start_time>time()){
    require_once "template/hznu/contest_header.php";
    require("template/".$OJ_TEMPLATE."/footer.php");
    exit(0);
}
if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
$lock=$end_time-$row['lock_time'];
$unlock=$row['unlock'];
if(isset($_GET['unlock']) && HAS_PRI("edit_contest")){
    $unlock=1;
}
$first_prize=$row['first_prize'];
$second_prize=$row['second_prize'];
$third_prize=$row['third_prize'];



$sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$cid'";
//$result=$mysqli->query($sql);
if($OJ_MEMCACHE){
    //        require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}else{
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
}

if($OJ_MEMCACHE) $row=$result[0];
else $row=$result->fetch_array();

//$row=$result->fetch_array();
$pid_cnt=intval($row['pbc']);
if(!$OJ_MEMCACHE)$result->free();

/* 获取班级列表 start */
$classSet = Array();
if (!$user_limit) {
    $sql = "SELECT
              DISTINCT(class)
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
              on users.user_id=solution.user_id
            ORDER BY class";
    $result = $mysqli->query($sql) or die($mysqli->error);
    while ($row=$result->fetch_object()) $classSet[] = $row->class;
    $result->free();
}
else{
    $sql = "SELECT
              DISTINCT(class)
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              RIGHT JOIN (SELECT * FROM team WHERE contest_id='$cid') team
              on team.user_id=solution.user_id
            ORDER BY class";
    $result = $mysqli->query($sql) or die($mysqli->error);
    while ($row=$result->fetch_object()) $classSet[] = $row->class;
    $result->free();
}
/* 获取班级列表 end */


if(!$OJ_MEMCACHE) $result->free();

/* origin sql
$sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name
                FROM
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
*/


$cls = $_GET['class']; // class
if ($cls == "") {
    if (!$user_limit)
        $sql_u = "SELECT
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class,users.stu_id
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                ORDER BY users.user_id,in_date";
    else $sql = "SELECT 
              team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class,team.stu_id,team.real_name
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
              on team.user_id=solution.user_id
            ORDER BY team.user_id,in_date";
} else if ($cls == "null") {
    if (!$user_limit)
        $sql_u = "SELECT
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class,users.stu_id
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                WHERE users.class='null' or users.class is null
                ORDER BY users.user_id,in_date";
    else $sql = "SELECT
              team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class,team.stu_id,team.real_name
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
              on team.user_id=solution.user_id
            WHERE team.class='null' or team.class is null or team.class='其它'
            ORDER BY team.user_id,in_date";
} else {
    if (!$user_limit)
        $sql_u = "SELECT
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class,users.stu_id
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                WHERE users.class='$cls'
                ORDER BY users.user_id,in_date";
    else $sql = "SELECT
                team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class,team.stu_id,team.real_name
              FROM
                (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
                on team.user_id=solution.user_id
              WHERE team.class='$cls'
              ORDER BY team.user_id,in_date";
}
/* 获取查询的SQL语句 end */

/* 执行查询 start */
if($OJ_MEMCACHE){
    // require("./include/memcache.php");
    $result = $mysqli->query_cache($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
} else {
    $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
}
/* 执行查询 end */


/* 获取查询结果 start */
$user_cnt=0;
$user_name='';
$U=array();
$U[$user_cnt]=new TM();
$U[0]->solved=-1;



// 查询user部分
if (isset($sql_u)) {
    $result = $mysqli->query($sql_u);// or die("Error! ".$mysqli->error);
    if($result) $rows_cnt=$result->num_rows;
    else $rows_cnt=0;
    
    for ($i=0; $i<$rows_cnt; $i++){
        if($OJ_MEMCACHE) $row=$result[$i];
        else $row=$result->fetch_array();
        
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
        if(!$unlock && $lock<strtotime($row['in_date']))
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,-1);//Unknown
        else
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));
    }
}
else{
    // 查询team部分
    //echo "$sql";
    for ($i=0; $i<$rows_cnt; $i++){
        if($OJ_MEMCACHE) $row=$result[$i];
        else $row=$result->fetch_array();
        
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
        if(!$unlock && $lock<strtotime($row['in_date']))
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,-1);//Unknown
        else
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));
    }
    $result->free();
}
/* 获取查询结果 start */

//echo $U[0]->solved;
if(!$OJ_MEMCACHE) $result->free();
usort($U,"s_cmp");

////firstblood
$first_blood=array();
for($i=0;$i<$pid_cnt;$i++){
    $sql="SELECT user_id from solution where contest_id=$cid and result=4 and num=$i order by in_date limit 1";
    $result=$mysqli->query($sql);
    $row_cnt=$result->num_rows;
    $row=$result->fetch_array();
    if($row_cnt==1){
        $first_blood[$i]=$row['user_id'];
    }else{
        $first_blood[$i]="";
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
    header("Content-Disposition: attachment; filename=\"" . $title.".xls" . "\"");
    header("Content-Type: application/force-download");
    echo "<center><h3>Contest RankList -- $title</h3></center>";
    echo "<table border=1 align='center' class='excel_table'><tr><td>Rank<td>User<td>Real Name<td>Student ID<td>Class<td>Nick<td>Solved<td>Penalty";
    for ($i=0;$i<$pid_cnt;$i++)
        echo "<td>$PID[$i]";
    echo "</tr>";
    // getMark($U,$mark_start,$mark_end,$mark_sigma);
    $rank=1;
    for ($i=0;$i<$user_cnt;$i++){
        if ($i&1) echo "<tr class=oddrow align=center>";
        else echo "<tr class=evenrow align=center>";
        echo "<td>";
        if($is_excluded[$U[$i]->user_id]){
            echo "*";
        }
        else{
            echo "$rank";
            $rank++;
        }
        $uuid=$U[$i]->user_id;
        
        $usolved=$U[$i]->solved;
        echo "<td style='mso-number-format:\"\\@\"'>".$uuid ;
        if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
            $U[$i]->nick=iconv("utf8","gbk",$U[$i]->nick);
        }
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->real_name."</td>";
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->stu_id."</td>";
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->class."</td>";
        echo "<td style='mso-number-format:\"\\@\"'>".$U[$i]->nick."</td>";
        echo "<td>$usolved";
        echo "<td>".$U[$i]->time."";
        
        //echo $U[$i]->mark>0?intval($U[$i]->mark):0;
        for ($j=0;$j<$pid_cnt;$j++){
            echo "<td class='pcell'>";
            if(isset($U[$i])){
                if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                    echo sec2str($U[$i]->p_ac_sec[$j]);
                if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
                    echo "(-".$U[$i]->p_wa_num[$j].")";
            }
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
require("template/".$OJ_TEMPLATE."/contestrank.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
