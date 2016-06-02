<?php
  include_once 'functions.php';
  include_once 'db_connect.php';
  include_once 'psl-config.php';


  $asker = $_POST['asker'];
  $question = $_POST['question'];

  //echo($asker);
  //echo($question);


  $query = $conn->prepare("INSERT INTO `questions` (`id`, `question`, `asked_by`, `asked_on`, `status`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, 'Pending')");
  $query->bind_param("ss",$question,$asker);
  $query->execute();
  header("Location: ../help.php?a=1");

  ?>
