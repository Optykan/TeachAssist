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

$courseId=isset($_GET['course']) ? intval($_GET['course']) : $user->getFirstCourse();
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
	<link rel="stylesheet" type="text/css" href="dist/vendor/chartist/chartist-plugin-tooltip.css">
	<link rel="stylesheet" type="text/css" href="dist/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="dist/css/theme-default.css">
	<title>TeachAssist</title>
</head>
<body>
	<div class="top-bar">
		<div class="logo"><span class="teach">Teach</span><span class="assist">Assist</span></div>
		<div class="header-center">Dashboard</div>
		<div class="account">
			<a href="dashboard.php?action=logout"><i class="icon ion-log-out"></i></a>
		</div>
	</div>
	<div class="site-wrapper">
		<div class="course-navigation">
			<p class="nav-header">Courses</p>
			<?php $courseCount=$user->getCourseCount();?>
			<?php for($i=0; $i<$courseCount; $i++):?>
				<?php $currentCourse=$user->getCourse($i);?>
				<?php if(is_null($currentCourse)) continue;?>
				<div class="course-link<?=$i==$courseId ? ' active' : ''?>">
					<a href="dashboard.php?course=<?=$i?>">
						<span class="course-average"><?=number_format($user->getCourse($i)->getAverage(),1)?>%</span>
						<span class="course-id"><?=$user->getCourse($i)->getId()?></span>
					</a>
				</div>
			<?php endfor;?>
		</div>
		<div class="main">
			<div class="status module">
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

			<div class="charts module">
				<div class="chart">
					<div class="module-header">
						Weighting
					</div>
					<div class="ct-chart ct-square" id="weighting"></div>
				</div>
				<div class="chart">
					<div class="module-header">
						Trends
					</div>
					<div class="ct-chart ct-square" id="trends"></div>
				</div>
			</div>

			<div id="assignments" class="module">
				<div class="module-header">
					Assignments
					<input class="search" placeholder="Search" />
				</div>
				<table>
					<thead class="table-header">
						<tr>
							<th>
								<div class="sort-header">
									<span class="sort sort-name" data-sort="name">Name <i class="icon ion-ios-arrow-up"></i></span>
								</div>
							</th>
							<th>
								<div class="sort-header">
									<span class="sort sort-ku" data-sort="ku">K/U <i class="icon ion-ios-minus-empty"></i></span>
									<span class="sort sort-weight" data-sort="weight-ku">W <i class="icon ion-ios-minus-empty"></i></span>
								</div>
							</th>
							<th>
								<div class="sort-header">
									<span class="sort sort-ti" data-sort="ti">T/I <i class="icon ion-ios-minus-empty"></i></span>
									<span class="sort sort-weight" data-sort="weight-ti">W <i class="icon ion-ios-minus-empty"></i></span>
								</div>
							</th>
							<th>
								<div class="sort-header">
									<span class="sort sort-comm" data-sort="comm">Comm <i class="icon ion-ios-minus-empty"></i></span>
									<span class="sort sort-weight" data-sort="weight-comm">W <i class="icon ion-ios-minus-empty"></i></span>
								</div>
							</th>
							<th>
								<div class="sort-header">
									<span class="sort sort-app" data-sort="app">App <i class="icon ion-ios-minus-empty"></i></span>
									<span class="sort sort-weight" data-sort="weight-app">W <i class="icon ion-ios-minus-empty"></i></span>
								</div>
							</th>
							<th>
								<div class="sort-header">
									<span class="sort sort-final" data-sort="final">Final <i class="icon ion-ios-minus-empty"></i></span>
									<span class="sort sort-weight" data-sort="weight-final">W <i class="icon ion-ios-minus-empty"></i></span>
								</div>
							</th>
						</tr>
					</thead>
					<tbody class='list'>
						<?php $assignments=$course->getAssignment();?>
						<?php foreach($assignments as $assignment):?>
							<tr class="assignment">
								<td class="category name"><?=$assignment->getName()?></td>
								<td class="category cat-ku"><div class="container"><?=$assignment->getFormattedScore(0)?></div></td>
								<td class="category cat-ti"><div class="container"><?=$assignment->getFormattedScore(1)?></div></td>
								<td class="category cat-comm"><div class="container"><?=$assignment->getFormattedScore(2)?></div></td>
								<td class="category cat-app"><div class="container"><?=$assignment->getFormattedScore(3)?></div></td>
								<td class="category cat-final"><div class="container"><?=$assignment->getFormattedScore(4)?></div></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var pieData = {
			<?php $pieData=Extras::generatePieData($course)?>

			labels: <?=$pieData['labels']?>,
			series: <?=$pieData['series']?>
		};
		var assignmentData = {
			<?php $assignmentData=Extras::generateChartData($course->getAssignment());?>
			labels: <?=$assignmentData['labels']?>,
			series: [<?=$assignmentData['series'][0]?>,<?=$assignmentData['series'][1]?>,<?=$assignmentData['series'][2]?>,<?=$assignmentData['series'][3]?>,<?=$assignmentData['series'][4]?>]
		};
	</script>
	<script type="text/javascript" src="dist/vendor/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="dist/vendor/list/list.min.js"></script>
	<script type="text/javascript" src="dist/vendor/list/list.fuzzysearch.min.js"></script>
	<script type="text/javascript" src="dist/vendor/chartist/chartist.min.js"></script>
	<script type="text/javascript" src="dist/vendor/chartist/chartist-plugin-tooltip.min.js"></script>
	<script type="text/javascript" src="dist/js/app.js"></script>
</body>
</html>
