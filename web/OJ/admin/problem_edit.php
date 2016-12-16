<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.27
 **/
?>


<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
require_once "admin-header.php";
require_once "kindeditor.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/problem.php";
?>
<?php
$add_problem_mod=false;
if(isset($_GET['new_problem'])){
    $add_problem_mod=true;
}
if(!$add_problem_mod){
    if(isset($_GET['id'])) {
        $pid=intval($_GET['id']);
        $sql="SELECT * FROM `problem` WHERE `problem_id`=$pid";
        $result=$mysqli->query($sql);
        $row=$result->fetch_object();
        if (!HAS_PRI("edit_".$row->problemset."_problem")) {
            require_once("error.php");
            exit(0);
        }
        
        $sql="SELECT input, output, show_after FROM problem_samples WHERE problem_id='$pid' ORDER BY sample_id";
        $res=$mysqli->query($sql);
        $samples=array();
        while($r=$res->fetch_array()){
            array_push($samples, array(
                "input" => $r['input'],
                "output" => $r['output'],
                "show_after" => $r['show_after'],
            ));
        }
    }
}
?>
<?php if (isset($_GET['id']) || $add_problem_mod): ?>
  <title><?php
      if($add_problem_mod){
          echo "Add New Problem";
      }
      else{
          echo "Edit Problem:".$pid;
      }
      ?></title>
  <div class="container" style="width: 800px">
    <h1>
        <?php
        if($add_problem_mod){
            echo "Add New Problem";
        }
        else{
            echo<<<HTML
        Edit problem:<a href="/OJ/problem.php?id=$pid">$pid</a>
HTML;
        }
        ?></h1>
    <hr/>
    <form class="form-horizontal" method=POST action="problem_edit.php" id="problem_form">
      <input class="form-control" type=hidden name=problem_id value='<?php if(!$add_problem_mod)echo $row->problem_id?>'>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=title value="<?php if(!$add_problem_mod)echo htmlentities($row->title,ENT_QUOTES,"UTF-8")?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Problemset</label>
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
        <label for="" class="col-sm-2 control-label">Limit</label>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">Time</div>
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
            <div class="input-group-addon">Memory</div>
            <input class="form-control" type="text" name="memory_limit" value=
            <?php
            if(!$add_problem_mod)echo $row->memory_limit;
            else echo "256";
            ?>
            >
            <div class="input-group-addon">MB</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10"><textarea name="description" id="" rows="13" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->description,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Input</label>
        <div class="col-sm-10"><textarea name="input" id="" rows="13" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->input,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Output</label>
        <div class="col-sm-10"><textarea name="output" id="" rows="13" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->output,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Samples</label>
        <div class="col-sm-10">
          <button id="toggle_sample" class="btn btn-default btn-sm btn-block">Toggle samples</button>
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
                      Input <sapn class="label label-primary">$key</sapn>
                    </div>
                    <div class="input-group input-group-sm" style="float: right;">
                      <div class="input-group-addon">Show after trying: </div>
                      <input name="show_after[]" class="form-control" style="width:50px; text-align: center;" value="{$sample['show_after']}">
                      <div class="input-group-addon">times</div>
                    </div>
                  </div>
                  <div><textarea name="sample_input[]" class="form-control" id="" rows="4">$text_input</textarea></div>
                  <div style="margin-bottom: 13px; margin-top: 13px;">
                    Output <sapn class="label label-primary">$key</sapn>
                  </div>
                  <div><textarea name="sample_output[]" class="form-control" id="" rows="4">$text_output</textarea></div>
                  <hr/>
                </div>
HTML;
                    }
                }
                ?>
            </div>
            <button id="remove_sample" class="btn btn-danger btn-sm btn-block">Remove the last sample</button>
            <button id="add_sample" class="btn btn-primary btn-sm btn-block">Add a sapmple</button>
          </div>
        
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Hint</label>
        <div class="col-sm-10"><textarea name="hint" id="" rows="13" class="kindeditor"><?php if(!$add_problem_mod)echo htmlentities($row->hint,ENT_QUOTES,"UTF-8")?></textarea></div>
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
        <label for="" class="col-sm-2 control-label">Author</label>
          <?php
          if(!$add_problem_mod){
              $author = $row->author;
              if ($author != "") {
                  $xing = strstr($author, ", ", true);
                  $ming = strstr($author, ", ");
                  $ming = substr($ming, 2);
                  if ($xing=="" && $ming=="") {
                      if ($author[strlen($author)-1] >='A' && $author[strlen($author)-1] <='Z') {
                          $xing = $author;
                      } else {
                          $ming = $author;
                      }
                  }
              }
          }
          ?>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">Last name(XING)</div>
            <input class="form-control" type="text" name="xing" value="<?php if(!$add_problem_mod)echo htmlspecialchars($xing)?>">
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon">First name(MING)</div>
            <input class="form-control" type="text" name="ming" value="<?php if(!$add_problem_mod)echo htmlspecialchars($ming)?>">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Source</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=source value="<?php if(!$add_problem_mod)echo htmlentities($row->source,ENT_QUOTES,"UTF-8")?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">contest</label>
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
        <button type=submit value=submit class="btn btn-default">Submit</button>
      </div>
    </form>
  </div>
<?php endif ?>
<?php
if(isset($_POST['problem_id'])){
    require_once("../include/check_post_key.php");
    $id=intval($_POST['problem_id']);
    $title=$_POST['title'];
    $problemset=$_POST['problemset'];
    $time_limit=$_POST['time_limit'];
    $memory_limit=$_POST['memory_limit'];
    $description=$_POST['description'];
    $input=$_POST['input'];
    $output=$_POST['output'];
    $sample_inputs=$_POST['sample_input'];
    $sample_outputs=$_POST['sample_output'];
    $sample_show_after=$_POST['show_after'];
    // var_dump($sample_inputs);
    // var_dump($sample_outputs);
    $hint=$_POST['hint'];
    $xing_tmp = $_POST['xing'];
    $ming_tmp = $_POST['ming'];
    $xing = $ming = "";
    $strlen = strlen($xing_tmp);
    for ($i=0; $i<$strlen; ++$i) {
        if ($xing_tmp[$i]==' ' || $xing_tmp[$i]=='\n' || $xing_tmp[$i]=='\t' || $xing_tmp[$i]=='\r') continue;
        $xing .= $xing_tmp[$i];
    }
    $xing = strtoupper($xing);
    $strlen = strlen($ming_tmp);
    for ($i=0; $i<$strlen; ++$i) {
        if ($ming_tmp[$i]==' ' || $ming_tmp[$i]=='\n' || $ming_tmp[$i]=='\t' || $ming_tmp[$i]=='\r') continue;
        $ming .= $ming_tmp[$i];
    }
    $ming = ucfirst($ming);
    $author = $xing.", ".$ming;
    if ($author[0] == ',') $author = "";
    if (strlen($author)>=2 && $author[strlen($author)-2] == ',') $author = substr($author, 0, strlen($author)-2);
    $source=$_POST['source'];
    $spj=$_POST['spj'];
    echo "Sample data file Updated!<br>";
    
    
    //remove original samples
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
            $sample_input=preg_replace("/(\r\n)/","\n",$sample_input);
            $sample_output=preg_replace("/(\r\n)/","\n",$sample_outputs[$key]);
            if($sample_input=="" && $sample_output=="")continue;
            
            $fp=fopen($OJ_DATA."/$id/sample".$key.".in","w");
            fputs($fp,$sample_input);
            fclose($fp);
            //echo "<pre>create: ".$OJ_DATA."/$id/sample".$key.".in"."</pre>";
            
            $fp=fopen($OJ_DATA."/$id/sample".$key.".out","w");
            fputs($fp,preg_replace("/(\r\n)/","\n",$sample_output));
            fclose($fp);
            
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
    $title=$mysqli->real_escape_string($title);
    $time_limit=$mysqli->real_escape_string($time_limit);
    $memory_limit=$mysqli->real_escape_string($memory_limit);
    $description=$mysqli->real_escape_string($description);
    $input=$mysqli->real_escape_string($input);
    $output=$mysqli->real_escape_string($output);
//  $test_input=($test_input);
//  $test_output=($test_output);
    $hint=$mysqli->real_escape_string($hint);
    $author=$mysqli->real_escape_string($author);
    $source=$mysqli->real_escape_string($source);
//  $spj=($spj);
    if(isset($_POST['add_problem_mod'])){
        $_SESSION["p$id"]=true;
        echo "<a href=quixplorer/index.php?action=list&dir=$id&order=name&srt=yes>Add More Test Data</a>";
    }
    else{
        $sql="UPDATE `problem` set `problemset`='$problemset',`title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
          `description`='$description',`input`='$input',`output`='$output',`hint`='$hint',author='$author',`source`='$source',`spj`=$spj,`in_date`=NOW()
          WHERE `problem_id`=$id";
        @$mysqli->query($sql) or die($mysqli->error);
        echo "Edit OK!";
        //echo $sql;
        echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
    }
}
?>
<?php require_once("admin-footer.php"); ?>

<!-- samples edit scripts START-->
<script>
    var sample_cnt=<?php
        if($add_problem_mod) echo "0";
        else echo count($samples);
        ?>;
    $("#add_sample").on("click",function(event){
        sample_cnt++;
        var html="";
        html+="\
              <div>\
                <div class='form-inline' style='height: 35px;'>\
                  <div style='float: left; padding-top: 6px;'>\
                    Input <sapn class='label label-primary'>"+(sample_cnt-1)+"</sapn>\
                  </div>\
                  <div class='input-group input-group-sm' style='float: right;'>\
                    <div class='input-group-addon'>Show after trying: </div>\
                    <input name='show_after[]' class='form-control' style='width:50px; text-align: center;' value='0'>\
                    <div class='input-group-addon'>times</div>\
                  </div>\
                </div>\
                <div><textarea name='sample_input[]' class='form-control' id='' rows='4'></textarea></div>\
                <div style='margin-bottom: 13px; margin-top: 13px;'>\
                  Output <sapn class='label label-primary'>"+(sample_cnt-1)+"</sapn>\
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