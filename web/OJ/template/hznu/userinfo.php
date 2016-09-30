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

<?php $title="User Info";?>
<?php require_once("header.php") ?>

<div class="am-container" style="margin-top:40px;">
  <!-- userinfo上半部分 start -->
  <div class='am-g'>
    <!-- 左侧个人信息表格 start -->
    <div class='am-u-md-6' align="center">
      <table class="am-table am-table-compact am-text-center"  style="width:400px;">
        <tbody>
          <tr><td class="am-primary">User ID</td><td class="am-warning"><?php echo $user?></td></tr>
          <tr><td class="am-primary">Nick Name</td><td class="am-warning"><?php echo $nick?></td></tr>
          <tr><td class="am-primary">Rank</td><td class="am-warning"><?php echo $Rank?></td></tr>
          <tr><td class="am-primary">Douqi</td><td class="am-warning"><?php echo round($strength)?></td></tr>
          <tr><td class="am-primary">Level</td><td class="am-warning"><?php echo $level?></td></tr>
          <tr>
            <td class="am-primary">Solved</td>
            <td class="am-warning"><a href='status.php?user_id=<?php echo $user?>&jresult=4'><?php echo $total_solved?></a></td>
          </tr>
          <tr>
            <td class="am-primary">HZNU</td>
            <td class="am-warning"><a href='status.php?user_id=<?php echo $user?>&jresult=4'><?php echo $AC?></a></td>
          </tr>
          <tr>
            <td class="am-primary">CodeForces</td>
            <td class="am-warning"><a href='<?php echo $VJ_URL;?>/problem/status.action#un=<?php echo $_GET['user'];?>&OJId=CF&res=1&orderBy=run_id'><?php echo $CF?></a></td>
          </tr>
          <tr>
            <td class="am-primary">HDU</td>
            <td class="am-warning"><a href='<?php echo $VJ_URL;?>/problem/status.action#un=<?php echo $_GET['user'];?>&OJId=HDU&res=1&orderBy=run_id'><?php echo $HDU?></a></td>
          </tr>
          <tr>
            <td class="am-primary">PKU</td>
            <td class="am-warning"><a href='<?php echo $VJ_URL;?>/problem/status.action#un=<?php echo $_GET['user'];?>&OJId=POJ&res=1&orderBy=run_id'><?php echo $PKU?></a></td>
          </tr>
          <tr>
            <td class="am-primary">UVA</td>
            <td class="am-warning"><a href='<?php echo $VJ_URL;?>/problem/status.action#un=<?php echo $_GET['user'];?>&OJId=UVA&res=1&orderBy=run_id'><?php echo $UVA?></a></td>
          </tr>
          <tr>
            <td class="am-primary">ZJU</td>
            <td class="am-warning"><a href='<?php echo $VJ_URL;?>/problem/status.action#un=<?php echo $_GET['user'];?>&OJId=ZOJ&res=1&orderBy=run_id'><?php echo $ZJU?></a></td>
          </tr>
          <tr><td class="am-primary">School</td><td class="am-warning"><?php echo $school?></td></tr>
          <tr><td class="am-primary">Email</td><td class="am-warning"><?php echo $email?></td></tr>
          <?php
            if (HAS_PRI("see_hidden_user_info")) {
          ?>
              <tr><td class="am-danger"></td><td class="am-danger">The follows are  admin only</td></</tr>
              <tr><td class="am-primary">Student ID</td><td class="am-warning"><?php echo $stu_id?></td></tr>
              <tr><td class="am-primary">Real Name</td><td class="am-warning"><?php echo $real_name?></td></tr>
              <tr><td class="am-primary">Class</td><td class="am-warning"><?php echo $class?></td></tr>
          <?php
            }
          ?>
        </tbody>
      </table>
    </div>
    <!-- 左侧个人信息表格 end -->

    <!-- 个人图表信息 start -->
    <div class='am-u-md-6' align="center">
      <label>用户评价</label><br>
      <a href="charts/show_fore.php?user=<?php echo $_GET['user']?>">用于教学的过程性评价详情请点这里</a>
      <!--<script src="charts/echarts.min.js"></script>-->
      <script src="//cdn.bootcss.com/echarts/3.2.3/echarts.min.js"></script>
      <div id='chart' style='height:400px;width:470px'></div>
      <script type="text/javascript">
        var chart = echarts.init(document.getElementById('chart'));
        var option = {
          title : {
            text: "总体评价：<?php echo $avg_score?>分",
            x : 'right',
            y : 'bottom',
          }, 
          tooltip : { trigger: 'axis' },
          calculable : true,
          radar : [
            {
              indicator : [
                {text: '解题量', max:100},
                {text: '解题难度', max:100},
                {text: '活跃度', max:100},
                {text: '独立性', max:100}
              ],
              radius : 130
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
      </script>
    </div>
    <!-- 个人图表信息 end -->
  </div>
  <!-- userinfo上半部分 end -->
  <hr />
  <!-- userinfo下半部分 start -->
  <div>
    <?php
      if ($AC) {
        echo "<h2 class='am-text-center'>HZNU</h2>";
        echo "<div>Solved: </div>";
        sort($hznu_solved_set);
        foreach($hznu_solved_set as $i) {
          echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
        }
        echo "<br /><br />";
        echo "<div>Submitted: </div>";
        foreach($hznu_unsolved_set as $i) {
          if ($i != 0) echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
        }
        echo "<br /><br />";
        echo "<div>Recommend: </div>";
        foreach($hznu_recommend_set as $i) {
          echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
        }
        echo "<hr />";
      }
      if ($CF) {
        echo "<h2 class='am-text-center'>CodeForces</h2>";
        sort($cf_solved_set);
        foreach ($cf_solved_set as $i) {
          echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$cf_vj_id[$i]."'>".$i." </a>&nbsp";
        }
        echo "<hr />";
      }
      if ($HDU) {
        echo "<h2 class='am-text-center'>HDU</h2>";
        sort($hdu_solved_set);
        foreach ($hdu_solved_set as $i) {
          echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$hdu_vj_id[$i]."'>".$i." </a>&nbsp";
        }
        echo "<hr />";
      }
      if ($PKU) {
        echo "<h2 class='am-text-center'>PKU</h2>";
        sort($pku_solved_set);
        foreach ($pku_solved_set as $i) {
          echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$pku_vj_id[$i]."'>".$i." </a>&nbsp";
        }
        echo "<hr />";
      }
      if ($UVA) {
        echo "<h2 class='am-text-center'>UVA</h2>";
        sort($uva_solved_set);
        foreach ($uva_solved_set as $i) {
          echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$uva_vj_id[$i]."'>".$i." </a>&nbsp";
        }
        echo "<hr />";
      }
      if ($ZJU) {
        echo "<h2 class='am-text-center'>ZJU</h2>";
        sort($zju_solved_set);
        foreach ($zju_solved_set as $i) {
          echo "<a href='".$VJ_URL."/problem/viewProblem.action?id=".$zju_vj_id[$i]."'>".$i." </a>&nbsp";
        }
        echo "<hr />";
      }
    ?>
  </div>
  <!-- userinfo下半部分 end -->
</div>
<?php require_once("footer.php") ?>
