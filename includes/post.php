<?php

include_once 'db_connect.php';
include_once 'functions.php';
include_once 'psl-config.php';

$username = $_POST['username'];
$title = $_POST['title'];
$text = $_POST['text'];

	if(isset($_POST['status'])){
		$status = $_POST['status'];
	}
	else{
		$status = "Posted";
	}


	$query = $conn->prepare("INSERT INTO `articles`  (`id`, `username`, `date`, `status`, `edited`, `edate`, `eby`, `title`, `text`) VALUES (NULL, ?, CURRENT_TIMESTAMP, ?, 0, NULL, NULL, ?, ?)");
	$query->bind_param("ssss",$username,$status,$title,$text);
	$query->execute();
	$query->close();
	header("Location: ../articles_man.php");

//echo $_POST['title'];
//echo $_POST['text'];
//echo $_POST['username'];
//echo $status;




?>
