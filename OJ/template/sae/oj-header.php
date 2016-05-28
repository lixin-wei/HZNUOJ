<div id=head>
<h2><img id=logo src="<?php echo "template/".$OJ_TEMPLATE?>/image/logo.png"><span class="red"><?php echo $OJ_NAME?></span></h2>
</div><!--end head-->
<div id=subhead>
	  <div id=menu class=navbar>
	  <?php $ACTIVE="btn-warning";?>
		<a  class='btn'  href="<?php echo $OJ_HOME?>"><i class="icon-home"></i>
		<?php echo $MSG_HOME?>						
		</a>
		
		<a  class='btn <?php if ($url==$OJ_BBS.".php") echo " $ACTIVE";?>'  href="bbs.php">
		<i class="icon-comment"></i><?php echo $MSG_BBS?></a>
		<a  class='btn <?php if ($url=="problemset.php") echo " $ACTIVE";?>' href="problemset.php">
		<i class="icon-question-sign"></i><?php echo $MSG_PROBLEMS?></a>
		
		<a  class='btn <?php if ($url=="submitpage.php") echo " $ACTIVE";?>' href="submitpage.php">
		<i class="icon-pencil"></i><?php echo "编辑器"?></a>
		
		<a  class='btn <?php if ($url=="status.php") echo "  $ACTIVE";?>' href="status.php">
		<i class="icon-check"></i><?php echo $MSG_STATUS?></a>
		
		<a class='btn <?php if ($url=="ranklist.php") echo "  $ACTIVE";?>' href="ranklist.php">
		<i class="icon-signal"></i><?php echo $MSG_RANKLIST?></a>
		
		<a class='btn <?php if ($url=="contest.php") echo "  $ACTIVE";?>'  href="contest.php">
		<i class="icon-fire"></i><?php echo checkcontest($MSG_CONTEST)?></a>
		
		<a class='btn <?php if ($url=="recent-contest.php") echo " $ACTIVE";?>' href="recent-contest.php">
		<i class="icon-share"></i><?php echo "$MSG_RECENT_CONTEST"?></a>
		
		<a class='btn <?php if ($url==(isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php")) echo " $ACTIVE";?>' href="<?php echo isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php"?>">
                <i class="icon-info-sign"></i><?php echo "$MSG_FAQ"?></a>
		
		<?php if(isset($OJ_DICT)&&$OJ_DICT&&$OJ_LANG=="cn"){?>
					  
		<span div class='btn '  style="color:1a5cc8" id="dict_status"></span>
					 
					  <script src="include/underlineTranslation.js" type="text/javascript"></script>
					  <script type="text/javascript">dictInit();</script>
		<?php }?>
	</div><!--end menu-->
<div id=profile >
<script src="include/profile.php?<?php echo rand();?>" ></script>
</div><!--end profile-->
</div><!--end subhead-->
<div id=broadcast class="container">
<marquee id=broadcast scrollamount=1 direction=up scrolldelay=250 onMouseOver='this.stop()' onMouseOut='this.start()';>
  <?php echo $view_marquee_msg?>
</marquee>
</div>
<br>
