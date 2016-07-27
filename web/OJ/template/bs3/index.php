<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
 	  <script language="javascript" type="text/javascript" src="jquery-1.8.2.min.js"></script>
    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
      <?php include("template/$OJ_TEMPLATE/nav.php");?>	    
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <p>
          <center><div id=submission style="width:600px;height:300px" ></div></center>
        </p>
	      <?php echo $view_news?>
      </div>
    </div> <!-- /container -->


    <!-- mail login model start -->
    <script type="text/javascript">
    	$(function(){
    		$(window).scroll(function(){
    			console.log(1);
    			var scrollTop = document.body.scrollTop || document.documentElement.scrollTop || 0;
    			$(".christmas_ad").stop();
    			var scrollTop2 = (scrollTop+160) - $(".christmas_ad").position().top;
    			if(scrollTop> 55){
    				$(".christmas_ad:not(:animated)").animate({top:"+="+scrollTop2+"px"},1000);
    			}else{
    				$(".christmas_ad").css("top",200+"px");
    			}
    		})
    	}) 
    </script>
    <div class="christmas_ad" style="width:150px; height:220px; position:absolute; top:200px; right:60px;">
      <style>
        .bizmail_loginpanel{font-size:12px;width:300px;height:auto;border:1px solid #cccccc;background:#ffffff;}
        .bizmail_LoginBox{padding:10px 15px;}
        .bizmail_loginpanel h3{padding-bottom:5px;margin:0 0 5px 0;border-bottom:1px solid #cccccc;font-size:14px;}
        .bizmail_loginpanel form{margin:0;padding:0;}
        .bizmail_loginpanel input.text{font-size:12px;width:100px;height:20px;margin:0 2px;border:1px solid #C3C3C3;border-color:#7C7C7C #C3C3C3 #C3C3C3 #9A9A9A;}
        .bizmail_loginpanel .bizmail_column{height:28px;}
        .bizmail_loginpanel .bizmail_column label{display:block;float:left;width:30px;height:24px;line-height:24px;font-size:12px;}
        .bizmail_loginpanel .bizmail_column .bizmail_inputArea{float:left;width:240px;}
        .bizmail_loginpanel .bizmail_column span{font-size:12px;word-wrap:break-word;margin-left: 2px;line-height:200%;}
        .bizmail_loginpanel .bizmail_SubmitArea{margin-left:30px;clear:both;}
        .bizmail_loginpanel .bizmail_SubmitArea a{font-size:12px;margin-left:5px;}
        .bizmail_loginpanel select{width:110px;height:20px;margin:0 2px;}
      </style>
      <script type="text/javascript" src="http://exmail.qq.com/zh_CN/htmledition/js_biz/outerlogin.js"  charset="gb18030"></script>
      <script type="text/javascript">
        writeLoginPanel({domainlist:"hsacm.cn", mode:"vertical"});
      </script>
    </div>
    <!-- mail login model end -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>	    
 <script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>

<script type="text/javascript">
$(function () {
var d1 = [];
var d2 = [];
<?php
foreach($chart_data_all as $k=>$d){
?>
d1.push([<?php echo $k?>, <?php echo $d?>]);
<?php }?>
<?php
foreach($chart_data_ac as $k=>$d){
?>
d2.push([<?php echo $k?>, <?php echo $d?>]);
<?php }?>
//var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];
// a null signifies separate line segments
var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
$.plot($("#submission"), [
{label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
{label:"<?php echo $MSG_AC?>",data:d2,bars:{show:true}} ],{
grid: {
backgroundColor: { colors: ["#fff", "#eee"] }
},
xaxis: {
mode: "time",
max:(new Date()).getTime(),
min:(new Date()).getTime()-100*24*3600*1000
}
});
});
//alert((new Date()).getTime());
</script>

  </body>
</html>
