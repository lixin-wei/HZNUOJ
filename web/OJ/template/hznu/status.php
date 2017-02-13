<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.03.22
  **/
?>

<?php $title="Status";?>
<?php include "header.php" ?>
  <style>
    .am-form-inline > .am-form-group {
      margin-left: 15px;
      margin-right: 15px;
    }
    .am-form-inline {
      margin-bottom: 1.5rem;
    }
  </style>
  <div class="am-container">
    <div class="am-avg-md-1">
      <div class="am-g" style="margin-top: 20px; margin-bottom: 20px;">
        <ul class="am-nav am-nav-tabs">
          <li><a href="/OJ/problemset.php">Problems</a></li>
          <li class="am-active"><a href="/OJ/status.php">Status</a></li>
          <li><a href="/OJ/ranklist.php">Standings</a></li>
        </ul>
      </div>
    </div>
      <!-- 搜索框 start -->
    <div class="am-g">
      <div class="am-u-md-12">
        <form action="status.php" method="get" class="am-form am-form-inline" role="form" style="float: left;">
          <div class="am-form-group"><input type="text" class="am-form-field" placeholder=" &nbsp;Problem ID" name="problem_id" value="<?php echo htmlentities($problem_id)?>"></div>
          <div class="am-form-group">
            <input type="text" class="am-form-field" placeholder=" &nbsp;User ID" name="user_id" value="<?php echo htmlentities($user_id)?>">
              <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
          </div>
          <div class="am-form-group">
            <label for="language">Language:</label>
            <select class="am-round" id="language" name="language" data-am-selected="{btnWidth: '100px'}">
                <?php
                if (isset($_GET['language'])) $language=$_GET['language'];
                else $language=-1;
                if ($language<0||$language>=count($language_name))
                    $language=-1;
                if ($language==-1)
                    echo "<option value='-1' selected>All</option>";
                else
                    echo "<option value='-1'>All</option>";
                $i=0;
                foreach ($language_name as $lang){
                    if ($i==$language)
                        echo "<option value=$i selected>$language_name[$i]</option>";
                    else
                        echo "<option value=$i>$language_name[$i]</option>";
                    $i++;
                }
                ?>
            </select>
            <span class="am-form-caret"></span>
          </div>
          <div class="am-form-group">
            <label for="jresult">Result:</label>
            <select class="am-round" id="jresult" name="jresult" data-am-selected="{btnWidth: '100px'}">
                <?php
                if (isset($_GET['jresult']))
                    $jresult_get=intval($_GET['jresult']);
                else
                    $jresult_get=-1;
                if ($jresult_get>=12||$jresult_get<0)
                    $jresult_get=-1;
                /*if ($jresult_get!=-1){
                   $sql=$sql."AND `result`='".strval($jresult_get)."' ";
                   $str2=$str2."&jresult=".strval($jresult_get);
                }*/
                if ($jresult_get==-1)
                    echo "<option value='-1' selected>All</option>";
                else
                    echo "<option value='-1'>All</option>";
                for ($j=0;$j<12;$j++){
                    $i=($j+4)%12;
                    if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
                    else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>";
                }
                ?>
            </select>
            <span class="am-form-caret"></span>
          </div>
          <button type="submit" class="am-btn am-btn-secondary"><span class='am-icon-filter'></span> Filter</button>
        </form>
        <form action="status.php" method="get" class="am-form am-form-inline" role="form" style="float: left;;">
          <button type="submit" class="am-btn am-btn-default">Reset</button>
        </form>
      </div>
    </div>
      <!-- 搜索框 start -->
    <div class="am-avg-md-1">
      <table class="am-table am-table-hover">
        <thead>
        <tr>
          <th>Run.ID</th>
          <th>User</th>
          <th>Prob.ID</th>
          <th>Result</th>
          <th>Memory</th>
          <th>Time</th>
          <th>Language</th>
          <th>Code Length</th>
          <th>Submit Time</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($view_status as $row){
            echo "<tr>";
            foreach($row as $table_cell){
                echo "<td>";
                echo $table_cell;
                echo "</td>";
            }
            
            echo "</tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
    <div class="am-g am-u-sm-centered am-u-sm-offset-10 am-u-sm-2">
      <ul class="am-pagination">
          <?php echo "<li><a href=\"status.php?".htmlentities($str2)."\">Top</a></li>&nbsp;&nbsp;";
          if (isset($_GET['prevtop']))
              echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".intval($_GET['prevtop'])."\">&laquo; Previous</a></li>&nbsp;&nbsp;";
          else
              echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".($top+20)."\">&laquo; Previous</a></li>&nbsp;&nbsp;";
          echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".$bottom."&prevtop=$top\">Next &raquo;</a></li>";
          ?>
      </ul>
    </div>
  </div>
<?php include "footer.php" ?>