<?php
/**
 * Created by PhpStorm.
 * User: d-star
 * Date: 12/20/16
 * Time: 12:31 AM
 */
require_once "include/db_info.inc.php";

if(isset($_GET['class'])) {
    $class=$mysqli->real_escape_string($_GET['class']);
    $sql="SELECT user_id, nick, class, real_name FROM users WHERE class='$class'";
    $students=$mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
}

?>


<?php
require_once "template/".$OJ_TEMPLATE."/header.php";
?>
<table>
    <tbody>
    <tr>
        <th>user_id</th>
        <th>nick</th>
        <th></th>
        <th></th>
    </tr>
    <?php
    if(isset($students)) {
        foreach ($students as $stu) {
            echo "<tr>";
            foreach ($stu as $cell) {
                echo "<td>$cell</td>";
            }
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>
<?php
require_once "template/".$OJ_TEMPLATE."/footer.php"
?>