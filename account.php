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

$user = $_SESSION['username'];

if(!isset($_GET['id'])){
  header('Location: /accounts.php');
}
$id = $_GET['id'];

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
 } else { header('Location: /accounts.php'); }

 if(@$_GET['e'] == 1){
   $e = 1;
 }
 else{
   $e = 0;
 }

 if(@$_GET['p'] == 1){
   // 1 = Success
   $p = 1;
 } elseif(@$_GET['p'] == 2){
   // 2 = Failure
   $p = 2;
 } elseif(@$_GET['p'] ==  3) {
   // 3 = Something went horribly wrong
   $p = 3;
 } else {
   $p = 0;
 }

if($_SESSION['user_id'] == $id){
  $own = true;
} else{ $own = false;}


//GET INFO OF USER
$info = $conn->prepare("SELECT username, email FROM members WHERE id = ?");
$info->bind_param("s",$id);
$info->execute();
$inf = $info->get_result();


if($inf->num_rows == 0){
  header('Location: accounts.php?e=1');
}

$row = $inf->fetch_assoc();


?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $ENT_Name ?> Launcher Article Management</title>

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

  <script type="text/JavaScript" src="js/sha512.js"></script>
  <script type="text/JavaScript" src="js/forms.js"></script>
  <script type="text/JavaScript" src="js/passform.js"></script>
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
            <li class="active"><a href="accounts.php">Accounts</a></li>
            <li><a href="help.php">Help</a></li>
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
          <h1>Managing <?php echo($row["username"]."'s") ?> Account</h1>
          <p> Here you can change the email or password of a user.</p>
        </div>
        <br>
        <?php if($e == 1) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Success!</strong> Your email has been updated. Please log out and back in to see a change in the "Current Email" field.
        </div>
        <?php endif ?>
        <?php if($p == 3) : ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Oh No!</strong> Something went horribly wrong! Please contact <?php echo($sysadmin) ?>.
          </div>
        <?php endif ?>
        <?php if($p == 1) : ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong> Your password has been sucessfully changed!
          </div>
        <?php endif ?>
        <?php if($p == 2) : ?>
          <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Woops!</strong> Looks like your current password was incorrect! (If you can not remember your password contact <?php echo($sysadmin) ?> about getting a new password)
          </div>
        <?php endif ?>
        <div class="col-md-6">
          <h3>Update Email</h3>
          <hr>
          <h4> Current Email: </h4>
          <p style="text-indent: 5px;"><strong><?php echo $row["email"] ?></strong></p>
          <form class="form-inline" action="includes/update_email.php" method="post">
            <input type="hidden" name="id" value=<?php echo "'".$id."'" ?> />
              <input class="form-control" type="email" name="email" placeholder="New Email" />
              <button class="btn btn-primary" type="submit"> Update </button>
          </form>
        </div>
        <div class="col-md-6">
          <h3>Update Password</h3>
            <hr>
        <?php if($own == true) : ?>
          <form action="includes/update_pass.php" method="post">
            <input type="hidden" name="id" value=<?php echo "'".$id."'" ?> />
            <div class="form-group">
              <input class="form-control" id="currentPass" name="currentPass" placeholder="Current Password" required />
            </div>
            <div class="form-group">
              <input class="form-control" id="newPass" name="newPass" placeholder="New Password" required />
            </div>
            <div class="form-group">
              <input class="form-control" id="confirmPass" name="confirmPass" placeholder="Confirm New Password" required />
            </div>
            <input type="button" value="Update Password" class="btn btn-primary" onclick="killmenow(this.form, this.form.currentPass, this.form.newPass, this.form.confirmPass);">
          </form>
        <?php endif ?>
        <?php if($own == false) : ?>
          <form action="includes/update_pass.php" method="post">
            <input type="hidden" name="id" value=<?php echo "'".$id."'" ?> />
            <div class="form-group">
              <input class="form-control" id="newPass" name="newPass" placeholder="New Password" required />
            </div>
            <div class="form-group">
              <input class="form-control" id="confirmPass" name="confirmPass" placeholder="Confirm New Password" required />
            </div>
            <input type="button" value="Update Password" class="btn btn-primary" onclick="killmelater(this.form, this.form.newPass, this.form.confirmPass);">
          </form>
        <?php endif ?>
        </div>
      </div>
      <div class="container">
        <hr>
        <footer>
          <p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
        </footer>
      </div>
      </body>
