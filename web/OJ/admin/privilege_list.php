<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php 
  require("admin-header.php");
  require_once("../include/set_get_key.php");
  require_once('../include/setlang.php');
  require_once("../include/my_func.inc.php");
  // everyone can see
  // if (!HAS_PRI("edit_privilege_group")) {
  //   echo "Permission denied!";
  //   exit(1);
  // }
  $user_group=get_group($mysqli->real_escape_string($_SESSION['user_id']));
  $user_order=get_order($user_group);
  // echo "<pre>user_group:$user_group</pre>";
  // echo "<pre>user_order:$user_order</pre>";
  $sql=<<<SQL
    SELECT
      a.*,
      b.group_order
    FROM 
      privilege a 
    JOIN
      privilege_groups b
    ON
      a.rightstr=b.group_name
    ORDER BY
      a.user_id, b.group_order
SQL;
  $result=$mysqli->query($sql) or die($mysqli->error);
  ?>
  <title><?php echo $html_title.$MSG_PRIVILEGE.$MSG_LIST ?></title>
  <h1><?php echo $MSG_PRIVILEGE.$MSG_LIST ?></h1>
  <h4><?php echo $MSG_HELP_PRIVILEGE_LIST ?></h4>
  <hr/>
  <table class='table table-condensed table-striped table-hover table-bordered' style='white-space: nowrap;width:800px;'>
  <thead><tr>
  	<th><?php echo $MSG_USER_ID?></th>
    <th><?php echo $MSG_PRIVILEGE ?></th>
    <th><?php echo $MSG_Operations ?></th>
  </tr></thead>
  <?php
  for (;$row=$result->fetch_object();){
    echo "<tr>\n<td style='vertical-align:middle;'><a href='../userinfo.php?user=$row->user_id' target='_blank'>$row->user_id</a></td>\n";
    echo "<td style='vertical-align:middle;'>".$row->rightstr."</td>\n";
    if (HAS_PRI("edit_privilege_group") && $user_order<get_order($row->rightstr)) 
      echo "<td style='vertical-align:middle;'><a class='btn btn-primary' href=privilege_delete.php?uid=$row->user_id&rightstr=$row->rightstr&getkey=".$_SESSION['getkey'].">$MSG_DEL</a></td>\n";
    else echo "<td style='vertical-align:middle;'><span class='btn btn-primary' disabled>$MSG_DEL</span></td>";
    echo "</tr>";
  }
?>
</table>
<?php 
  require_once("admin-footer.php")
?>
