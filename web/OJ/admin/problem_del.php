<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  require_once("admin-header.php");
  ini_set("display_errors","On");
  require_once("../include/check_get_key.php");
  require_once("../include/my_func.inc.php");
  if (!HAS_PRI("edit_".get_problemset($_GET['id'])."_problem")) {
    require_once("error.php");
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
        // delete images start
        function getImages($content){
          preg_match_all("<[iI][mM][gG][^<>]+[sS][rR][cC]=\"?([^ \"\>]+)/?>",$content,$images);
          return $images;
        }
        function delImages($html,&$did){
          $images=getImages($html);
          $imgs=array_unique($images[1]);
          foreach($imgs as $img){
            if(!in_array($img,$did)){
              $file = $_SERVER['DOCUMENT_ROOT'].$img;
              if(file_exists($file)){
                unlink($file);
              }
              array_push($did,$img);
            }
          }
        }
        $did = array();
        $sql = "SELECT * FROM `problem` WHERE `problem_id`='$id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object()){
          delImages($row->description, $did);
          delImages($row->input, $did);
          delImages($row->output, $did);
          delImages($row->hint, $did);
        }
        // delete images end
        $sql="delete FROM `problem` WHERE `problem_id`=$id";
        $mysqli->query($sql) or die($mysqli->error);
        $sql="UPDATE solution SET problem_id=NULL WHERE problem_id=$id";
        $mysqli->query($sql);
        $sql="DELETE FROM problem_samples WHERE problem_id=$id";
        $mysqli->query($sql);
        $sql="select max(problem_id) FROM `problem`" ;
        $result=$mysqli->query($sql);
        $row=$result->fetch_row();
        $max_id=$row[0];
        $max_id++;
        if($max_id<1000)$max_id=1000;
        $result->free();
        $sql="ALTER TABLE problem AUTO_INCREMENT = $max_id;";
        $mysqli->query($sql);
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
