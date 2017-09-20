<?php
/**
 * User: d-star
 * Date: 4/16/17
 * Time: 6:52 PM
 */
require_once "admin-header.php";
require_once("../include/my_func.inc.php");
if(!HAS_PRI("generate_team")){
    echo "Permission denied!";
    exit(1);
}
if(isset($_POST['user_id'])){
    //require_once("../include/check_post_key.php");
    $user_id=explode("\n",trim($_POST['user_id']));
    $stu_id=explode("\n",trim($_POST['stu_id']));
    $school=explode("\n",trim($_POST['school']));
    $class=explode("\n",trim($_POST['class']));
    $real_name=explode("\n",trim($_POST['real_name']));
    $nick=explode("\n",trim($_POST['nick']));
    $email=explode("\n",trim($_POST['email']));
    $password=explode("\n",trim($_POST['password']));
    $cnt=count($user_id);
    echo "<table class='table'>";
    echo "<tr><th>user_id</th><th>stu_id</th><th>school</th><th>class</th><th>real_name</th><th>nick</th><th>email</th><th>password</th></tr>";
	$exist_cnt = 0;
    foreach ($user_id as $key => $value) {
        //$password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
        //while (is_numeric($password))  $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
        $ori_pass=trim($password[$key]);
        $pass_hash=pwGen($ori_pass);
        
        $user_id[$key]=trim($user_id[$key]);
        $stu_id[$key]=trim($stu_id[$key]);
        $school[$key]=trim($school[$key]);
        $class[$key]=trim($class[$key]);
        $real_name[$key]=trim($real_name[$key]);
        $nick[$key]=trim($nick[$key]);
        $email[$key]=trim($email[$key]);
	$sql = "SELECT COUNT(*) FROM users WHERE user_id = '{$user_id[$key]}'";
	$is_exist = $mysqli->query($sql)->fetch_array()[0];
	if($is_exist > 0) {
		$exist_cnt++;
		continue;
	}
        $sql=<<<SQL
			INSERT INTO users (
				user_id,
				stu_id,
				school,
				class,
				real_name,
				nick,
				email,
				password,
				reg_time
			)
			VALUES
			(
				'{$user_id[$key]}',
				'{$stu_id[$key]}',
				'{$school[$key]}',
				'{$class[$key]}',
				'{$real_name[$key]}',
				'{$nick[$key]}',
				'{$email[$key]}',
				'$pass_hash',
				NOW()
			)
SQL;
        //echo "<pre>$sql</pre>";
        $mysqli->query($sql);
        echo <<<HTML
		<tr>
			<td>{$user_id[$key]}</td>
			<td>{$stu_id[$key]}</td>
			<td>{$school[$key]}</td>
			<td>{$class[$key]}</td>
			<td>{$real_name[$key]}</td>
			<td>{$nick[$key]}</td>
			<td>{$email[$key]}</td>
			<td>$ori_pass</td>
		</tr>
HTML;
    }
    echo "</table>";
	$cnt -= $exist_cnt;
    echo "DONE! $cnt teams imported! $exist_cnt users already exists!";
}

?>
    <form method="post" accept-charset="utf-8">
        user_id:<textarea name="user_id" rows=20></textarea>
        stu_id:<textarea name="stu_id" rows=20></textarea>
        school:<textarea name="school" rows=20></textarea><br/>
        class:<textarea name="class" rows=20></textarea>
        real_name:<textarea name="real_name" rows=20></textarea>
        nick:<textarea name="nick" rows=20></textarea><br/>
        email:<textarea name="email" rows=20></textarea>
        password:<textarea name="password" rows=20></textarea><br/>
        <?php require_once("../include/set_post_key.php");?>
        <button>submit</button>
    </form>
<?php
require_once "admin-footer.php";
?>
