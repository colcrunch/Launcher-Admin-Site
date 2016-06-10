<?php

include_once 'db_connect.php';
include_once 'functions.php';
include_once 'psl-config.php';


sec_session_start();
if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ./login.php");
	end();
}

$username = $_POST['username'];
$title = $_POST['title'];
$text = $_POST['text'];

	if(isset($_POST['status'])){
		$status = $_POST['status'];
	}
	else{
		$status = "Posted";
	}


	$query = $conn->prepare("INSERT INTO `articles`  (`id`, `username`, `date`, `status`, `title`, `text`) VALUES (NULL, ?, CURRENT_TIMESTAMP, ?, ?, ?)");
	$query->bind_param("ssss",$username,$status,$title,$text);
	$query->execute();
	header("Location: ../articles_man.php");

//echo $_POST['title'];
//echo $_POST['text'];
//echo $_POST['username'];
//echo $status;




?>
