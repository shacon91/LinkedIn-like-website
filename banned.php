<?php  

require "config/core.php";

if (!compBan()) {
	header("Location: index.php");
}else{

	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/login.css">
</head>
<body>

	<div class="container">

		<h1>MotorPros</h1>


		You have been Banned!

	</div>

</body>
</html>
<?php } ?>