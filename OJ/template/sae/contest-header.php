<?php  
    require_once('./include/cache_start.php');

        if(isset($OJ_LANG)){
                require_once("./lang/$OJ_LANG.php");
        }
?>
<?php if(isset($_GET['cid']))
        $cid=intval($_GET['cid']);
if (isset($_GET['pid']))
        $pid=intval($_GET['pid']);
?>
<script src="bootstrap/js/bootstrap.js"></script>

        <div class="btn-group">
          <a class="btn btn-primay" data-toggle="dropdown" href="./"><?php echo $OJ_NAME?></a>
          <a class="btn btn-info" href='./bbs.php?cid=<?php echo $cid?>'><i class="icon-comment"></i><?php echo $MSG_BBS?></a>
          <a class="btn btn-warning" href='./contest.php?cid=<?php echo $cid?>'><i class="icon-question-sign"></i><?php echo $MSG_PROBLEMS?></a>
          <a class="btn btn-success" href='./status.php?cid=<?php echo $cid?>'><i class="icon-check"></i><?php echo $MSG_STATUS?></a>
          <a class="btn btn-danger" href='./contestrank.php?cid=<?php echo $cid?>'><i class="icon-signal"></i><?php echo $MSG_STANDING?></a>
          <a class="btn btn-inverse" href='./conteststatistics.php?cid=<?php echo $cid?>'><i class="icon-info-sign"></i><?php echo $MSG_STATISTICS?></a>


        </div>
        
       
       
<div id=broadcast>
<marquee id=broadcast scrollamount=1 direction=up scrolldelay=250 onMouseOver='this.stop()' onMouseOut='this.start()';>
  <?php echo $view_marquee_msg?>
</marquee>
</div><!--end broadcast-->