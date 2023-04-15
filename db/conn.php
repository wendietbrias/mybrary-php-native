<?php

$serverName = "localhost";
$username = "root";
$password = "";
$db = "mybrary";

$conn = new mysqli($serverName,$username,$password,$db);

if($conn->connect_error) {
   die("connection fail : " + $conn->connect_error);
}
