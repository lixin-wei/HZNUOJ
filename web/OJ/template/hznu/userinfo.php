<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.04.27
  **/
?>

<?php $title=$MSG_USERINFO;?>
<?php
require_once("header.php");
?>
<style type="text/css">
  .first-col{
    width: 120px;
  }
</style>
<div class="am-container" style="margin-top:20px;">
  <!-- userinfo上半部分 start -->
  <h1><?php echo $MSG_USERINFO.$MSG_STATISTICS ?></h1><hr>
  <div class='am-g'>
    <!-- 左侧个人信息表格 start -->`
    <div class='am-u-md-4'>
    <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ ?>
      <form action="modify.php" method="post">
    <?php } ?>  
        <table class="am-table am-table-striped am-table-compact">
          <tbody>
            <tr><th class="first-col am-text-right"><?php echo $MSG_USER_ID ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($user).$defunct?></td></tr>
            <tr><th class="first-col am-text-right"><?php echo $MSG_NICK ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($nick)?></td></tr>
            <tr><th class="first-col am-text-right"><?php echo $MSG_RANK ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo $Rank?></td></tr>
            <tr><th class="first-col am-text-right"><?php echo $MSG_STRENGTH ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo round($strength)?></td></tr>
            <tr><th class="first-col am-text-right"><?php echo $MSG_LEVEL ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo $level?></td></tr>
            <tr>
              <th class="first-col am-text-right"><?php echo $MSG_SOLVED ?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <td><a href="status.php?user_id=<?php echo htmlentities($user)?>&jresult=4"><?php echo $local_ac?></a></td>
            </tr>
            <tr>
              <th class="first-col am-text-right"><?php echo $MSG_SUBMIT?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <td><a href='status.php?user_id=<?php echo htmlentities($user)?>'><?php echo $Submit?></a></td>
            </tr>
            <?php
              foreach($view_userstat as $row){
                echo "<tr><th class='first-col am-text-right'>".$jresult[$row[0]]."&nbsp;&nbsp;&nbsp;&nbsp;</th>\n";
                echo "<td><a href=status.php?user_id=".htmlentities($user)."&jresult=".$row[0]." >".$row[1]."</a></td></tr>\n";
                }
            ?>
            <tr><th class="first-col am-text-right"><?php echo $MSG_SCHOOL ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($school)?></td></tr>
            <tr><th class="first-col am-text-right"><?php echo $MSG_EMAIL ?>&nbsp;&nbsp;&nbsp;&nbsp;</th><td><a href="mailto:<?php echo htmlentities($email); ?>"><?php echo htmlentities($email)?></a></td></tr>
            <?php if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){ 
              if (HAS_PRI("edit_user_profile")){
                  require_once('./include/set_post_key.php');?>
              <input type="hidden" name="admin_mode" value="1">
              <input type="hidden" name="user_id" value="<?php echo htmlentities($user)?>">
              <tr><td colspan="2" class="am-danger  am-text-center">----The followings are  admin only----</td></tr>
              <tr>
                <th class="first-col am-text-right" style="padding-top: 10px;"><?php echo $MSG_StudentID ?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <td>
                  <input class="am-form-field" name="stu_id" type="text" value="<?php echo htmlentities($stu_id)?>">
                </td>
              </tr>
              <tr>
                <th class="first-col am-text-right" style="padding-top: 10px;"><?php echo $MSG_REAL_NAME ?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <td>
                  <input class="am-form-field" name="real_name" type="text" value="<?php echo htmlentities($real_name)?>">
                </td>
              </tr>
              <tr>
                <th class="first-col am-text-right" style="padding-top: 10px;"><?php echo $MSG_Class ?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <td>
                  <select name="class" data-am-selected="{searchBox: 1, maxHeight: 400, btnWidth:'100%'}">
                    <?php 
                      foreach ($classList as $c){
                          if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
                          foreach ($c[1] as $cl){
                            if($cl == $class) $selected = "selected"; else $selected ="";
                            echo "<option value='$cl' $selected>$cl</option>\n";
                          }
                          echo "</optgroup>\n";
                      }
                    ?>
                  </select>
                </td>
              </tr>
            <?php  }elseif (HAS_PRI("see_hidden_user_info")){ ?>
              <tr><td colspan="2" class="am-danger  am-text-center">----The followings are  admin only----</td></tr>
              <tr><th class="first-col am-text-right">Student ID&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($stu_id)?></td></tr>
              <tr><th class="first-col am-text-right">Real Name&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($real_name)?></td></tr>
              <tr><th class="first-col am-text-right">Class&nbsp;&nbsp;&nbsp;&nbsp;</th><td><?php echo htmlentities($class)?></td></tr>
            <?php } }?>
          </tbody>
        </table>
        <?php 
  if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
  if (HAS_PRI("edit_user_profile")){ ?>
          <div class="am-text-center">
            <button class="am-btn am-btn-secondary"><?php echo $MSG_SUBMIT ?></button>
          </div>
        <?php } ?>
      </form>
      <?php }?>
    </div>
    <!-- 左侧个人信息表格 end -->
     
    <!-- 个人图表信息 start -->
    <div class='am-u-md-8'>
      <div class='am-g'>
        <div class="am-u-md-6" >
          <div id="chart-sub" style="height: 327px; width: 100%;"></div>
        </div>
        <div class='am-u-md-6'>
          <!-- <label>用户评价</label><br> -->
          <!-- <a href="charts/show_fore.php?user=<?php echo $_GET['user']?>">用于教学的过程性评价详情请点这里</a> -->
          <div id='chart' style='height:327px;width:100%'></div>
        </div>
      </div>
      <div class='am-g'>
        <div class="am-u-md-12" >
          <div id="chart-sub2" style="height: 327px; width: 100%;"></div>
        </div>
      </div>
    </div>
    <!-- 个人图表信息 end -->
  </div>
  <!-- userinfo上半部分 end -->
  <hr />
  <div class="am-g">
    <div class="am-u-md-12">
      <!-- userinfo下半部分 start -->
      <?php //if ($AC): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd"> <strong><?php echo $OJ_NAME."($AC)" ?>:</strong></div>
          <div class="am-panel-bd">
          <?php
            echo "<b>$MSG_SOLVED:</b><br/>";
            $sql="SELECT set_name,set_name_show FROM problemset";
            $res=$mysqli->query($sql);
            echo "<div style='margin-left: 10px;'>";
            while($row=$res->fetch_array()){
              $set_name=$row['set_name'];
              $set_name_show=$row['set_name_show'];
              $cnt=count($ac_set[$set_name]);
              if($cnt){
                echo "$set_name_show($cnt):<br/>";

                echo "<div style='margin-left: 20px;'>";
                foreach ($ac_set[$set_name] as $pid) {
                  echo "<a href=problem.php?id=$pid> $pid </a>&nbsp;";
                }
                echo "</div>";
              }
            }
            echo "</div>";
            echo "<br />";
            echo "<div><b>$MSG_Tried:</b></div>";
            foreach($hznu_unsolved_set as $i) {
              if ($i != 0) echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
            }
            echo "<br /><br />";

            //solution video START
            $sql="SELECT DISTINCT video_id FROM solution_video_watch_log WHERE user_id='$user'";
            $res=$mysqli->query($sql);
            $solution_video_set=array();
            while($id=$res->fetch_array()[0]){
              array_push($solution_video_set,$id);
            }
            if(count($solution_video_set)){
              echo "<div><b>Solution Video Watched:</b></div>";
              foreach ($solution_video_set as $id) {
                echo "<a href=problem.php?id=$id> $id </a>";
              }
              echo "<br /><br />";
            }
            //solution video END
            if(count($hznu_recommend_set)){
              echo "<div><b>$MSG_Recommended:</b></div>";
              foreach($hznu_recommend_set as $i) {
                echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
              }
            }
          ?>
          </div>
        </div>
      <?php //endif ?>
      <?php if ($CF): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">CodeForces <?php echo "($CF)" ?>:</div>
          <div class="am-panel-bd">
          <?php
            sort($cf_solved_set);
            foreach ($cf_solved_set as $i) {
              echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$cf_vj_id[$i]."'>".$i." </a>&nbsp";
            }
          ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($HDU): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">HDUOJ <?php echo "($HDU)" ?>:</div>
          <div class="am-panel-bd">
          <?php
            sort($hdu_solved_set);
            foreach ($hdu_solved_set as $i) {
              echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$hdu_vj_id[$i]."'>".$i." </a>&nbsp";
            }
          ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($PKU): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">POJ <?php echo "($PKU)" ?>:</div>
          <div class="am-panel-bd">
          <?php
            sort($pku_solved_set);
            foreach ($pku_solved_set as $i) {
              echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$pku_vj_id[$i]."'>".$i." </a>&nbsp";
            }
          ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($UVA): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">UVAOJ</div>
          <div class="am-panel-bd">
          <?php
            sort($uva_solved_set);
            foreach ($uva_solved_set as $i) {
              echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$uva_vj_id[$i]."'>".$i." </a>&nbsp";
            }
          ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($ZJU): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">ZOJ <?php echo "($ZJU)" ?>:</div>
          <div class="am-panel-bd">
          <?php
            sort($zju_solved_set);
            foreach ($zju_solved_set as $i) {
              echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$zju_vj_id[$i]."'>".$i." </a>&nbsp";
            }
          ?>
          </div>
        </div>
      <?php endif ?>
      <!-- userinfo下半部分 end -->
    </div>
  </div>
</div>
<?php require_once("footer.php") ?>
<!--<script src="charts/echarts.min.js"></script>-->
<script src="plugins/echarts/echarts.min.js"></script>


<?php
$chart_sub_data="";
foreach($view_userstat as $row){
  $chart_sub_data.="{value: $row[1], name: '{$judge_result[$row[0]]}'},";
}
?>
<script type="text/javascript">
var chart = echarts.init(document.getElementById('chart'));
var option = {
  title : {
    text: "总体评价：<?php echo $avg_score?>分",
    x : 'right',
    y : 'bottom',
  }, 
  tooltip : { trigger: 'axis',},
  calculable : true,
  radar : [
    {
      indicator : [
        {text: '题量', max:100},
        {text: '难度', max:100},
        {text: '活跃', max:100},
        {text: '独立', max:100}
      ],
    }
  ],
  series : [
    {
      name: 'User Info',
      type: 'radar',
      tooltip: { trigger: 'item' },
      itemStyle: { normal: { areaStyle: { type: 'default' } } },
      data : [
        {
          value:[ <?php echo $solved_score.",".$dif_score.",".$act_score.",".$idp_score; ?> ],
          name : '<?php echo $user?>'
        }
      ]
    }
  ]
};
chart.setOption(option);

var chart_sub=echarts.init(document.getElementById("chart-sub"));
option = {
    title : {
        text: "<?php echo $MSG_SUBMISSION?>"
    },
    tooltip : {
        trigger: 'item',
        formatter: "{b} : {c} ({d}%)"  
    },
    // color : [
    //   '#5EB95E', '#6b8e23', '#DD514C', '#F37B1D', '#b8860b', 
    //   '#ff69b4', '#ba55d3', '#6495ed', '#ffa500', '#40e0d0', 
    //   '#1e90ff', '#ff6347', '#7b68ee', '#00fa9a', '#ffd700', 
    //   '#ff00ff', '#3cb371', '#87cefa', '#30e0e0', '#32cd32' ,
    // ],
    //color : ['#5EB95E','#DD514C'],
    series : [
        {
            name: 'Submissions in HZNUOJ',
            type: 'pie',
            data:[
                <?php echo $chart_sub_data; ?>
            ],
        }
    ]
};
chart_sub.setOption(option);

var chart_sub2=echarts.init(document.getElementById("chart-sub2"));
option = {
  grid: {
      x: 50,
      x2: 50, y2: 50
  },
  color: ['#3398DB','red'],
  tooltip: {
      trigger: 'axis',
      axisPointer: {
          type: 'shadow'
      }
  },
  legend: {
      show: true,
      data:['<?php echo $MSG_SUBMIT ?>','<?php echo $MSG_Accepted ?>']
  },
  xAxis: {
      type: 'category',
      data: ['<?php echo implode("','",$xAxis_data) ?>']
  },
  yAxis: [
      {
          type : "value",
          name : "<?php echo $MSG_SUBMISSIONS ?>"
      }
  ],
  series: [
      {
          name: '<?php echo $MSG_SUBMIT ?>',
          barWidth : 10,
          type: 'bar',
          stack: 'total',
          data: ['<?php echo implode("','",array_column($chart_data_all, 'total')) ?>']
      },
      {
          name: '<?php echo $MSG_Accepted ?>',
          type: 'line',
          data: ['<?php echo implode("','",array_column($chart_data_all, 'ac')) ?>']
      }
  ]
};
chart_sub2.setOption(option);

$(window).resize(function(){
  chart.resize();
  chart_sub.resize();
  chart_sub2.resize();
});
$(window).ready(function(){
  chart.resize();
  chart_sub.resize();
  chart_sub2.resize();
});
</script>