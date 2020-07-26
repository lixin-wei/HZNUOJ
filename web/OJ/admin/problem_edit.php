<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.27
 **/
?>


<?php
require_once "../include/db_info.inc.php";
require_once '../include/my_func.inc.php';
require_once "admin-header.php";
require_once "kindeditor.php";
require_once "../include/problem.php";
?>
<?php
$add_problem_mod=false;
if(isset($_GET['new_problem'])){
    $add_problem_mod=true;
}
function getProblemInfo($pid){
  global $mysqli, $row, $samples;
  $sql="SELECT * FROM `problem` WHERE `problem_id`=$pid";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  $sql="SELECT `input`, `output`, `show_after` FROM `problem_samples` WHERE `problem_id`='$pid' ORDER BY `sample_id`";
  $res=$mysqli->query($sql);
  while($r=$res->fetch_array()){
      array_push($samples, array(
          "input" => $r['input'],
          "output" => $r['output'],
          "show_after" => $r['show_after'],
      ));
  }
}
if(!$add_problem_mod){
    if(isset($_GET['id'])) {
        $pid=intval($_GET['id']);
        if (!HAS_PRI("edit_".get_problemset($pid)."_problem")) {
          require_once("error.php");
          exit(0);
        }
        $row="";
        $samples=array();
        getProblemInfo($pid);
    }
}

if(isset($_GET['copy_problem'])){//复制题目
    require_once("../include/check_get_key.php");
    $problemset=$row->problemset;
    $title=$row->title." copy";
    $time_limit=$row->time_limit;
    $memory_limit=$row->memory_limit;
    $description=$row->description;
    $input=$row->input;
    $output=$row->output;
    $hint=$row->hint;
    $author=$row->author;
    $source=$row->source;
    $spj=$row->spj;
    $id=addproblem($problemset, $title, $time_limit, $memory_limit, $description, $input, $output, $hint, $author, $source, $spj, $OJ_DATA );
    mkdir($OJ_DATA."/$id");
    foreach ($samples as $key => $sample) {
      $sample_input=$sample['input'];
      $sample_output=$sample['output'];
      $sample_show_after=$sample['show_after'];
      //don't auto generate sample files if is SPJ
      if(!$spj) {
          mkdata($id, "sample{$key}.in", $sample_input, $OJ_DATA);
          mkdata($id, "sample{$key}.out", $sample_output, $OJ_DATA);
      }
      $sql="INSERT INTO `problem_samples` (`problem_id`, `sample_id`, `input`, `output`, `show_after`)
        VALUES ($id, $key, '$sample_input', '$sample_output', '$sample_show_after')";
      //echo "<pre>$sql</pre>";
      $mysqli->query($sql);
    }
    // 复制测试数据start
    $src = $OJ_DATA."/$pid";
    $dst = $OJ_DATA."/$id";
    $files = scandir($src);
    foreach ($files as $file) {
      if ($file != "." && $file != ".." && !is_dir($src."/$file")){
        if (file_exists($src."/$file")) copy($src."/$file", $dst."/$file");
      }
    }
    // 复制测试数据end
    echo "&nbsp;".$MSG_SampleDataIsUpdated.$MSG_HELP_MORE_TESTDATA_LATER."<br>";
    $_SESSION["p$id"]=true;
    echo "<a href='../problem.php?id=$id'>$MSG_SeeProblem</a>&nbsp;";
    echo "<a href=quixplorer/index.php?action=list&dir=$id&order=name&srt=yes>$MSG_AddMoreTestData</a>";
    echo "<br><b>可在测试数据文件夹中上传prepen.xx、append.xx等预定义代码，将题目变成代码附加题。</b>";

    $_GET['id']=$id;
    $pid=intval($_GET['id']);
    $row="";
    $samples=array();
    getProblemInfo($pid);
}
?>
<?php if (isset($_GET['id']) || $add_problem_mod): ?>
  <title><?php echo $html_title;
      if($add_problem_mod){
          echo "$MSG_ADD$MSG_PROBLEM";
      }
      else{
          echo "$MSG_EDIT$MSG_PROBLEM:".$pid;
      }
      ?></title>
  <div style="width: 800px">    
        <?php
        if($add_problem_mod){
            echo "<h1>$MSG_ADD$MSG_PROBLEM</h1>";
			echo "<h4>$MSG_HELP_ADD_PROBLEM</h4>";
        }
        else{
            echo<<<HTML
        <h1>$MSG_EDIT$MSG_PROBLEM:<a href="../problem.php?id=$pid">$pid</a></h1>
HTML;
        }
        ?>
    <hr/>
    <form class="form-horizontal" method=POST action="problem_edit.php" id="problem_form">
      <input class="form-control" type=hidden name=problem_id value='<?php if(!$add_problem_mod)echo $row->problem_id?>'>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_TITLE ?></label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=title value="<?php if(!$add_problem_mod)echo htmlentities($row->title,ENT_QUOTES,"UTF-8")?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_PROBLEMSET ?></label>
        <div class="col-sm-10">
          <select class="form-control selectpicker" name="problemset">
              <?php
              $res=$mysqli->query("SELECT * FROM problemset");
              while($row2=$res->fetch_array()){
                  if(HAS_PRI("edit_".$row2['set_name']."_problem")){
                      echo "<option value=".$row2['set_name'];
                      if(isset($row->problemset) && $row2['set_name']==$row->problemset){
                          echo " selected='true'";
                      }
                      echo ">";
                      echo $row2['set_name_show'];
                      echo "</oition>";
                  }
              }
              
              ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_Limit ?></label>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon"><?php echo $MSG_Times ?></div>
            <input class="form-control" type="text" name="time_limit" value=
            <?php
            if(!$add_problem_mod)echo $row->time_limit;
            else echo "1";
            ?>
            >
            <div class="input-group-addon">s</div>
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon"><?php echo $MSG_MEMORY ?></div>
            <input class="form-control" type="text" name="memory_limit" value=
            <?php
            if(!$add_problem_mod)echo $row->memory_limit;
            else echo "128";
            ?>
            >
            <div class="input-group-addon">MB</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_Description ?></label>
        <div class="col-sm-10"><textarea name="description" id="" rows="7" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->description,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_Input ?></label>
        <div class="col-sm-10"><textarea name="input" id="" rows="7" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->input,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_Output ?></label>
        <div class="col-sm-10"><textarea name="output" id="" rows="7" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->output,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_Samples ?></label>
        <div class="col-sm-10">
          <button id="toggle_sample" class="btn btn-default btn-sm btn-block"><?php echo $MSG_ToggleSamples ?></button>
          <div id="samples" style="padding: 10px; border: 1px solid #ccc; margin-top: 10px;">
            <div id="samples_without_button">
                <?php
                if(!$add_problem_mod){
                    foreach ($samples as $key => $sample) {
                        $text_input=htmlentities($sample['input']);
                        $text_output=htmlentities($sample['output']);
                        echo <<<HTML
                <div>
                  <div class="form-inline" style="height: 35px;">
                    <div style="float: left; padding-top: 6px;">
                      $MSG_Sample_Input <sapn class="label label-primary">$key</sapn>
                    </div>
                    <div class="input-group input-group-sm" style="float: right;">
                      <div class="input-group-addon">$MSG_ShowAfterTrying: </div>
                      <input name="show_after[]" class="form-control" style="width:50px; text-align: center;" value="{$sample['show_after']}">
                      <!-- <div class="input-group-addon">times</div> -->
                    </div>
                  </div>
                  <div><textarea name="sample_input[]" class="form-control" id="" rows="4">$text_input</textarea></div>
                  <div style="margin-bottom: 13px; margin-top: 13px;">
                    $MSG_Sample_Output <sapn class="label label-primary">$key</sapn>
                  </div>
                  <div><textarea name="sample_output[]" class="form-control" id="" rows="4">$text_output</textarea></div>
                  <hr/>
                </div>
HTML;
                    }
                } else { //添加题目时，显示两组测试数据的输入框
                        echo <<<HTML
                <div>
                  <div class="form-inline" style="height: 35px;">
                    <div style="float: left; padding-top: 6px;">
                      $MSG_Sample_Input <sapn class="label label-primary">0</sapn>
                    </div>
                    <div class="input-group input-group-sm" style="float: right;">
                      <div class="input-group-addon">$MSG_ShowAfterTrying: </div>
                      <input name="show_after[]" class="form-control" style="width:50px; text-align: center;" value="0">
                      <!-- <div class="input-group-addon">times</div> -->
                    </div>
                  </div>
                  <div><textarea name="sample_input[]" class="form-control" id="" rows="4">$text_input</textarea></div>
                  <div style="margin-bottom: 13px; margin-top: 13px;">
                    $MSG_Sample_Output <sapn class="label label-primary">0</sapn>
                  </div>
                  <div><textarea name="sample_output[]" class="form-control" id="" rows="4">$text_output</textarea></div>
                  <hr/>
                </div>
				<div>
                  <div class="form-inline" style="height: 35px;">
                    <div style="float: left; padding-top: 6px;">
                      $MSG_Sample_Input <sapn class="label label-primary">1</sapn>
                    </div>
                    <div class="input-group input-group-sm" style="float: right;">
                      <div class="input-group-addon">$MSG_ShowAfterTrying: </div>
                      <input name="show_after[]" class="form-control" style="width:50px; text-align: center;" value="0">
                      <!-- <div class="input-group-addon">times</div> -->
                    </div>
                  </div>
                  <div><textarea name="sample_input[]" class="form-control" id="" rows="4">$text_input</textarea></div>
                  <div style="margin-bottom: 13px; margin-top: 13px;">
                    $MSG_Sample_Output <sapn class="label label-primary">1</sapn>
                  </div>
                  <div><textarea name="sample_output[]" class="form-control" id="" rows="4">$text_output</textarea></div>
                  <hr/>
                </div>
HTML;
                }
                ?>
            </div>
            <button id="remove_sample" class="btn btn-danger btn-sm btn-block"><?php echo $MSG_RemoveSample ?></button>
            <button id="add_sample" class="btn btn-primary btn-sm btn-block"><?php echo $MSG_AddSapmple ?></button>
          </div>
        
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $MSG_HINT ?></label>
        <div class="col-sm-10"><textarea name="hint" id="" rows="7" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->hint,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Special judge</label>
        <div class="col-sm-10">
          <label class="radio-inline"><input type="radio" name="spj" value="0"
                  <?php
                  if(!$add_problem_mod)echo $row->spj=="0"?"checked":"";
                  else echo "checked";
                  ?>
            >N</label>
          <label class="radio-inline"><input type="radio" name="spj" value="1" <?php if(!$add_problem_mod)echo $row->spj=="1"?"checked":""?>>Y</label>
        </div>
      </div>
      <hr/>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_AUTHOR ?></label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=author placeholder="<?php echo $add_problem_mod ? "留空则自动填入您的用户名":"" ?>" value="<?php if(!$add_problem_mod)echo htmlentities($row->author,ENT_QUOTES,"UTF-8")?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_Source ?></label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=source placeholder="多个关键字请以空格分隔" value="<?php if(!$add_problem_mod)echo htmlentities($row->source,ENT_QUOTES,"UTF-8")?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo $MSG_CONTEST  ?></label>
        <div class="col-sm-10">
          <select class="form-control selectpicker" type=text name=contest_id>
              <?php
              $sql="SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
              $result=$mysqli->query($sql);
              echo "<option value=''>none</option>";
              if ($result->num_rows!=0) {
                  while ($row=$result->fetch_object())
                      echo "<option value='$row->contest_id'>$row->contest_id $row->title</option>";
              }
              ?>
          </select>
        </div>
      </div>
        <?php if ($add_problem_mod): ?>
          <input type="hidden" name="add_problem_mod">
        <?php endif ?>
        <?php require_once("../include/set_post_key.php");?>
      <div align=center>
        <button type=submit value=submit class="btn btn-default" style="width:100px;"><?php echo $MSG_SUBMIT ?></button>
      </div>
    </form>
  </div>
<?php endif ?>
<?php
if(isset($_POST['problem_id'])){ //写入数据库
	if(isset($_POST['add_problem_mod'])){
        echo "<title>$MSG_ADD$MSG_PROBLEM</title>";
    }else{
        echo "<title>$MSG_EDIT$MSG_PROBLEM</title>";
    }
    require_once("../include/check_post_key.php");
    $id=intval($_POST['problem_id']);
    $title=$mysqli->real_escape_string(trim($_POST['title']));
    $problemset=$mysqli->real_escape_string(trim($_POST['problemset']));
    $time_limit=$mysqli->real_escape_string(trim($_POST['time_limit']));
    $memory_limit=$mysqli->real_escape_string(trim($_POST['memory_limit']));
    $description=$mysqli->real_escape_string(trim($_POST['description']));
    $input=$mysqli->real_escape_string(trim($_POST['input']));
    $output=$mysqli->real_escape_string(trim($_POST['output']));
    $description=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$_POST['description']));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $input=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$_POST['input']));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $output=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$_POST['output']));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $sample_inputs=$_POST['sample_input'];
    $sample_outputs=$_POST['sample_output'];
    $sample_show_after=$_POST['show_after'];
    // var_dump($sample_inputs);
    // var_dump($sample_outputs);
    $hint=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$_POST['hint']));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $source=str_replace("\r\n","",$_POST['source']);
    $source=str_replace("\r","",$source);
    $source=$mysqli->real_escape_string(trim($source));
    $source=array_unique(explode(" ",$source));//关键字去重
    sortByPinYin($source);//关键字按拼音字母排序
    $source=implode(' ', $source);
    $spj=$mysqli->real_escape_string($_POST['spj']);
    $author=str_replace("\r\n","",$_POST['author']);
    $author=str_replace("\r","",$author);
    $author=str_replace("\t","",$author);
    $author=str_replace(" ","",$author);
    $author = $mysqli->real_escape_string($author);
    //火狐浏览器73.0.1版本中kindeditor会在textarea内容的末尾加入<!---->
    $description = str_replace("<!---->","",$description);
    $input = str_replace("<!---->","",$input);
    $output = str_replace("<!---->","",$output);
    $hint = str_replace("<!---->","",$hint);

	  if($author == "" && isset($_POST['add_problem_mod'])) $author = $_SESSION['user_id']; 

    //remove original samples
    //----remove original sample file from hustoj, start
    $path=$OJ_DATA."/$id/sample.in";
    if(file_exists($path)) $success=unlink($path);
    $path=$OJ_DATA."/$id/sample.out";
    if(file_exists($path)) $success=unlink($path);
    //----remove original sample file from hustoj, end
    $sql="SELECT COUNT(1) FROM problem_samples WHERE problem_id=$id";
    $original_sample_cnt=$mysqli->query($sql)->fetch_array()[0];
    for($i=0 ; $i<$original_sample_cnt ; ++$i){
        $path=$OJ_DATA."/$id/sample".$i.".in";
        if(file_exists($path)) $success=unlink($path);
        //echo "<pre>remove $path:$success</pre>";
        
        $path=$OJ_DATA."/$id/sample".$i.".out";
        if(file_exists($path)) $success=unlink($path);
        //echo "<pre>remove $path:$success</pre>";
    }
    if(isset($_POST['add_problem_mod'])){
        $id=addproblem($problemset, $title, $time_limit, $memory_limit, $description, $input, $output, $hint, $author, $source, $spj, $OJ_DATA );
        mkdir($OJ_DATA."/$id");
    }
    $sql="DELETE FROM problem_samples WHERE problem_id=$id";
    $mysqli->query($sql);
    if($sample_inputs){
        foreach ($sample_inputs as $key => $sample_input) {
            $sample_output=$sample_outputs[$key];
            if($sample_input=="" && $sample_output=="")continue;
            
            //don't auto generate sample files if is SPJ
            if(!$spj) {
                mkdata($id, "sample{$key}.in", $sample_input, $OJ_DATA);
                mkdata($id, "sample{$key}.out", $sample_output, $OJ_DATA);
            }
            $sample_input=$mysqli->real_escape_string($sample_input);
            $sample_output=$mysqli->real_escape_string($sample_output);
            $sql=<<<SQL
          INSERT INTO problem_samples (
            problem_id,
            sample_id,
            input,
            output,
            show_after
          )
          VALUES
            ($id, $key, "$sample_input", "$sample_output", "{$sample_show_after[$key]}")
SQL;
            //echo "<pre>$sql</pre>";
            $mysqli->query($sql);
        }
    }
    echo "&nbsp;".$MSG_SampleDataIsUpdated.$MSG_HELP_MORE_TESTDATA_LATER."<br>";
    if(isset($_POST['add_problem_mod'])){
        $_SESSION["p$id"]=true;
        echo "<a href='../problem.php?id=$id'>$MSG_SeeProblem</a>&nbsp;";
        echo "<a href=quixplorer/index.php?action=list&dir=$id&order=name&srt=yes>$MSG_AddMoreTestData</a>";
    }
    else{
        $sql="UPDATE `problem` set `problemset`='$problemset',`title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
          `description`='$description',`input`='$input',`output`='$output',`hint`='$hint',author='$author',`source`='$source',`spj`=$spj,`in_date`=NOW()
          WHERE `problem_id`=$id";
        @$mysqli->query($sql) or die($mysqli->error);
        echo "$MSG_EditOK";
        //echo $sql;
        echo "<a href='../problem.php?id=$id'>$MSG_SeeProblem</a>&nbsp;";
        echo "<a href=quixplorer/index.php?action=list&dir=$id&order=name&srt=yes>$MSG_AddMoreTestData</a>";
    }
}
?>
<?php require_once("admin-footer.php"); ?>

<!-- samples edit scripts START-->
<script>
    var sample_cnt=<?php
        if($add_problem_mod) echo "2";
        else echo count($samples);
        ?>;
    $("#add_sample").on("click",function(event){
        sample_cnt++;
        var html="";
        html+="\
              <div>\
                <div class='form-inline' style='height: 35px;'>\
                  <div style='float: left; padding-top: 6px;'>\
                    <?php echo $MSG_Sample_Input ?> <sapn class='label label-primary'>"+(sample_cnt-1)+"</sapn>\
                  </div>\
                  <div class='input-group input-group-sm' style='float: right;'>\
                    <div class='input-group-addon'><?php echo $MSG_ShowAfterTrying?> : </div>\
                    <input name='show_after[]' class='form-control' style='width:50px; text-align: center;' value='0'>\
                    <!--<div class='input-group-addon'>times</div>-->\
                  </div>\
                </div>\
                <div><textarea name='sample_input[]' class='form-control' id='' rows='4'></textarea></div>\
                <div style='margin-bottom: 13px; margin-top: 13px;'>\
                  <?php echo $MSG_Sample_Output ?> <sapn class='label label-primary'>"+(sample_cnt-1)+"</sapn>\
                </div>\
                <div><textarea name='sample_output[]' class='form-control' id='' rows='4'></textarea></div>\
                <hr/>\
              </div>"
        $("#samples_without_button").append(html);
        event.preventDefault();
    });
    $samples=$("#samples");
    $("#toggle_sample").on("click",function(event){
        $samples.toggle(200);
        event.preventDefault();
    });
    $("#remove_sample").on("click",function(event){
        event.preventDefault();
        if(sample_cnt<=0)return;
        $("#samples_without_button>div:last").remove();
        sample_cnt--;
    })
</script>
<!-- samples edit scripts END-->
