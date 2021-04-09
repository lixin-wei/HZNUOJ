<?php
require_once('include/db_info.inc.php');
require_once('include/setlang.php');
require_once "template/".$OJ_TEMPLATE."/header.php";
?>

<style type="text/css">
	.ac-row {
		cursor: pointer;
	}
	.ac-row.fb {
		color: green;
	}
	.strong > td {
		background: #ff8e8a !important;
	}
</style>

<?php
if(isset($_GET['cid'])){
	echo "<div class='am-container' style='max-width:500px;'>";
	require_once("./include/const.inc.php");
	$cid=$mysqli->real_escape_string($_GET['cid']);
	$highlight_id = intval($_GET['highlight_id']);
	$sql=<<<SQL
		SELECT
			team.nick,
			solution.num
		FROM
			solution
		LEFT JOIN team ON solution.user_id = team.user_id
		AND solution.contest_id = team.contest_id
		WHERE
			solution.contest_id = $cid
		AND solution.result = 4
		ORDER BY
			solution.in_date DESC
SQL;
	$res=$mysqli->query($sql);
	$ac_log_arr = [];
	while($row=$res->fetch_object()) {
		array_push($ac_log_arr, $row);
	}

	// 同nick，同num，只取第一个
	$vis = array();
	$fb_vis = array();
	$is_fb = array();
	$ignore = array();
	$len = count($ac_log_arr);
	$cnt=$res->num_rows;
	for ($i=$len-1 ; $i>=0 ; $i--) {
		$nick = $ac_log_arr[$i]->nick;
		$p_num = $ac_log_arr[$i]->num;
		if (!isset($fb_vis[$p_num])) {
			$fb_vis[$p_num] = true;
			$is_fb[$i] = true;
		}
		if (!isset($vis[$nick])) {
			$vis[$nick] = array();
		}
		if (!isset($vis[$nick][$p_num])) {
			$vis[$nick][$p_num] = true;
		} else {
			$ignore[$i] = true;
			$cnt--;
		}
	}

	
	echo "<table class='am-table am-table-striped am-table-hover'>";
	for ($i=0 ; $i<$len ; $i++){
		if (isset($ignore[$i])) {
			continue;
		}
		$row = $ac_log_arr[$i];
		$label = PID($row->num);
		$class_str = $cnt==$highlight_id ? "strong" : "";
		$fb_class = $is_fb[$i] ? "fb" : "";
		echo <<<HTML
			<tr class="ac-row $class_str $fb_class" id="row_$cnt">
				<td>$cnt</td>
				<td>$label</td>
				<td>$row->nick</td>
			</tr>
HTML;
		$cnt--;
	}
	echo "</table>";
	echo "</div>";
	require_once "template/".$OJ_TEMPLATE."/footer.php";
}
?>

<script type="text/javascript">
	$(".ac-row").click(function() {
		var cid=<?php echo $cid ?>;
		var highlight_id=$(this).attr("id").split('_')[1];
		window.location.href="contest_ac_count.php?cid=" + cid + "&highlight_id=" + highlight_id; 
	});
</script>
