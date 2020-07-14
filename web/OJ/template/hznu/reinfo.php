<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.03.26
  **/
?>

<?php 
  $title="RE or WA Info";
  if (isset($cid) && is_numeric($cid)){
    $_GET['cid']=$cid;
    require_once("contest_header.php");
  } else require_once("header.php");
?>
<div class="am-container">
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <pre id='errtxt' class="alert alert-error"><?php echo $view_reinfo?></pre>
    <div id='errexp'>Explain:</div>
  </div>
    <script>
      var pats=new Array();
      var exps=new Array();
      pats[0]=/A Not allowed system call.* /;
      exps[0]="使用了系统禁止的操作系统调用，看看是否越权访问了文件或进程等资源。<br>此类问题常见为程序运行超时，比如死循环或者循环时间过长超过规定时间";
      pats[1]=/Segmentation fault/;
      exps[1]="段错误，检查是否有数组越界，指针异常，访问到不应该访问的内存区域";
      pats[2]=/Floating point exception/;
      exps[2]="浮点错误，检查是否有除以零的情况";
      pats[3]=/buffer overflow detected/;
      exps[3]="缓冲区溢出，检查是否有字符串长度超出数组的情况";
      pats[4]=/Killed/;
      exps[4]="进程因为内存或时间原因被杀死，检查是否有死循环";
      pats[5]=/Alarm clock/;
      exps[5]="进程因为时间原因被杀死，检查是否有死循环，本错误等价于超时TLE";
      function explain(){
        //alert("asdf");
        var errmsg=document.getElementById("errtxt").innerHTML;
        var expmsg="辅助解释：<br>";
        for(var i=0;i<pats.length;i++){
          var pat=pats[i];
          var exp=exps[i];
          var ret=pat.exec(errmsg);
          if(ret){
            expmsg+=ret+":"+exp+"<br>";
          }
        }
        document.getElementById("errexp").innerHTML=expmsg;
        //alert(expmsg);
      }
      explain();
    </script>
</div> <!-- /container -->  
<?php require_once("footer.php") ?>