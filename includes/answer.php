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

  $query = $conn->prepare("INSERT INTO `helparticles` (`id`, `question`, `answer`, `user`, `date`, `qid`, `complete`) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?)");
  $query->bind_param("sssss", $question, $answer,$user,$qid,$status);
  $query->execute();
  $query = $conn->query("UPDATE `questions` SET status='Answered' WHERE id=".$qid);

  header("Location: /questions.php");


 ?>
