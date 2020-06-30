<?php
/**
 * This file is created
 * by lixun516@qq.com
 * @2020.06.20
 **/
?><?php
$title = $MSG_COURSE.$MSG_Assist;
require_once("header.php");
?>
<div class="am-container">
    <div class="am-avg-md-1" style="margin-top: 40px;">
        <section class="am-panel am-panel-default">
            <header class="am-panel-hd">
                <?php echo $MSG_COURSE.$MSG_Assist ?>
            </header>
            <main class="am-panel-bd">
                <div><ul id="courseTree" class="ztree"></ul></div>
            </main>
        </section>
    </div>
</div> <!-- /container -->
<?php require_once("footer.php") ?>
<link rel="stylesheet" href="./plugins/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="./plugins/zTree/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript">
    var setting = {
      view: {
        fontCss: setFontCss
      },
      async: {
        enable: true,
        url:"./admin/zTreeAjax.php?getNodes",
        autoParam:["id","isProblem"],
        otherParam:{"otherParam":"zTreeAsyncTest"},
        dataFilter: filter
      }
    };

    function setFontCss(treeId, treeNode) {
      var obj={};
      if(treeNode.level == 0) obj={color:"red","font-size":"15px"};
      if(treeNode.isParent) obj["text-decoration"]="none";
      return obj;
    };

    function filter(treeId, parentNode, childNodes) {
      if (!childNodes) return null;
      for (var i=0, l=childNodes.length; i<l; i++) {
        childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
      }
      return childNodes;
    }

    $(document).ready(function(){
      $.fn.zTree.init($("#courseTree"), setting);
    });
</script>
