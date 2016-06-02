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
    require_once 'includes/paginate.class.php';


    $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
    $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
    $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 1;
    $query      = "SELECT * FROM `articles`";

    $Paginator  = new Paginator( $conn, $query );

    $results    = $Paginator->getData( $page, $limit );

$e = @$_GET['e']

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

  <!-- MODALS -->

<?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
<div class="modal fade" id="articleModal<?php echo $results->data[$i]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="articleModalLabel<?php echo $results->data[$i]['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="articleModalLabel<?php echo $results->data[$i]['id']; ?>"><strong><?php echo $results->data[$i]['title']; ?></strong></h4>
            </div>
            <div class="modal-body">
                <strong style="float: left;"> Date: <?php echo date("d F, Y", strtotime($results->data[$i]['date']));?> <?php if($results->data[$i]['edited'] == 1){ echo " (". date("d-n-y", strtotime($results->data[$i]['edate'])).")";} ?> | Author: <?php echo $results->data[$i]['username']; ?> <?php if($results->data[$i]['edited'] == 1){ echo " (".$results->data[$i]['eby'].")";} ?> </strong> <strong style="float: right;"> Status: <?php echo $results->data[$i]['status'] ?></strong>
                <hr>
                <p> <?php echo $results->data[$i]['text'] ?> </p>
            </div>
            <div class="modal-footer">
              <div class="btn-group">
                   <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="glyphicon glyphicon-wrench"></span> Status <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                      <li><a <?php echo "href='includes/post_status_update.php?id=".$results->data[$i]['id']."&status=Posted'" ?>>Posted</a></li>
                      <li><a <?php echo "href='includes/post_status_update.php?id=".$results->data[$i]['id']."&status=Draft'" ?>>Draft</a></li>
                   </ul>

              </div>
              <a href=<?php echo "edit_post.php?id=".$results->data[$i]['id'] ?>>
                <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-pencil"> </span> Edit Article</button>
              </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endfor; ?>


  <!-- END MODALS -->

	<div class="container">
		<div class="jumbotron">
			<h1>Article Management</h1>
			<p>Here you can view, delete, and create new articles!</p>
		</div>

    <?php if($e == 1) : ?>
      <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Oops!</strong> Looks like you tried to edit an aritcle that dosen't exist!
      </div>
    <?php endif ?>
    <?php if($e == 2) : ?>
      <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong> The article has been successfully updated!
      </div>
    <?php endif ?>

		<h2 class="sub-header">Articles</h2>
		<div class="table-responsive">

			<table class="table table-striped table-bordered">
				<thead>
					<th>#</th>
					<th>Title</th>
					<th>Date (Edited)</th>
					<th>Author (Editor)</th>
					<th>Status</th>
					<th>Utilities</th>
				</thead>
				<tbody>
			<?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
				<tr>
					<td><?php echo $results->data[$i]['id']; ?></td>
					<td><?php echo $results->data[$i]['title']; ?></td>
					<td><?php echo date("d M, Y", strtotime($results->data[$i]['date'])); if($results->data[$i]['edited'] == 1){ echo " (".date("d-m-y", strtotime($results->data[$i]['edate'])).")"; } ?></td>
					<td><?php echo $results->data[$i]['username']; ?> <?php if($results->data[$i]['edited'] == 1){ echo "(".$results->data[$i]['eby'].")"; } ?></td>
					<td><?php echo $results->data[$i]['status']; ?></td>
					<td>
					     <div class="util">
						      <form method="POST" action="includes/del.php" class="form-inline">
							         <input type="hidden" name="id" id="id" <?php echo "value ='".$results->data[$i]['id']."'"?> />
							         <button type="submit" class="btn btn-danger">
								          <span class="glyphicon glyphicon-remove" > </span>
							         </button>
						      </form>
					     </div>
					     <div class="util">
							     <div class="btn-group">
								        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									         <span class="glyphicon glyphicon-wrench"></span> Status <span class="caret"></span>
								        </button>
								        <ul class="dropdown-menu">
									         <li><a <?php echo "href='includes/post_status_update.php?id=".$results->data[$i]['id']."&status=Posted'" ?>>Posted</a></li>
									         <li><a <?php echo "href='includes/post_status_update.php?id=".$results->data[$i]['id']."&status=Draft'" ?>>Draft</a></li>
								        </ul>

							       </div>
					    </div>
					     <div class="util">
						      <a <?php echo "href='edit_post.php?id=".$results->data[$i]['id']."'" ?>>
							       <button class="btn btn-success">
								        <span class="glyphicon glyphicon-pencil"> </span> Edit
							       </button>
						      </a>
					     </div>
               <div class="util">
                 <a href="#" data-toggle="modal" data-target=<?php echo "#articleModal".$results->data[$i]['id'] ?> >
                  <button class="btn btn-info">
                    <span class="glyphicon glyphicon-eye-close"></span> View
                  </button>
                 </a>
               </div>
					</td>
				</tr>
			<?php endfor; ?>
				</tbody>

			</table>
			<?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> <a href="new_article.php"><button class="btn btn-primary btn-sm" style="float: right; margin-top: 20px;"><span class="glyphicon glyphicon-plus"></span> New Article</button></a>
		</div>

		<hr>

		<footer>
			<p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
		</footer>
	</div>

	</body>
