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
<div id='container' class="am-container">
<div style=<?php echo $showStyle?> class='am-g' id='show'>
  <h3 align="center">Contest RankList  -- <?php echo $title?></h3>
  <hr />

  <!-- 工具栏 start -->
  <div class='am-text-center'>
    <?php 
      if(HAS_PRI("download_ranklist")) echo "[ <a href='contestrank.xls.php?cid=".$cid."'>".$MSG_DOWNLOAD_RANK."</a> ]&nbsp;&nbsp;&nbsp;";
      // if (!$_GET['scroll'])
      //   echo "[ <a href='contestrank.php?scroll=true&cid=".$cid."'>Auto-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
      // else 
      //   echo "[ <a href='contestrank.php?cid=".$cid."'>No-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
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
  <?php
  $sql="SELECT user_id FROM contest_excluded_user WHERE contest_id=$cid";
  $res=$mysqli->query($sql);
  $is_excluded=array();
  while($uid=$res->fetch_array()[0]){
    $is_excluded[$uid]=true;
  }
  ?>
  <!-- 排名表格 start -->
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
    .pcell{
      white-space: normal;
      overflow: hidden;
      padding: 1px !important;
      vertical-align: middle !important;
      line-height: 1.4 !important;
      border-left: 1px solid #ddd;
    }
    .has-num:hover{
      cursor: pointer;
    }
    .nick{
      /*max-width: 900px;*/
      min-width: 120px;
      height: 30px;
      white-space: nowrap;
      overflow: hidden;
      padding: 0px;
      padding-top: 5px;
      vertical-align: middle !important;
      line-height: 1.4 !important;
    }
    .pcell-ac{
      background: #aefeae;
    }
    .pcell-fb{
      color: white;
      background: #3db03d;
    }
    .pcell-wa{
      background: #ff6b6b;
    }
    .wa-times{
      font-size: 11px;
    }
    .ac-time{
      font-size: 12px;
    }
  </style>
  <table class="am-table" style='font-size:13px;' id="rank_table">
    <thead align="center" style="height: 30px;">
      <td style="width: 1%;" id="rank">Rank</td>
      <td style="width: 1%;" id="user">User</td>
      <td style="width: 90%;" id="nick">Nick</td>
      <td style="width: 1%;" id="solved">Solved</td>
      <td style="width: 1%;" id="penalty">Penalty</td>
      <?php
        for ($i=0;$i<$pid_cnt;$i++)
          echo "<td id='p-cell-$i' style='min-width: 40px;'><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
      ?>
    </thead>
    <tbody>
      <?php
        $rank=1;
        for ($i=0;$i<$user_cnt;$i++){
          echo "<tr align=center>";
          echo "<td class='rankcell' style='border-left:0;'><span class=''>";
          $uuid=$U[$i]->user_id;
          $nick=$U[$i]->nick;
          if(!isset($is_excluded[$uuid])) {
            echo $rank++;//名次变量
          }
          else 
            echo "*";
          echo "</span></td>";

          $usolved=$U[$i]->solved;
        echo "<td class='rankcell'>";
          echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";


          echo "<td class='rankcell'><div class='nick'>";
          if(isset($is_excluded[$uuid])) echo "<span>*</span>";
          echo "<a href=userinfo.php?user=$uuid>".$U[$i]->nick."</a>";
          echo "</div></td>";

          echo "<td class='rankcell'><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";


          echo "<td class='rankcell'>".floor($U[$i]->time/60);
          for ($j=0;$j<$pid_cnt;$j++){
            $cell_class="pcell ";
            if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
              if($uuid==$first_blood[$j]){
                $cell_class.="pcell-fb";
              }
              else{
                $cell_class.="pcell-ac";
              }
            }else if(isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
              $cell_class.="pcell-wa";
            }
            $probelm_lable=chr(ord('A')+$j);
            $data_toggle="";
            if($U[$i]->p_wa_num[$j]>0 || isset($U[$i]->p_ac_sec[$j])){
              $cell_class.=" has-num";
              $data_toggle.="data-am-modal=\"{target: '#modal-submission', width:1000}\"";
            }
            echo "<td class='$cell_class' id='pcell-$uuid-$probelm_lable' $data_toggle>";
            if(isset($U[$i])){
              if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                echo "<span class='ac-time'>".floor($U[$i]->p_ac_sec[$j]/60)."</span><br>";
              if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
                echo "<span class='wa-times'>(-".$U[$i]->p_wa_num[$j].")</span>";
              else
                echo "-";
            }
            echo "</td>";
          }
          echo "</tr>";
        }       
      ?>
    </tbody>
  </table>
  <!-- 排名表格 END -->
  <div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-submission">
    <div class="am-modal-dialog">
      <div class="am-modal-hd">Submissions
        <a class="am-close am-close-spin" data-am-modal-close>&times;</a>
      </div>
      <div class="am-modal-bd" id="modal-submission-bd">
        <i class="am-icon-spinner am-icon-pulse"></i> Loading...
      </div>
    </div>
  </div>

</div>
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
    var tb=window.document.getElementById('rank_table');
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
            cell.className="am-badge am-icon-trophy";
          }
          var tmp = 1; // 只有第一名被占用
          // if (total*goldRate-1 > 3) { // 金牌数大于3时启动
          //   if (r == 2) {
          //     cell.innerHTML = "&nbsp;2nd";
          //     cell.className="am-btn am-btn-primary am-btn-sm am-icon-angellist";
          //   }
          //   if (r == 3) {
          //     cell.innerHTML = "&nbsp;3rd";
          //     cell.className="am-btn am-btn-warning am-btn-sm am-icon-angellist";
          //   }
          //   tmp = 3; // 前三名都被占用
          // }
          if(r>tmp && r<=total*goldRate+1) { // 金牌
            cell.style.cssText="background-color:#f8c100;";
            cell.className="am-badge";
          }
          if(r>total*goldRate+1 && r<=total*silverRate+1) { // 银牌
            cell.style.cssText="background-color:#a4a4a4;";
            cell.className="am-badge";
          }
          if(r>total*silverRate+1 && r<=total*bronzeRate+1) { // 铜牌
            cell.style.cssText="background-color:#815d18;";
            cell.className="am-badge";
          }
          if(r>total*bronzeRate+1 && ac>0) { // 铁牌
            cell.style.cssText="background-color:transparent;color:black;";
            cell.className="am-badge";
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


<!-- auto set nick-cell's max-width -->
<script>
  function change_max_width(){
    var p_cnt=<?php echo $pid_cnt ?>;
    var p_width=$("#p-cell-0").outerWidth();
    var c_width=$("#container").outerWidth();
    var else_width=$("#rank").outerWidth()+$("#user").outerWidth()+$("#solved").outerWidth()+$("#penalty").outerWidth();
    var left=c_width-p_cnt*p_width-else_width;
    left-=20;
    $(".nick").css({'width':left});
  }
  $(document).ready(function(){
    change_max_width();
  });
  $(window).resize(function(){
    change_max_width();
  });
</script>

<!-- set submission dialog contents START -->
<script>
  $("td[id^='pcell']").click(function(){
    var id=$(this).attr("id");
    var arg=id.split('-');
    var uid=arg[1];
    var pid=arg[2];
    var cid=<?php echo $cid; ?>;
    //set loading icon
    $("#modal-submission-bd").html("<i class='am-icon-spinner am-icon-pulse'></i> Loading...");
    $.ajax({
      type: "GET",
      url: "status.php",
      data: {
        ranklist_ajax_query: 1,
        cid: cid,
        language: -1,
        jresult: -1,
        user_id: uid,
        problem_id: pid,
      },
      context: this,
      success: function(data){
        $("#modal-submission-bd").html(data);
      },
      complete: function(){
        console.log("ajax complete!");
      },
      error: function(xmlrqst,info){
        console.log(info);
      }
    });
  });
</script>
<!-- set submission dialog contents END -->