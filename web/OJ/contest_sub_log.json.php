<?php
if(isset($_GET['cid'])){
	require_once "./include/db_info.inc.php";
	$cid=$_GET['cid'];

	$sql="SELECT COUNT(1) FROM contest_problem WHERE contest_id=$cid";
	$problem_cnt=$mysqli->query($sql)->fetch_array()[0];

	$sql="SELECT UNIX_TIMESTAMP(start_time) FROM contest WHERE contest_id=$cid";
	$start_time=$mysqli->query($sql)->fetch_array()[0];

	$json=array();
	$json['problem_count']=intval($problem_cnt);

	$sql="SELECT user_limit FROM contest WHERE contest_id=$cid";
	$user_limit=$mysqli->query($sql)->fetch_array()[0];

	$sql = "SELECT user_id FROM contest_excluded_user WHERE contest_id = $cid";
	$res = $mysqli->query($sql);
	$is_exclude = array();
	while ($row = $res->fetch_array()) {
		$is_exclude[$row['user_id']] = true;
	}
	if($user_limit=="Y"){
		$sql=<<<SQL
		SELECT
			solution.solution_id,
			solution.user_id,
			team.nick,
			solution.num,
			solution.result,
			UNIX_TIMESTAMP(solution.in_date) AS in_date
		FROM
			solution
		LEFT JOIN team ON solution.user_id = team.user_id
		WHERE
			solution.contest_id = $cid
		ORDER BY
			in_date
SQL;
	}
	else{
		$sql=<<<SQL
			SELECT
				solution.solution_id,
				solution.user_id,
				users.nick,
				solution.num,
				solution.result,
				UNIX_TIMESTAMP(solution.in_date) AS in_date
			FROM
				solution
			LEFT JOIN users ON solution.user_id = users.user_id
			WHERE
				solution.contest_id = "$cid"
			ORDER BY
				in_date
SQL;
	}
	$res=$mysqli->query($sql);

	$json['solutions']=array();
	$json['users']=array();
	$vis=array();
	$id=1;
	while($row=$res->fetch_assoc()){
		$temp=array();
		if(isset($vis[$row['user_id']]));
		else{
			$json['users']["$id"]=array(
				"name" => substr($row['nick'], 6),
				"college" => "HZNU",
				"is_exclude" => isset($is_exclude[$row['user_id']])
			);
			$vis[$row['user_id']]="$id";
			$id++;
		}
		$temp['user_id']=$vis[$row['user_id']];
		$index=$row['num']+1;
		$temp['problem_index']="$index";
		$temp['verdict']=$row['result']==4?"AC":"WA";
		$temp['submitted_seconds']=$row['in_date']-$start_time;

		$run_id=$row['solution_id'];
		$json['solutions']["$run_id"]=$temp;
	}
	echo json_encode($json);
}
?>