<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.04.12
  **/
?>

<?php 
  $title=$MSG_PROBLEM.$MSG_STATISTICS;
 require_once "header.php";
?>


<div class="am-container" style="margin-top:10px;">
    <?php
    echo <<<HTML
      <h1>$MSG_CodeArchive: <a href="./problem.php?id=$pid">$pid</a></h1>
      <hr/>
HTML;
  if(isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE) {
    ?>
  <div style="color: grey;">
    <h4><?php echo $MSG_HELP_PROBLEM_STATISTICS ?></h4>
  </div>
  <?php } ?>
  <div style="padding: 15px;">
    <div style="width: 350px; float: left;" class="am-text-center">
    <div class="am-panel-group">
      <section class="am-panel am-panel-default">
        <header class="am-panel-hd">Solution filter</header>
        <main class="am-panel-bd">
          
          <form action="" method="GET">
            <div style="height: 40px;" class="am-vertical-align">
              <label for="language" class="am-vertical-align-middle"><?php echo $MSG_LANG ?>:</label>
              <div style="float: right;">
                <select name="language" id="language" data-am-selected="{maxHeight:300}">
                  <option value="-1">All</option>
                <?php
                $count_lang=count($language_name);
                for($i=0 ; $i<$count_lang ; ++$i) {
                    $j = $language_order[$i];
                    $sel = "";
                    if($j==$language) $sel="selected";
                    if($OJ_LANGMASK & (1<<$j))
                    echo <<<HTML
                      <option value="$j" $sel>{$language_name[$j]}</option>
HTML;
                }
                ?>
                </select>
              </div>
            </div>
            
            <div style="height: 40px;" class="am-vertical-align">
              <label for="result" class="am-vertical-align-middle"><?php echo $MSG_RESULT ?>:</label>
              <div style="float: right;">
                <select name="result" id="result" data-am-selected>
                <?php
                for($i=4 ; $i<=11 ; ++$i){
                    $sel = "";
                    if($i==$_GET['result']) $sel="selected";
                    echo <<<HTML
                      <option value="$i" $sel>{$judge_result[$i]}</option>
HTML;
                }
                ?>
                </select>
              </div>
            </div>
  
            <div style="height: 40px;" class="am-vertical-align">
              <label for="order" class="am-vertical-align-middle"><?php echo $MSG_Order_by ?>:</label>
              <div style="float: right;">
                <select name="order" id="order" data-am-selected>
                  <option value="length" <?php if($_GET['order']=="length") echo "selected";?>><?php echo $MSG_CODE_LENGTH ?></option>
                  <option value="time" <?php if($_GET['order']=="time") echo "selected";?>><?php echo $MSG_TIME ?></option>
                  <option value="memory" <?php if($_GET['order']=="memory") echo "selected";?>><?php echo $MSG_MEMORY ?></option>
                  <option value="date" <?php if($_GET['order']=="date") echo "selected";?>><?php echo $MSG_SUBMIT_TIME ?></option>
                </select>
              </div>
            </div>
            <hr/>
              <?php
              echo "<input type=\"hidden\"  name=\"id\" value=\"$pid\">";
              ?>
            <button class="am-btn am-btn-primary am-btn-sm"><?php echo $MSG_FILTER ?></button>
          </form>
        </main>
      </section>
      
      <section class="am-panel am-panel-default" >
        <header class="am-panel-hd"><?php echo $MSG_SUBMISSIONS.$MSG_STATISTICS ?></header>
        <main class="am-panel-bd">
        <div id="statistics_chart" style="width: 100%;height: 200px;"></div>
        <table class="am-table am-text-middle">
          <?php
          foreach ( $view_problem as $row ) {
            echo "<tr>\n";
            echo "<th class='am-text-right'>" . $row[0] . "：</th>\n";
            echo "<td width='40%'  class='am-text-left'>&nbsp;&nbsp;" . $row[1] . "</td>\n";
            echo "</tr>\n";
          }
          ?>
           <tr>      
    </tr>
        </table>
        </main>
      </section>
    </div>
    </div>
    <div style="margin-left: 400px;">
      <table class="am-table am-table-compact am-table-striped am-table-hover">
        <tr>
          <th><?php echo $MSG_RANK ?></th>
          <th><?php echo $MSG_RUNID ?></th>
          <th><?php echo $MSG_USER ?></th>
          <th><?php echo $MSG_RESULT ?></th>
          <th><?php echo $MSG_TIME ?></th>
          <th><?php echo $MSG_MEMORY ?></th>
          <th><?php echo $MSG_CODE_LENGTH ?></th>
          <th><?php echo $MSG_LANG ?></th>
          <th><?php echo $MSG_SUBMIT_TIME ?></th>
        </tr>
          <?php
          foreach ($data as $row) {
            echo "<tr>";
            echo "<td>".++$rank."</td>"; 
            echo "<td>{$row['solution_id']}</td>";
            echo "<td>";
            if(isset($row['is_temp_user'])) {
              echo<<<HTML
              <a>{$row['user_id']}</a><sup title='this is a temporary user in a special contest'><a href="./contest.php?cid={$row['is_temp_user']}" style='color: grey;'>{$row['is_temp_user']}</a></sup>
HTML;
            }
            else {
              echo "<a href=\"./userinfo.php?user={$row['user_id']}\" target='_blank'>{$row['user_id']}</a>";
            }
            echo "</td>";
            echo <<<HTML
              <td>{$judge_result[$row['result']]}</td>
              <td>{$row['time']} ms</td>
              <td>{$row['memory']} KB</td>
HTML;
              echo "<td>{$row['code_length']} B</td>";
            if(canSeeSource($row['solution_id'])) {
                echo <<<HTML
                  <td>
                    <a href="showsource.php?id={$row['solution_id']}&normal_mod" target='_blank'>
                      {$language_name[$row['language']]}
                    </a>
                  </td>
HTML;
            }
            else {
                echo "<td>{$language_name[$row['language']]}</td>";
            }
            echo "<td>{$row['in_date']}</td>";

            echo "</tr>";
          }
          ?>
      </table>
        <?php
        function generate_page_url($page) {
          $url = "./problemstatus.php?";
          foreach ($_GET as $key => $value) {
            $key = htmlentities($key);
            $value = htmlentities($value);
            if($key!='page')
              $url .= "&$key=$value";
          }
          $url .= "&page=$page";
          return $url;
        }
        $top_page_url = generate_page_url(1);
        $pre_page_url = generate_page_url(max($page-1, 1));
        $next_page_url = generate_page_url(min($page+1, $total_page));
        $last_page_url = generate_page_url($total_page);
        echo <<<HTML
          <ul class="am-pagination">
            <li><a href="$top_page_url">Top</a></li>
            <li><a href="$pre_page_url">&laquo; Prev</a></li>
            ($page/$total_page)
            <li><a href="$next_page_url">Next &raquo;</a></li>
            <li><a href="$last_page_url">Last</a></li>
          </ul>
HTML;

        ?>
    </div>
  </div>
</div>
<?php require_once("footer.php")?>

<script type="text/javascript" src="./plugins/echarts/echarts.min.js"></script>
<!-- <script src="//cdn.bootcss.com/echarts/3.2.3/echarts.min.js"></script> -->
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var statistics_chart = echarts.init(document.getElementById('statistics_chart'));
    //statistics_chart.showLoading();
    option = null;
option = {
    tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b} : {c} ({d}%)'
    },
    series: [
        {
            name: '<?php echo $MSG_SUBMISSIONS.$MSG_STATISTICS ?>',
            type: 'pie',
            radius: '65%',
            center: ['50%', '50%'],
            labelLine: {
                smooth: 0.1,
                length: 5,
                length2: 10
            },
            data: [
              <?php 
                foreach ( $view_echart as $row ) {
                   echo " {value:" . $row[1] . ", name: '". $row[0] . "'},\n";
                }
              ?>
            ].sort(function (a, b) { return a.value - b.value; })
        }
    ]
};
if (option && typeof option === "object") {
  statistics_chart.setOption(option, true);
}
</script>

<!--get charts json START-->

<!--get charts json END-->