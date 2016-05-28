<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $view_title?></title>
        <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
        <?php require_once("oj-header.php");?>
<div id=main>
        <form action=login.php method=post>
        <center>
        <table width=480 algin=center>
        <tr><td width=240><?php echo $MSG_USER_ID?>:<td width=200><input style="height:24px" name="user_id" type="text" size=20></tr>
        <tr><td><?php echo $MSG_PASSWORD?>:<td><input name="password" type="password" size=20 style="height:24px"></tr>
        <?php if($OJ_VCODE){?>
                <tr><td><?php echo $MSG_VCODE?>:</td>
                        <td><input name="vcode" size=4 type=text style="height:24px"><img alt="click to change" src=vcode.php onclick="this.src='vcode.php?'+Math.random()">*</td>
                </tr>
                <?php }?>
        <tr><td colspan=3><input name="submit" type="submit" size=10 value="Submit">
        <a href="lostpassword.php">Lost Password</a>
</tr>
        </table>
        <center>
</form>

<div id=foot>
        <?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>

