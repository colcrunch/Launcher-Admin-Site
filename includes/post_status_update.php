<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start();

if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ../login.php");
	end();
}

if($logged == 'in')
{
	$id = $_GET['id'];
	$status = $_GET['status'];
	$query = $conn->prepare("UPDATE `articles` SET `status` = ? WHERE `id` = ?");
  $query->bind_param("ss",$status,$id);
  $query->execute();

	echo $id;
	echo $status;

	header("Location: ../articles_man.php");
}
else{
	header("Location: ../login.php");
}
?>
