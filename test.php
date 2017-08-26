<?php
/**
 * Project:  
 * Creator:
 * Date:
 * Time:
 */
echo '<h1>测试MYSQL</h1> ';
$mysqli = new mysqli('172.30.194.81','root','six6_gud8,lyp100tui.tk','sampledb');
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Success... ' . $mysqli->host_info . "\n";

$mysqli->close();
?>