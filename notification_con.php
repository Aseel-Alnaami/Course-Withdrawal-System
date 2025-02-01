<?php
session_start();
include 'DB.php';

$notid = $_POST['notid'];

$x= not_con($notid); //DB

if($x==1) {
    echo 1;
} else {
    echo 0;
}


?>