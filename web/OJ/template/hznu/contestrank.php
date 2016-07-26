<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.24
   * last modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php include "contest_header.php" ?>

<!-- 滚屏js代码 start -->
<script type="text/javascript">
  function startmarquee(lh, speed, delay) {
    var t;
    var oHeight = 300; /** div的高度 **/　
    var p = false;
    var o = document.getElementById("show");
    var preTop = 0;
    o.scrollTop = 0;
    function start() {
      t = setInterval(scrolling, speed);
      o.scrollTop += 1;
    }
    function scrolling() {
      if (o.scrollTop % lh != 0
          && o.scrollTop % (o.scrollHeight - oHeight - 1) != 0) {
        preTop = o.scrollTop;
        o.scrollTop += 1;
        if (preTop >= o.scrollHeight || preTop == o.scrollTop) {
          o.scrollTop = 0;
        }
      } else {
        clearInterval(t);
        setTimeout(start, delay);
      }
    }
    setTimeout(start, delay);
  }
  window.onload=function(){
    /**startmarquee(一次滚动高度,速度,停留时间);**/　　
    startmarquee(50, 20, 2000);
  }
</script>
<!-- 滚屏js代码 end -->

<?php // 根据当前是否需要滚屏给予show不同的style
  if ($_GET['scroll']) $showStyle = "margin-top:40px;height:1000px;overflow-y:scroll;overflow-x:scroll;";
  else $showStyle = "margin-top:40px;";
?>

<div style=<?php echo $showStyle?> class='am-g' id='show'>
  <h3 align="center">Contest RankList  -- <?php echo $title?></h3>
  <hr />

  <!-- 工具栏 start -->
  <div class='am-text-center'>
    <?php 
      if($GE_TA)
        echo "[ <a href='contestrank.xls.php?cid=".$cid."'>".$MSG_DOWNLOAD_RANK."</a> ]&nbsp;&nbsp;&nbsp;";
      if (!$_GET['scroll'])
        echo "[ <a href='contestrank.php?scroll=true&cid=".$cid."'>Auto-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
      else 
        echo "[ <a href='contestrank.php?cid=".$cid."'>No-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
    ?>
    [ Choose Class
      <select id="class">
        <option value="" <?php if ($_GET['class']=="") echo "selected"; ?> >显示全部</option>
        <option value="null" <?php if ($_GET['class']=="null") echo "selected"; ?> >其它</option>
        <!-- don't remove "其它" option to for loop, if both null and "null" exist, there will occur two options -->
        <?php 
          $sz = count($classSet);
          for ($i=0; $i<$sz; $i++) {
            if ($classSet[$i]==null || $classSet[$i]=="null" || $classSet[$i]=="其它") continue; 
        ?>
            <option value="<?php echo $classSet[$i]; ?>" <?php if ($_GET['class']==$classSet[$i]) echo "selected"; ?> ><?php echo $classSet[$i]; ?></option>
        <?php
          }
        ?>
      </select>
    ]
    <!-- 选择班级后自动跳转页面的js代码 start -->
    <script type="text/javascript">
      var oSelect=document.getElementById("class");
      oSelect.onchange=function() { //当选项改变时触发
        var valOption=this.options[this.selectedIndex].value; //获取option的value
        var url = window.location.search;
        var cid = url.substr(url.indexOf('=')+1,4);
        var url = window.location.pathname+"?cid="+cid+"&class="+valOption;
        window.location.href = url;
      }
    </script>
    <!-- 选择班级后自动跳转页面的js代码 end -->
  </div>
  <!-- 工具栏 end --> 

  <br />

  <!-- 排名表格 start -->
  <table class="am-table am-table-bordered am-table-striped" style='font-size:14px;' id="rank">
    <thead align="center">
      <td width="70px">Rank</td>
      <td>User</td>
      <td style="min-width: 150px">Nick</td>
      <td>Solved</td>
      <td>Penalty</td>
      <?php
        for ($i=0;$i<$pid_cnt;$i++)
          echo "<td><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
      ?>
    </thead>
    <tbody>
      <?php
        $rank=1;
        for ($i=0;$i<$user_cnt;$i++){
          echo "<tr align=center>";
          echo "<td><span class=''>";
          $uuid=$U[$i]->user_id;
          $nick=$U[$i]->nick;
          if($nick[0]!="*") {
            echo $rank++;//名次变量
          }
          else 
            echo "*";
          echo "</span></td>"; 
          $usolved=$U[$i]->solved;
          if($uuid==$_GET['user_id']) echo "<td>";
          else echo"<td>";
          echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
          echo "<td><a href=userinfo.php?user=$uuid>".$U[$i]->nick."</a>";
          echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
          echo "<td>".sec2str($U[$i]->time);
          for ($j=0;$j<$pid_cnt;$j++){
            //$bg_color="eeeeee";
            $color = "";
            if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
              $bg_color="am-success";
              if($uuid==$first_blood[$j]){
                $bg_color="am-primary";
              }               
            }else if(isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
              if ($U[$i]->p_wa_num[$j] < 5) $bg_color="am-danger";
              else if ($U[$i]->p_wa_num[$j] < 10) $color = "#FF8888";
              else if ($U[$i]->p_wa_num[$j] < 15) $color = "#FF6666";
              else if ($U[$i]->p_wa_num[$j] < 20) $color = "#FF3333";
              else $color = "#FF0000";
            }
            if ($color != "") echo "<td style='background:$color; '>";
            else echo "<td class=$bg_color>";
            if(isset($U[$i])){
              if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                echo sec2str($U[$i]->p_ac_sec[$j]);
              if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
                echo "(-".$U[$i]->p_wa_num[$j].")";
            }
            $bg_color="";
            echo "</td>";
          }
          echo "</tr>";
        }       
      ?>
    </tbody>
  </table>
  <!-- 排名表格 start -->
</div>
<script>
  function getTotal(rows){
    var total=0;
   // alert(rows.length);
    for(var i=0;i<rows.length&&total==0;i++){
      try{
        //alert(rows[rows.length-i].cells[0].children[0].innerHTML);
         total=parseInt(rows[rows.length-i].cells[0].children[0].innerHTML);
          if(isNaN(total)) total=0;
          //alert(total);
      }catch(e){
      
      }
    }
    return total;
  
  }

  // 设置奖牌
  function metal(){
    var tb=window.document.getElementById('rank');
    var rows=tb.rows;
    var goldRate = <?php echo $GOLD_RATE; ?>;
    var silverRate = <?php echo $SILVER_RATE; ?>;
    var bronzeRate = <?php echo $BRONZE_RATE; ?>;
    try {
      var total=getTotal(rows);
      //alert(total);
      for(var i=1;i<rows.length;i++){
        var cell=rows[i].cells[0].children[0];
        var acc=rows[i].cells[3];
        var ac=parseInt(acc.innerText);
        if (isNaN(ac)) ac=parseInt(acc.textContent);
        if(cell.innerHTML!="*"&&ac>0){
          var r = parseInt(cell.innerHTML);
          if(r == 1) { // 冠军
            cell.innerHTML = "&nbsp;Winner";
            cell.style.cssText="background-color:#ce0000;";
            cell.className="am-badge am-round am-icon-trophy";
          }
          var tmp = 1; // 只有第一名被占用
          // if (total*goldRate-1 > 3) { // 金牌数大于3时启动
          //   if (r == 2) {
          //     cell.innerHTML = "&nbsp;2nd";
          //     cell.className="am-btn am-btn-primary am-round am-btn-sm am-icon-angellist";
          //   }
          //   if (r == 3) {
          //     cell.innerHTML = "&nbsp;3rd";
          //     cell.className="am-btn am-btn-warning am-round am-btn-sm am-icon-angellist";
          //   }
          //   tmp = 3; // 前三名都被占用
          // }
          if(r>tmp && r<=total*goldRate+1) { // 金牌
            cell.style.cssText="background-color:#f8c100;";
            cell.className="am-badge am-round";
          }
          if(r>total*goldRate+1 && r<=total*silverRate+1) { // 银牌
            cell.style.cssText="background-color:#a4a4a4;";
            cell.className="am-badge am-round";
          }
          if(r>total*silverRate+1 && r<=total*bronzeRate+1) { // 铜牌
            cell.style.cssText="background-color:#815d18;";
            cell.className="am-badge am-round";
          }
          if(r>total*bronzeRate+1 && ac>0) { // 铁牌
            cell.style.cssText="background-color:transparent;color:black;";
            cell.className="am-badge am-round";
          }
        }
      }
    } catch(e) {
       //alert(e);
    }
  }
  metal();
</script>
<?php include "footer.php" ?>