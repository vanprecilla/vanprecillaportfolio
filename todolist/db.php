<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'van01user');
define('DB_PASSWORD', '');
define('DB_NAME', 'van01user');

$link = mysqli_connect("localhost", "van01user","" ,"van01user");

if ($link === false) {
    die("ERROR: Could not connect. " .mysqli_connect_error());
}

?>