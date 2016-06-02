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

 if($admin == false){
   header('Location: accounts.php');
 }

 require_once 'includes/paginate.class.php';


 $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
 $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
 $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 1;
 $query      = "SELECT id, username, email FROM `members`";

 $Paginator  = new Paginator( $conn, $query );

 $results    = $Paginator->getData( $page, $limit );
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

    <!-- Registration Form Modal -->
    <div class="modal fade" id="accountCreator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Create a new account!</h4>
          </div>
          <div class="modal-body">
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
	<br />

  <div class="container">
    <div class="jumbotron">
      <h1>Member Management</h1>
      <p>Here you can select a user account to edit!</p>
    </div>

    <h2 class="sub-header">Members</h2>
    <div class="table-responsive">

      <table class="table table-striped table-bordered">
        <thead>
          <th>#</th>
          <th>Username</th>
          <th>Email</th>
          <th>Utilities</th>
        </thead>
        <tbody>
          <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
            <tr>
              <td><?php echo $results->data[$i]['id']; ?></td>
              <td><?php echo $results->data[$i]['username']; ?></td>
              <td><?php echo $results->data[$i]['email']; ?></td>
              <td>
                   <div class="util">
                      <a <?php echo "href='account.php?id=".$results->data[$i]['id']."'" ?>>
                         <button class="btn btn-success">
                            <span class="glyphicon glyphicon-pencil"> </span> Edit
                         </button>
                      </a>
                   </div>
              </td>
            </tr>
          <?php endfor; ?>
        </tbody>

      </table>
      <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?><a href="#" data-toggle="modal" data-target="#accountCreator" class="btn btn-primary btn-sm" role="button" style="float: right; margin-top: 20px;" > <span class="glyphicon glyphicon-plus"></span> New Account</a>
    </div>

    <hr>

    <footer>
      <p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
    </footer>
  </div>

  </body>
