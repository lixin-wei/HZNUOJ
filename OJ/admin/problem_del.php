<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  require_once("admin-header.php");
  require_once("permission.php");
  ini_set("display_errors","On");
  require_once("../include/check_get_key.php");
  if (!$p_ok) {
    echo "<a href='../loginpage.php'>Permission denied!</a>";
    exit(1);
  }
?> 
<?php
  if($OJ_SAE||function_exists('system')){
        $id=intval($_GET['id']);
        
        $basedir = "$OJ_DATA/$id";
        if($OJ_SAE)
      ;//need more code to delete files
    else
      system("rm -rf $basedir");
        $sql="delete FROM `problem` WHERE `problem_id`=$id";
        mysql_query($sql) or die(mysql_error());
        $sql="select max(problem_id) FROM `problem`" ;
        $result=mysql_query($sql);
        $row=mysql_fetch_row($result);
        $max_id=$row[0];
        $max_id++;
        mysql_free_result($result);
        $sql="ALTER TABLE problem AUTO_INCREMENT = $max_id;";
        mysql_query($sql);
        ?>
        <script language=javascript>
                history.go(-1);
        </script>
<?php 
  }else{
  
  
  ?>
        <script language=javascript>
                alert("Nees enable system() in php.ini");
                history.go(-1);
        </script>
  <?php 
  
  }

?>
