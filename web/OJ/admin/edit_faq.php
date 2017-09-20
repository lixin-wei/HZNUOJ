<?php
/**
 * This file is modified
 * by yybird
 * @2016.05.24
 **/
?>

<?php
require_once ("admin-header.php");
if (!HAS_PRI("edit_news")) {
    echo "Permission denied!";
    exit(1);
}
?>
<?php
if (isset($_POST['content'])) {
    require_once("../include/check_post_key.php");
    $content = $_POST ['content'];
    if (get_magic_quotes_gpc ()) {
        $content = stripslashes ( $content );
    }
    $content=$mysqli->real_escape_string($content);
    
    $sql="UPDATE `faqs` set `content`='$content'";
    //echo $sql;
    $mysqli->query($sql) or die($mysqli->error);
    echo "<script type='text/javascript'>window.location.href='edit_faq.php';</script>";
    exit(0);
} else {
    $sql="SELECT * FROM `faqs` LIMIT 0,1";
    $result=$mysqli->query($sql);
    if ($result->num_rows!=1){
        $result->free();
        echo "No faqs!";
        exit(0);
    }
    $row=$result->fetch_array();
    $content=$row['content'];
    $result->free();
}
?>

<form class="form-inline" method=POST action='edit_faq.php'>
    <h1>Edit FAQ(using markdown)</h1><hr/>
    <p align=left>Content:<br>
        <textarea name=content style="width: 100%; height: 500px;"><?php echo htmlentities($content,ENT_QUOTES,"UTF-8")?></textarea>
    </p>
    <?php require_once("../include/set_post_key.php");?>
    <button type=submit class="btn btn-default">Submit</button>
</form>
<?php
require_once("admin-footer.php")
?>
