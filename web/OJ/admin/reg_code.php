<?php

/**
 * This file is created
 * by lixun516@qq.com
 * @2020.02.20
 **/
?>

<?php
require_once("admin-header.php");
if (!HAS_PRI("generate_team")) {
    $view_error = "You don't have the privilege to view this page!";
    require_once("error.php");
    exit(1);
}
require_once("../include/my_func.inc.php");
if (isset($_POST['save']) || isset($_POST['del'])) { //删除或者更新注册码
    require_once("../include/check_get_key.php");
    $class =  $mysqli->real_escape_string(trim($_POST['class']));
    $reg_code =  $mysqli->real_escape_string(trim($_POST['reg_code']));
    $remain_num =  $mysqli->real_escape_string(trim($_POST['remain_num']));
    $err_str = "";
    $err_cnt = 0;
    if ($class && class_is_exist($class)) {
        if (!preg_match("/^[_a-zA-Z0-9]{1,20}$/", $reg_code)) {
            $err_str .= "输入的{$MSG_REG_CODE}限20位字母、数字或下划线 ！\\n";
            $err_cnt++;
        }
        if (!preg_match("/^[1-9][0-9]{0,3}$/", $remain_num) && $remain_num!=-1) {
            $err_str .= "输入的{$MSG_Remain_Num}要求是介于-1~9999的整数 ！\\n";
            $err_cnt++;
        }
        if ($err_cnt > 0) {
            print "<script language='javascript'>\n";
            echo "alert('";
            echo $err_str;
            print "');\n history.go(-1);\n</script>";
            exit(0);
        }
        if (isset($_POST['save'])) {
            $sql = "UPDATE `reg_code` SET `reg_code`='$reg_code', `remain_num`='$remain_num'  WHERE `class_name`='$class'";
            $mysqli->query($sql);
        } else if (isset($_POST['del'])) {
            $sql = "DELETE FROM `reg_code` WHERE `class_name`='$class'";
            $mysqli->query($sql);
        }
    }
    echo "<script language=javascript>history.go(-1);</script>";
    exit(0);
} else if (isset($_POST['add'])) { //添加注册码    
    require_once("../include/check_post_key.php");
    $classes = $_POST['classes'];
    $remain_num =  $mysqli->real_escape_string(trim($_POST['remain_num']));
    $err_str = "";
    $err_cnt = 0;
    if (!count($_POST['classes'])) {
        $err_str .= "请选择{$MSG_Class}！";
        $err_cnt++;
    }
    if (!preg_match("/^[1-9][0-9]{0,3}$/", $remain_num) && $remain_num!=-1) {
        $err_str .= "输入的{$MSG_Remain_Num}要求是介于-1~9999的整数 ！\\n";
        $err_cnt++;
    }
    if (($_POST['mode']) == "B" && !trim($_POST['reg_code'])) {
        $err_str .= "请填写所选{$MSG_Class}的{$MSG_REG_CODE}！";
        $err_cnt++;
    }
    if ($err_cnt > 0) {
        print "<script language='javascript'>\n";
        echo "alert('";
        echo $err_str;
        print "');\n history.go(-1);\n</script>";
        exit(0);
    }
    
    $class_list = array();
    switch ($_POST['mode']) {
        case "A":
            foreach ($classes as $c) {
                $temp[0] = $mysqli->real_escape_string(trim($c));
                $temp[1] = createPwd("", 10, false);
                $temp[2] = $remain_num;
                array_push($class_list, $temp);
            }
            break;
        case "B":
            $reg_code =  explode("\n", trim($_POST['reg_code']));
            foreach ($classes as $key => $value) {
                $temp[0] = $mysqli->real_escape_string(trim($classes[$key]));
                $temp[1] = ($reg_code[$key] ? $mysqli->real_escape_string(trim(str_replace("\r", "", $reg_code[$key]))) : createPwd("", 10, false));
                $temp[2] = $remain_num;
                array_push($class_list, $temp);
            }
            break;
    }
    $cnt = 0;
    foreach($class_list as $c){
        if (!class_is_exist($c[0])) {
            echo "{$MSG_Class} <b>$c[0]</b> 不存在，{$MSG_REG_CODE}添加失败！<br>";
        } else if (get_class_regcode($c[0])){
            echo "{$MSG_Class} <b>$c[0]</b> 已有{$MSG_REG_CODE}，{$MSG_REG_CODE}添加失败！<br>";
        } else if (!preg_match("/^[_a-zA-Z0-9]{6,20}$/", $c[1])) {
            echo "{$MSG_Class} <b>$c[0]</b> 的{$MSG_REG_CODE} {$c[1]} 不合规（限6-20位字母、数字或下划线），{$MSG_REG_CODE}添加失败！<br>";
        } else if (!preg_match("/^[1-9][0-9]{0,3}$/", $c[2]) && $c[2]!=-1) {
            echo "{$MSG_Class} <b>$c[0]</b> 的{$MSG_Remain_Num}不合规（要求是介于-1~9999的整数） ！，{$MSG_REG_CODE}添加失败！<br>";
        } else {
            $sql = "INSERT INTO `reg_code` VALUES ('{$c[0]}', '{$c[1]}', '{$c[2]}')";
            $sql .= " ON DUPLICATE KEY UPDATE `reg_code`='$c[1]', `remain_num`='$c[2]'";
            $mysqli->query($sql);
            echo "{$MSG_Class} $c[0] 的{$MSG_REG_CODE} {$c[1]} <b>添加成功</b>！<br>";
            $cnt++;
        }
    }

    echo "成功写入{$cnt}个{$MSG_REG_CODE}。";
    echo "<p><input type='button' name='submit' value='$MSG_Back' onclick='javascript:history.go(-1);' style='margin-bottom: 20px;'>";
    require_once("admin-footer.php");
    exit(0);
}

require_once("../include/set_get_key.php");
$sql_filter = "";
if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
    //分页start
    $page = 1;
    $args = array();
    if (isset($_GET['page'])) $page = intval($_GET['page']);
    if (isset($_GET['year'])) $args['year'] = $_GET['year'];
    else $args['year'] = "";
    if (isset($_GET['keyword'])) {
        $_GET['keyword'] = trim($_GET['keyword']);
        $args['keyword'] = urlencode($_GET['keyword']);
    }
    if (isset($_GET['sort_method'])) $args['sort_method'] = $_GET['sort_method'];
    else $args['sort_method'] = "";
    if (isset($page)) $args['page'] = $page;
    function generate_url($data)
    {
        global $args;
        $link = "reg_code.php?";
        foreach ($args as $key => $value) {
            if (isset($data["$key"])) {
                $value = htmlentities($data["$key"]);
                $link .= "&$key=$value";
            } else if ($value) {
                $link .= "&$key=" . htmlentities($value);
            }
        }
        return $link;
    }
    //分页end
    $sql_filter = " WHERE r.`class_name`<>'其它' ";
    if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
        $keyword = $mysqli->real_escape_string($_GET['keyword']);
        $keyword = "'%$keyword%'";
        $sql_filter .= " AND r.`class_name` LIKE $keyword ";
    }
    if (isset($_GET['year']) && $_GET['year'] != "" && $_GET['year'] != "all") {
        $sql_filter .= " AND c.`enrollment_year` = '{$mysqli->real_escape_string($_GET['year'])}'";
    }
    $leftjoin = " LEFT JOIN `class_list` AS c ON r.`class_name`=c.`class_name` ";
    $sql0 = "SELECT COUNT(r.`class_name`) FROM `reg_code` AS r " . $leftjoin . $sql_filter;
    $result = $mysqli->query($sql0)->fetch_all();
    $total = 0;
    if ($result) $total = $result[0][0];
    $page_cnt = 10;
    $view_total_page = ceil($total / $page_cnt); //计算页数
    $view_total_page = $view_total_page>0?$view_total_page:1;
    if ($page > $view_total_page) $args['page'] = $page = $view_total_page;
    if ($page < 1) $page = 1;
    $left_bound = $page_cnt * $page - $page_cnt;
    $u_id = $left_bound;
    switch ($args['sort_method']) {
        case 'remain_DESC':
            $class_icon = "am-icon-sort";
            $year_icon = "am-icon-sort";
            $remain_icon = "am-icon-sort-amount-desc";
            $sql_orderby = " ORDER BY od, `remain_num` DESC ";
            $class = 'class_DESC';
            $year = 'year_DESC';
            $remain = 'remain_ASC';
            break;
        case 'remain_ASC':
            $class_icon = "am-icon-sort";
            $year_icon = "am-icon-sort";
            $remain_icon = "am-icon-sort-amount-asc";
            $sql_orderby = " ORDER BY od, `remain_num` ";
            $class = 'class_DESC';
            $year = 'year_DESC';
            $remain = 'remain_DESC';
            break;
        case 'class_DESC':
            $class_icon = "am-icon-sort-amount-desc";
            $year_icon = "am-icon-sort";
            $remain_icon = "am-icon-sort";
            $sql_orderby = " ORDER BY od, `class_name` DESC ";
            $class = 'class_ASC';
            $year = 'year_DESC';
            $remain = 'remain_DESC';
            break;
        case 'class_ASC':
            $class_icon = "am-icon-sort-amount-asc";
            $year_icon = "am-icon-sort";
            $remain_icon = "am-icon-sort";
            $sql_orderby = " ORDER BY od, `class_name` ";
            $class = 'class_DESC';
            $year = 'year_DESC';
            $remain = 'remain_DESC';
            break;
        case 'year_ASC':
            $class_icon = "am-icon-sort";
            $year_icon = "am-icon-sort-amount-asc";
            $remain_icon = "am-icon-sort";
            $sql_orderby = " ORDER BY od, `enrollment_year`, `class_name`";
            $class = 'class_DESC';
            $year = 'year_DESC';
            $remain = 'remain_DESC';
            break;
        case 'year_DESC':
        default:
            $class_icon = "am-icon-sort";
            $year_icon = "am-icon-sort-amount-desc";
            $remain_icon = "am-icon-sort";
            $sql_orderby = " ORDER BY od, `enrollment_year` DESC, `class_name` ";
            $class = 'class_DESC';
            $year = 'year_ASC';
            $remain = 'remain_DESC';
            break;
    }
    $sql_orderby .= " LIMIT $left_bound, $page_cnt";
}

$view_class = array();
$cnt = 0;
$sql = "SELECT * FROM `reg_code` WHERE `class_name`='其它'";
$result = $mysqli->query($sql);
if ($row = $result->fetch_object()) {
    if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        $view_class[$cnt][0] = "0";
        $view_class[$cnt][1] = "";
        $view_class[$cnt][2] = $row->class_name;
    }
    $view_class[$cnt][3] = "<form action='reg_code.php?getkey={$_SESSION['getkey']}' method='post'><input type='hidden' name='class' value='{$row->class_name}'>";
    $view_class[$cnt][3] .= "<input type='text' style='width:200px;' maxlength='20' pattern='^[_a-zA-Z0-9]{6,20}$' name='reg_code' value='$row->reg_code' required />";
    $view_class[$cnt][4] = "<input type='number' style='width:100px;' name='remain_num' min='-1' max='9999' value='$row->remain_num' required />";
    $view_class[$cnt][5] = "<input type='submit' name='save' value='{$MSG_SUBMIT}'>";
    $view_class[$cnt][6] = "&nbsp;</form>";
    $cnt++;
} else { //查不到‘其它’就重新写入并刷新
    $sql = "INSERT INTO `reg_code` VALUES ('其它', '', '0')";
    $mysqli->query($sql);
    echo "<script language='javascript'>\n";
    echo "window.location.href='reg_code.php';";
    echo "</script>";
    exit(0);
}
if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
    $sql = "SELECT 0 as od, c.`enrollment_year`, r.* FROM `reg_code` AS r " . $leftjoin . $sql_filter . " AND c.`enrollment_year` = 0 ";
    $sql .= "UNION ALL (SELECT 1 as od, c.`enrollment_year`, r.* FROM `reg_code` AS r " . $leftjoin . $sql_filter. " AND c.`enrollment_year` <> 0) ";
    $sql .= $sql_orderby;
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_object()) {
        $view_class[$cnt][0] = ++$u_id;
        $view_class[$cnt][1] = $row->enrollment_year ? $row->enrollment_year . "级" : "";
        $view_class[$cnt][2] = $row->class_name . "【{$row->reg_code}】";
        $view_class[$cnt][3] = "<form action='reg_code.php?getkey={$_SESSION['getkey']}' method='post'><input type='hidden' name='class' value='{$row->class_name}'>";
        $view_class[$cnt][3] .= "<input type='text' style='width:200px;' maxlength='20' pattern='^[_a-zA-Z0-9]{6,20}$' name='reg_code' value='$row->reg_code' required />";
        $view_class[$cnt][4] = "<input type='number' style='width:100px;' name='remain_num' min='-1' max='9999' value='$row->remain_num' required />";
        $view_class[$cnt][5] = "<input type='submit' name='save' value='{$MSG_SUBMIT}'>";
        $view_class[$cnt][6] = "<input type='submit' name='del' onclick='javascript:if(confirm(\" {$MSG_DEL} ?\")) return true; else return false;' value='{$MSG_DEL}'></form>";
        $cnt++;
    }
}
?>
<title><?php echo $html_title . $MSG_REG_CODE . $MSG_LIST ?></title>
<h1><?php echo $MSG_REG_CODE . $MSG_LIST ?></h1>
<h4>
    <?php
    if (isset($OJ_REG_NEED_CONFIRM)) {
        switch ($OJ_REG_NEED_CONFIRM) {
            case "off":
                echo $MSG_HELP_RegCode_OpenMode;
                break;
            case "on":
                echo $MSG_HELP_RegCode_ComfirmMode;
                break;
            case "pwd":
                echo $MSG_HELP_RegCode_PwdMode;
                break;
            case "pwd+confirm";
                echo $MSG_HELP_RegCode_PwdComfirmMode;
                break;
        }
    } else echo $MSG_HELP_RegCode_OpenMode;
    ?>
</h4>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <li><a href="user_list.php"><?php echo $MSG_USER . $MSG_LIST ?></a></li>
        <li><a href="user_list.php?team=all"><?php echo $MSG_TEAM . $MSG_LIST ?></a></li>
        <li><a href="class_list.php"><?php echo $MSG_Class . $MSG_LIST ?></a></li>
        <li><a href="change_class.php"><?php echo $MSG_ChangeClass ?></a></li>
        <li class="am-active"><a href="reg_code.php"><?php echo $MSG_REG_CODE ?></a></li>
    </ul>
</div>

<?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
    <!-- 查找 start -->
    <div class='am-g' style="margin-left: 5px;">
        <form id="searchform" class="am-form am-form-inline">
            <div class='am-form-group'>
                <select class="selectpicker show-tick" data-live-search="true" name='year' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                    <option value='all' <?php if (isset($_GET['year']) && ($_GET['year'] == "" || $_GET['year'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Enrollment_Year ?></option>
                    <?php
                    $sql = "SELECT DISTINCT c.`enrollment_year` FROM `class_list` AS c, `reg_code` AS r WHERE c.`class_name`<> '其它' AND c.`class_name`=r.`class_name` ORDER BY c.`enrollment_year` DESC";
                    $result = $mysqli->query($sql);
                    $years = $result->fetch_all(MYSQLI_BOTH);
                    $result->free();
                    foreach ($years as $row) {
                        echo "<option value='" . $row[0] . "' ";
                        if ($args['year'] == $row[0])  echo "selected";
                        echo $row[0]?">$row[0]级</option>":">无$MSG_Enrollment_Year</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="am-form-group am-form-icon">
                <i class="am-icon-search"></i>
                <input class="am-form-field" name="keyword" type="text" placeholder="<?php echo $MSG_KEYWORDS ?>" value="<?php echo $_GET['keyword'] ?>" />
            </div>
            <input class="btn btn-default" type=submit value="<?php echo $MSG_SEARCH ?>">
        </form>
    </div>

    <!-- 查找 end -->

    <!-- 页标签 start -->
    <div class="am-g" style="margin-left: 5px;">
        <ul class="pagination text-center" style="margin-top: 10px;margin-bottom: 0px;">
            <?php $link = generate_url(Array("page"=>"1"))?>
            <li><a href="<?php echo $link ?>">Top</a></li>
            <?php $link = generate_url(array("page" => max($page - 1, 1))) ?>
            <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
            <?php
            //分页
            $page_size=10;
            $page_start=max(ceil($page/$page_size-1)*$page_size+1,1);
            $page_end=min(ceil($page/$page_size-1)*$page_size+$page_size,$view_total_page);
            for ($i=$page_start;$i<$page;$i++){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            $link=generate_url(Array("page"=>"$page"));
            echo "<li class='active'><a href=\"$link\">{$page}</a></li>";
            for ($i=$page+1;$i<=$page_end;$i++){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            if ($i <= $view_total_page){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            ?>
            <?php $link = generate_url(array("page" => min($page + 1, intval($view_total_page)))) ?>
            <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
        </ul>
    </div>
    <!-- 页标签 end -->

    <style type="text/css" media="screen">
        #class,
        #remain,
        #year:hover {
            cursor: pointer;
        }
    </style>
<?php } ?>

<!-- 罗列班级注册码start -->
<div class="am-g" style="max-width: 1300px;">
    <div class="am-u-sm-8">
        <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;">
            <thead>

                <tr>
                    <td colspan="<?php echo (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) ? 7 : 4 ?>">
                        <label>说明：</label><br>
                        <ol>
                            <li><b><?php echo $MSG_REG_CODE ?></b>限6-20位以内的字母、数字、下划线，支持<b>系统生成随机注册码</b>和<b>自定义注册码</b>两种生成模式；</li>
                            <li><b><b><?php echo $MSG_Remain_Num ?></b>限 -1 ~ 9999 的整数，<?php echo $MSG_Remain_Num ?></b> = -1 ——此通道的注册不限人数，<b><?php echo $MSG_Remain_Num ?></b> = 0 —— 此通道注册关闭；</li>
                            <li><b><?php echo $MSG_Remain_Num ?></b> > 0 时，人员每注册一个账号，<b><?php echo $MSG_Remain_Num ?></b>自动减1直至为0，此通道的注册将被关闭（<b>后台导入的账号不占<?php echo $MSG_Remain_Num ?></b>）；</li>
                            <li><b><?php echo $MSG_Class ?></b>修改名称后，系统会自动更新其对应<?php echo $MSG_REG_CODE ?>的<?php echo $MSG_Class_Name ?>，<?php echo $MSG_Class ?><b>删除</b>后，其<?php echo $MSG_REG_CODE ?>记录也会被清除；</li>
                            <li>修改<?php echo $MSG_REG_CODE ?>和<?php echo $MSG_Remain_Num ?>后，点击对应的"<?php echo $MSG_SUBMIT ?>"按钮保存设置，修改及删除注册码不会对已注册用户产生影响。</li>
                        </ol>
                    </td>
                </tr>
                <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
                    <tr>
                        <th width="10px"><?php echo $MSG_ID ?></th>
                        <th width="30px" id="year" style="white-space: nowrap;"><?php echo $MSG_Enrollment_Year ?>&nbsp;<span class="<?php echo $year_icon ?>"></span></th>
                        <th width="80%" id="class"><?php echo $MSG_Class_Name ?>&nbsp;<span class="<?php echo $class_icon ?>"></span></th>
                    <?php } else {
                    echo "<tr>";
                } ?>
                    <th><?php echo $MSG_REG_CODE ?></th>
                    <th id="remain"><?php echo $MSG_Remain_Num ?>&nbsp;<span class="<?php echo $remain_icon ?>"></span></th>
                    <th colspan="2" style="text-align: center"><?php echo $MSG_Operations ?></th>
                    </tr>
            </thead>
            <tbody>
                <?php
                foreach ($view_class as $row) {
                    echo "<tr>\n";
                    foreach ($row as $table_cell) {
                        echo "<td>";
                        echo $table_cell;
                        echo "</td>\n";
                    }
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
        <div class="am-u-sm-4">
            <section class="am-panel am-panel-primary">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title"><b><?php echo $MSG_ADD . $MSG_REG_CODE ?></b></h3>
                </header>
                <main class="am-panel-bd" style="margin-left: 0px;">
                    <form class="am-form am-form-horizontal" method="POST">
                        <?php require_once("../include/set_post_key.php"); ?>
                        <div class="am-form-group" style="white-space: nowrap;">
                            <label class="am-u-sm-3 am-form-label"><?php echo $MSG_Class ?>:</label>
                            <select name="classes[]" size="10" style="width:260px;" multiple class="sam-u-sm-9 am-u-end" required>
                                <?php
                                require_once("../include/classList.inc.php");
                                $classList = get_classlist(false, "c.`class_name` NOT IN (SELECT `class_name` FROM `reg_code`)");
                                foreach ($classList as $c) {
                                    if ($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
                                    foreach ($c[1] as $cl) {
                                        echo "<option value='$cl'>$cl</option>\n";
                                    }
                                    if ($c[0]) echo "</optgroup>\n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="am-form-group" style="white-space: nowrap;">
                            <label class="am-u-sm-3 am-form-label"><?php echo $MSG_Remain_Num ?>:</label>
                            <input type='number' style='width:260px;' name='remain_num' min='-1' max='9999' value='30' required />
                        </div>
                        <div class="am-form-group" style="white-space: nowrap;">
                            <label class="am-u-sm-3 am-form-label"><?php echo $MSG_Mode ?>:</label>
                            <select id="mode" name="mode" style="width:260px;" class="am-u-sm-9 am-u-end">
                                <option value="A" selected>系统生成随机<?php echo $MSG_REG_CODE ?></option>
                                <option value="B"><?php echo $MSG_Customiz . $MSG_REG_CODE ?></option>
                            </select>
                        </div>

                        <div class="am-form-group" style="white-space: nowrap;" id="B" hidden>
                            <label class="am-u-sm-3 am-form-label"><?php echo $MSG_REG_CODE ?>:</label>
                            <textarea id="reg_code" name="reg_code" rows="5" class="am-u-sm-9 am-u-end" style="width:260px;" placeholder="*示例：一个班级的<?php echo $MSG_REG_CODE ?>占一行<?php echo "\n" ?>班级1<?php echo $MSG_REG_CODE."\n" ?>班级2<?php echo $MSG_REG_CODE."\n" ?>班级3<?php echo $MSG_REG_CODE."\n" ?>若行数不足系统将生成随机<?php echo $MSG_REG_CODE ?>补足差额。" disabled required></textarea>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-8 am-u-sm-offset-4">
                                <input type="submit" value="<?php echo $MSG_ADD ?>" name="add" class="am-btn am-btn-success">
                            </div>
                        </div>
                    </form>
                </main>
                <footer class="am-panel-footer">
                    <font color="red">注：<b>班级可多选。</b></font>
                </footer>
            </section>
        </div>
    <?php } ?>
</div>
<!-- 罗列班级注册码end -->
<?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
    <!-- 页标签 start -->
    <div class="am-g" style="margin-left: 5px;">
        <ul class="pagination text-center" style="margin-top: 1px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>"1"))?>
            <li><a href="<?php echo $link ?>">Top</a></li>
            <?php $link = generate_url(array("page" => max($page - 1, 1))) ?>
            <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
            <?php
            //分页
            $page_size=10;
            $page_start=max(ceil($page/$page_size-1)*$page_size+1,1);
            $page_end=min(ceil($page/$page_size-1)*$page_size+$page_size,$view_total_page);
            for ($i=$page_start;$i<$page;$i++){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            $link=generate_url(Array("page"=>"$page"));
            echo "<li class='am-active'><a href=\"$link\">{$page}</a></li>";
            for ($i=$page+1;$i<=$page_end;$i++){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            if ($i <= $view_total_page){
                $link=generate_url(Array("page"=>"$i"));
                echo "<li><a href=\"$link\">{$i}</a></li>";
            }
            ?>
            <?php $link = generate_url(array("page" => min($page + 1, intval($view_total_page)))) ?>
            <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
        </ul>
    </div>
    <!-- 页标签 end -->
<?php } ?>
<?php require_once("admin-footer.php") ?>
<?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
<!-- sort by class、year BEGIN -->
<script>
    <?php $args['sort_method'] = $class; ?>
    $("#class").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $year; ?>
    $("#year").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $remain; ?>
    $("#remain").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    $("#mode").change(function() {
        if ($(this).val() == "A") {
            $("#B").hide();
            $('#reg_code').attr("disabled", true);
        } else {
            $("#B").show();
            $('#reg_code').attr("disabled", false);
        }
    });
</script>
<?php } ?>
