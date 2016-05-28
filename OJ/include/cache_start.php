<?php
        require_once("./include/db_info.inc.php");
        //cache head start
        if(!isset($cache_time)) $cache_time=10;
        $file="cache/cache_".$_SERVER["REQUEST_URI"].".html";
        $sid=$OJ_NAME;
        $OJ_CACHE_SHARE=(isset($OJ_CACHE_SHARE)&&$OJ_CACHE_SHARE)&&!isset($_SESSION['administrator']);
        if (!$OJ_CACHE_SHARE&&isset($_SESSION['user_id'])){
                $sid.=session_id().$_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER["REQUEST_URI"])){
                $sid.=$_SERVER["REQUEST_URI"];
        }
        
        $sid=md5($sid);
        $file = "cache/cache_$sid.html";
        
        if($OJ_MEMCACHE ){
                $mem = new Memcache;
                if($OJ_SAE)
                        $mem=memcache_init();
                else{
                        $mem->connect($OJ_MEMSERVER,  $OJ_MEMPORT);
                }
                $content=$mem->get($file);
                if($content){
                         echo $content;
                         exit();
                }else{
                        $use_cache=false;
                        $write_cache=true;
                }
        }else{
                
                if (file_exists ( $file ))
                        $last = filemtime ( $file );
                else
                        $last =0;
                $use_cache=(time () - $last < $cache_time);
                
        }
        if ($use_cache) {
                //header ( "Location: $file" );
                include ($file);
                exit ();
        } else {
                ob_start ();
        }
//cache head stop
?>
