<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv='refresh' content='60'>
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
   <script type="text/javascript" src="include/jquery-latest.js"></script> 
<script type="text/javascript" src="include/jquery.tablesorter.js"></script> 
<script type="text/javascript">
$(document).ready(function() 
    { 

 $.tablesorter.addParser({ 
        // set a unique id 
        id: 'punish', 
        is: function(s) { 
            // return false so this parser is not auto detected 
            return false; 
        }, 
        format: function(s) { 
            // format your data for normalization 
	    var v=s.toLowerCase().replace(/\:/,'').replace(/\:/,'').replace(/\(-/,'.').replace(/\)/,''); 
	    //alert(v);
	    v=parseFloat('0'+v);
	    return v>1?v:v+Number.MAX_VALUE-1;
        }, 
        // set type, either numeric or text 
        type: 'numeric' 
    }); 

        $("#rank").tablesorter({ 
            headers: { 
                4: { 
                    sorter:'punish' 
                }
		
<?php
for ($i=0;$i<$pid_cnt;$i++){
                echo ",".($i+5).": { ";
                echo "    sorter:'punish' ";
                echo "}";
}
?>
            } 
        }); 
    } 
); 
</script>
</head>
<body>
<div id="wrapper">
<div id=main>
	<?php require_once("contest-header.php");?>
<?php
$rank=1;
?>
<center><h3>Contest RankList -- <?php echo $title?></h3><a href="contestrank.xls.php?cid=<?php echo $cid?>" >Download</a></center>
<table id=rank><thead><tr class=toprow align=center><td class="{sorter:'false'}" width=5%>Rank<th width=10%>User<th width=10%>Nick<th width=5%>Solved<th width=5%>Penalty
<?php
for ($i=0;$i<$pid_cnt;$i++)
	echo "<th class=\"{sorter:'punish'}\"><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a>";
     echo "</tr></thead>\n<tbody>";
for ($i=0;$i<$user_cnt;$i++){
	if ($i&1) echo "<tr class=oddrow align=center>\n";
	else echo "<tr class=evenrow align=center>\n";
	echo "<td>";
	$uuid=$U[$i]->user_id;
  $nick=$U[$i]->nick;
  if($nick[0]!="*")
        echo $rank++;
  else 
        echo "*";
      
	$usolved=$U[$i]->solved;
  if($uuid==$_GET['user_id']) echo "<td bgcolor=#ffff77>";
  else echo"<td>";
	echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
	echo "<td><a href=userinfo.php?user=$uuid>".$U[$i]->nick."</a>";
	echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
	echo "<td>".sec2str($U[$i]->time);
	for ($j=0;$j<$pid_cnt;$j++){
		$bg_color="eeeeee";
		 if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
                	$aa=0x33+$U[$i]->p_wa_num[$j]*32;
                        $aa=$aa>0xaa?0xaa:$aa;
                	$aa=dechex($aa);
			$bg_color="$aa"."ff"."$aa";
                
                
                  //$bg_color="aaffaa";
                        if($uuid==$first_blood[$j]){
                                $bg_color="aaaaff";
                        }
			
                        
                        
		}else if(isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
                        $aa=0xaa-$U[$i]->p_wa_num[$j]*10;
                        $aa=$aa>16?$aa:16;
                	$aa=dechex($aa);
			$bg_color="ff$aa$aa";
		}
		
		
		 echo "<td class=well style='padding:1px;background-color:$bg_color'>";
		if(isset($U[$i])){
			if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
				echo sec2str($U[$i]->p_ac_sec[$j]);
			if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
				echo "(-".$U[$i]->p_wa_num[$j].")";
		}
	}
	echo "</tr>\n";
}
     echo "</tbody></table>";
?>

<script>
  function getTotal(rows){
    var total=0;
    for(var i=0;i<rows.length&&total==0;i++){
      try{
         total=parseInt(rows[rows.length-i].cells[0].innerHTML);
          if(isNaN(total)) total=0;
      }catch(e){
      
      }
    }
    return total;
  
  }
function metal(){
  var tb=window.document.getElementById('rank');
  var rows=tb.rows;
  try{
  var total=getTotal(rows);
  //alert(total);
	  for(var i=1;i<rows.length;i++){
	  	var cell=rows[i].cells[0];
      var acc=rows[i].cells[3];
      var ac=parseInt(acc.innerText);
      if (isNaN(ac)) ac=parseInt(acc.textContent);
                
                
	  	if(cell.innerHTML!="*"&&ac>0){
	 
	  	     var r=parseInt(cell.innerHTML);
	  	     if(r==1){
	  	       cell.innerHTML="Winner";
                       //cell.style.cssText="background-color:gold;color:red";
                       cell.className="badge badge-warning";
	  	     }
	  	     if(r>1&&r<=total*.05+1)
	  	        cell.className="badge badge-warning";
	  	     if(r>total*.05+1&&r<=total*.20+1)
	  	        cell.className="badge";
	  	     if(r>total*.20+1&&r<=total*.45+1)
	  	        cell.className="badge badge-error";
	  	     if(r>total*.45+1&&ac>0)
              		cell.className="badge badge-info";
	  	}
	  }
  }catch(e){
     //alert(e);
  }
}
metal();


</script>

<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
