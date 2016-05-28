<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

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
	<form action="modify.php" method="post">
<br><br>
<center><table>
<tr><td colspan=2 height=40 width=500>&nbsp;&nbsp;&nbsp;Update Information</tr>
<tr><td width=25%>User ID:
<td width=75%><?php echo $_SESSION['user_id']?>
<?php require_once('./include/set_post_key.php');?>
</tr>
<tr><td>Nick Name:
<td><input name="nick" size=50 type=text value="<?php echo htmlspecialchars($row->nick)?>" >
</tr>
<tr><td>Old Password:
<td><input name="opassword" size=20 type=password>
</tr>
<tr><td>New Password:
<td><input name="npassword" size=20 type=password>
</tr>
<tr><td>Repeat New Password::
<td><input name="rptpassword" size=20 type=password>
</tr>
<tr><td>School:
<td><input name="school" size=30 type=text value="<?php echo htmlspecialchars($row->school)?>" >
</tr>
<tr><td>Class:
<td>
<select name="class">
<option value="null">其它</option>
<option value="cs151" <?php if ($row->class=="cs151") echo selected; ?> >计算机151</option>
<option value="cs152" <?php if ($row->class=="cs152") echo selected; ?>>计算机152</option>
<option value="cs153" <?php if ($row->class=="cs153") echo selected; ?>>计算机153</option>
<option value="cs154" <?php if ($row->class=="cs154") echo selected; ?>>计算机154</option>
<option value="se151" <?php if ($row->class=="se151") echo selected; ?>>软件工程151</option>
<option value="se152" <?php if ($row->class=="se152") echo selected; ?>>软件工程152</option>
<option value="iot151" <?php if ($row->class=="iot151") echo selected; ?>>物联网151</option>
<option value="iot152" <?php if ($row->class=="iot152") echo selected; ?>>物联网152</option>
<option value="cs141" <?php if ($row->class=="cs141") echo selected; ?>>计算机141</option>
<option value="cs142" <?php if ($row->class=="cs142") echo selected; ?>>计算机142</option>
<option value="cs143" <?php if ($row->class=="cs143") echo selected; ?>>计算机143</option>
<option value="cs144" <?php if ($row->class=="cs144") echo selected; ?>>计算机144</option>
<option value="se141" <?php if ($row->class=="se141") echo selected; ?>>软件工程141</option>
<option value="se142" <?php if ($row->class=="se142") echo selected; ?>>软件工程142</option>
<option value="iot141" <?php if ($row->class=="iot141") echo selected; ?>>物联网141</option>
</select>
</tr>
<tr><td>Real Name:
<td><input name="real_name" size=30 type=text value="<?php echo htmlspecialchars($row->real_name)?>" >
</tr>
<tr><td>Email:
<td><input name="email" size=30 type=text value="<?php echo htmlspecialchars($row->email)?>" >
</tr>
<tr><td>
<td><input value="Submit" name="submit" type="submit">
&nbsp; &nbsp;
<input value="Reset" name="reset" type="reset">
</tr>
</table></center>
<br>
<a href=export_ac_code.php>Download All AC Source</a>
<br>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php");?>	    
  </body>
</html>
