<?php

include "db_connect.php";


$id = $_POST['id'];
$query = $conn->query("DELETE FROM `articles` WHERE `id` = ".$id);


header("Location: ../articles_man.php");
?>