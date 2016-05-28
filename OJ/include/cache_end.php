<?php
 //cache foot start      
                if($file){
                        if($OJ_MEMCACHE){
                                $mem->set($file,ob_get_contents(),0,$cache_time);
                        }else{
                          // if(!file_exists("cache")) mkdir("cache");
                          //      file_put_contents($file,ob_get_contents());
                        }
                }
        //cache foot stop
?>
<!--not cached-->
