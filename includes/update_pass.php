<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start();

$p = $_POST['cp'];
$np = $_POST['np'];
$i = $_POST['id'];

if($_SESSION['user_id'] == $i){

// echo($u);

if(checkpass($i, $p, $conn) == true){
  //Right Password
 if(updatepass($i, $np, $conn) == true){
    //Success!
     header('Location: ../accounts.php?p=1');
 }else{
   //Something went horribly wrong!
   header('Location: ../accounts.php?p=3');
 }
} elseif(checkpass($i, $p, $conn) == false){
  //Wrong Password
  header('Location: ../accounts.php?p=2');
}
} else {
  if(updatepass($i, $np, $conn) == true){
     //Success!
      header('Location: ../accounts.php?p=1&id='.$i);
  }else{
    //Something went horribly wrong!
    header('Location: ../accounts.php?p=3&id='.$i);
  }

}


?>
