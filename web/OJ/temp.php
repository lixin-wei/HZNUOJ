<?php
/**
 * Created by PhpStorm.
 * User: d-star
 * Date: 2/11/17
 * Time: 9:31 PM
 */

$in = $_GET['in'];
echo htmlentities($in);
$r_in = htmlentities($in);
echo "<li><a href=\"$r_in\">1</a></li>";