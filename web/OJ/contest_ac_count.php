<?php
if(isset($_GET['cid'])){
	require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/header.php";
	echo "<div class='am-container' style='max-width:500px;'>";
	require_once("./include/const.inc.php");
	$cid=$_GET['cid'];
	$sql=<<<SQL
		SELECT
			team.nick,
			solution.num
		FROM
			solution
		LEFT JOIN team ON solution.user_id = team.user_id
		WHERE solution.contest_id=$cid AND solution.result=4
		ORDER BY solution.in_date DESC
SQL;
	$res=$mysqli->query($sql);
	$cnt=$res->num_rows;
	echo "<table class='am-table am-table-striped am-table-hover'>";
	while($row=$res->fetch_object()){
		echo <<<HTML
			<tr>
				<td>$cnt</td>
				<td>{$PID[$row->num]}</td>
				<td>$row->nick</td>
			</tr>
HTML;
		$cnt--;
	}
	echo "</table>";
	echo "</div>";
	require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/footer.php";
}
?>