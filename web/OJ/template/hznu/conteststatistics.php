<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.24
   * last modified
   * by yybird
   * @2016.03.24
  **/
?>
<style type="text/css" media="screen">
    .rankcell{
      white-space: normal;
      overflow: hidden;
      padding-top: 1px !important;
      padding-bottom: 1px !important;
      padding-left: 4px;
      padding-right: 4px;
      vertical-align: middle !important;
      line-height: 1.4 !important;
      border-left: 1px solid #ddd;
    }
    
  </style>
<?php $title=$MSG_STATISTICS;?>
<?php include "contest_header.php" ?>
<div class="am-container" style="margin-top:40px;">
 <!-- <h3 align="center">Contest Statistics</h3><hr/> -->
  <div class="am-container well am-scrollable-horizontal" style="max-width: 98%;">
  <table class="am-table am-table-hover am-table-striped" style="white-space: nowrap;">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo $MSG_AC ?></th>
        <th><?php echo $MSG_PE ?></th>
        <th><?php echo $MSG_WA ?></th>
        <th><?php echo $MSG_TLE ?></th>
        <th><?php echo $MSG_MLE ?></th>
        <th><?php echo $MSG_OLE ?></th>
        <th><?php echo $MSG_RE ?></th>
        <th><?php echo $MSG_CE ?></th>
        <th><?php echo $MSG_TR ?></th>
        <th><?php echo $MSG_TOTAL ?></th>
<?php 
  $i=0;
  foreach ($language_name as $lang){
	if(isset($R[$pid_total][$i+10]))	
		echo "<th class='center'>$language_name[$i]</th>";
	$i++;
  }
?>
      </tr> 
    </thead>
    <tbody>
      <?php
        //for ($i=0;$i<$pid_cnt;$i++){
		foreach($pid_nums as $num){ 
          echo "<tr><td><a href='problem.php?cid=$cid&pid=$num[0]'>".PID($num[0])."</a>";
          for ($j=0;$j<=9;$j++) {
			if(!isset($R[$num[0]][$j])) $R[$num[0]][$j]="&nbsp;";
			echo "<td class='rankcell'>".$R[$num[0]][$j];
		  }
		  for ($j=0;$j<count($language_name);$j++) {
			if(isset($R[$pid_total][$j+10])){
			   if(!isset($R[$num[0]][$j+10])) $R[$num[0]][$j+10]="&nbsp;";
			   echo "<td class='rankcell'>".$R[$num[0]][$j+10];
			}
		  }
          echo "</tr>\n";
        }
        echo "<tr><td>$MSG_TOTAL"; 
		for ($j=0;$j<=9;$j++) {
		   if(!isset($R[$pid_total][$j])) $R[$pid_total][$j]="";
		   echo "<td class='rankcell'>".$R[$pid_total][$j];
		}
        for ($j=0;$j<count($language_name);$j++) {
			if(isset($R[$pid_total][$j+10])){
			   echo "<td class='rankcell'>".$R[$pid_total][$j+10];
			}
		}
        echo "</tr>\n";
      ?>
    </tbody>
  </table>
  </div>
</div>


<?php include "footer.php" ?>