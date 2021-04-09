<?php
/**
 * This file is created
 * by yybird
 * @2016.03.22
 * last modified
 * by tautcony
 * @2017.10.25
 **/
?>

<footer class="blog-footer">
  <hr />
  <a href="https://github.com/lixin-wei/HZNUOJ" target="_blank">HZNUOJ</a> is based on <a href="https://github.com/zhblue/hustoj" target="_blank">HUSTOJ</a><br />
  <div>
  <span>-</span>
  <a href='./setlang.php?lang=en'>English</a>
  <span>-</span>
  <a href='./setlang.php?lang=cn'>简体中文</a>
  <span>-</span>
  </div>
  ★<?php echo $MSG_SERVERTIME ?>: <span id='footerdate'><?php echo date('Y-m-d H:i:s',time()); ?></span>★
  <div style="padding-bottom: 20px;">
  <?php if(isset($OJ_BEIAN)&&$OJ_BEIAN){ ?>
    <span><a href='http://beian.miit.gov.cn/' target='_blank'><?php echo $OJ_BEIAN?></a></span>
  <?php } ?>
  </div>
  <!-- go to top btn START -->
  <div class="amz-toolbar" id="go-top" style="display: none; position: fixed; bottom: 15px; right: 15px;">
    <a data-am-smooth-scroll href="#" title="回到顶部" class="am-icon-btn am-icon-arrow-up"></a>
  </div>
  <!-- go to top btn END -->
</footer>
<!--[if (gte IE 9)|!(IE)]><!-->

<script src="./plugins/jquery/jquery-3.1.1.min.js"></script>
<script src="./plugins/AmazeUI/js/amazeui.min.js"></script>
<!-- <script src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script> -->
<!-- <script src="AmazeUI/js/handlebars.min.js"></script> -->

<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<![endif]-->

<!-- go to top btn auto hide START -->
<script type="text/javascript">
    $(window).on("scroll load", function() {
        if ($(window).scrollTop() >= 400) {
            $("#go-top").fadeIn("fast");
        } else {
            $("#go-top").fadeOut("fast");
        }
    });
</script>
<!-- go to top btn auto hide END -->
<!-- 动态显示时间 start -->
<!-- 动态显示时间 start -->
<script>
    var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
    //alert(diff);
    function clock_foot() {
        var x,h,m,s,n,xingqi,y,mon,d;
        var x = new Date(new Date().getTime()+diff);
        y = x.getYear()+1900;
        if (y>3000) y-=1900;
        mon = x.getMonth()+1;
        d = x.getDate();
        xingqi = x.getDay();
        h=x.getHours();
        m=x.getMinutes();
        s=x.getSeconds();

        n=y+"-"+(mon>=10?mon:"0"+mon)+"-"+(d>=10?d:"0"+d)+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
        //alert(n);
        document.getElementById('footerdate').innerHTML=n;
        setTimeout("clock_foot()",1000);
    }
    clock_foot();
</script>
<!-- 动态显示时间 end -->
<!-- 动态显示时间 end -->

<?php if (isset($_GET['cid']) && $is_started): ?>
  <!-- contest time bar BEGIN -->
  <script>
      // The javescript's timestamp is in milliseconds, but PHP's is in seconds.
      var now = function() { return Math.floor(Date.now() / 1000); };
      var contest_len = <?php echo $contest_len ?>;
      var begin_time = <?php echo $contest_time[0] ?>;
      var warnning_percent = <?php echo $warnning_percent ?>;
      var lock_time=-1;
      <?php if ($unlock!=1){ ?>
        lock_time=<?php echo $view_lock_time ?>;
      <?php }?>
      function time_format(time_stamp) {
          var h = Math.floor(time_stamp / 3600);
          time_stamp -= h * 3600;
          var m = Math.floor(time_stamp / 60);
          time_stamp -= m * 60;
          var s = Math.floor(time_stamp);
          if (h < 10) h = "0" + h;
          if (m < 10) m = "0" + m;
          if (s < 10) s = "0" + s;
          return h+":"+m+":"+s;
      }

      function update() {
          var dur = now() - begin_time;
          if (dur >= contest_len){
              dur = contest_len;
          }
          var bar_percent = dur / contest_len * 100;
          if (bar_percent >= 100) {
              $("#contest-bar").removeClass("am-active");
              $("#contest-bar-progress").attr("class", "am-progress-bar am-progress-bar-secondary");
              $("#contest-bar-progress").html("<?php echo $MSG_Ended ?>");
          } else if (bar_percent >= warnning_percent) {
              $("#contest-bar-progress").attr("class", "am-progress-bar am-progress-bar-danger");
              $("#contest-bar-progress").html("<?php echo $MSG_NearlyEnd ?>");
          } else if (now() >= lock_time && lock_time > 0) {
            $("#contest-bar-progress").html("<?php echo $MSG_Locked ?>");
          }
          $("#contest-bar-progress").css({"width" : bar_percent+"%"});
          $("#time_elapsed").html(time_format(dur));
          $("#time_remaining").html(time_format(contest_len - dur));
      }
      update();
      setInterval(update, 1000);
  </script>
  <!-- contest time bar END -->
<?php endif ?>

</body>
</html>
