<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Edit Problem</title>
   </head>
  <body>
    <center>
      <?php require_once("../include/db_info.inc.php");?>
      <?php require_once("admin-header.php"); ?>
      <?php
        $type = "OJ";
        if (isset($_GET['type'])) {
          $type = $_GET['type'];
        }
        if ($type=="C" && !HAS_PRI("edit_c_problem")) {
          echo "Permission denied!";
          exit(1);
        }
        if ($type=="OJ" && !HAS_PRI("edit_hznu_problem")) {
          echo "Permission denied!";
          exit(1);
        }
      ?>
      <?php include_once("kindeditor.php") ; ?>
      <p align="center"><font color="#333399" size="4">Welcome To Administrator's Page of Judge Online of ACM ICPC, <?php echo $OJ_NAME?>.</font></p>
      <td width="100"></td>
    </center>
    <hr>
  <?php 
    if(isset($_GET['id'])) {
      ;// require_once("../include/check_get_key.php");
  ?>
      <h1>Edit problem</h1>
      <form method=POST action=problem_edit.php>
        <input type=hidden name=problem_id value=New Problem>
  <?php 
        $sql="SELECT * FROM `problem` WHERE `problem_id`=".intval($_GET['id']);
        $result=mysql_query($sql);
        $row=mysql_fetch_object($result);
   ?>
        <p>Problem Id: <?php echo $row->problem_id?></p>
        <input type=hidden name=problem_id value='<?php echo $row->problem_id?>'>
        <p>Title:<input type=text name=title size=71 value='<?php echo htmlentities($row->title,ENT_QUOTES,"UTF-8")?>'></p>
        <p>Time Limit:<input type=text name=time_limit size=20 value='<?php echo $row->time_limit?>'>S</p>
        <p>Memory Limit:<input type=text name=memory_limit size=20 value='<?php echo $row->memory_limit?>'>MByte</p>
        <p>Description:<br><textarea class="kindeditor" rows=13 name=description cols=120><?php echo htmlentities($row->description,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>Input:<br><textarea class="kindeditor" rows=13 name=input cols=120><?php echo htmlentities($row->input,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>Output:<br><textarea class="kindeditor" rows=13 name=output cols=120><?php echo htmlentities($row->output,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>Sample Input:<br><textarea rows=13 name=sample_input cols=120><?php echo htmlentities($row->sample_input,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>Sample Output:<br><textarea rows=13 name=sample_output cols=120><?php echo htmlentities($row->sample_output,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>Hint:<br><textarea class="kindeditor" rows=13 name=hint cols=120><?php echo htmlentities($row->hint,ENT_QUOTES,"UTF-8")?></textarea></p>
        <p>SpecialJudge: 
          N<input type=radio name=spj value='0' <?php echo $row->spj=="0"?"checked":""?>>
          Y<input type=radio name=spj value='1' <?php echo $row->spj=="1"?"checked":""?>>
        </p>
        <p>Author:<br>
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
          XING: <textarea name='xing' rows='1' cols='70' style='width:100px'><?php echo htmlspecialchars($xing)?></textarea>
          MING: <textarea name='ming' rows='1' cols='70' style='width:100px'><?php echo htmlspecialchars($ming)?></textarea>
        </p>
        <p>Source: <textarea name=source rows=1 cols=70><?php echo htmlentities($row->source,ENT_QUOTES,"UTF-8")?></textarea></p>
        <div align=center>
          <?php require_once("../include/set_post_key.php");?>
          <input type=submit value=Submit name=submit>
        </div>
      </form>
      <p>
    <?php 
        require_once("../oj-footer.php");
      } else {
        require_once("../include/check_post_key.php");
        $id=intval($_POST['problem_id']);
        if(!$GE_TA) exit();  
        $title=$_POST['title'];
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
        $title=mysql_real_escape_string($title);
        $time_limit=mysql_real_escape_string($time_limit);
        $memory_limit=mysql_real_escape_string($memory_limit);
        $description=mysql_real_escape_string($description);
        $input=mysql_real_escape_string($input);
        $output=mysql_real_escape_string($output);
        $sample_input=mysql_real_escape_string($sample_input);
        $sample_output=mysql_real_escape_string($sample_output);
    //  $test_input=($test_input);
    //  $test_output=($test_output);
        $hint=mysql_real_escape_string($hint);
        $author=mysql_real_escape_string($author);
        $source=mysql_real_escape_string($source);
    //  $spj=($spj);
  
        $sql="UPDATE `problem` set `title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
            `description`='$description',`input`='$input',`output`='$output',`sample_input`='$sample_input',`sample_output`='$sample_output',`hint`='$hint',author='$author',`source`='$source',`spj`=$spj,`in_date`=NOW()
            WHERE `problem_id`=$id";

        @mysql_query($sql) or die(mysql_error());
        echo "Edit OK!";

        echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
      }
    ?>
  </body>
</html>

