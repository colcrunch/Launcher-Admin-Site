<?php
include_once 'functions.php';
include_once 'db_connect.php';
include_once 'psl-config.php';


  sec_session_start();

  if(login_check($conn) == true){
    $logged = 'in';
  } else {
    $logged = 'out';
    header("Location: ./login.php");
    end();
  }

  $qid = $_POST['qid'];
  $user = $_POST['username'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];
  if(isset($_POST['status'])){
		$status = 0;
	}
	else{
		$status = 1;
	}
  $ref = $_POST['ref'];

$query = $conn->prepare("UPDATE `helparticles` SET question= ?, answer= ?, complete= ? WHERE qid= ?");
$query->bind_param("ssss", $question, $answer, $status, $qid);
$query->execute();


header('Location: '.$ref.'?u=1');

 ?>
