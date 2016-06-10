<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
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
$email = $_POST['email'];
$safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);

$query = $conn->prepare("UPDATE  `members` SET `email` = ? WHERE `id` = ?");
$query->bind_param("ss", $safe_email, $id);
$query->execute();


header("Location: ../accounts.php?e=1");
?>
