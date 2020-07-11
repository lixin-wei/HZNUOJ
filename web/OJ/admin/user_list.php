<?php

/**
 * This file is created
 * by lixun516@qq.com
 * @2020.02.03
 **/
?>

<?php require_once("admin-header.php"); ?>
<?php
require_once("../include/set_get_key.php");
require_once("../include/my_func.inc.php");
//分页start
$page = 1;
$args = array();
if (isset($_GET['page'])) $page = intval($_GET['page']);
if (isset($_GET['team'])) $args['team'] = $_GET['team'];
if (isset($_GET['contest'])) $args['contest'] = $_GET['contest'];
if (isset($_GET['defunct'])) $args['defunct'] = $_GET['defunct'];
if (isset($_GET['class'])) $args['class'] = urlencode($_GET['class']);
if (isset($_GET['sort_method'])) $args['sort_method'] = $_GET['sort_method'];
else $args['sort_method'] = "";
if (isset($_GET['keyword'])) {
    $_GET['keyword'] = trim($_GET['keyword']);
    $args['keyword'] = urlencode($_GET['keyword']);
}
if (isset($page)) $args['page'] = $page;
function generate_url($data, $link)
{
    global $args;
    if ($link == "") $link = "user_list.php?";
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
$sql_filter = " WHERE 1 ";
if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
    $keyword = $mysqli->real_escape_string($_GET['keyword']);
    $keyword = "'%$keyword%'";
    $sql_filter .= " AND (";
    if (!isset($_GET['team'])) {
        $sql_filter .= " (user_id LIKE $keyword ) OR (nick LIKE $keyword ) OR (school LIKE $keyword ) OR (email LIKE $keyword )";
    } else {
        $sql_filter .= " (a.user_id LIKE $keyword ) OR (nick LIKE $keyword ) OR (school LIKE $keyword ) OR (institute LIKE $keyword )";
    }
    if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        $sql_filter .= " OR (real_name LIKE $keyword ) ";
    }
    $sql_filter .= ") ";
}
if (isset($_GET['team'])) {
    if ($_GET['team'] != "all" && $_GET['team'] != "") $sql_filter .= " AND `prefix`= '{$mysqli->real_escape_string($_GET['team'])}' ";
    if (isset($_GET['contest']) && $_GET['contest'] != "all" && $_GET['contest'] != "") $sql_filter .= " AND a.`contest_id`= {$mysqli->real_escape_string($_GET['contest'])} ";
}
if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE && isset($_GET['class']) && $_GET['class'] != "all" && $_GET['class'] != "") {
    if($_GET['class']<>"empty"){
        $sql_filter .= " AND `class`='{$mysqli->real_escape_string($_GET['class'])}' ";
    } else $sql_filter .= " AND (ISNULL(`class`) OR `class`='') ";
}
if (isset($_GET['defunct']) && $_GET['defunct'] != "all") {
    if ($_GET['defunct'] == "N") {
        $sql_filter .= " AND `defunct`= 'N' ";
    } else $sql_filter .= " AND `defunct`= 'Y' ";
}
if (!isset($_GET['team']))
    $sql = "SELECT COUNT('user_id') FROM `users` " . $sql_filter;
else
    $sql = "SELECT COUNT('user_id') FROM `team` as a " . $sql_filter;
$result = $mysqli->query($sql)->fetch_all();
$total = 0;
if ($result) $total = $result[0][0];
$page_cnt = 50;
$view_total_page = ceil($total / $page_cnt); //计算页数
$view_total_page = $view_total_page>0?$view_total_page:1;
if ($page > $view_total_page) $args['page'] = $page = $view_total_page;
if ($page < 1) $page = 1;
$left_bound = $page_cnt * $page - $page_cnt;
$u_id = $left_bound;
switch ($args['sort_method']) {
    case 'AccTime_DESC':
        $acctime_icon = "am-icon-sort-amount-desc";
        $regtime_icon = "am-icon-sort";
        $strength_icon = "am-icon-sort";
        $sql_filter .= " ORDER BY `accesstime` DESC,user_id ";
        $accTime = 'AccTime_ASC';
        $regTime = 'RegTime_DESC';
        $strength = 'strength_DESC';
        break;
    case 'AccTime_ASC':
        $acctime_icon = "am-icon-sort-amount-asc";
        $regtime_icon = "am-icon-sort";
        $strength_icon = "am-icon-sort";
        $sql_filter .= " ORDER BY `accesstime`,user_id ";
        $accTime = 'AccTime_DESC';
        $regTime = 'RegTime_DESC';
        $strength = 'strength_DESC';
        break;
    case 'RegTime_ASC':
        $acctime_icon = "am-icon-sort";
        $regtime_icon = "am-icon-sort-amount-asc";
        $strength_icon = "am-icon-sort";
        $sql_filter .= " ORDER BY `reg_time`,user_id ";
        $accTime = 'AccTime_DESC';
        $regTime = 'RegTime_DESC';
        $strength = 'strength_DESC';
        break;
    case 'RegTime_DESC':
    default:
        $acctime_icon = "am-icon-sort";
        $regtime_icon = "am-icon-sort-amount-desc";
        $strength_icon = "am-icon-sort";
        $sql_filter .= " ORDER BY `reg_time` DESC,user_id ";
        $accTime = 'AccTime_DESC';
        $regTime = 'RegTime_ASC';
        $strength = 'strength_DESC';
        break;
    case 'strength_DESC':
        $acctime_icon = "am-icon-sort";
        $regtime_icon = "am-icon-sort";
        $strength_icon = "am-icon-sort-amount-desc";
        $sql_filter .= " ORDER BY `strength` DESC,user_id ";
        $accTime = 'AccTime_DESC';
        $regTime = 'RegTime_DESC';
        $strength = 'strength_ASC';
        break;
    case 'strength_ASC':
        $acctime_icon = "am-icon-sort";
        $regtime_icon = "am-icon-sort";
        $strength_icon = "am-icon-sort-amount-asc";
        $sql_filter .= " ORDER BY `strength`,user_id ";
        $accTime = 'AccTime_DESC';
        $regTime = 'RegTime_DESC';
        $strength = 'strength_DESC';
        break;
}
$sql_filter .= " LIMIT $left_bound, $page_cnt";
$view_users = array();
$cnt = 0;
$colspan = (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) ? 16 : 13;
if (!isset($_GET['team'])) { //查询普通账号
    $sql = "SELECT `user_id`,`nick`,`defunct`,`accesstime`,`reg_time`,`ip`,`email`,`school`,`stu_id`,`class`,`real_name`,`strength`,`level` FROM `users` " . $sql_filter;
    //echo $sql;    
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_object()) {
        if (HAS_PRI("edit_user_profile")) $view_users[$cnt][0] = "<input type=checkbox name='cid[]' value='$row->user_id' />&nbsp;" . ++$u_id;
        else $view_users[$cnt][0] = ++$u_id;
        $view_users[$cnt][1] = "<a href='../userinfo.php?user=" . $row->user_id . "' target='_blank'>" . $row->user_id . "</a>";
        $view_users[$cnt][2] = $row->nick;
        if (HAS_PRI("edit_user_profile")) {
            if ($row->user_id != 'admin' && $row->user_id != $_SESSION['user_id']) {
                if ($row->defunct == "N") {
                    $view_users[$cnt][3] = "<a class='btn btn-primary' href='user_df_change.php?cid=" . $row->user_id . "&getkey=" . $_SESSION['getkey'] . "'>" . $MSG_Available . "</a>";
                } else {
                    $view_users[$cnt][3] = "<a class='btn btn-danger' href='user_df_change.php?cid=" . $row->user_id . "&getkey=" . $_SESSION['getkey'] . "'>" . $MSG_Reserved . "</a>";
                }
                $view_users[$cnt][4] = get_group($row->user_id);
                if (!IS_ADMIN($row->user_id)) {
                    $view_users[$cnt][5] = "<a class='btn btn-primary' href='#' onclick='javascript:if(confirm(\" $MSG_DEL ?\")) location.href=\"user_edit.php?del&cid=$row->user_id&getkey={$_SESSION['getkey']}\"'>$MSG_DEL</a>";
                } else $view_users[$cnt][5] = "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
            } else {
                if ($row->defunct == "N") {
                    $view_users[$cnt][3] = "<span class='btn btn-primary' disabled>$MSG_Available</span>";
                } else {
                    $view_users[$cnt][3] = "<span class='btn btn-danger' disabled>$MSG_Reserved</span>";
                }
                $view_users[$cnt][4] = get_group($row->user_id);
                $view_users[$cnt][5] = "<span class='btn btn-primary' disabled>$MSG_DEL</span>";
            }
            if ($row->user_id != $_SESSION['user_id'] && get_order(get_group($row->user_id)) <= get_order(get_group(""))) {
                $view_users[$cnt][6] = "<span class='btn btn-primary' disabled>$MSG_EDIT</span>";
            } else $view_users[$cnt][6] = "<a class='btn btn-primary' href='" . generate_url("", "user_edit.php") . "&cid=$row->user_id'>$MSG_EDIT</a>";
            if (!IS_ADMIN($row->user_id)) {
                $view_users[$cnt][7] = "<a class='btn btn-primary' href='changepass.php?cid=$row->user_id' target='_blank'>$MSG_SETPASSWORD</a>";
            } else $view_users[$cnt][7] = "<span class='btn btn-primary' disabled>$MSG_SETPASSWORD</span>";
        } else {
            if ($row->defunct == "N") {
                $view_users[$cnt][3] = "<span class='btn btn-primary' disabled>$MSG_Available</span>";
            } else {
                $view_users[$cnt][3] = "<span class='btn btn-danger' disabled>$MSG_Reserved</span>";
            }
            $view_users[$cnt][4] = get_group($row->user_id);
        }
        $view_users[$cnt][8] = round($row->strength);
        $view_users[$cnt][9] = $row->level;
        $view_users[$cnt][10] = $row->accesstime;
        $view_users[$cnt][13] = $row->email;
        $view_users[$cnt][14] = $row->school;
        if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
            $view_users[$cnt][17] = $row->class;
            $view_users[$cnt][16] = $row->real_name;
            $view_users[$cnt][15] = $row->stu_id;
        }
        $view_users[$cnt][11] = $row->reg_time;
        $view_users[$cnt][12] = $row->ip;
        $cnt++;
    }
} else { //查询比赛临时账号
    $sql = "SELECT a.`user_id`,a.`nick`,a.`contest_id`, `contest`.`title`, `contest`.`defunct`,a.`school`,a.`class`,a.`stu_id`,a.`real_name`,a.`accesstime`,a.`reg_time`,a.`ip`,a.`institute`,a.`seat` FROM `team` as a ";
    $sql .= " LEFT JOIN `contest` ON a.`contest_id` = `contest`.`contest_id`";
    $sql .= $sql_filter;
    //echo $sql;
    //exit(0);
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_object()) {
        if (HAS_PRI("edit_user_profile")) $view_users[$cnt][0] = "<input type=checkbox name='cid[]' value='$row->user_id@$row->contest_id' />&nbsp;" . ++$u_id;
        else $view_users[$cnt][0] = ++$u_id;
        $view_users[$cnt][1] = $row->user_id;
        $view_users[$cnt][2] = $row->nick;
        if (HAS_PRI("edit_user_profile")) {
            $view_users[$cnt][3] = "<a class='btn btn-primary' href='' onclick='javascript:if(confirm(\" $MSG_DEL ?\")) location.href=\"user_edit.php?team&del&cid=$row->user_id@$row->contest_id&getkey={$_SESSION['getkey']}\"'>$MSG_DEL</a>";
            $view_users[$cnt][4] = "<a class='btn btn-primary' href='" . generate_url("", "user_edit.php") . "&cid=$row->user_id@$row->contest_id'>$MSG_EDIT</a>";
            $view_users[$cnt][5] = "<a class='btn btn-primary' href='user_edit.php?resetpwd&cid=$row->user_id@$row->contest_id&getkey={$_SESSION['getkey']}'>$MSG_RESET$MSG_PASSWORD</a>";
        }
        $contest_status = ($row->defunct == 'Y') ? '<font color=red><b>【' . $MSG_Reserved . '】</b></font>' : "";
        $view_users[$cnt][6] = ($row->title) ? "<a href='../status.php?cid=$row->contest_id' target='_blank'>【{$row->contest_id}】$row->title $contest_status</a>" : "【{$row->contest_id}】";
        $view_users[$cnt][7] = $row->accesstime;
        $view_users[$cnt][10] = $row->school;
        if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
            $view_users[$cnt][13] = $row->class;
            $view_users[$cnt][12] = $row->real_name;
            $view_users[$cnt][11] = $row->stu_id;
        }
        $view_users[$cnt][14] = $row->seat;
        $view_users[$cnt][15] = $row->institute;
        $view_users[$cnt][8] = $row->reg_time;;
        $view_users[$cnt][9] = $row->ip;
        $cnt++;
    }
}

?>
<title><?php echo $html_title . $MSG_USER . $MSG_LIST ?></title>
<h1><?php echo $MSG_USER . $MSG_LIST ?></h1>
<h4><?php echo $MSG_HELP_USER_LIST ?></h4>
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <?php if (isset($_GET['team'])) { ?>
            <li><a href="user_list.php"><?php echo $MSG_USER . $MSG_LIST ?></a></li>
            <li class="am-active"><a href="user_list.php?team=all"><?php echo $MSG_TEAM . $MSG_LIST ?></a></li>
            <li><a href="class_list.php"><?php echo $MSG_Class . $MSG_LIST ?></a></li>
            <li><a href="change_class.php"><?php echo $MSG_ChangeClass ?></a></li>
            <li><a href="reg_code.php"><?php echo $MSG_REG_CODE ?></a></li>
        <?php } else { ?>
            <li class="am-active"><a href="user_list.php"><?php echo $MSG_USER . $MSG_LIST ?></a></li>
            <li><a href="user_list.php?team=all"><?php echo $MSG_TEAM . $MSG_LIST ?></a></li>
            <li><a href="class_list.php"><?php echo $MSG_Class . $MSG_LIST ?></a></li>
            <li><a href="change_class.php"><?php echo $MSG_ChangeClass ?></a></li>
            <li><a href="reg_code.php"><?php echo $MSG_REG_CODE ?></a></li>
        <?php } ?>
    </ul>
</div>
<!-- 查找 start -->
<div class='am-g' style="margin-left: 5px;">
    <form id="searchform" class="am-form am-form-inline">
        <?php if (isset($_GET['team'])) { ?>
            <div class='am-form-group'>
                <select class="selectpicker show-tick" data-live-search="true" id='team' name='team' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                    <option value='all' <?php if (isset($_GET['team']) && ($_GET['team'] == "" || $_GET['team'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_TEAM ?></option>
                    <?php
                    $sql = "SELECT DISTINCT `prefix` FROM `team` ORDER BY `prefix`";
                    $result = $mysqli->query($sql);
                    $prefix = $result->fetch_all();
                    $result->free();
                    foreach ($prefix as $row) {
                        echo "<option value='" . $row[0] . "' ";
                        if (isset($_GET['team']) && $_GET['team'] == $row[0])  echo "selected";
                        echo ">" . $row[0] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class='am-form-group'>
                <select class="selectpicker show-tick" data-live-search="true" id='contest' name='contest' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                    <option value='all' <?php if (isset($_GET['contest']) && ($_GET['contest'] == "" || $_GET['contest'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Special ?></option>
                    <?php
                    $sql = "SELECT DISTINCT `team`.`contest_id`, `contest`.`title`,`contest`.`defunct` FROM `team`";
                    $sql .= " LEFT JOIN `contest` ON `team`.`contest_id` = `contest`.`contest_id` ORDER BY `team`.`contest_id` desc";
                    $result = $mysqli->query($sql);
                    $contest = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                    foreach ($contest as $row) {
                        echo "<option value='" . $row['contest_id'] . "' ";
                        if (isset($_GET['contest']) && $_GET['contest'] == $row['contest_id'])  echo "selected";
                        $contest_status = ($row['defunct'] == 'Y') ? '【' . $MSG_Reserved . '】' : "";
                        echo ">【" . $row['contest_id'] . "】" . $row['title'] . $contest_status . "</option>";
                    }
                    ?>
                </select>
            </div>
        <?php }else { ?>
            <div class='am-form-group'>
            <select class="selectpicker show-tick" id='defunct' name='defunct' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                <option value='all' <?php if (isset($_GET['defunct']) && ($_GET['defunct'] == "" || $_GET['defunct'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_STATUS ?></option>
                <option value='N' <?php if (isset($_GET['defunct']) && $_GET['defunct'] == "N" ) echo "selected"; ?>> <?php echo $MSG_Available?></option>
                <option value='Y' <?php if (isset($_GET['defunct']) && $_GET['defunct'] == "Y" ) echo "selected"; ?>> <?php echo $MSG_Reserved ?></option>
            </select>
        </div>
        <?php }
        if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) {
        ?>
            <div class='am-form-group'>
                <select class="selectpicker show-tick" data-live-search="true" id='class' name='class' data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
                    <option value='all' <?php if (isset($_GET['class']) && ($_GET['class'] == "" || $_GET['class'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Class ?></option>
                    <option value='其它' <?php if (isset($_GET['class']) && $_GET['class'] == "其它") echo "selected"; ?>>其它</option>
                    <option value='empty' <?php if (isset($_GET['class']) && $_GET['class'] == "empty") echo "selected"; ?>>无归属班级</option>                    
                    <?php
                    if (isset($_GET['team'])) {
                        $sql = "SELECT DISTINCT `class` FROM `team` WHERE NOT ISNULL(`class`) AND `class`<>'' AND `class`<>'其它' ORDER BY `class`";
                    } else $sql = "SELECT DISTINCT `class` FROM `users` WHERE NOT ISNULL(`class`) AND `class`<>'' AND `class`<>'其它' ORDER BY `class`";
                    $result = $mysqli->query($sql);
                    $prefix = $result->fetch_all();
                    $result->free();
                    foreach ($prefix as $row) {
                        echo "<option value='" . $row[0] . "' ";
                        if (isset($_GET['class']) && $_GET['class'] == $row[0])  echo "selected";
                        echo ">" . $row[0] . "</option>";
                    }
                    ?>
                </select>
            </div>
        <?php } ?>
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
<style type="text/css" media="screen">
    #acctime:hover,
    #regtime,
    #strength:hover {
        cursor: pointer;
    }
</style>
<div class="am-g am-scrollable-horizontal" style="max-width: 1300px;margin-left: 5px;">
    <?php if (!isset($_GET['team'])) { ?>
        <!-- 罗列普通用户 start -->
        <form action="user_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>" method='post'>
            <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;">
                <thead>
                    <?php if (HAS_PRI("edit_user_profile")) { ?>
                        <tr>
                            <td colspan=<?php echo $colspan + 2 ?>>
                                <input type=submit name='delete' class='btn btn-default' value='<?php echo $MSG_DEL ?>' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) $("form").attr("action","user_edit.php?del&getkey=<?php echo $_SESSION['getkey'] ?>");'>&nbsp;
                                <input type=submit name='enable' class='btn btn-default' value='<?php echo $MSG_Available ?>'>&nbsp;
                                <input type=submit name='disable' class='btn btn-default' value='<?php echo $MSG_Reserved ?>'>
                                <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { 
                                    require_once("../include/classList.inc.php");
                                    $classList = get_classlist(true, "");
                                ?>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <select name="new_class" class="selectpicker show-tick" data-live-search="true" data-width="auto" data-size="8" data-title="选择一个<?php echo $MSG_Class ?>">
                                <option value='' selected></option>
                                <?php 
                                    foreach ($classList as $c){
                                        if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
                                        foreach ($c[1] as $cl){
                                            echo "<option value='$cl'>$cl</option>\n";
                                        }
                                        if ($c[0]) echo "</optgroup>\n";
                                    }
                                ?>
                                </select>&nbsp;
                                <input type=submit name='changeClass' class='btn btn-default' value='<?php echo $MSG_ChangeClass ?>' onclick='javascript:if(confirm("<?php echo $MSG_ChangeClass ?>?")) $("form").attr("action","user_edit.php?getkey=<?php echo $_SESSION['getkey'] ?>");'>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <th width="10px"><input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'>&nbsp;<?php echo $MSG_ID ?></th>
                        <?php } else { ?>
                        <tr>
                            <th width="10px"><?php echo $MSG_ID ?></th>
                        <?php } ?>
                        <th><?php echo $MSG_USER_ID ?></th>
                        <th><?php echo $MSG_NICK ?></th>
                        <th><?php echo $MSG_STATUS ?></th>
                        <th><?php echo $MSG_PRIVILEGE ?></th>
                        <?php if (HAS_PRI("edit_user_profile")) { ?>
                            <th colspan="3" style="text-align: center"><?php echo $MSG_Operations ?></th>
                        <?php } ?>
                        <th id="strength"><?php echo $MSG_STRENGTH ?>&nbsp;<span class="<?php echo $strength_icon ?>"></span></th>
                        <th><?php echo $MSG_LEVEL ?></th>
                        <th id="acctime"><?php echo $MSG_AccessTime ?>&nbsp;<span class="<?php echo $acctime_icon ?>"></span></th>
                        <th><?php echo $MSG_EMAIL ?></th>
                        <th><?php echo $MSG_SCHOOL ?></th>
                        <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
                            <th><?php echo $MSG_Class ?></th>
                            <th><?php echo $MSG_REAL_NAME ?></th>
                            <th><?php echo $MSG_StudentID ?></th>
                        <?php } ?>
                        <th id="regtime"><?php echo $MSG_RegTime ?>&nbsp;<span class="<?php echo $regtime_icon ?>"></span></th>
                        <th><?php echo $MSG_RegIP ?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($view_users as $row) {
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
        <!-- 罗列普通用户 end -->
    <?php } else { ?>
        <!-- 罗列比赛账号 start -->
        <form method='post'>
            <table class="table table-hover table-bordered table-condensed table-striped" style="white-space: nowrap;">
                <thead>
                    <?php
                    if (HAS_PRI("edit_user_profile")) {
                        $view_contest = get_contests("");
                    ?>
                        <tr>
                            <td colspan=<?php echo $colspan ?>>
                                <input type=submit name='delete' class='btn btn-default' value='<?php echo $MSG_DEL ?>' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) $("form").attr("action","user_edit.php?team&del&getkey=<?php echo $_SESSION['getkey'] ?>");'>&nbsp;
                                <input type=submit name='resetpwd' class='btn btn-default' value='<?php echo $MSG_RESET . $MSG_PASSWORD ?>' onclick='javascript:if(confirm("<?php echo $MSG_RESET . $MSG_TEAM . $MSG_PASSWORD ?>?")) $("form").attr("action","user_edit.php?resetpwd&getkey=<?php echo $_SESSION['getkey'] ?>");'>
                                <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { 
                                    require_once("../include/classList.inc.php");
                                    $classList = get_classlist(true, "");
                                ?>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <select name="new_class" class="selectpicker show-tick" data-live-search="true" data-width="auto" data-size="8" data-title="选择一个<?php echo $MSG_Class ?>" >
                                <option value='' selected></option>
                                <?php 
                                    foreach ($classList as $c){
                                        if($c[0]) echo "<optgroup label='$c[0]级'>\n"; else echo "<optgroup label='无入学年份'>\n";
                                        foreach ($c[1] as $cl){
                                            echo "<option value='$cl'>$cl</option>\n";
                                        }
                                        if ($c[0]) echo "</optgroup>\n";
                                    }
                                ?>
                                </select>&nbsp;
                                <input type=submit name='changeClass' class='btn btn-default' value='<?php echo $MSG_ChangeClass ?>' onclick='javascript:if(confirm("<?php echo $MSG_ChangeClass ?>?")) $("form").attr("action","user_edit.php?team&getkey=<?php echo $_SESSION['getkey'] ?>");'>
                                <?php }?>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <select name="new_contest_id" class="selectpicker show-tick" data-live-search="true" data-width="auto"  data-size="8" data-title="选择一个<?php echo $MSG_CONTEST ?>">
                                    <option value='' selected></option>
                                    <?php
                                    foreach ($view_contest as $view_con) :
                                        if ($view_con['data']) { ?>
                                            <optgroup <?php echo "label='{$view_con['type']}' {$view_con['disabled']}" ?>>
                                                <?php foreach ($view_con['data'] as $contest) :
                                                    $contest_status = ($contest['defunct'] == 'Y') ? '【' . $MSG_Reserved . '】' : ""; ?>
                                                    <option value="<?php echo $contest['contest_id'] ?>" <?php if ($contest['contest_id'] == $row->contest_id) echo "selected" ?>><?php echo "【" . $contest['contest_id'] . "】" . $contest['title'] . $contest_status ?></option>
                                                <?php endforeach ?>
                                            </optgroup>
                                    <?php }
                                    endforeach  ?>
                                </select>&nbsp;
                                <input type=submit name='changeTeamContest' class='btn btn-default' value='<?php echo $MSG_ChangeTeamContest ?>' onclick='javascript:if(confirm("<?php echo $MSG_ChangeTeamContest ?>?")) $("form").attr("action","user_edit.php?getkey=<?php echo $_SESSION['getkey'] ?>");'>
                            </td>
                        </tr>
                        <tr>
                            <th width="10px"><input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'>&nbsp;<?php echo $MSG_ID ?></th>
                        <?php } else { ?>
                        <tr>
                            <th width="10px"><?php echo $MSG_ID ?></th>
                        <?php } ?>
                        <th><?php echo $MSG_USER_ID ?></th>
                        <th><?php echo $MSG_NICK ?></th>
                        <?php if (HAS_PRI("edit_user_profile")) { ?>
                            <th colspan="3" style="text-align: center"><?php echo $MSG_Operations ?></th>
                        <?php } ?>
                        <th><?php echo $MSG_CONTEST ?></th>
                        <th id="acctime"><?php echo $MSG_AccessTime ?>&nbsp;<span class="<?php echo $acctime_icon ?>"></span></th>                        
                        <th><?php echo $MSG_SCHOOL ?></th>
                        <?php if (isset($OJ_NEED_CLASSMODE) && $OJ_NEED_CLASSMODE) { ?>
                            <th><?php echo $MSG_Class ?></th>
                            <th><?php echo $MSG_REAL_NAME ?></th>
                            <th><?php echo $MSG_StudentID ?></th>
                        <?php } ?>
                        <th><?php echo $MSG_Seat ?></th>
                        <th><?php echo $MSG_Institute ?></th>
                        <th id="regtime"><?php echo $MSG_RegTime ?>&nbsp;<span class="<?php echo $regtime_icon ?>"></span></th>
                        <th><?php echo $MSG_RegIP ?></th>                        
                        </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($view_users as $row) {
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
        <!-- 罗列比赛账号 end -->
    <?php }; ?>
</div>
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
<!-- sort by acctime、regtime BEGIN -->
<script>
    <?php $args['sort_method'] = $accTime; ?>
    $("#acctime").click(function() {
        var link = "<?php echo generate_url(array("page" => "1"), "") ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $regTime; ?>
    $("#regtime").click(function() {
        var link = "<?php echo generate_url(array("page" => "1"), "") ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $strength; ?>
    $("#strength").click(function() {
        var link = "<?php echo generate_url(array("page" => "1"), "") ?>";
        window.location.href = link;
    });
</script>
<!-- sort by acctime、regtime  END -->
