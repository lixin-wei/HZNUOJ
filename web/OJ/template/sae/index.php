<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	<script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script>
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
</head>
<body>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>
	<center>
	<div id=submission style="width:600px;height:300px" ></div>
	</center>
	<?php echo $view_news?>
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
