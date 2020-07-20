<?php

/**
 * This file is created
 * by lixun516@qq.com
 * @2020.02.13
 **/
?>

<?php
require_once("admin-header.php");
require_once("../include/set_get_key.php");
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
if (isset($_GET['zero'])) $args['zero'] = $_GET['zero'];
if (isset($_GET['sort_method'])) $args['sort_method'] = $_GET['sort_method'];
else $args['sort_method'] = "";
if (isset($page)) $args['page'] = $page;
function generate_url($data, $link){
    global $args;
    if ($link == "") $link = "class_list.php?";
    else $link .= "?";
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
$sql_filter = " WHERE `class_name`<>'其它' ";
if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
    $keyword = $mysqli->real_escape_string($_GET['keyword']);
    $keyword = "'%$keyword%'";
    $sql_filter .= " AND `class_name` LIKE $keyword ";
}
if (isset($_GET['year']) && $_GET['year'] != "" && $_GET['year'] != "all") {
    $sql_filter .= " AND `enrollment_year` = '{$mysqli->real_escape_string($_GET['year'])}'";
}
if (isset($_GET['zero'])){
    switch($_GET['zero']){
        case "y":
            $sql_filter .= " AND ISNULL(stu_num) AND ISNULL(team_account_num) ";
            break;
        case "n":
            $sql_filter .= " AND NOT (ISNULL(stu_num) AND ISNULL(team_account_num)) ";
            break;
    }
}
$leftJoin = " LEFT JOIN (SELECT `users`.`class`, COUNT(`users`.`user_id`) AS stu_num FROM `users` GROUP BY `users`.`class`) AS u  ON `class_name`= u.`class` ";
$leftJoin .= " LEFT JOIN (SELECT `team`.`class`, COUNT(`team`.`user_id`) AS team_account_num FROM `team` GROUP BY `team`.`class`) AS t ON `class_name`= t.`class`";
$leftJoin = " FROM `class_list` " . $leftJoin;
$sql0 = "SELECT COUNT(`class_name`)" . $leftJoin . $sql_filter;
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
    case 'class_DESC':
        $class_icon = "am-icon-sort-amount-desc";
        $year_icon = "am-icon-sort";
        $sql_order = " ORDER BY od,`class_name` DESC ";
        $class = 'class_ASC';
        $year = 'year_DESC';
        break;
    case 'class_ASC':
        $class_icon = "am-icon-sort-amount-asc";
        $year_icon = "am-icon-sort";
        $strength_icon = "am-icon-sort";
        $sql_order = " ORDER BY od,`class_name` ";
        $class = 'class_DESC';
        $year = 'year_DESC';
        break;
    case 'year_ASC':
        $class_icon = "am-icon-sort";
        $year_icon = "am-icon-sort-amount-asc";
        $strength_icon = "am-icon-sort";
        $sql_order = " ORDER BY od,`enrollment_year`, `class_name`";
        $class = 'class_DESC';
        $year = 'year_DESC';
        break;
    case 'year_DESC':
    default:
        $class_icon = "am-icon-sort";
        $year_icon = "am-icon-sort-amount-desc";
        $strength_icon = "am-icon-sort";
        $sql_order = " ORDER BY od,`enrollment_year` DESC, `class_name` ";
        $class = 'class_DESC';
        $year = 'year_ASC';
        break;
}
$view_class = array();
$cnt = 0;
$view_class[$cnt][0] = "<input type=checkbox name='other' value='' disabled/>&nbsp;0";
$view_class[$cnt][1] = "";
$view_class[$cnt][2] = "其它";
$view_class[$cnt][3] = "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
$view_class[$cnt][4] = "<span class='btn btn-primary' disabled>$MSG_EDIT</span>";

$sql = "SELECT `class_list`.*, stu_num, team_account_num";
$sql_other = $sql. $leftJoin . " WHERE `class_name`='其它'";
$result = $mysqli->query($sql_other);
if ($row = $result->fetch_object()) {
    if (!$row->stu_num) $row->stu_num = 0;
    if (!$row->team_account_num) $row->team_account_num = 0;
    if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        if ($row->stu_num) {
            $view_class[$cnt][5] = "<a class='btn btn-primary' href='user_list.php?class=".urlencode("其它")."'>$MSG_Stu_List($row->stu_num)</a>";
        } else $view_class[$cnt][5] = "<span class='btn btn-primary' disabled>$MSG_Stu_List($row->stu_num)</span>";
        if ($row->team_account_num) {
            $view_class[$cnt][6] = "<a class='btn btn-primary' href='user_list.php?team=all&class=".urlencode("其它")."'>$MSG_TEAM($row->team_account_num)</a>";
        } else $view_class[$cnt][6] = "<span class='btn btn-primary' disabled>$MSG_TEAM($row->team_account_num)</span>";
    } else {
        $view_class[$cnt][5] = "$MSG_Stu_List($row->stu_num)";
        $view_class[$cnt][6] = "$MSG_TEAM($row->team_account_num)";
    }
    $cnt++;
} else {
    $view_class[$cnt][5] = $MSG_Stu_List . "(0)";
}

$sql = $sql.", 0 as od ".$leftJoin.$sql_filter." AND `enrollment_year`=0 UNION ALL (".$sql.", 1 as od ".$leftJoin.$sql_filter." AND `enrollment_year`<>0) ".$sql_order ." LIMIT $left_bound, $page_cnt";
$result = $mysqli->query($sql);
while ($row = $result->fetch_object()) {
    if (!$row->stu_num) $row->stu_num = 0;
    if (!$row->team_account_num) $row->team_account_num = 0;
    if (HAS_PRI("edit_user_profile")) $view_class[$cnt][0] = "<input type=checkbox name='cid[]' value='$row->class_name' />&nbsp;" . ++$u_id;
    else $view_class[$cnt][0] = ++$u_id;
    $view_class[$cnt][1] = $row->enrollment_year==0?"":$row->enrollment_year . "级";
    $view_class[$cnt][2] = $row->class_name;
    if (HAS_PRI("edit_user_profile")) {
        $view_class[$cnt][3] = "<a class='btn btn-primary' href='#' onclick='javascript:if(confirm(\" $MSG_DEL $row->class_name ?\")) location.href=\"class_edit.php?del&cid=".urlencode($row->class_name)."&getkey={$_SESSION['getkey']}\"'>$MSG_DEL</a>";
        $view_class[$cnt][4] = "<a class='btn btn-primary' href='" . generate_url("", "class_edit.php") . "&cid=".urlencode($row->class_name)."'>$MSG_EDIT</a>";
    } else {
        $view_class[$cnt][3] = "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
        $view_class[$cnt][4] = "<span class='btn btn-primary' disabled>$MSG_EDIT</span>";
    }
    if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        if ($row->stu_num) {
            $view_class[$cnt][5] = "<a class='btn btn-primary' href='user_list.php?class=".urlencode($row->class_name)."'>$MSG_Stu_List($row->stu_num)</a>";
        } else $view_class[$cnt][5] = "<span class='btn btn-primary' disabled>$MSG_Stu_List($row->stu_num)</span>";
        if ($row->team_account_num) {
            $view_class[$cnt][6] = "<a class='btn btn-primary' href='user_list.php?team=all&class".urlencode($row->class_name)."'>$MSG_TEAM($row->team_account_num)</a>";
        } else $view_class[$cnt][6] = "<span class='btn btn-primary' disabled>$MSG_TEAM($row->team_account_num)</span>";
    } else {
        $view_class[$cnt][5] = "<span class='btn btn-primary' disabled>$MSG_Stu_List($row->stu_num)</span>";
        $view_class[$cnt][6] = "<span class='btn btn-primary' disabled>$MSG_TEAM($row->team_account_num)</span>";
    }
    $cnt++;
}
?>
<title><?php echo $html_title . $MSG_Class . $MSG_LIST ?></title>
<h1><?php echo $MSG_Class . $MSG_LIST ?></h1>
<h4><?php echo $MSG_HELP_CLASS_LIST ?>
    <?php
    if (!(isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE))
        echo "<font color=red>(班级模式未启用，若有需要请联系管理员开启)</font>";
    ?>
</h4>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <li><a href="user_list.php"><?php echo $MSG_USER . $MSG_LIST ?></a></li>
        <li><a href="user_list.php?team=all"><?php echo $MSG_TEAM . $MSG_LIST ?></a></li>
        <li class="am-active"><a href="class_list.php"><?php echo $MSG_Class . $MSG_LIST ?></a></li>
        <li><a href="change_class.php"><?php echo $MSG_ChangeClass ?></a></li>
        <li><a href="reg_code.php"><?php echo $MSG_REG_CODE ?></a></li>
    </ul>
</div>
<!-- 查找 start -->
<div class='am-g' style="margin-left: 5px;">
    <form id="searchform" class="am-form am-form-inline">
        <div class='am-form-group'>
            <select class="selectpicker show-tick" data-live-search="true" name='year' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                <option value='all' <?php if (isset($_GET['year']) && ($_GET['year'] == "" || $_GET['year'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Enrollment_Year ?></option>
                <?php
                $sql = "SELECT DISTINCT `enrollment_year` FROM `class_list` WHERE `class_name`<> '其它' ORDER BY `enrollment_year` DESC";
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
        <div class='am-form-group'>
            <select class="selectpicker show-tick" name='zero' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                <option value='all' <?php if (isset($_GET['zero']) && $_GET['zero'] != "y" && $_GET['zero'] != "n") echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_STATUS ?></option>
                <option value='y' <?php if (isset($_GET['zero']) && $_GET['zero'] == "y") echo "selected"; ?>><?php echo $MSG_Empty_Class ?></option>
                <option value='n' <?php if (isset($_GET['zero']) && $_GET['zero'] == "n") echo "selected"; ?>><?php echo $MSG_Not_Empty_Class ?></option>
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
        <?php $link = generate_url(Array("page"=>"1"), "")?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(array("page" => max($page - 1, 1)), "") ?>
        <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        $page_size=10;
        $page_start=max(ceil($page/$page_size-1)*$page_size+1,1);
        $page_end=min(ceil($page/$page_size-1)*$page_size+$page_size,$view_total_page);
        for ($i=$page_start;$i<$page;$i++){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"), "");
        echo "<li class='active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(array("page" => min($page + 1, intval($view_total_page))), "") ?>
        <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
</div>
<!-- 页标签 end -->

<!-- 罗列班级start -->
<style type="text/css" media="screen">
    #class,
    #year:hover {
        cursor: pointer;
    }
</style>
<div class="am-g" style="max-width: 1300px;">
    <div class="am-u-sm-8">
        <form method='post'>
            <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;">
                <thead>
                    <?php if (HAS_PRI("edit_user_profile")) { ?>
                        <tr>
                            <td colspan="7">
                                <input type=submit name='delete' class='btn btn-default' value='<?php echo $MSG_DEL ?>' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) $("form").attr("action","class_edit.php?del&getkey=<?php echo $_SESSION['getkey'] ?>");'>
                            </td>
                        </tr>
                        <tr>
                            <th width="10px"><input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'>&nbsp;<?php echo $MSG_ID ?></th>
                        <?php } else { ?>
                        <tr>
                            <th width="10px"><?php echo $MSG_ID ?></th>
                        <?php } ?>
                        <th width="30px" id="year"><?php echo $MSG_Enrollment_Year ?>&nbsp;<span class="<?php echo $year_icon ?>"></span></th>
                        <th width="80%" id="class"><?php echo $MSG_Class_Name ?>&nbsp;<span class="<?php echo $class_icon ?>"></span></th>
                        <th colspan="4" style="text-align: center"><?php echo $MSG_Operations ?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($view_class as $row) {
                        echo "<tr>\n";
                        foreach ($row as $table_cell) {
                            echo "<td style='vertical-align:middle;'>";
                            echo $table_cell;
                            echo "</td>\n";
                        }
                        echo "</tr>\n";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
    <div class="am-u-sm-4">
        <section class="am-panel am-panel-primary">
            <header class="am-panel-hd">
                <h3 class="am-panel-title"><b><?php echo $MSG_ADD . $MSG_Class ?></b></h3>
            </header>
            <main class="am-panel-bd" style="margin-left: 0px;">
                <form class="am-form am-form-horizontal" action="class_edit.php" method="POST">
                    <?php require_once("../include/set_post_key.php"); ?>
                    <div class="am-form-group" style="white-space: nowrap;">
                        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Enrollment_Year ?>:</label>
                        <select name="year" style="width:220px;" class="am-u-sm-8 am-u-end">
                            <option value='0'>无入学年份</option>
                            <?php
                            for ($i = 5; $i >= -4; $i--) {
                                $eyear = date("Y", strtotime("-{$i} year"));
                                echo "<option value='$eyear'";
                                if (!$i) echo "selected";
                                echo ">" . $eyear . "级</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="am-form-group" style="white-space: nowrap;">
                        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Mode ?>:</label>
                        <select id="mode" name="mode" style="width:220px;" class="am-u-sm-8 am-u-end">
                            <option value="A" selected>1/2/3/4(最多99个班)</option>
                            <option value="B">A/B/C/D(最多26个班)</option>
                            <option value="C"><?php echo $MSG_Customiz.$MSG_Class.$MSG_LIST ?></option>
                        </select>
                    </div>
                    <div class="am-form-group" style="white-space: nowrap;" id="A">
                        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Prefix ?>:</label>
                        <input type="text" style="width:220px;" class="am-u-sm-8 am-u-end" maxlength="20" id="prefix" name="prefix" placeholder="填入统一的班级名称前缀" pattern="^[\u4e00-\u9fa5_+a-zA-Z0-9]{1,20}$" required/>
                    </div>
                    <div class="am-form-group" style="white-space: nowrap;" id="B">
                        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Amount ?>:</label>
                        <input type="number" style="width:220px;" class="am-u-sm-8 am-u-end" id="class_num" name="class_num" min="1" max="99" value="4" required/>
                    </div>
                    <div class="am-form-group" style="white-space: nowrap;" id="C" hidden>
                        <label class="am-u-sm-4 am-form-label"><?php echo $MSG_Class.$MSG_LIST ?>:</label>
                        <textarea id="classes" name="classes" rows="5" class="am-u-sm-8 am-u-end" style="width:220px;" placeholder="*示例：一个班级名称占一行<?php echo "\n"?>班级1<?php echo "\n"?>班级2<?php echo "\n"?>班级3<?php echo "\n"?>" disabled required></textarea>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-sm-8 am-u-sm-offset-4">
                            <input type="submit" value="<?php echo $MSG_ADD ?>" name="add" class="am-btn am-btn-success">
                        </div>
                    </div>
                </form>
            </main>
            <footer class="am-panel-footer"><b><?php echo $MSG_Prefix ?></b>限20个以内汉字、字母、数字、下划线及加号</footer>
        </section>
    </div>
</div>
<!-- 罗列班级end -->

<!-- 页标签 start -->
<div class="am-g" style="margin-left: 5px;">
    <ul class="pagination text-center" style="margin-top: 1px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>"1"), "")?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(array("page" => max($page - 1, 1)), "") ?>
        <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=$page_start;$i<$page;$i++){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"), "");
        echo "<li class='active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
            $link=generate_url(Array("page"=>"$i"), "");
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(array("page" => min($page + 1, intval($view_total_page))), "") ?>
        <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
</div>
<!-- 页标签 end -->

<?php
require_once("admin-footer.php")
?>
<!-- sort by class、year BEGIN -->
<script>
    <?php $args['sort_method'] = $class; ?>
    $("#class").click(function() {
        var link = "<?php echo generate_url(array("page" => "1"), "") ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $year; ?>
    $("#year").click(function() {
        var link = "<?php echo generate_url(array("page" => "1"), "") ?>";
        window.location.href = link;
    });
    $("#mode").change(function(){
        if($(this).val() =="A" || $(this).val() =="B"){
            $("#A").show();
            $("#B").show();
            $("#C").hide();
            $('#prefix').attr("disabled",false);
            $('#class_num').attr("disabled",false);
            $('#classes').attr("disabled",true);
        } else {
            $("#A").hide();
            $("#B").hide();
            $("#C").show();
            $('#prefix').attr("disabled",true);
            $('#class_num').attr("disabled",true);
            $('#classes').attr("disabled",false);
        }
    });
</script>