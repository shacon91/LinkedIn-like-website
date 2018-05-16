<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Page :/</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/unavailable.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		This page is unavailable, it may be that the link is broken or the page has been removed.......
		</div>
	</div>

</body>
</html>

<?php } ?>