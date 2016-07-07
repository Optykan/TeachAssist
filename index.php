<?php 
session_start();
$data=unserialize($_SESSION['user']);
if(isset($data->credentials['password'])){
	header("Location: dashboard.php");
	exit(0);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TA Scraper</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="/css/normalize.css">
</head>
<body>
	<div class="wrapper">
		<form class="form" action="action.php" method="post">
			<div class="logo">
				ta
			</div>
			<input type="text" name="action" value="login" style="display:none"/>
			<input type="text" name="username" placeholder="username"/>
			<input type="password" name="password" placeholder="password"/>
			<button type="submit">login</button>
			<p class="message">By logging in, I agree to use this Board technology in accordance with the Appropriate Use of Technology Policy and Procedure and abide by Board policies, procedures and directives.</p>
			<p class="message"><span style='color:red'>Warning: </span>For exclusive use by YRDSB staff and students. Unauthorized access prohibited.</p>
		</form>
	</div>
</body>
</html>