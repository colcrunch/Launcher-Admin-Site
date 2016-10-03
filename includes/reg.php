<?php

include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'functions.php';

sec_session_start();

if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ../login.php");
	end();
}

$user = $_SESSION['username'];

$query = $conn->prepare("SELECT * FROM `admin` WHERE `username` = ?");
$query->bind_param("s",$user);
$query->execute();
$ls = $query->get_result();
$results = array();
 while($result = $ls->fetch_assoc()){
	 $results[] = $result;
 }
 if(!empty($results)){
    $admin = true;
 }
 else{
   $admin = false;
 }
 if($admin == true){
   //DO NOTHING
 } else { header('Location: ../accounts.php'); }

 if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
     // Sanitize and validate the data passed in
     $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
     $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
     $email = filter_var($email, FILTER_VALIDATE_EMAIL);
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       //Invalid email
       $er = 1;
       header('Location: ../accounts.php?r=1');
     }

     $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
     if (strlen($password) != 128) {
         // The hashed pwd should be 128 characters long.
         // If it's not, something really odd has happened
         $er = 1;
         header('Location: ../accounts.php?r=2');
     }


     //Check if email exists
     $em = $conn->prepare("SELECT id FROM members WHERE email = ? LIMIT 1");
     $em->bind_param("s",$email);
     $em->execute();
     if($em->num_rows == 1){
       //email already in use
       $er = 1;
       header('Location: ../accounts.php?r=3');
     }
	 $em->close();

	 
     //Check existing Username
     $us = $conn->prepare("SELECT id FROM members WHERE username = ? LIMIT 1");
     $us->bind_param("s",$username);
     $us->execute(); 
     if($us->num_rows == 1){
       //Username already in use
       $er = 1;
       header('Location: ../accounts.php?r=4');
     }
	 $us->close();

     if(!isset($er)){
     //Create a random salted
     $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

     //Salt password
     $password = hash('sha512', $password.$random_salt);

     //Insert new user into the database
     $query = $conn->prepare("INSERT INTO members (username, email, password, salt) VALUES (?,?,?,?)");
     $query->bind_param("ssss",$username,$email,$password,$random_salt);
     $query->execute();
     header('Location: ../accounts.php?r=5');
	 $query->close();
   }



  }
?>
