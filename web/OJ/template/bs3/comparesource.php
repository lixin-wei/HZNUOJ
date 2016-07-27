<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>
  </head>
<body>
<div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php");?>	    
    <div class="jumbotron">

<div id=main>
	
<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 
<script src='highlight/scripts/shCore.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>

<script language='javascript'> 
SyntaxHighlighter.config.bloggerMode = false;
SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>

<!-- Requires jQuery -->
  <script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script>

	  
	<!-- Requires CodeMirror 2.16 -->
	<script type="text/javascript" src="mergely/codemirror.js"></script>
	<link type="text/css" rel="stylesheet" href="mergely/codemirror.css" />
	
	<!-- Requires Mergely -->
	<script type="text/javascript" src="mergely/mergely.js"></script>
	<link type="text/css" rel="stylesheet" href="mergely/mergely.css" />
	
	<script type="text/javascript">

        $(document).ready(function () {
			$('#compare').mergely({
				cmsettings: { readOnly: false, lineWrapping: true }
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['left'])?>',
				success: function (response) {
					$('#compare').mergely('lhs', response);
				}
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['right'])?>',
				success: function (response) {
					$('#compare').mergely('rhs', response);
				}
			});
		});
	</script>

<div id="mergely-resizer">
		<div id="compare" >
		</div>
</div>


<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
