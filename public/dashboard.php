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
	Extras::logout();
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
	<link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" type="text/css" href="dist/vendor/chartist/chartist.css">
	<link rel="stylesheet" type="text/css" href="dist/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="dist/css/theme-dark.css">
	<title>TeachAssist</title>
</head>
<body>
	<div class="top-bar">
		<div class="logo">TeachAssist</div>
		<div class="header-center">Dashboard</div>
		<div class="account">
			<a href="dashboard.php?action=logout">Logout</a>
		</div>
	</div>
	<nav class="course-navigation">
		<ul>
			<?php $courseCount=$user->getCourseCount();?>
			<?php for($i=0; $i<$courseCount; $i++):?>
				<?php $currentCourse=$user->getCourse($i);?>
				<?php if(is_null($currentCourse)) continue;?>
				<li><a href="dashboard.php?course=<?=$i?>"><?=$user->getCourse($i)->getName()?></a></li>
			<?php endfor;?>
		</ul>
	</nav>
	<div class="main">
		<div class="status">
			<div class="scraper"><?=round($course->getScraperAverage()*100,2)?>%</div>
			<div class="teachassist"><?=$course->getAverage()?>%</div>
			<?php $flags=$user->getFlags($courseId);?>
			<?php $status=Extras::formatStatus($flags);?>
			<div class="status-circle">
				<div class="circle <?=$status['status']?>">
					<i class="icon <?=$status['icon']?>"></i>
				</div>
				<h3 class="status-message"><?=$status['message']?></h3>
			</div>
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
							<td class="category name"><?=$assignment->getName()?></td>
							<td class="category ku"><?=$assignment->getFormattedScore(0)?></td>
							<td class="category ti"><?=$assignment->getFormattedScore(1)?></td>
							<td class="category comm"><?=$assignment->getFormattedScore(2)?></td>
							<td class="category app"><?=$assignment->getFormattedScore(3)?></td>
							<td class="category final"><?=$assignment->getFormattedScore(4)?></td>
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
