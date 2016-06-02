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

 $r = @$_GET['r'];
 if($r == 0){
   $r = 6;
 }


if(isset($_GET['id'])){
  $query = $conn->prepare("SELECT username FROM members WHERE id= ? LIMIT 1");
  $query->bind_param("s",$id);
  $query->execute();
  $q = $query->get_result();
  $row = $q->fetch_assoc();
  if($q->num_rows ==1){
    $u = true;
  } else {
    $u = false;
  }
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

    <title><?php echo $ENT_Name ?> Account Management</title>

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


  <?php if($admin == false) : ?>
  <br />
    <div class="container">
      <div class="jumbotron">
        <h1>Account Management</h1>
        <p> Here you will manage your account!</p>
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
        <p style="text-indent: 5px;"><strong><?php echo $_SESSION['email'] ?></strong></p>
        <form class="form-inline" action="includes/update_email.php" method="post">
          <input type="hidden" name="id" value=<?php echo "'".$_SESSION['user_id']."'" ?> />
            <input class="form-control" type="email" name="email" placeholder="New Email" />
            <button class="btn btn-primary" type="submit"> Update </button>
        </form>
      </div>
      <div class="col-md-6">
        <h3>Update Password</h3>
          <hr>
        <form action="includes/update_pass.php" method="post">
          <input type="hidden" name="id" value=<?php echo "'".$_SESSION['user_id']."'" ?> />
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



      </div>

    </div>





<?php elseif($admin == true) : ?>
  <!-- Registration Form Modal -->
  <div class="modal fade" id="accountCreator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create a new account!</h4>
        </div>
        <div class="modal-body">
          <?php if($r == 1)  : ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Oops!</strong> Looks like your email was not properly formatted.
            </div>
          <?php endif ?>
          <?php if($r == 2)  : ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Oh No!</strong> Invalid Password Configuration (Report this to <?php echo($sysadmin) ?> ASAP!)
            </div>
          <?php endif ?>
          <?php if($r == 3)  : ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Oops!</strong> Looks like there is already an account linked to that email.
            </div>
          <?php endif ?>
          <?php if($r == 4)  : ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Oops!</strong> Looks like an account with that username already exists.
            </div>
          <?php endif ?>
            <form action="includes/reg.php" method="post">
              <div class="form-group">
                <input class="form-control" name="username" placeholder="Username" type="text" required />
              </div>
              <div class="form-group">
                <input class="form-control" name="email" placeholder="EMail" type="text" required />
              </div>
              <div class="form-group">
                <input class="form-control" name="password" placeholder="Password" type="password" required />
              </div>
              <div class="form-group">
                <input class="form-control" name="confirmpwd" placeholder="Confirm Password" type="password" required />
              </div>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-primary" value="Register" onclick="regformhash(this.form, this.form.username, this.form.email, this.form.password, this.form.confirmpwd);" />
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal -->


  <div class="jumbotron">
    <div class="container">
      <h1>Account Management</h1>
      <p> Here you will manage your account! As you are an admin, you may also manage other accounts and create new ones. </p>
    </div>
  </div>
    <div class="container">
      <?php if($r < 5) : ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Oops!</strong> Looks like we couldn't create that account. Click the "Create Account" button for more info!
        </div>
      <?php endif ?>
      <?php if($e == 1) : ?>
      <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Oops!</strong> Looks like you tried to edit an account that doesn't exist!
      </div>
      <?php endif ?>
      <?php if($p == 1) : ?>
        <?php if($u == true ) : ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong> <?php echo $row["username"]."'s" ?> password has been successfully changed!
          </div>
        <?php endif ?>
        <?php if($u == false) : ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Oh No!</strong> You managed to change the password of a user that does not exist. Report this error to <?php echo $sysadmin ?> ASAP!
          </div>
        <?php endif ?>
      <?php endif ?>
      <?php if($p == 3) : ?>
        <?php if($u == true) : ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Oh No!</strong> Something went horribly wrong when changing <?php echo $row["username"]."'s" ?> password. Report this to  <?php echo $sysadmin ?>.
          </div>
        <?php endif ?>
        <?php if($u == false) : ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Oh No!</strong> Something went horribly wrong! You were able to try to change the password of a non-existant user, and something weird happened. Report this to <?php echo $sysadmin ?> ASAP!
          </div>
        <?php endif ?>
      <?php endif ?>
      <?php if($r == 5) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Success!</strong> Account successfully created! If you need this account to have admin ability, please contact <?php echo($sysadmin) ?>.
        </div>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <h2> Manage My Account </h2>
          <p> Here you can manage your own account! </p>
          <p>
            <a class="btn btn-default" <?php echo "href='account.php?id=".$_SESSION['user_id']."'" ?> role="button"> Manage » </a>
          </p>

        </div>
        <div class="col-md-4">
          <h2> Manage Another Account</h2>
          <p> Here you can select another account to manage!</p>
          <p> <a class="btn btn-default" href="memberlist.php" role="button"> Manage » </a> </p>
        </div>
        <div class="col-md-4">
          <h2>Create A New Account</h2>
          <p>Click the button to create a new user account!</p>
          <p><a href="#" data-toggle="modal" data-target="#accountCreator" class="btn btn-default" role="button" > Account Creator » </a> </p>
        </div>
      </div>


    </div>

  </div>

<?php endif; ?>
<div class="container">
  <hr>

  <footer>
    <p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
  </footer>
</div>
  </body>
