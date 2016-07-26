<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
     <link rel="next" href="submitpage.php?<?php
        
        if ($pr_flag){
		echo "id=$id";
	}else{
		echo "cid=$cid&pid=$pid&langmask=$langmask";
	}
        
        ?>">
</head>
<body>
<div id="wrapper">
	<?php
	if(isset($_GET['id']))
		require_once("oj-header.php");
	else
		require_once("contest-header.php");
	
	?>
<div id=main>
	
	<?php
	
	if ($pr_flag){
		echo "<title>$MSG_PROBLEM $row->problem_id. -- $row->title</title>";
		echo "<center><h2>$id: $row->title</h2>";
	}else{
		$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		echo "<title>$MSG_PROBLEM $PID[$pid]: $row->title </title>";
		echo "<center><h2>$MSG_PROBLEM $PID[$pid]: $row->title</h2>";
		$id=$row->problem_id;
	}
	echo "<span class=green>$MSG_Time_Limit: </span>$row->time_limit Sec&nbsp;&nbsp;";
	echo "<span class=green>$MSG_Memory_Limit: </span>".$row->memory_limit." MB";
	if ($row->spj) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
	echo "<br><span class=green>$MSG_SUBMIT: </span>".$row->submit."&nbsp;&nbsp;";
	echo "<span class=green>$MSG_SOVLED: </span>".$row->accepted."<br>"; 
	
	if ($pr_flag){
		echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
	}else{
		echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
	}
	echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";
	echo "[<a href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>]";
	  if(isset($_SESSION['administrator'])){
      require_once("include/set_get_key.php");
      ?>
      [<a href="admin/problem_edit.php?id=<?php echo $id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>]
      [<a href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>]
      <?php

    }

	echo "</center>";
	
	echo "<h2>$MSG_Description</h2><div class=content>".$row->description."</div>";
	echo "<h2>$MSG_Input</h2><div class=content>".$row->input."</div>";
	echo "<h2>$MSG_Output</h2><div class=content>".$row->output."</div>";
	
  
	
	$sinput=str_replace("<","&lt;",$row->sample_input);
  $sinput=str_replace(">","&gt;",$sinput);
	$soutput=str_replace("<","&lt;",$row->sample_output);
  $soutput=str_replace(">","&gt;",$soutput);
  if($sinput) {
      echo "<h2>$MSG_Sample_Input</h2>
			<pre class=content><span class=sampledata>".($sinput)."</span></pre>";
  }
  if($soutput){
	echo "<h2>$MSG_Sample_Output</h2>
			<pre class=content><span class=sampledata>".($soutput)."</span></pre>";
  }
  if ($pr_flag||true) 
		echo "<h2>$MSG_HINT</h2>
			<div class=content><p>".nl2br($row->hint)."</p></div>";
	if ($pr_flag) 
		echo "<h2>$MSG_Source</h2>
			<div class=content><p><a href='problemset.php?search=$row->source'>".nl2br($row->source)."</a></p></div>";
	echo "<center>";
	if ($pr_flag){
		echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
	}else{
		echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
	}
	echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";

	echo "[<a href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>]";
	
	if(isset($_SESSION['administrator'])){
      require_once("include/set_get_key.php");
  ?>
     [<a href="admin/problem_edit.php?id=<?php echo $id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>]
      [<a href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>]
     <?php
  }	
  echo "</center>";
	?>
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
