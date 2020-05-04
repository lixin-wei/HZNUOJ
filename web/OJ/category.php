<?php
////////////////////////////Common head
    $cache_time=10;
    $OJ_CACHE_SHARE=false;
    require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
    require_once('./include/const.inc.php');
    require_once('./include/setlang.php');
    require_once('./include/my_func.inc.php');
    $view_title= "Welcome To Online Judge";
    $result=false;
///////////////////////////MAIN
    
	$view_category=array();
	$sql="SELECT distinct `source` FROM `problem` WHERE 0 ";
	$problen_sets = get_problemset("");
	foreach($problen_sets as $row){
		if(HAS_PRI("see_hidden_".$row[0]."_problem")){
			$sql.=" OR `problemset`='$row[0]'";
        } else 	$sql.=" OR (`problemset`='$row[0]' AND `defunct`='N')";
	}
	$sql.= " LIMIT 500";
    if($OJ_MEMCACHE){
        require("./include/memcache.php");
        $result = $mysqli->query_cache($sql) ;
    } else {
        $result = $mysqli->query($sql) or die("Error! ".$mysqli->error);
    }
    if (!$result){
        $view_category= "<h3>No Category Now!</h3>";
    }else{
        $category="";
        foreach ($result as $row){
			$cate=explode(" ",trim($row['source']));
            foreach($cate as $cat){
                $category .= trim($cat)." ";
            }
		}
		$view_category[0] = trim($category) ? count(array_unique(explode(" ",trim($category)))) : 0;
        $view_category[1] .= "<div style='word-wrap:break-word;'>";
        $view_category[1] .= show_category($category,"lg");
        $view_category[1] .= "</div>";
    }

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/category.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
