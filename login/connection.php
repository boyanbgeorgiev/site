<?php

$dbhost = "192.168.20.99";
$dbuser = "root";
$dbpass = "bobiubuntu";
$dbname = "login_sample_DB";

if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass))
{
    die("fail");
}