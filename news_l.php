<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

 $query  = $conn->query("SELECT * FROM `articles` WHERE `status` != 'Draft' ORDER BY `id` DESC LIMIT 10");
 $results = array();

 while($result = $query->fetch_assoc()){
	 $results[] = $result;

 }

// print_r($results);
 ?>
<style>
	body {
		font-family: 'Open Sans', sans-serif;
		margin-left: 10px;
		background: url(admin/images/bgt.png);
	}
	h1 {
		font-weight: 6;
	}
	h3 {
		text-align: right;
		margin-right: 15px;
	}
	.newsheadertext {
		margin-left: 5px;
	}
	.newsdetails {
		margin-left: 10px;
	}
	.newscontent {
		margin-left: 10px;
	}
	.article {
		padding-bottom: 10px;
		margin-right: 20px;
		border-bottom-color: #4f4f4f4f;
		border-bottom-width: 8px;
		border-bottom-style: double;
	}
</style>
<?php for( $i = 0; $i < count( $results ); $i++ ) : ?>
<div class="article">
<h1 class="newsheadertext"><?php echo $results[$i]['title']; ?></h1>
<h3 class="newsdetails"><?php echo date("d M, Y", strtotime($results[$i]['date'])); ?></h3>
<p class="newscontent"><?php echo $results[$i]['text']; ?></p>
</div>
<?php endfor ?>
