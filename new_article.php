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

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $ENT_Name ?> Launcher Article Creation</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <script src="ckeditor/ckeditor.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
            <li class="active"><a href="#">Articles</a></li>
            <li><a href="accounts.php">Accounts</a></li>
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
			<h1>Article Creation</h1>
			<p>Here you can write a new article. The editor is a bit bare, but it should get better with time.<p>
		</div>

		<?php if(!empty($art_err)){ echo "<div class='alert alert-warning alert-dismissable fade in' role='alert'> <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>". $art_err ."</div>"; } ?>

		<h2 class="sub-header">New Article</h2>
		<form action="includes/post.php" method="POST">
			<div class="row">
				<div class="form-group">
					<div class="col-md-8">
						<label for="Title"> Title </label>
						<input type="textbox" class="form-control .col-md-8" name="title" id="title" />
					</div>
					<div class="col-md-2">
						<label>Date</label>
						<p><?php echo date('d F, Y') ?></p>
					</div>
					<div class="col-md-2">
						<label>Posting As</label>
						<p><?php echo $_SESSION['username'] ?></p>
						<input type="hidden" value="<?php echo $_SESSION['username']; ?>" name="username" />
					</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="text"> Article Text </label>
						<textarea class="form-control" rows="18" name="text"></textarea>
            <script> CKEDITOR.replace('text'); </script>
					</div>
				</div>
			</div>
			<input type="checkbox" value="Draft" name="status" style="float: left;"> &nbsp <strong>Save as draft?</strong> </input>
			<button type="submit" class="btn btn-success" style="float: right;"><span class="glyphicon glyphicon-save"></span> Save</button>
			<button type="reset" class="btn btn-warning" style="float: right; margin-right: 10px;"><span class="glyphicon glyphicon-remove-circle"></span> Clear Fields</span></button>
		</form>

		<br />
		<hr>
		<footer>
			<p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
		</footer>
