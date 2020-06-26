<?php
/**
 * This file is created
 * by lixun516@qq.com
 * @2020.06.20
 **/
?>
<?php
require_once("../include/db_info.inc.php");
if(isset($_GET['getNodes'])) {
    $filter="0";
    if($_POST['id']!="" && $_POST['isProblem']=="false"){
        $filter=$mysqli->real_escape_string($_POST['id']);
    }
    $arr=array();
    //$notDrag="";
    //if($filter=="0") $notDrag=", drag:false";//顶级目标不能拖拽

    if(!isset($_GET['admin'])) {//前台页面访问时要标记用户是否AC题目
        /* 获取当前用户提交过的题目 start */
        $sub_arr=Array();
        if (isset($_SESSION['user_id'])) {
            $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."' GROUP BY `problem_id`";
            $result=@$mysqli->query($sql) or die($mysqli->error);
            while ($row=$result->fetch_array())
                $sub_arr[$row[0]]=true;
        }
        /* 获取当前用户提交过的题目 end */
        /* 获取当前用户已AC的题目 start */
        $acc_arr=Array();
        if (isset($_SESSION['user_id'])) {
            $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."' AND `result`=4 GROUP BY `problem_id`";
            $result=@$mysqli->query($sql) or die($mysqli->error);
            while ($row=$result->fetch_array())
                $acc_arr[$row[0]]=true;
        }
        /* 获取当前用户已AC的题目 end */
    }
    $sql="SELECT * FROM `course` WHERE parent_id='$filter' order by `order`, `id`";
    $result=$mysqli->query($sql) or die($mysqli->error);
    while ($row = $result->fetch_object()){
        $str="{ \"id\":\"$row->id\"";
        if($row->isProblem){
            if (isset($sub_arr[$row->section])) {
                $icon=",iconSkin:".(isset($acc_arr[$row->section])?"'accept'":"'wrong'");
            } else $icon="";
            $sql="SELECT `title` FROM `problem` WHERE `problem_id`='$row->section'";
            if($p = $mysqli->query($sql)->fetch_object()) $title=$p->title; else $title="null";
            $str.=",\"name\":\"【".$row->section."】$title\", \"url\":\"/problem.php?id=".$row->section."\" $icon";
            $str.=", \"isProblem\":true, \"dropInner\":false}";
        } else {
            $str.=", \"name\":\"$row->section\", \"isProblem\":false, \"isParent\":true}";
        }
        array_push($arr,$str);
    }
    echo "[".implode(",", $arr)."]";
}

if(isset($_GET['delNodes'])) {
    if (!HAS_PRI("edit_contest")) {
        exit(1);
    }
    $id=$mysqli->real_escape_string($_POST['id']);
    $parentID=$mysqli->real_escape_string($_POST['parentID']);
    $isProblem=$mysqli->real_escape_string($_POST['isProblem']);
    if($isProblem=="true"){ //删除文件夹节点$parentID下的单个题目
        $mysqli->query("DELETE FROM `course` WHERE `parent_id`='$parentID' AND `id`='$id'");
        echo "{\"delNode\": $mysqli->affected_rows}";
    } else { //删除文件夹节点$id及其各个子节点以及相关的题目
        $arr=array();
        array_push($arr,$id);
        $i=0;
        while($i<count($arr)){//取得文件夹节点$id的所有子节点及其自身的id列表
            $sql="SELECT `id` FROM `course` WHERE `parent_id`='$arr[$i]'";
            $result=$mysqli->query($sql) or die($mysqli->error);
            while ($row = $result->fetch_object()){
                if(!in_array($row->id, $arr)) array_push($arr, $row->id);
            }
            $i++;
        }
        $sql="DELETE FROM `course` WHERE `id` IN (".implode(",", $arr).")";
        $mysqli->query($sql) or die($mysqli->error);
        echo "{\"delNode\": $mysqli->affected_rows}";
    }
}
function insertNode($target,$source,$section,$isProblem){
    //$target  新节点作为$target的子节点插入
    //$source  待复制子树的根节点，需要把整个子树复制一份
    //若不是复制操作，按照$section,$isProblem的描述插入一个新节点
    //返回插入子树的根节点id,返回0表示无插入
    global $mysqli;
    if($source!=""){//复制模式
        $sql="SELECT * FROM `course` WHERE `id`='$source'";
        $result=$mysqli->query($sql) or die($mysqli->error);
        if($row = $result->fetch_object()){
            $sql="INSERT INTO `course`(`section`,`order`,`parent_id`,`isProblem`) VALUES('$row->section','$row->order','$target','$row->isProblem')";
            $mysqli->query($sql);
            if($mysqli->affected_rows>0){
                $target=$mysqli->insert_id;
                $sql="SELECT `id` FROM `course` WHERE `parent_id`='$source'";
                $result=$mysqli->query($sql) or die($mysqli->error);
                while ($row = $result->fetch_object()){
                    insertNode($target,$row->id,"","");
                }
            }
        } else $target=0;
    } else { //新建模式
        $section=trim($section);
        $sql="INSERT INTO `course`(`section`,`parent_id`, `isProblem`) VALUES('$section','$target','$isProblem')";
        $mysqli->query($sql);
        if($mysqli->affected_rows>0){
            $target=$mysqli->insert_id;
        } else $target=0;
    }
    return $target;
}

if(isset($_GET['moveNodes'])) {
    if (!HAS_PRI("edit_contest")) {
        exit(1);
    }
    $id=$mysqli->real_escape_string($_POST['id']);
    $parentID=$mysqli->real_escape_string($_POST['parentID']);
    $moveType=$mysqli->real_escape_string($_POST['moveType']);
    $isCopy=$mysqli->real_escape_string($_POST['isCopy']);
    $target=($moveType=="inner") ? $mysqli->real_escape_string($_POST['target']) : $mysqli->real_escape_string($_POST['tParentID']);//移动节点要考虑moveType
    if($isCopy=="true"){ //复制节点
        $newNodeID=insertNode($target,$id,"","");
        if($newNodeID!=0){
            echo "{\"moveNode\": 1, \"id\": \"$newNodeID\"}";
        } else echo "{\"moveNode\": 0, \"id\":0}";
    } else { //移动节点
        $sql="UPDATE `course` SET `parent_id`='$target' WHERE `parent_id`='$parentID' AND `id`='$id'";
        $mysqli->query($sql) or die($mysqli->error);
        echo "{\"moveNode\": $mysqli->affected_rows}";
    }
}

if(isset($_GET['editNode'])) {
    //新增节点可以修改为题目或者分类,非新增节点类型不能改变
    if (!HAS_PRI("edit_contest")) {
        exit(1);
    }
    $parentID=$mysqli->real_escape_string($_POST['parentID']);
    $sql="SELECT count(`id`) FROM `course` WHERE `id`='$parentID'";
    if(intval($mysqli->query($sql)->fetch_array()[0]) <= 0 && $parentID != 0) exit(1);
    $newSection=$mysqli->real_escape_string(trim($_POST['newName']));
    if($newSection==$mysqli->real_escape_string($_POST['oldName'])) {//前后名称不变则不做任何数据库操作
        echo "{\"editNode\": 0, \"code\":0}";
        exit(0);
    }
    $id=$mysqli->real_escape_string($_POST['id']);
    $isProblem=$mysqli->real_escape_string($_POST['isProblem']);
    $isNewNode=$mysqli->real_escape_string($_POST['isNewNode']);
    $newSections=array();
    if($isNewNode=="true"){//新增节点的插入,查不到题号就当章节名称处理
        if(strpos($newSection,",")==false) $singleInsert=true; else $singleInsert=false;
        if($singleInsert){ //单条插入
            if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $newSection)) {
                echo "{\"editNode\": -1, \"code\":4}";
                exit(0);
            } else $newSections[0]=$newSection;
        } else {//多条批量导入
            if (!preg_match("/^[\u{4e00}-\u{9fa5}_,+a-zA-Z0-9]{1,200}$/", $newSection)) {
                echo "{\"editNode\": -2, \"code\":4}";
                exit(0);
            } else { 
                $newSections=explode(",",$newSection);
            }
        }
        foreach($newSections as $newSection){
            $sql="SELECT count(`problem_id`) FROM `problem` WHERE `problem_id`='$newSection'"; 
            if(intval($mysqli->query($sql)->fetch_array()[0]) > 0){
                $newNodeID=insertNode($parentID,"",$newSection,1);
            } else {
                $newNodeID=insertNode($parentID,"",$newSection,0);
            }
        }
        if($singleInsert){
            $str="{\"editNode\": 0, \"code\":0}";
            if($newNodeID>0){
                $sql="SELECT * FROM `course` WHERE `id`='$newNodeID'";
                $result=$mysqli->query($sql) or die($mysqli->error);
                if ($row = $result->fetch_object()){
                    $str="{\"editNode\":1, \"code\":2, \"id\":\"$row->id\"";
                    if($row->isProblem){
                        $sql="SELECT `title` FROM `problem` WHERE `problem_id`='$row->section'";
                        if($p = $mysqli->query($sql)->fetch_object()) $title=$p->title; else $title="null";
                        $str.=",\"name\":\"【".$row->section."】$title\", \"isProblem\":true}";
                    } else {
                        $str.=", \"name\":\"$row->section\", \"isProblem\":false}";
                    }
                } 
            }
            echo $str;
        } else echo "{\"editNode\": 0, \"code\":5}";
    } else { //老节点的修改，类型不能变
        if (!preg_match("/^[\u{4e00}-\u{9fa5}_+a-zA-Z0-9]{1,60}$/", $newSection)) {
            echo "{\"editNode\": -3, \"code\":4}";
            exit(0);
        }
        if($isProblem=="true"){ //修改题目的题号
            $sql="SELECT * FROM `problem` WHERE `problem_id`='$newSection'";
            $result=$mysqli->query($sql) or die($mysqli->error);
            if ($row = $result->fetch_object()){
                $sql="UPDATE `course` SET `section`='$newSection' WHERE `isProblem`=1 AND `id`='$id' AND `parent_id`='$parentID'";
                $mysqli->query($sql) or die($mysqli->error);
                if($mysqli->affected_rows>0){
                    $str="{\"editNode\":1, \"code\":3, \"name\":\"【".$row->problem_id."】$row->title\"}";
                } else $str="{\"editNode\": 0, \"code\":0}";
                echo $str;
            } else echo "{\"editNode\": -1, \"code\":1}";//查不到对应题号$newSection
        } else { //修改章节名称
            $sql="UPDATE `course` SET `section`='$newSection' WHERE `isProblem`=0 AND `id`='$id' AND `parent_id`='$parentID'";
            $mysqli->query($sql) or die($mysqli->error);
            echo "{\"editNode\": $mysqli->affected_rows, \"code\":0}";
        }
    }
}

if(isset($_GET['storeOrder'])) {
    if (!HAS_PRI("edit_contest")) {
        exit(1);
    }
    $parentID=$mysqli->real_escape_string($_POST['parentID']);
    $i=0;
    foreach($_POST['children'] as $child){
        $sql="UPDATE `course` SET `order`=$i WHERE `id`='".$child["id"]."' AND `parent_id`='$parentID'";
        $mysqli->query($sql) or die($mysqli->error);
        $i++;
    }
    echo $i;
}
?>
