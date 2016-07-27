<?php require_once ("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
?>
<ol>
<li>
Copy from http://plg1.cs.uwaterloo.ca/~acm00/
<form method=POST action=problem_add_page_waterloo.php>
  <input name=url type=text size=100>
  <input type=submit>
</form>
</li>
<li>
Copy from acm.pku.edu.cn
<form method=POST action=problem_add_page_pku.php>
  <input name=url type=text size=100>
  <input type=submit>
</form>
</li>

<li>
Copy from acm.hdu.edu.cn
<form method=POST action=problem_add_page_hdu.php>
  <input name=url type=text size=100>
  <input type=submit>
</form>
</li>

<li>
Copy from acm.zju.edu.cn
<form method=POST action=problem_add_page_zju.php>
  <input name=url type=text size=100>
  <input type=submit>
</form>
</li>

</ol>
