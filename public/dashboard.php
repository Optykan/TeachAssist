<?php
//the dashboard
require_once '../extras/extras.php';
require_once '../core/User.php';
if(($_POST['username']) && isset($_POST['password'])){
	if(!Extras::auth($_POST['username'], $_POST['password'])){
		Extras::redirect('/index.php?e=1');
	}
}elseif($_GET['action']=='logout'){
	Extras::redirect('/index.php?m=1');
}
$user = new User($_POST['username'], $_POST['password']);
$user->store();
?>

<!DOCTYPE html>
<html>
<head>
	<title>TeachAssist</title>
</head>
<body>
	<div class="top-bar">
		<p class="logo">TeachAssist</p>
		<p class="center">Dashboard</p>
		<div class="account">
			<a href="dashboard.php?action=logout">Logout</a>
		</div>
	</div>
	<nav class="course-navigation">
		<ul>
			<li><a href="dashboard.php?course="></a></li>
		</ul>
	</nav>
	<div class="main">
		<div class="status">
			<h3 class="scraper"></h3>
			<h1 class="teachassist"></h1>
			<h3 class="status"></h3>
		</div>
		<hr  />

	</div>
</body>
</html>
