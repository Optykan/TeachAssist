<?php 
session_start();
$data=unserialize($_SESSION['user']);
if(isset($data->credentials['password']){
	header("Location: dashboard.php");
	exit(0);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TA Scraper</title>
</head>
<body>
	<form action="action.php" method="post">
		<input type="text" name="username">
		<input type="password" name="password">
		<input name="action" value="login" style="display:none">
		<input type="submit" value="Submit">
	</form>
</body>
</html>