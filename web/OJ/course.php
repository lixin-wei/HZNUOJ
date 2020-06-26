<?php
/**
 * This file is created
 * by lixun516@qq.com
 * @2020.06.20
 **/
?>
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
///////////////////////////MAIN
    
	
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/course.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
