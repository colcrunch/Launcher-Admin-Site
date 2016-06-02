<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($conn) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	header("Location: ./login.php");
	end();
}
if(!isset($_GET['id'])){header('/questions.php');}

$user = $_SESSION['username'];

$query = $conn->prepare("SELECT * FROM `admin` WHERE `username` = ?");
$query->bind_param("s",$user);
$query->execute();
$re = $query->get_result();

$results = array();

 while($result = $re->fetch_assoc()){
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
 } else { header('Location: /help.php'); }

$id = $_GET['id'];

$query = $conn->prepare("SELECT * FROM `questions` WHERE id=?");
$query->bind_param("s", $id);
$query->execute();
$inf=$query->get_result();
$results = array();
while($result = $inf->fetch_assoc()){
  $results[] = $result;
}


 ?>
 <html lang="en">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="description" content="">
     <meta name="author" content="">
     <link rel="icon" href="favicon.ico">

     <title><?php echo $ENT_Name ?> Question Answer Form</title>

     <!-- Bootstrap core CSS -->
     <link href="css/bootstrap.min.css" rel="stylesheet">

     <!-- Custom styles for this template -->
     <link href="css/bootstrap-theme.css" rel="stylesheet">

 	<script src="http://code.jquery.com/jquery.js"></script>

     <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
     <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
     <script src="js/ie-emulation-modes-warning.js"></script>
 	<script src="js/bootstrap.min.js"></script>
 	<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
  <script src="ckeditor/ckeditor.js"></script>

     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
     <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->
 	<style>
 		div.util{
 			float: left;
 			margin-left: 5px;
 		}
 	</style>
   </head>

   <body>

     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="position:relative;margin-bottom:0;">
       <div class="container">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
           </button>
           <a class="navbar-brand" href="#"><?php echo $ENT_Name ?> Admin</a>
         </div>
         <div id="navbar" class="collapse navbar-collapse">
           <ul class="nav navbar-nav">
             <li><a href="index.php">Home</a></li>
             <li><a href="articles_man.php">Articles</a></li>
             <li><a href="accounts.php">Accounts</a></li>
             <li class="active"><a href="help.php">Help</a></li>
           </ul>
         <div id="navbar" class="navbar-right navbar-form">
 		<font color="gray" face="Arial, Helvetica, sans-serif">Logged in as:  <?php echo $_SESSION['username']; ?> &nbsp; </font>
         	<a href="includes/logout.php">
             <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-off"></span> Log Out</button>
         	</a>
         </div>
         </div><!--/.nav-collapse -->
       </div>
     </nav>
 	<br />
  <div class="container">
    <div class="jumbotron">
      <h1>Question Answer Form</h1>
      <p>Here you will andwer questions from users that will appear on the help page. The text editor is a bit bare at the moment, but that will change soon. </p>
      <p style="font-size: 100%;">Note: DO NOT SIGN YOUR NAME ON ANSWERS, OR MENTION THE USER THAT POSTED THE QUESTION!</p>
    </div>

    <h2> Question </h2>
    <form action="includes/answer.php" method="post">
      <div class="row">
        <div class="form-group">
          <div class="col-md-6">
            <label for="Question"> Question </label>
            <input type="textbox" class="form-control .col-md-8" name="question" id="question" <?php echo "value='".$results[0]['question']."'" ?>/>
          </div>
          <div class="col-md-2">
            <label>Date</label>
            <p><?php echo date("d M, Y");?></p>
          </div>
          <div class="col-md-2">
            <label>Asked By</label>
            <p><?php echo $results[0]['asked_by']; ?></p>
          </div>
          <div class="col-md-2">
            <label>Answering As</label>
            <p><?php echo $_SESSION['username'] ?></p>
            <input type="hidden" value="<?php echo $_SESSION['username']; ?>" name="username" />
            <input type="hidden" value="<?php echo $id; ?>" name="qid" />
          </div>
        </div>
      </div>
      <br />
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="text"> Answer Text </label>
              <textarea class="form-control" rows="18" name="answer" >  </textarea>
              <script>CKEDITOR.replace('answer');</script>
            </div>
          </div>
        </div>
        <input type="checkbox" value="Draft" name="status" style="float: left;"> &nbsp <strong>Mark as incomplete?</strong> </input>
        <button type="submit" class="btn btn-success" style="float: right;"><span class="glyphicon glyphicon-save"></span> Save</button>
      </form>

      <br />
  		<hr>
  		<footer>
  			<p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
  		</footer>
  </div>
