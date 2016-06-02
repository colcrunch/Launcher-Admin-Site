<?php
include_once 'db_connect.php';
include_once 'psl-config.php';

$id = $_POST['id'];
$email = $_POST['email'];
$safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);

$query = $conn->prepare("UPDATE  `members` SET `email` = ? WHERE `id` = ?");
$query->bind_param("ss", $safe_email, $id);
$query->execute();


header("Location: ../accounts.php?e=1");
?>
