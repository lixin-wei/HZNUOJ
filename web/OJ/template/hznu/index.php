<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.05.07
   * last modified
   * by wlx
   * @2016.05.24
  **/
?>

<?php $title="Home";?>
<?php 
  require_once("header.php");
  require_once('./include/db_info.inc.php');
?>

<style>
  .bg{
    background-image: url(template/hznu/ojINDEX.jpg);
    background-repeat: no-repeat;
    background-position: top;
  }
  #slider {
    height: 300px;
    width: 1000px;
    margin-left: auto;
    margin-right: auto;
  }
</style>
<div class="am-container" style="margin-top:0px;">
  <!-- 轮播模块 start -->
<!--   <div id='slider' data-am-widget='slider' class='am-slider am-slider-default' data-am-slider='{}'>
  <ul class='am-slides'>
    <?php
      //foreach ($slider_url as $i)
      //  echo "<li><img src='".$i."'></li>"
    ?>
  </ul>
</div> -->
  <!-- 轮播模块 end -->
  <div class="am-g ill" style="height: 380px;"></div>
  <div class="am-g ill" style="height: 20px;">
    <center><div class="link" style="cursor: pointer; height: 20px; width: 100px;"></div></center>
  </div>
  <div class="am-g ill" style="height: 30px;"></div>
  <div class='am-g'>
    <!-- 公告模块 start -->
    <div class="am-u-md-8">
      <div class="am-panel am-panel-primary" id="accordion0">
        <div class="am-panel-hd" class="am-panel-title" data-am-collapse="{parent: '#accordion0', target: '#do-not-say-0'}">News</div>
        <div id="do-not-say-0" class="am-panel-collapse am-collapse am-in">
          <div class="am-panel-bd">
            <div class="am-panel-group" id="accordion"> 
              <?php
                $n = count($news_title);
                if ($n) { // 有公告的话
                  $i = 2;
                  for ($j=0; $j<$n; ++$j) {
                    if ($news_importance[$j] == 10) echo "<div class='am-panel am-panel-danger'>";
                    else if ($news_importance[$j] == 3) echo "<div class='am-panel am-panel-warning'>";
                    else if ($news_importance[$j] == 2) echo "<div class='am-panel am-panel-secondary'>";
                    else if ($news_importance[$j] == 1) echo "<div class='am-panel am-panel-success'>";
                    else echo "<div class='am-panel am-panel-default'>";
echo <<<sss
                      <div class="am-panel-hd" class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-$i'}">
                        $news_title[$j]
                      </div>
                      <div id="do-not-say-$i" class="am-panel-collapse am-collapse">
                        <div class="am-panel-bd">
                          $news_content[$j]
                        </div>
                      </div>    
                    </div>  
sss;
                    $i++;
                  }
                }
              ?>    
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 公告模块 end -->

    
    <!--Submission Statics START-->
    <div class="am-u-md-4">
      <?php
      $sql="SELECT count(*) FROM solution WHERE in_date<=NOW() AND in_date>date_add(NOW(), interval -1 day)";
      $res=mysql_query($sql);
      $cnt=mysql_fetch_array($res)[0];
      ?>
      <div class="am-panel am-panel-primary">
        <div class="am-panel-hd">Statics</div>
        <div id="submission_chart" style="width: 100%;height: 250px;"></div>
      </div>
    </div>
    <!--Submission Statics END-->
  </div>
  
  

    <!-- 邮箱登录模块 start -->
<!--     <div class="am-u-md-3">
      <style>
        .bizmail_loginpanel{font-size:12px;width:250px;height:auto;border:1px solid #cccccc;background:#ffffff;}
        .bizmail_LoginBox{padding:10px 15px;}
        .bizmail_loginpanel h3{padding-bottom:5px;margin:0 0 5px 0;border-bottom:1px solid #cccccc;font-size:14px;}
        .bizmail_loginpanel form{margin:0;padding:0;}
        .bizmail_loginpanel input.text{font-size:12px;width:100px;height:25px;margin:0 2px;border:1px solid #C3C3C3;border-color:#7C7C7C #C3C3C3 #C3C3C3 #9A9A9A;}
        .bizmail_loginpanel .bizmail_column{height:28px;}
        .bizmail_loginpanel .bizmail_column label{display:block;float:left;width:30px;height:24px;line-height:24px;font-size:12px;}
        .bizmail_loginpanel .bizmail_column .bizmail_inputArea{float:left;width:240px;}
        .bizmail_loginpanel .bizmail_column span{font-size:12px;word-wrap:break-word;margin-left: 2px;line-height:200%;}
        .bizmail_loginpanel .bizmail_SubmitArea{padding-top:10px;margin-left:0px;clear:both;}
        .bizmail_loginpanel .bizmail_SubmitArea a{font-size:12px;margin-left:5px;}
        .bizmail_loginpanel select{width:90px;height:25px;margin:0 2px;}
      </style>
      <script type="text/javascript" src="http://exmail.qq.com/zh_CN/htmledition/js_biz/outerlogin.js"  charset="gb18030"></script>
      <script type="text/javascript">
        writeLoginPanel({domainlist:"hsacm.cn;hsacm.com", mode:"vertical"});
      </script>
    </div> -->
    <!-- 邮箱登录模块 end -->

  </div>

</div>
<?php require_once("footer.php") ?>

<script type="text/javascript">
  $('div.link').click(function(){
    window.open('http://www.pixiv.net/member_illust.php?mode=medium&illust_id=13212258');
    //window.location.href="http://pixiv.net/member.php?id=430651";
  });
</script>
<!-- <script type="text/javascript" src="charts/echarts.min.js"></script> -->
<script src="//cdn.bootcss.com/echarts/3.2.3/echarts.min.js"></script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var submission_chart = echarts.init(document.getElementById('submission_chart'));
    submission_chart.showLoading();
</script>


<!--get recent submission json START-->
<?php
$tot_days=20;
$series_data="";
$xAxis_data="";
for($i=$tot_days-1 ; $i>=0 ; --$i){
  $sql="SELECT count(*) FROM solution WHERE in_date<=date_add(date(NOW()), interval -$i+1 day) AND in_date>date_add(date(NOW()), interval -$i day)";
  $res=mysql_query($sql);
  $cnt=mysql_fetch_array($res)[0];
  $series_data.="$cnt,";
  if($i==0)$xAxis_data.="'today',";
  else if($i==1) $xAxis_data.="'yesterday',";
  else $xAxis_data.="'".(-$i+1)." days',";
}
?>
<!--get recent submission json END-->


<script type="text/javascript">
$(document).ready(function(){
    // 指定图表的配置项和数据
    var option = {
        color: ['#3398DB'],
        title: {
            text: 'Submissions In Recent Days',
            padding: 15
        },
        tooltip: {},
        legend: {
            show: false,
            data:['submissions']
        },
        xAxis: {
            data: [<?php echo $xAxis_data; ?>],
        },
        yAxis: {},
        series: [{
            name: 'submissions',
            type: 'bar',
            data: [<?php echo $series_data; ?>]
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    submission_chart.setOption(option);
    submission_chart.hideLoading();
});
$(window).resize(function(){
  submission_chart.resize();
});
</script>