<?php
  /**
   * This file is modified
   * by yybird
   * @2015.07.03
  **/
?>

<?php
  $url=basename($_SERVER['REQUEST_URI']);
  $dir=basename(getcwd());
  if($dir=="discuss3") $path_fix="../";
  else $path_fix="";
?>

<!-- Static navbar -->
<nav class="navbar navbar-default" role="navigation" >
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo $OJ_HOME?>"><?php echo $OJ_NAME?></a>
    </div>        
    

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
<?php 
      if (isset($_GET['cid'])) {
        $cid=intval($_GET['cid']);
        $ACTIVE="class='active'"
?>
        <li><a href="<?php echo $OJ_HOME?>"><?php echo $MSG_BACK_TO_HOME?></a></li>
        <li <?php if ($url=="problemset.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>contest.php?cid=<?php echo $cid?>"><?php echo $MSG_PROBLEM?></a></li>
        <li <?php if ($url=="status.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>status.php?cid=<?php echo $cid?>"><?php echo $MSG_STATUS?></a></li>
        <li <?php if ($url=="contestrank.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>contestrank.php?cid=<?php echo $cid?>"><?php echo $MSG_RANKLIST?></a></li>
        <li <?php if ($url=="conteststatistics.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>conteststatistics.php?cid=<?php echo $cid?>"><?php echo $MSG_STATISTICS?></a></li>
        <li <?php if ($url=="contest.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>contest.php"><?php echo $BACK_TO_CONTEST?></a></li>

<?php 
      } else { 
        $ACTIVE="class='active'"
?>
        <li <?php if ($dir=="discuss") echo " $ACTIVE";?>><a href="../bbs/" target="_blank"><?php echo $MSG_BBS?></a></li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><?php echo $MSG_PROBLEMSET ?></span><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $path_fix?>problemset.php"><?php echo HZNU?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>problemsetOther.php?OJ=ZOJ"><?php echo ZJU?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>problemsetOther.php?OJ=HDU"><?php echo HDU?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>problemsetOther.php?OJ=POJ"><?php echo PKU?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>problemsetOther.php?OJ=UVA"><?php echo UVA?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>problemsetOther.php?OJ=CodeForces"><?php echo CodeForces?></a></li>&nbsp
          </ul>
        </li>

        <li <?php if ($url=="status.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>status.php"><?php echo $MSG_STATUS?></a></li>
        <li <?php if ($url=="ranklist.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>ranklist.php"><?php echo $MSG_RANKLIST?></a></li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><?php echo $MSG_CONTEST ?></span><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $path_fix?>contest.php"><?php echo Local ?></a></li>&nbsp
            <li><a href="<?php echo $path_fix?>recent-contest.php"><?php echo Remote ?></a></li>&nbsp
          </ul>
        </li>       

        <li <?php if ($url=="faqs.php") echo " $ACTIVE";?>><a href="<?php echo $path_fix?>faqs.php"><?php echo $MSG_FAQ?></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><?php echo $MSG_ACM_MAIL ?></span><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="http://exmail.qq.com/cgi-bin/loginpage?t=logindomain&param=@hsacm.cn" target="_blank"><?php echo "hsACM.cn"?></a></li>&nbsp
            <li><a href="http://exmail.qq.com/cgi-bin/loginpage?t=logindomain&param=@hsacm.com" target="_blank"><?php echo "hsACM.com"?></a></li>&nbsp
          </ul>
        </li>  
        
        <li><a href="http://vj.hsacm.com" target="_blank"><?php echo vjudge ?></a></li>

<?php
      }
?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span id="profile">Login</span><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
        <script src="<?php echo $path_fix."template/$OJ_TEMPLATE/profile.php?".rand();?>" ></script>
        <!--<li><a href="../navbar-fixed-top/">Fixed top</a></li>-->
      </ul>
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>
