<?php
//the dashboard
require_once '../extras/extras.php';
require_once '../core/User.php';
session_start();
if((isset($_POST['username']) && isset($_POST['password']))){
	if(!Extras::auth($_POST['username'], $_POST['password'])){
		Extras::redirect('/index.php?e=1');
	}else{
		$_SESSION['username']=$_POST['username'];
		$_SESSION['password']=$_POST['password'];
		$user = new User($_POST['username'], $_POST['password']);
	}
}elseif(isset($_SESSION['username']) && isset($_SESSION['password'])){
	if(!Extras::auth($_SESSION['username'], $_SESSION['password'])){
		Extras::redirect('/index.php?e=1');
	}else{
		$user = new User($_SESSION['username'], $_SESSION['password']);
	}
}
else{
	Extras::redirect('/index.php?e=1');
}
if($_GET['action']=='logout'){
	$_SESSION = array();
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
		);
	session_destroy();
    //thanks php manual 

	Extras::redirect('/index.php?m=1');
}

$courseId=isset($_GET['course']) ? intval($_GET['course']) : 0;
$course=$user->getCourse($courseId);

// if($course==false)
// 	Extras::redirect('/index.php?e=2');

$user->store();
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="dist/vendor/chartist/chartist.css">
	<link rel="stylesheet" type="text/css" href="dist/css/dashboard.css">
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
		<hr />
		<div class="charts">
		</div>
		<hr />
		<div id="assignments">
			<div class="module-header">
				Assignments
				<input class="search" placeholder="Search" />
			</div>
			<div class="table-header">
				<div class="column">Name</div>
				<div class="column">K/U</div>
				<div class="column">T/I</div>
				<div class="column">Comm</div>
				<div class="column">App</div>
				<div class="column">Final</div>
			</div>
			<table>
				<tbody class='list'>
					<?php $assignments=$course->getAssignment();?>
					<?php foreach($assignments as $assignment):?>
						<tr class="assignment">
							<td class="name"><?=$assignment->getName()?></td>
							<td class="ku"><?=$assignment->getFormattedScore(0)?></td>
							<td class="ti"><?=$assignment->getFormattedScore(1)?></td>
							<td class="comm"><?=$assignment->getFormattedScore(2)?></td>
							<td class="app"><?=$assignment->getFormattedScore(3)?></td>
							<td class="final"><?=$assignment->getFormattedScore(4)?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="dist/vendor/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="dist/vendor/list/list.min.js"></script>
	<script type="text/javascript" src="dist/vendor/chartist/chartist.min.js"></script>
	<script type="text/javascript" src="dist/js/app.js"></script>
</body>
</html>
