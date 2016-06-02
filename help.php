<?php
  include_once 'includes/db_connect.php';
  include_once 'includes/functions.php';

  sec_session_start();

  if(login_check($conn) == true){
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

   $a = @$_GET['a'];
   $u = @$_GET['u'];

   $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 15;
   $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
   $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 1;
   $query      = "SELECT * FROM `helparticles`";

   $Paginator  = new Paginator( $conn, $query );

   $results    = $Paginator->getData( $page, $limit );

 ?>

 <html lang="en">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="description" content="">
     <meta name="author" content="">
     <link rel="icon" href="favicon.ico">

     <title><?php echo $ENT_Name ?> Help</title>

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
             <li><a href="accounts.php">Accounts</a></li>
             <li class="active"><a href="#">Help</a></li>
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

     <!-- MODAL -->

     <div class="modal fade" id="askModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title" id="myModalLabel">Ask a Question</h4>
           </div>
           <div class="modal-body">
             <form action="includes/ask.php" method="POST">
               <input type="hidden" value="<?php echo $_SESSION['username'] ?>" name="asker" />
               <div class="form-group">
                 <input class="form-control" type="text" placeholder="Ask your question. (Limit 100 characters)" name="question" />
               </div>
           </div>
           <div class="modal-footer">
             <button type="submit" class="btn btn-primary">Ask Question</button>
           </form>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
         </div>
       </div>
     </div>




     <!-- END MODAL -->




     <br />
     <div class="container">
       <h1> Help Articles </h1>
       <hr>
       <?php if($a == 1) : ?>
         <div class="alert alert-success alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <strong>Success!</strong> Your question has been asked. An admin will look over it and answer it when they get around to it.
         </div>
       <?php endif ?>
       <?php if($a == 2) : ?>
         <div class="alert alert-danger alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <strong>Oh No!</strong> Looks like something went horribly wrong! Make sure to let <?php echo $sysadmin ?> know about this!
         </div>
       <?php endif ?>
       <?php if($u == 1) : ?>
         <div class="alert alert-success alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <strong>Success!</strong>  The article has been successfully updated!
         </div>
       <?php endif ?>
       <?php if($u == 2) : ?>
         <div class="alert alert-danger alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <strong>Oh No!</strong> Looks like something went horribly wrong! Make sure to let <?php echo $sysadmin ?> know about this!
         </div>
       <?php endif ?>

       <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
         <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
         <div class="panel panel-default">
           <div class="panel-heading" role="tab" id="heading<?php echo $results->data[$i]['id']; ?>">
             <h4 class="panel-title">
               <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $results->data[$i]['id']; ?>" aria-expanded="true" aria-controls="collapseOne">
                 <?php echo $results->data[$i]['question']; ?>
               </a>
             </h4>
           </div>
           <div id="collapse<?php echo $results->data[$i]['id']; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
             <div class="panel-body">
               <?php if($results->data[$i]['complete'] == 0) : ?> <p style="float: right; color: #b3b3b3; margin-left: 10px;"><strong> INCOMPLETE </strong></p> <?php else : ?><p style="float: right; color: #b3b3b3; margin-left: 10px;"><strong> COMPLETE </strong></p> <?php endif ?>
               <?php if($admin == true) : ?><a href="edit_answer.php?id=<?php echo $results->data[$i]['qid'] ?>" style="float: right;"><button type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-pencil"></span> Edit Answer</button></a><?php endif ?>
               <?php echo $results->data[$i]['answer']; ?>
             </div>
           </div>
         </div>
       <?php endfor ?>
       </div>
       		<?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?><a href="#" data-toggle="modal" data-target="#askModal"><button class="btn btn-primary btn-sm" style="float: right; margin-top: 20px;"><span class="glyphicon glyphicon-question-sign"></span> Ask A Question</button></a><?php if($admin == true) : ?><a href="questions.php"><button type="button" class="btn btn-info btn-sm" style="float: right; margin-top: 20px; margin-right: 10px;"><span class="glyphicon glyphicon-th-list"></span> Go to Questions</button></a><?php endif ?>

     <hr>

 		<footer>
 			<p> © Copyright <?php echo $ENT_Name ?> <?php echo date("Y")?> </p>
 		</footer>
 	</div>
