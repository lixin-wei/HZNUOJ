<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

    <title>Edit Problem</title>
      <?php require_once("../include/db_info.inc.php");?>
      <?php require_once("admin-header.php"); ?>
      <?php include_once("kindeditor.php") ; ?>
  <?php 
    if(isset($_GET['id'])) {
      ;// require_once("../include/check_get_key.php");
  ?>
  <?php 
        $sql="SELECT * FROM `problem` WHERE `problem_id`=".intval($_GET['id']);
        $result=$mysqli->query($sql);
        $row=$result->fetch_object();
        if (!HAS_PRI("edit_".$row->problemset."_problem")) {
          require_once("error.php");
          exit(0);
        }
  ?>

  <div class="container" style="width: 800px">
    <h1>Edit problem</h1>
    <hr/>
    <form class="form-horizontal" method=POST action="problem_edit.php" id="problem_form">
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Problem Id</label>
        <div class="col-sm-10">
          <input class="form-control" disabled="true" type=text name="" value='<?php echo $row->problem_id?>'>
          <input class="form-control" type=hidden name=problem_id value='<?php echo $row->problem_id?>'>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=title value="<?php echo htmlentities($row->title,ENT_QUOTES,"UTF-8")?>">
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
                if($row2['set_name']==$row->problemset){
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
            <input class="form-control" type="text" name="time_limit" value="<?php echo $row->time_limit?>">
            <div class="input-group-addon">s</div>
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon">Memory</div>
            <input class="form-control" type="text" name="memory_limit" value="<?php echo $row->memory_limit?>">
            <div class="input-group-addon">MB</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10"><textarea name="description" id="" cols="80" rows="13" class="kindeditor"><?php echo htmlentities($row->description,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Input</label>
        <div class="col-sm-10"><textarea name="input" id="" cols="80" rows="13" class="kindeditor"><?php echo htmlentities($row->input,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Output</label>
        <div class="col-sm-10"><textarea name="output" id="" cols="80" rows="13" class="kindeditor"><?php echo htmlentities($row->output,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Sample Input</label>
        <div class="col-sm-10"><textarea name="sample_input" id="" cols="80" rows="13"><?php echo htmlentities($row->sample_input,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Sample Output</label>
        <div class="col-sm-10"><textarea name="sample_output" id="" cols="80" rows="13"><?php echo htmlentities($row->sample_output,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Hint</label>
        <div class="col-sm-10"><textarea name="hint" id="" cols="80" rows="13" class="kindeditor"><?php echo htmlentities($row->hint,ENT_QUOTES,"UTF-8")?></textarea></div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Special judge</label>
        <div class="col-sm-10">
          <label class="radio-inline"><input type="radio" name="spj" value="0" <?php echo $row->spj=="0"?"checked":""?>>N</label>
          <label class="radio-inline"><input type="radio" name="spj" value="1" <?php echo $row->spj=="1"?"checked":""?>>Y</label>
        </div>
      </div>
      <hr/>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Author</label>
          <?php
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
          ?>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">Last name(XING)</div>
            <input class="form-control" type="text" name="xing" value="<?php echo htmlspecialchars($xing)?>">
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon">First name(MING)</div>
            <input class="form-control" type="text" name="ming" value="<?php echo htmlspecialchars($ming)?>">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Source</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=source value="<?php echo htmlentities($row->source,ENT_QUOTES,"UTF-8")?>">
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
      <div align=center>
        <?php require_once("../include/set_post_key.php");?>
        <button type=submit value=submit class="btn btn-default">Submit</button>
      </div>
      <input type='hidden' value=<?php echo $type ?> name='type'></input>
    </form>
  </div>
      <p>
    <?php 
        require_once("../oj-footer.php");
      } else {
        require_once("../include/check_post_key.php");
        $id=intval($_POST['problem_id']);
        $title=$_POST['title'];
        $problemset=$_POST['problemset'];
        $time_limit=$_POST['time_limit'];
        $memory_limit=$_POST['memory_limit'];
        $description=$_POST['description'];
        $input=$_POST['input'];
        $output=$_POST['output'];
        $sample_input=$_POST['sample_input'];
        $sample_output=$_POST['sample_output'];
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
        if (get_magic_quotes_gpc ()) {
          $title = stripslashes ( $title);
          $time_limit = stripslashes ( $time_limit);
          $memory_limit = stripslashes ( $memory_limit);
          $description = stripslashes ( $description);
          $input = stripslashes ( $input);
          $output = stripslashes ( $output);
          $sample_input = stripslashes ( $sample_input);
          $sample_output = stripslashes ( $sample_output);
      //  $test_input = stripslashes ( $test_input);
      //  $test_output = stripslashes ( $test_output);
          $hint = stripslashes ( $hint);
          $source = stripslashes ( $source); 
          $spj = stripslashes ( $spj);
          $source = stripslashes ( $source );
        }
        $basedir=$OJ_DATA."/$id";
        echo "Sample data file in $basedir Updated!<br>";

        if($sample_input){
          //mkdir($basedir);
          $fp=fopen($basedir."/sample.in","w");
          fputs($fp,preg_replace("(\r\n)","\n",$sample_input));
          fclose($fp);
          
          $fp=fopen($basedir."/sample.out","w");
          fputs($fp,preg_replace("(\r\n)","\n",$sample_output));
          fclose($fp);
        }
        $title=$mysqli->real_escape_string($title);
        $time_limit=$mysqli->real_escape_string($time_limit);
        $memory_limit=$mysqli->real_escape_string($memory_limit);
        $description=$mysqli->real_escape_string($description);
        $input=$mysqli->real_escape_string($input);
        $output=$mysqli->real_escape_string($output);
        $sample_input=$mysqli->real_escape_string($sample_input);
        $sample_output=$mysqli->real_escape_string($sample_output);
    //  $test_input=($test_input);
    //  $test_output=($test_output);
        $hint=$mysqli->real_escape_string($hint);
        $author=$mysqli->real_escape_string($author);
        $source=$mysqli->real_escape_string($source);
    //  $spj=($spj);
  
        $sql="UPDATE `problem` set `problemset`='$problemset',`title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
            `description`='$description',`input`='$input',`output`='$output',`sample_input`='$sample_input',`sample_output`='$sample_output',`hint`='$hint',author='$author',`source`='$source',`spj`=$spj,`in_date`=NOW()
            WHERE `problem_id`=$id";
        @$mysqli->query($sql) or die($mysqli->error);
        echo "Edit OK!";
        //echo $sql;
        echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
      }
    ?>
  </body>
</html>

<?php require_once("admin-footer.php"); ?>