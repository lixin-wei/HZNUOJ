<?php 

	$dir=basename(getcwd());
	if($dir=="discuss3") $path_fix="../";
	else $path_fix="";
?>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.css">

<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>bootstrap-theme.min.css">

<link rel="stylesheet" href="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>local.css">



