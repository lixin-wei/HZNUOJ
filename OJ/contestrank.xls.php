<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.26
  **/
?>

<?php
  ini_set("display_errors","On");
  ob_start();
  header ( "content-type:   application/excel" );
?>
<?php 
  require_once("./include/db_info.inc.php");
  global $mark_base,$mark_per_problem,$mark_per_punish;
  $mark_start=60;
  $mark_end=100;
  $mark_sigma=10;
  if(isset($OJ_LANG)){
    require_once("./lang/$OJ_LANG.php");
  }
  require_once("./include/const.inc.php");
  require_once("./include/my_func.inc.php");
  class TM {
    var $solved=0;
    var $time=0;
    var $p_wa_num;
    var $p_ac_sec;
    var $user_id;
    var $nick;
    var $mark=0;
    var $real_name;
    var $class;
    function TM(){
      $this->solved=0;
      $this->time=0;
      $this->p_wa_num=array(0);
      $this->p_ac_sec=array(0);
    }
    function Add($pid,$sec,$res,$mark_base,$mark_per_problem,$mark_per_punish) {
      if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
        return;
      if ($res!=4) 
        if(isset($this->p_wa_num[$pid]))
          $this->p_wa_num[$pid]++;
        else
          $this->p_wa_num[$pid]=1;
      else{
        $this->p_ac_sec[$pid]=$sec;
        $this->solved++;
        $this->time+=$sec+$this->p_wa_num[$pid]*1200;
        if($this->mark==0){
          $this->mark=$mark_base;
        }else{
          $this->mark+=$mark_per_problem;
        }
        $punish=intval($this->p_wa_num[$pid]*$mark_per_punish);
        if($punish<intval($mark_per_problem*.8))
          $this->mark-=$punish;
        else
          $this->mark-=intval($mark_per_problem*.8);
//      if($this->mark<$mark_base)
//        $this->mark=$mark_base;
//      echo "Time:".$this->time."<br>";
//      echo "Solved:".$this->solved."<br>";
      }
    }
  }

  function s_cmp($A,$B){
    if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
  }

  function normalDistribution( $x,  $u,  $s) {
    $ret = 1 / ($s * sqrt(2 *  M_PI))
        * pow( M_E, -pow($x - $u, 2) / (2 * $s * $s));
    return $ret;
  }

  function  getMark($users,  $start,  $end, $s) {
    $accum = 0;
    $p=0;
    $ret = 0;
    $cn=count($users);
    for ($i=$end; $i>$start; $i--) {
      $prob = $cn * normalDistribution($i, ($start+$end)/2, ($end-$start)/$s);
      $accum += $prob;
    }
    $p=$accum/$cn;
    $accum=0;
    $i=0;
  
    for ($i=$end; $i>$start; $i--) {
      $prob = $cn * normalDistribution($i, ($start+$end)/2, ($end-$start)/$s);
      $accum += $prob;
      while ($accum > $p/2) {
        if ($ret<$cn) 
          $users[$ret]->mark=$i;
        $accum -= $p;
        $ret++;
      }
    }
    while($ret<$cn){
      $users[$ret]->mark=$users[$ret-1]->mark;
      $ret++;
    }
    return $ret;
  }

  // contest start time
  if (!isset($_GET['cid'])) die("No Such Contest!");
  $cid=intval($_GET['cid']);
  // require_once("contest-header.php");
  $sql="SELECT `start_time`,`title` FROM `contest` WHERE `contest_id`='$cid'";
  $result=mysql_query($sql) or die(mysql_error());
  $rows_cnt=mysql_num_rows($result);
  $start_time=0;
  if ($rows_cnt>0) {
    $row=mysql_fetch_array($result);
    $start_time=strtotime($row[0]);
    $title=$row[1];
    if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
      $title=iconv("utf8","gbk",$title);
    }
    header ( "content-disposition:   attachment;   filename=contest".$cid."_".$title.".xls" );
  }
  mysql_free_result($result);
  if ($start_time==0){
    echo "No Such Contest";
    //require_once("oj-footer.php");
    exit(0);
  }

  if ($start_time>time()){
    echo "Contest Not Started!";
    //require_once("oj-footer.php");
    exit(0);
  }

  $sql="SELECT count(1) FROM `contest_problem` WHERE `contest_id`='$cid'";
  $result=mysql_query($sql);
  $row=mysql_fetch_array($result);
  $pid_cnt=intval($row[0]);
  if($pid_cnt==1) {
    $mark_base=100;
    $mark_per_problem=0;
  } else {
    $mark_per_problem=(100-$mark_base)/($pid_cnt-1);
  }
  $mark_per_punish=$mark_per_problem/5;
  mysql_free_result($result);

$sql="SELECT 
  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,class 
    FROM 
      (select * from solution where solution.contest_id='$cid' and num>=0) solution 
    left join users 
    on users.user_id=solution.user_id 
  ORDER BY users.user_id,in_date";
//echo $sql;
$result=mysql_query($sql);
$user_cnt=0;
$user_name='';
$U=array();
while ($row=mysql_fetch_object($result)){
  $n_user=$row->user_id;
  if (strcmp($user_name,$n_user)){
    $user_cnt++;
    $U[$user_cnt]=new TM();
    $U[$user_cnt]->user_id=$row->user_id;
    $U[$user_cnt]->nick=$row->nick;

    $user_name=$n_user;
  }
  $U[$user_cnt]->Add($row->num,strtotime($row->in_date)-$start_time,intval($row->result),$mark_base,$mark_per_problem,$mark_per_punish);
  $U[$user_cnt]->real_name = $row->real_name;
  $U[$user_cnt]->class = strtoupper($row->class);
}
mysql_free_result($result);
usort($U,"s_cmp");
$rank=1;
//echo "<style> td{font-size:14} </style>";
//echo "<title>Contest RankList -- $title</title>";
echo "<center><h3>Contest RankList -- $title</h3></center>";
echo "<table border=1 align='center'><tr><td>Rank<td>User<td>Real Name<td>Class<td>Nick<td>Solved<td>Mark";
for ($i=0;$i<$pid_cnt;$i++)
  echo "<td>$PID[$i]";
echo "</tr>";
getMark($U,$mark_start,$mark_end,$mark_sigma);

for ($i=0;$i<$user_cnt;$i++){
  if ($i&1) echo "<tr class=oddrow align=center>";
  else echo "<tr class=evenrow align=center>";
  echo "<td>$rank";
  $rank++;
  $uuid=$U[$i]->user_id;
        
  $usolved=$U[$i]->solved;
  echo "<td>".$uuid."&nbsp";
  if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
    $U[$i]->nick=iconv("utf8","gbk",$U[$i]->nick);
  }
  echo "<td>".$U[$i]->real_name."";
  echo "<td>".$U[$i]->class."";
  echo "<td>".$U[$i]->nick."";
  echo "<td>$usolved";
  echo "<td>";
  
  
  echo $U[$i]->mark>0?intval($U[$i]->mark):0;
  for ($j=0;$j<$pid_cnt;$j++){
    echo "<td>";
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

?>
