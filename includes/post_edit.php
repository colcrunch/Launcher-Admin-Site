<?php

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start();

if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ./login.php");
	end();
}

	$id = $_POST['id'];
	$title = $_POST['title'];
	$text = $_POST['text'];
	$eby = $_POST['username'];


	$query = $conn->prepare("UPDATE `articles` SET `title` = ?, `text` = ?, `edited` = '1', `edate` = CURRENT_TIMESTAMP, `eby` = ?  WHERE `id` = ?");
	$query->bind_param("ssss",$title,$text,$eby,$id);
	$query->execute();

//	echo "UPDATE `articles` SET `title` = '".$title."', `text` = '".$text."', `edited` = '1', `edate` = CURRENT_TIMESTAMP, `eby` = '".$eby."'  WHERE `id` = ".$id;


	header("Location: ../articles_man.php?e=2");




?>
