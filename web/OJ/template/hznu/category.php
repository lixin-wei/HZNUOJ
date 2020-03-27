<?php
$title = $MSG_Source;
require_once("header.php");
?>
<div class="am-container">
    <div class="am-avg-md-1" style="margin-top: 40px;">
        <section class="am-panel am-panel-default">
            <header class="am-panel-hd">
                <?php echo $MSG_Source."(".$view_category[0].")" ?>
            </header>
            <main class="am-panel-bd">
                <?php echo $view_category[1] ?>
            </main>
        </section>
    </div>
</div> <!-- /container -->
<?php require_once("footer.php") ?>