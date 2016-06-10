<?php

include "db_connect.php";
include "functions.php";

sec_session_start();

if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ./login.php");
	end();
}


$id = $_POST['id'];
$query = $conn->query("DELETE FROM `articles` WHERE `id` = ".$id);


header("Location: ../articles_man.php");
?>
