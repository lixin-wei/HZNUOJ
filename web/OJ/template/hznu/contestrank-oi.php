<?php $title = "OI ".$MSG_RANKLIST;?>
<?php include "contest_header.php" ?>

<?php // 根据当前是否需要滚屏给予show不同的style
  if ($_GET['scroll']) $showStyle = "margin-top:10px;height:1000px;overflow-y:scroll;overflow-x:scroll;";
  else $showStyle = "margin-top:10px;";
?>
<div id='container' class="am-container">
<div style='<?php echo $showStyle?>' class='am-g' id='show'>

  <!-- 工具栏 start -->
  <div class='am-text-center'>
    <?php 
      if(HAS_PRI("download_ranklist")) echo "[ <a href='contestrank-oi.php?cid=".$cid."&download_ranklist'>".$MSG_DOWNLOAD_RANK."</a> ]&nbsp;";
      // if (!$_GET['scroll'])
      //   echo "[ <a href='contestrank.php?scroll=true&cid=".$cid."'>Auto-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
      // else 
      //   echo "[ <a href='contestrank.php?cid=".$cid."'>No-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
     if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
      if(HAS_PRI('see_hidden_user_info')) {
        if ($real_name_mode) {
            echo "[ <a href='contestrank.php?cid=$cid'>$MSG_Normal_Mode</a> ]";
        }
        else echo "[ <a href='contestrank.php?cid=$cid&real_name_mode'>$MSG_RealName_Mode</a> ]";
      }
    ?>
    [ <?php echo $MSG_Class ?>
      <select id="class">
        <option value="" <?php if ($_GET['class']=="") echo "selected"; ?> ><?php echo $MSG_ALL ?></option>
        <option value="null" <?php if ($_GET['class']=="null") echo "selected"; ?> >其它</option>
        <!-- don't remove "其它" option to for loop, if both null and "null" exist, there will occur two options -->
        <?php 
          $sz = count($classSet);
          for ($i=0; $i<$sz; $i++) {
            if ($classSet[$i]==null || $classSet[$i]=="null" || $classSet[$i]=="其它") continue; 
        ?>
            <option value="<?php echo urlencode($classSet[$i]); ?>" <?php if ($_GET['class']==$classSet[$i]) echo "selected"; ?> ><?php echo $classSet[$i]; ?></option>
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
        var cid = <?php echo $cid?>;
        var real_name_mode = <?php echo $real_name_mode?"true":"false" ?>;
        var url = window.location.pathname+"?cid="+cid;
        if(real_name_mode) url += "&real_name_mode";
        url += "&class="+valOption;
        window.location.href = url;
      }
    </script>
    <!-- 选择班级后自动跳转页面的js代码 end -->
    <?php } ?>
  </div>
  <!-- 工具栏 end --> 

  <br />

  <!-- 排名表格 start -->
  <style type="text/css" media="screen">
    .rankcell{
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
      overflow: hidden;
      padding-top: 1px !important;
      padding-bottom: 1px !important;
      padding-left: 10px !important;
      vertical-align: middle !important;
      line-height: 1.4 !important;
      border-left: 1px solid #ddd;
    }
    .has-num:hover{
      cursor: pointer;
    }
    .nick{
      /*max-width: 900px;
      width: 200px;
      min-width: 120px;*/
      overflow: hidden;
      padding: 0px;
      padding-top: 5px;
      vertical-align: middle !important;
      line-height: 1.4 !important;
    }
    th.header:hover{
      cursor: pointer;
    }
    th.header:after{
      content: " \f0dc";
    }
    th.headerSortUp:after{
      content: " \f161";
    }
    th.headerSortDown:after{
      content: " \f160";
    }
</style>
<div class="am-container well am-scrollable-horizontal" style="max-width: 98%;">
  <table class="am-table am-table-hover" id="rank_table" style="white-space: nowrap;">
    <thead>
      <tr>
      <th id="rank" width="5%"><?php echo $MSG_RANK ?></th>
      <?php if($real_name_mode):?>
        <th id="user" width="10%" ><?php echo $MSG_StudentID ?></th>
        <th id="nick" width="10%"><?php echo $MSG_REAL_NAME ?></th>
      <?php else: ?>
        <th id="user" width="10%"><?php echo $MSG_USER ?></th>
        <th id="nick" width="10%"><?php echo $MSG_NICK ?></th>
      <?php endif; ?>
      <th id="solved" width="5%"><?php echo $MSG_SCORE ?></th>
      <th id="score" width="5%"><?php echo $MSG_SOLVED ?></th>
      <th id="penalty" width="5%"><?php echo $MSG_PENALTY ?></th>
      <?php
	   foreach($pid_nums as $num)
          echo "<th class='am-text-right' id='p-cell-$i'><a href='problem.php?cid=$cid&pid=$num[0]' target='_blank'>".PID($num[0])."</a></th><th width='1px'>&nbsp;</th>";
      ?>
      </tr>
    </thead>
    <tbody>
      <?php
        $rank=1;
        $num_gold=$first_prize;
        $num_silver=$second_prize;
        $num_bronze=$third_prize;
        for ($i=0;$i<$user_cnt;$i++){
          echo "<tr>";
          $medal_class="am-badge am-round";
          if ($U[$i]->solved>0){
            if($rank==1){
              $medal_class.=" am-icon-trophy am-badge-warning";
            } else if($rank<=$num_gold){
              $medal_class.=" am-icon-trophy am-badge-warning";
            } else if($rank<=$num_gold+$num_silver){
              $medal_class.=" am-icon-trophy am-badge-primary";
            } else if($rank<=$num_gold+$num_silver+$num_bronze){
              $medal_class.=" am-icon-trophy am-badge-danger";
            }
          }
          echo "<td class='rankcell' style='border-left:0;'>";
          $uuid=htmlentities($U[$i]->user_id);
          $nick=htmlentities($U[$i]->nick);
          if($real_name_mode) {
              $col2=htmlentities($U[$i]->stu_id);
              $col3=htmlentities($U[$i]->class . "-". $U[$i]->real_name);
          }
          else {
              $col2=htmlentities($U[$i]->user_id);
              $col3=htmlentities($U[$i]->nick);
          }

          if(!isset($is_excluded[$uuid])) {
            echo "<span class='$medal_class'> ";
            if($rank==1){
              echo "Winner";
            }
            else echo $rank;//名次变量
            echo "</span>";
            $rank++;
          }
          else 
            echo "*";
          echo "</td>";
          $uscore = $U[$i]->score;
          $usolved=$U[$i]->solved;
        echo "<td class='rankcell'>";
          echo "<a name=\"$uuid\" href=\"userinfo.php?user=$uuid\">$col2</a>\n";


          echo "<td class='rankcell'><div class='nick'>";
          if(isset($is_excluded[$uuid])) echo "<span>*</span>";
          echo $col3."</div></td>\n";
          echo "<td class='rankcell'>$uscore\n";
          echo "<td class='rankcell'><a href=\"status.php?user_id=$uuid&cid=$cid\">$usolved</a>\n";
          echo "<td class='rankcell'>".sec2str($U[$i]->time);
          foreach($pid_nums as $num){
            $bg_color="eeeeee";
            if (isset($U[$i]->p_ac_sec[$num[0]])&&$U[$i]->p_ac_sec[$num[0]]>0){
              if($uuid==$first_blood[$num[0]]) {
                $bg_color="aaaaff";
              } else {
                $aa=0x33+$U[$i]->p_wa_num[$num[0]]*32;
                $aa=$aa>0xaa?0xaa:$aa;
                $aa=dechex($aa);
                $bg_color="$aa"."ff"."$aa";
              }
            } else if(isset($U[$i]->p_wa_num[$num[0]])&&$U[$i]->p_wa_num[$num[0]]>0) {
              $aa=0xaa-$U[$i]->p_wa_num[$num[0]]*10;
              $aa=$aa>16?$aa:16;
              $aa=dechex($aa);
              $bg_color="ff$aa$aa";
            }
            $cell_class="pcell";
            $probelm_lable=PID($num[0]);
            $data_toggle="";
            //echo "<pre>";
            
            //echo "</pre>";
            if($U[$i]->p_wa_num[$num[0]]>0 || $U[$i]->p_ac_sec[$num[0]]>0 || $U[$i]->try_after_lock[$num[0]]>0){
              $cell_class.=" has-num";
              $data_toggle.="data-am-modal=\"{target: '#modal-submission', width:1000}\"";
            }
            echo "<td colspan='2' class='$cell_class' style='background-color:#$bg_color;' id='pcell $uuid $probelm_lable' $data_toggle>";
            if(isset($U[$i])){
              if (isset($U[$i]->p_ac_sec[$num[0]])&&$U[$i]->p_ac_sec[$num[0]]>0)
                echo sec2str($U[$i]->p_ac_sec[$num[0]]);
              else if (isset($U[$i]->p_wa_num[$num[0]])&&$U[$i]->p_wa_num[$num[0]]>0)
                echo "(+"+$U[$i]->p_pass_rate[$num[0]]*$problem_score[$num[0]]+")";
            }
            //echo "<br/>".$U[$i]->p_wa_num[$num[0]]."-".$U[$i]->p_ac_sec[$num[0]]."<br/>";
            echo "</td>\n";
          }
          echo "</tr>\n";
        }       
      ?>
    </tbody>
  </table>
  </div>
  <!-- 排名表格 END -->
   <!-- ajax 显示对应题目提交代码div -->
  <div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-submission">
    <div class="am-modal-dialog">
      <div class="am-modal-hd"><strong><?php echo $MSG_Codes ?></strong>
        <a class="am-close am-close-spin" data-am-modal-close>&times;</a>
      </div>
      <div class="am-modal-bd" id="modal-submission-bd">
        <i class="am-icon-spinner am-icon-pulse"></i> Loading...
      </div>
    </div>
  </div>

</div>
</div>
<?php include "footer.php" ?>
<?php
$sortHeader="";
for($i=0; $i<2*count($pid_nums); $i+=2){
  $sortHeader.=",". (6+$i).":{sorter:false}";
}
?>
<script type="text/javascript" src="plugins/tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript">
  $( document ).ready( function () {
    $( "#rank_table" ).tablesorter({headers:{0:{sorter:false}<?php echo $sortHeader?>}});
  });
</script>

<!-- auto set nick-cell's max-width START-->
<script>
  function change_max_width(){
    var p_cnt=<?php echo $pid_cnt ?>;
    var p_width=$("#p-cell-0").outerWidth();
    var c_width=$("#container").outerWidth();
    var else_width=$("#rank").outerWidth()+$("#user").outerWidth()+$("#solved").outerWidth()+$("#penalty").outerWidth();
    var left=c_width-p_cnt*p_width-else_width;
    left-=100;
    $(".nick").css({'width':left});
  }
  $(document).ready(function(){
    change_max_width();
  });
  $(window).resize(function(){
    change_max_width();
  });
</script>
<!-- auto set nick-cell's max-width END-->

<!-- set submission dialog contents START -->
<script>
  $("td[id^='pcell']").click(function(){
    var id=$(this).attr("id");
    var arg=id.split(' ');
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
        problem_id: pid
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
