<?php
/**
 * This file is created
 * by lixun516@qq.com
 * @2020.06.20
 **/
?>

<?php require_once("admin-header.php");?>
<?php
if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
}
if(isset($_POST['submit']) && $_POST['course']!=""){
    require_once("../include/check_post_key.php");
    $course=$mysqli->real_escape_string($_POST['course']);
    $sql="SELECT count(`section`) FROM `course` WHERE parent_id=0 AND `section`='$course'";
    $err_str = "";
    if($mysqli->query($sql)->fetch_array()[0]>0){
        $err_str = $err_str . "输入的{$MSG_New}{$MSG_COURSE}有重名，请重新输入！\\n";
    } else if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $course)) { //{1,60} 60=3*20，一个utf-8汉字占3字节
        $err_str = $err_str . "输入的{$MSG_New}{$MSG_COURSE}限20个以内汉字、字母、数字、下划线及加号！\\n";
    }
    if ($err_str != "") {
        print "<script language='javascript'>\n";
        echo "alert('";
        echo $err_str;
        print "');\n history.go(-1);\n</script>";
        exit(0);
    }
    $sql = "INSERT INTO `course`(`section`) VALUES ('" . $course. "')";
    $mysqli->query($sql);
}
?>
<title><?php echo $html_title.$MSG_CourseSet?></title>
<h1><?php echo $MSG_CourseSet?></h1>
<h4><?php echo $MSG_HELP_CourseSet ?></h4>
<hr>
<form class="form-inline" method="post">
  <p><?php echo $MSG_ADD.$MSG_New.$MSG_COURSE?> : <input class="form-control" type="text" pattern="^[\u4e00-\u9fa5_+a-zA-Z0-9]{1,20}$" placeholder="限20个以内汉字、字母、数字、下划线及加号" size="50" name="course" required>&nbsp;&nbsp;&nbsp;&nbsp;
  <?php require_once("../include/set_post_key.php");?>
  <input class="btn btn-default" name="submit" type="submit" value='<?php echo $MSG_SUBMIT ?>'>
</form>
<div>
    <ul id="courseTree" class="ztree"></ul>
</div>
<?php 
  require_once("admin-footer.php")
?>
<link rel="stylesheet" href="../plugins/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="../plugins/zTree/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="../plugins/zTree/js/jquery.ztree.exedit.min.js"></script>
<script type="text/javascript">
    var addTitle = "<?php echo $MSG_ADD ?>";
    var setting = {
      view: {
        fontCss: setFontCss,
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
        selectedMulti: false
      },
      edit: {
        drag: {
          autoExpandTrigger: true,
          prev: dropPrev,
          inner: dropInner,
          next: dropNext
        },
        enable: true,
        editNameSelectAll: true,
        showRemoveBtn: showRemoveBtn,
        showRenameBtn: showRenameBtn,
        removeTitle: "<?php echo $MSG_DEL ?>",
        renameTitle: "<?php echo $MSG_EDIT ?>"
      },
      callback: {
        beforeDrag: beforeDrag,
        beforeDrop: beforeDrop,
        onDrop: onDrop,
        beforeRemove: beforeRemove,
        onRemove: onRemove,
        beforeRename: beforeRename,
        onRename: onRename
      },
      async: {
        enable: true,
        url:"./zTreeAjax.php?getNodes&admin",
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
    function showRemoveBtn(treeId, treeNode) {
      if(treeNode.isNewNode) return false;//新建节点不提供添加按钮
      return true;
    }
    function showRenameBtn(treeId, treeNode) {
      return true;
    }
    function dropPrev(treeId, nodes, targetNode) {
      var pNode = targetNode.getParentNode();
      if (pNode && pNode.dropInner === false) {
        return false;
      } else {
        for (var i=0,l=curDragNodes.length; i<l; i++) {
          var curPNode = curDragNodes[i].getParentNode();
          if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
            return false;
          }
        }
      }
      return true;
    }
    function dropInner(treeId, nodes, targetNode) {
      if (targetNode && targetNode.dropInner === false) {
        return false;
      } else {
        for (var i=0,l=curDragNodes.length; i<l; i++) {
          if (!targetNode && curDragNodes[i].dropRoot === false) {
            return false;
          } else if (curDragNodes[i].parentTId && curDragNodes[i].getParentNode() !== targetNode && curDragNodes[i].getParentNode().childOuter === false) {
            return false;
          }
        }
      }
      return true;
    }
    function dropNext(treeId, nodes, targetNode) {
      var pNode = targetNode.getParentNode();
      if (pNode && pNode.dropInner === false) {
        return false;
      } else {
        for (var i=0,l=curDragNodes.length; i<l; i++) {
          var curPNode = curDragNodes[i].getParentNode();
          if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
            return false;
          }
        }
      }
      return true;
    }
    
    function beforeDrag(treeId, treeNodes) {
      for (var i=0,l=treeNodes.length; i<l; i++) {
        if (treeNodes[i].drag === false) {
          curDragNodes = null;
          return false;
        } else if (treeNodes[i].parentTId && treeNodes[i].getParentNode().childDrag === false) {
          curDragNodes = null;
          return false;
        }
      }
      curDragNodes = treeNodes;
      return true;
    }

    var emptyNode;
    var new_ID=0;
    var new_Name="";
    var new_IsProblem="";
    function storeOrder(parentID, children){
      //节点顺序持久化 start, 支持文件夹节点和题目节点混排的顺序持久化
      $.ajax({
          type: "POST",
          url: "./zTreeAjax.php?storeOrder",
          data: {"parentID":parentID,"children":children},
          dataType: "text",
          success: function(res){
            // console.log(res+" storeOrder success");
          }
        });
    }
    function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
      for (var i = 0; i < treeNodes.length; i++) {
          var treeNode = treeNodes[i];
          var parentNode = treeNode.getParentNode();
          if (parentNode != null && (parentNode.children == null || parentNode.children.filter(function (s) { return s.name != treeNode.name; }).length == 0)) {
              emptyNode = parentNode;
              break;
          }
      }
      parentNode = treeNodes[0].getParentNode();//被拖拽节点的父节点，用于节点的移动
      tParentNode = targetNode.getParentNode(); //目标节点的父节点，用于节点的移动和复制
      var pid = (parentNode == null) ? 0 : parentNode.id;
      var tpid = (tParentNode == null) ? 0 : tParentNode.id;
      var result = false;
      // console.log("---------------");
      // console.log("pid="+pid+" targetNode="+targetNode.name+" moveType="+moveType+" isCopy="+isCopy);
      // console.log("---------------");
      $.ajax({
        type: "POST",
        async: false, //设置为同步模式，不然最后的result不能ajax结果反馈出来就先return了
        url: "./zTreeAjax.php?moveNodes",
        data: {"id":treeNodes[0].id, "parentID":pid, "moveType":moveType , "isCopy":isCopy, "target":targetNode.id, "tParentID":tpid},
        dataType: "json",
        success: function(res){
          // console.log("moveNode :"+res.moveNode);
          if(isCopy){
            new_ID=res.id;
            // console.log("Copy newNode id="+res.id);
            if(new_ID==0){
              result=false;//复制节点没有插入成功
            } else result = (res.moveNode>0);
          } else result = (res.moveNode>=0);
          // console.log("moveNode result="+result);
        },
        error: function(event){
          // console.log(event);
          result = false;
          alert("Error!");
        }
      });
      return result;
    }
    function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      var pid=0;
      var pchild;
      if (emptyNode != null) {//修改空节点图标为文件夹样式
          emptyNode.isParent = true;
          zTree.updateNode(emptyNode);
          emptyNode = null;
      }
      if(isCopy&&new_ID!=0){ //复制模式下加载新增节点写入数据库后的id,
        treeNodes[0].id=new_ID;
        //treeNodes[0].name+=" copy";
        zTree.updateNode(treeNodes[0]);
        // console.log("isCopy:"+isCopy+" updateNode");
      }

      //节点顺序持久化 start
      if(moveType=="inner"){
        pid=targetNode.id;
        pchild=targetNode.children;
      } else {
        var p=targetNode.getParentNode();
        if(p!=null){
          pid=p.id;
          pchild=p.children;
        } else {
          pid=0;
          pchild=zTree.getNodes();
        }
      }
      storeOrder(pid, pchild);
      //节点顺序持久化 end
    }
    function beforeRemove(treeId, treeNode) {
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      var parentNode = treeNode.getParentNode();
      var pid = (parentNode == null) ? 0 : parentNode.id;
      var result = false;
      zTree.selectNode(treeNode);
      delconfirm=confirm("确认删除节点【" + treeNode.name + "】吗？");
      if(delconfirm){
        // console.log("id:"+treeNode.id+ " parentID:"+pid+ " isProblem:"+treeNode.isProblem);
        $.ajax({
          type: "POST",
          async: false, //设置为同步模式，不然最后的result不等ajax结果反馈出来就先return了
          url: "./zTreeAjax.php?delNodes",
          data: {"id":treeNode.id, "parentID":pid, "isProblem":treeNode.isProblem},
          dataType: "json",
          success: function(res){
            // console.log("delNode :"+res.delNode);
            result = (res.delNode>0);
            // console.log("delNode result="+result);
          },
          error: function(event){
            // console.log(event);
            result = false;
            alert("Error!");
          }
        });
      }
      // console.log(" last result="+result);
      return result;
    }
    function onRemove(event, treeId, treeNode) {
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      var parentNode = treeNode.getParentNode();
      if (parentNode != null && (parentNode.children == null || parentNode.children.length == 0)) {
         //修改父节点图标为文件夹样式
         parentNode.isParent = true;
         zTree.updateNode(parentNode); 
      }
    }

    var newCount = 1;
    function addHoverDom(treeId, treeNode) {
      if(treeNode.isProblem || treeNode.isNewNode) return false;//题目节点和新建节点不提供添加按钮
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      var sObj = $("#" + treeNode.tId + "_span");
      if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
      var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
        + "' title='"+ addTitle +"' onfocus='this.blur();'></span>";
      sObj.after(addStr);
      var btn = $("#addBtn_"+treeNode.tId);
      if (btn) btn.bind("click", function(){
        zTree.addNodes(treeNode, {id:("n" + newCount++), pId:treeNode.id, name:"请编辑本处输入分类名称或题目编号,多个项目以英文逗号分隔可以批量导入。",isNewNode:true,dropInner:false});
        return false;
      });
    };
    function removeHoverDom(treeId, treeNode) {
      $("#addBtn_"+treeNode.tId).unbind().remove();
    };

    function beforeRename(treeId, treeNode, newName, isCancel) {
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      var parentNode = treeNode.getParentNode();
      var pid = (parentNode == null) ? 0 : parentNode.id;
      var result = false;
      // console.log("---------------");
      // console.log("id="+treeNode.id+" parentID="+pid+" isProblem="+treeNode.isProblem+" isNewNode="+treeNode.isNewNode);
      // console.log("newName="+newName+" oldName="+treeNode.name);
      // console.log("---------------");
      zTree.selectNode(treeNode);
      $.ajax({
        type: "POST",
        async: false, //设置为同步模式，不然最后的result不等ajax结果反馈出来就先return了
        url: "./zTreeAjax.php?editNode",
        data: {"id":treeNode.id, "parentID":pid, "isProblem":treeNode.isProblem, "isNewNode":treeNode.isNewNode,"oldName":treeNode.name, "newName":newName},
        dataType: "json",
        success: function(res){
          // console.log("editNode :"+res.editNode);
          // console.log("code :"+res.code);
          result = (res.editNode>=0);
          // console.log("editNode result="+result);
          // console.log("Node id="+res.id+" name="+res.name+" isProblem="+res.isProblem+" isNewNode="+treeNode.isNewNode);

          switch(res.code){
            case 1:
              alert(newName+"：No such problem! / 无此题号！");
              zTree.cancelEditName();
              break
            case 2://更新单个新加节点相关数据
              //if(treeNode.isNewNode){
                new_ID=res.id;
                new_Name=res.name;
                new_IsProblem=res.isProblem;
              //}
              break;
            case 3://更新老题目节点的name属性
              new_Name=res.name;
              break;
            case 4:
              alert("输入的分类名称限20个以内汉字、字母、数字、下划线及加号！");
              zTree.cancelEditName();
              break;
            case 5://批量插入新节点，要整体刷新其父节点
              new_ID=-1;
              break;
          }
        },
        error: function(event){
          // console.log(event);
          result = false;
          alert("Error!");
        }
      });
      // console.log(" last result="+result);
      return result;
    };
    function onRename(event, treeId, treeNode, isCancel) {
      var zTree = $.fn.zTree.getZTreeObj(treeId);
      if(new_ID>0){//更新新插入节点的各项参数
        treeNode.isNewNode=false;
        treeNode.id=new_ID;
        treeNode.name=new_Name;
        treeNode.isProblem=new_IsProblem;
        if(treeNode.isProblem){
          treeNode.url="/problem.php?id="+treeNode.id;
          treeNode.dropInner=false;
        } else {
          treeNode.isParent=true;
          treeNode.dropInner=true;
        }
        zTree.updateNode(treeNode);
        // console.log("update New Node");
        target=treeNode.getParentNode();
        storeOrder(target.id, target.children);
      } else if(new_ID==-1){ //批量导入新节点，要刷新父节点
        target=treeNode.getParentNode();
        storeOrder(target.id, target.children);
        zTree.reAsyncChildNodes(treeNode.getParentNode(), "refresh");;
        // console.log("批量导入");
      } else if(new_Name!="") {//更新编辑后的老题目节点名称
        treeNode.name=new_Name;
        zTree.updateNode(treeNode);
        // console.log("update old Problem Node");
      }
      new_ID=0;
      new_Name="";
      new_IsProblem="";
    };

    $(document).ready(function(){
      $.fn.zTree.init($("#courseTree"), setting);
    });
</script>
