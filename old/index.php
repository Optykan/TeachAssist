<?php 
session_start();
$data=unserialize($_SESSION['user']);
if(isset($data->credentials['password'])){
	header("Location: dashboard.php");
	exit(0);
}
if(isset($_GET['error'])){
	switch ($_GET['error']) {
		case 1:
		$e="Invalid Credentials";
		break;
		
		default:
		$e="Unspecified Error";
		break;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TA Scraper</title>
	<link rel="stylesheet" type="text/css" href="/css/login.css">
	<link rel="stylesheet" type="text/css" href="/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>
<body>
	<form class="form" action="action.php" method="post">
		<div class="logo">
			ta
		</div>
		<?php if(isset($e)): ?>
			<p class="error">Error: <?=$e?></p>
		<?php endif;?>
		<input type="text" name="action" value="login" style="display:none"/>
		<input type="text" name="username" placeholder="username"/>
		<input type="password" name="password" placeholder="password"/>
		<button type="submit">login <i class="icon ion-log-in"></i></button>
		<p class="message">By logging in, I agree to use this Board technology in accordance with the <a target="_blank" href="http://www.yrdsb.ca/boarddocs/Documents/PP-appropriateuse-194.pdf">Appropriate Use of Technology Policy and Procedure</a> and abide by Board policies, procedures and directives.</p>
		<p class="message"><span style='color:red'>Warning: </span>For exclusive use by YRDSB staff and students. Unauthorized access prohibited.</p>
		<p class="message"><span style='color:#3B82EA'>Disclaimer: </span>This site is not affiliated with, maintained by, or in any way connected with the <a target="_blank" href="http://teachassist.ca">teachassist foundation</a></p>
	</form>
</body>
</html>