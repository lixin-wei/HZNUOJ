<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>
  <?php 
    require_once("../include/db_info.inc.php");
    require_once("admin-header.php");
  ?>
<?php
  $first=true;
  $html_select="";
  $res=$mysqli->query("SELECT * FROM problemset");
  while($row=$res->fetch_array()){
    if(HAS_PRI("edit_".$row['set_name']."_problem")){
      $html_select .= "<option value=".$row['set_name'];
      if($first){
        $first=false;
        $html_select .= " selected='true'";
      }
      $html_select .= ">";
      $html_select .= $row['set_name_show'];
      $html_select .= "</oition>";
    }
  }
  if($first==true){
    require_once("error.php");
    exit(0);
  }
?>
<div class="container" style="width: 800px;">
  <?php include_once("kindeditor.php"); ?>
    <title>Add New problem</title>
    <h1>Add New problem</h1>
    <hr/>
    <form class="form-horizontal" method=POST action='problem_add.php' id="problem_form">
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Problem Id</label>
        <div class="col-sm-10">
          <input class="form-control" disabled="true" type=text name=problem_id value="New Problem">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=title>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Problemset</label>
        <div class="col-sm-10">
          <select class="form-control selectpicker" name="problemset">
            <?php echo $html_select; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Limit</label>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">Time</div>
            <input class="form-control" type="text" name="time_limit" value="1">
            <div class="input-group-addon">s</div>
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon">Memory</div>
            <input class="form-control" type="text" name="memory_limit" value="128">
            <div class="input-group-addon">MB</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10"><textarea name="description" id="" rows="13" class="kindeditor"></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Input</label>
        <div class="col-sm-10"><textarea name="input" id="" rows="13" class="kindeditor"></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Output</label>
        <div class="col-sm-10"><textarea name="output" id="" rows="13" class="kindeditor"></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Sample Input</label>
        <div class="col-sm-10"><textarea name="sample_input" id="" rows="13"></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Sample Output</label>
        <div class="col-sm-10"><textarea name="sample_output" id="" rows="13"></textarea></div>
      </div>
<!--       <div class="form-group">
        <label class="col-sm-2 control-label">Test Input</label>
        <div class="col-sm-10"><textarea name="test_input" id="" rows="13"></textarea></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Test Output</label>
        <div class="col-sm-10"><textarea name="test_input" id="" rows="13"></textarea></div>
      </div> -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Hint</label>
        <div class="col-sm-10"><textarea name="hint" id="" rows="13" class="kindeditor"></textarea></div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Special judge</label>
        <div class="col-sm-10">
          <label class="radio-inline"><input type="radio" name="spj" value="0" checked>N</label>
          <label class="radio-inline"><input type="radio" name="spj" value="1">Y</label>
        </div>
      </div>
      <hr/>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Author</label>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">Last name(XING)</div>
            <input class="form-control" type="text" name="xing">
          </div>
        </div>
        <div class="col-sm-5">
          <div class=" input-group">
            <div class="input-group-addon">First name(MING)</div>
            <input class="form-control" type="text" name="ming">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Source</label>
        <div class="col-sm-10">
          <input class="form-control" type=text name=source>
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
    <p><?php require_once("../oj-footer.php");?></p>
</div><!-- container -->
  
<?php 
  require_once("admin-footer.php")
?>