<?php
/**
 * This file is created
 * by lixun516@qq.com
 * @2020.05.11
 **/
?>

<?php require_once("admin-header.php"); ?>
<?php
if (!HAS_PRI("inner_function")) {
    require_once("error.php");
    exit(1);
}

if (isset($_POST['edit'])) {
    require_once("../include/check_post_key.php");
    $err_str = "";
    $id = $_POST['id'];
    $set_name_show = $mysqli->real_escape_string(trim($_POST['set_name_show']));
    if (!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{1,60}$/", $set_name_show)) {
        $err_str .= "输入的{$MSG_Alias}限20个以内的汉字、字母、数字或下划线 ！\\n";
    }
    $sql="SELECT COUNT(`index`) FROM `problemset` WHERE `set_name_show`='$set_name_show'";
    if($mysqli->query($sql)->fetch_array()[0]>0){
        $err_str .= "输入的{$MSG_Alias}有重名，请修改 ！\\n";
    }
    if ($err_str != "") {
        echo "<script language='javascript'>\n alert('" . $err_str . "');\n</script>";
    } else {
        $mysqli->query("UPDATE `problemset` SET `set_name_show`='$set_name_show' WHERE `index`='$id'");
        if ($mysqli->affected_rows == 1) echo "<script language=javascript>alert('修改成功！');</script>";
    }
} else if (isset($_POST['del'])) {
    //默认题库不能删除，不能删除下面有题目的题库
    $id = $_POST['id'];
    $sql = "SELECT s.*,p.`num` FROM `problemset` AS s LEFT JOIN (SELECT problemset,COUNT(`problem_id`) AS num FROM `problem` GROUP BY problemset) AS p ON s.`set_name`=p.`problemset` WHERE `index`='$id'";
    if ($row = $mysqli->query($sql)->fetch_object()) {
        if ($row->num == "") {
            //删除privilege_distribution权限表中相关字段 edit_xxxx_problem see_hidden_xxx_problem
            $sql = "ALTER TABLE `privilege_distribution` DROP COLUMN `edit_" . $row->set_name . "_problem`;";
            $mysqli->query($sql);
            $sql = "ALTER TABLE `privilege_distribution` DROP COLUMN `see_hidden_" . $row->set_name . "_problem`;";
            $mysqli->query($sql);

            $mysqli->query("DELETE FROM `problemset` WHERE `index`='$id' AND set_name<>'default'");
            if ($mysqli->affected_rows == 1) {
                echo "<script language=javascript>alert('删除成功！');</script>";
                $max_id=$mysqli->query("SELECT MAX(`index`) FROM `problemset`")->fetch_row()[0];
                $max_id++;
                $mysqli->query("ALTER TABLE `problemset` AUTO_INCREMENT=$max_id");
            }
        } else echo "<script language=javascript>alert(\"$MSG_HELP_PROBLEMSET2\");</script>";
    }
} else if (isset($_POST['add'])) {
    require_once("../include/check_post_key.php");
    $set_name = $mysqli->real_escape_string(trim($_POST['set_name']));
    $set_name_show = $mysqli->real_escape_string(trim($_POST['set_name_show']));
    $err_str = "";
    if (!preg_match("/^[a-zA-Z0-9]{0,20}$/", $set_name)) {
        $err_str .= "输入的{$MSG_Name}要求为20位以内字母或数字的字符串！\\n";
    }
    if (!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{1,60}$/", $set_name_show)) {
        $err_str .= "输入的{$MSG_Alias}限20个以内的汉字、字母、数字或下划线 ！\\n";
    }
    $sql="SELECT COUNT(`index`) FROM `problemset` WHERE `set_name`='$set_name' OR `set_name_show`='$set_name_show'";
    if($mysqli->query($sql)->fetch_array()[0]>0){
        $err_str .= "输入的{$MSG_Name}或{$MSG_Alias}有重名，请修改 ！\\n";
    }
    if ($err_str != "") {
        echo "<script language='javascript'>\n alert('" . $err_str . "');\n</script>";
    } else {
        //添加privilege_distribution权限表中相关字段 edit_xxxx_problem see_hidden_xxx_problem
        $sql = "ALTER TABLE `privilege_distribution` ADD COLUMN `edit_" . $set_name . "_problem` tinyint(4) NULL DEFAULT 0";
        $mysqli->query($sql);
        $sql = "ALTER TABLE `privilege_distribution` ADD COLUMN `see_hidden_" . $set_name . "_problem` tinyint(4) NULL DEFAULT 0";
        $mysqli->query($sql);

        $sql = "UPDATE `privilege_distribution` SET `edit_" . $set_name . "_problem`=1,`see_hidden_" . $set_name . "_problem`=1 ";
        $sql .= "WHERE group_name IN ('root','administrator','teacher','teacher_assistant','hznu_viewer')"; //给各级管理员添加权限
        $mysqli->query($sql);
        $sql = "INSERT INTO `problemset`(`set_name`,`set_name_show`) VALUES('$set_name','$set_name_show')";
        $mysqli->query($sql);
        if ($mysqli->affected_rows == 1) echo "<script language=javascript>alert('添加成功！');</script>";
    }
}
$sql = "SELECT s.*,p.`num` FROM `problemset` AS s LEFT JOIN (SELECT problemset,COUNT(`problem_id`) AS num FROM `problem` GROUP BY problemset) AS p ON s.`set_name`=p.`problemset`";
$result = $mysqli->query($sql);
$view_problemset = array();
$i = 0;
while ($row = $result->fetch_object()) {
    $sql = "SELECT * FROM `privilege_distribution` WHERE see_hidden_".$row->set_name."_problem=1 OR edit_".$row->set_name."_problem=1";
    $privilege=$mysqli->query($sql) or die("`problemset`表中数据和`privilege_distribution`表中字段不符，请登录数据库处理！".$mysqli->error);
    $temp=$privilege->num_rows>1?"rowspan='".$privilege->num_rows."'":"";
    //$privilege->fetch_all(MYSQLI_ASSOC);
    $view_problemset[$i][0] = "<td style='vertical-align:middle;' $temp>$row->index<input type='hidden' name='id' value='$row->index'></td>\n";
    $view_problemset[$i][1] = "<td style='vertical-align:middle;' $temp>$row->set_name</td>\n";
    $view_problemset[$i][2] = "<td style='vertical-align:middle;' $temp>" . ($row->num ? $row->num : 0) . "</td>\n";
    $view_problemset[$i][3] = "<td style='vertical-align:middle;' $temp><input type='text' placeholder='限20个以内的汉字/字母/数字/下划线' style='width:300px;' maxlength='20' pattern='^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$' name='set_name_show' value='$row->set_name_show' required /></td>\n";
    $view_problemset[$i][4] = "<td style='vertical-align:middle;text-align: center' $temp><input class='btn btn-primary' type='submit' name='edit' value='$MSG_SUBMIT'></td>\n";
    $view_problemset[$i][5] = "<td style='vertical-align:middle;text-align: center' $temp>";
    if ($row->set_name == "default") {
        $view_problemset[$i][5] .= "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
    } else {
        if ($row->num != "") {
            $view_problemset[$i][5] .= "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
        } else $view_problemset[$i][5] .= "<input class='btn btn-primary' type='submit' name='del' value='$MSG_DEL'></a>";
    }
    $view_problemset[$i][5] .= "</td>\n";
    if ($row2 = $privilege->fetch_array(MYSQLI_ASSOC)) {
        $view_problemset[$i][6] = "<td>" . ($row2["edit_".$row->set_name."_problem"] ? $row2["group_name"] : "") . "</td>\n";
        $view_problemset[$i][7] = "<td>" . ($row2["see_hidden_".$row->set_name."_problem"] ? $row2["group_name"] : "") . "</td>\n";
    } else {
        $view_problemset[$i][6] = "";
        $view_problemset[$i][7] = "";
    }
    $i++;
    while ($row2 = $privilege->fetch_array(MYSQLI_ASSOC)) {
        $view_problemset[$i][0] = "";
        $view_problemset[$i][1] = "";
        $view_problemset[$i][2] = "";
        $view_problemset[$i][3] = "";
        $view_problemset[$i][4] = "";
        $view_problemset[$i][5] = "";
        $view_problemset[$i][6]="<td>" . ($row2["edit_".$row->set_name."_problem"] ? $row2["group_name"] : "") . "</td>";
        $view_problemset[$i][7]="<td>" . ($row2["see_hidden_".$row->set_name."_problem"] ? $row2["group_name"] : "") . "</td>";
        $i++;
    }
}
?>

<title><?php echo $html_title . $MSG_PROBLEMSET . $MSG_LIST ?></title>
<h1><?php echo $MSG_PROBLEMSET . $MSG_LIST ?></h1>
<h4><?php echo $MSG_HELP_PROBLEMSET1.$MSG_HELP_PROBLEMSET2 ?></h4>
<hr>
<form class="form-inline" method="post">
    <table class='table table-condensed ' style='white-space: nowrap; width:600px;'>
        <thead>
            <tr>
                <td style='vertical-align:middle;width:10px;font-weight:bold;'><?php echo $MSG_Name ?>:</td>
                <td style='vertical-align:middle;'><?php require_once("../include/set_post_key.php"); ?><input type='text' placeholder='限20个以内的字母、数字' style='width:300px;' maxlength='20' pattern='^[a-zA-Z0-9]{1,20}$' name='set_name' value='' required /></td>
                <td style='vertical-align:middle;width:10px;font-weight:bold;'><?php echo $MSG_Alias ?>:</td>
                <td style='vertical-align:middle;'><input type='text' placeholder='限20个以内的汉字/字母/数字/下划线' style='width:300px;' maxlength='20' pattern='^[\u4e00-\u9fa5_a-zA-Z0-9]{1,20}$' name='set_name_show' value='' required /></td>
                <td style='vertical-align:middle;'><input class='btn btn-primary' type='submit' name='add' value='<?php echo $MSG_ADD ?>'></td></td>
            </tr>
        </thead>
    </table>
</form>
<hr>
<table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap; width:600px;'>
    <thead>
        <tr>
            <th><?php echo $MSG_ID ?></th>
            <th><?php echo $MSG_Name ?></th>
            <th><?php echo $MSG_PROBLEM ?></th>
            <th><?php echo $MSG_Alias ?></th>
            <th colspan="2" style="text-align: center"><?php echo $MSG_Operations ?></th>
            <th>edit_xx_problem</th>
            <th>see_hidden_xx_problem</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($view_problemset as $row) {
            echo "<form method='post'>";
            require("../include/set_post_key.php");
            if($row[0]!=""){
                echo "<tr>";
                foreach ($row as $table_cell) {
                    echo $table_cell;
                }
                echo "</tr>\n";
            } else {
                echo "<tr>".$row[6].$row[7]."</tr>\n";
            }
            echo "</form>";
        }
        ?>
    </tbody>
</table>
<?php require_once("admin-footer.php") ?>