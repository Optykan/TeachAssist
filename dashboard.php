<?php
function jsMap($array){
	$examweight=$array[6];
	for ($i=1; $i < 6; $i++) { 
		$array[$i]=round((1.0-$examweight)*$array[$i], 2)*100;
	}
	$array[6]=$examweight*100;
	return array_values($array);
}
session_start();
if(!isset($_SESSION['data'])){ 
	//direct access without login denied
	header("Location: index.php?error=1");
	exit(0);
}
require_once 'class-user.php';
$user=unserialize($_SESSION['data']);

if(isset($_GET['course'])){
	$course=$_GET['course'];
}else{
	$course = $user->getCourse(0, 'id');
	if(!isset($course)){
		//no courses oops
	}
}
echo '<pre>';
// var_dump($user); 
echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>TA Scraper</title>
	<!-- <link rel="stylesheet" type="text/css" href="/css/foundation.min.css"> -->
	<link rel="stylesheet" type="text/css" href="/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="/css/themes/light.css">
	<link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" type="text/css" href="/css/normalize.css">
	<script type="text/javascript" src="/js/vendor/jquery.js"></script>
	<script src="http://listjs.com/no-cdn/list.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.min.js"></script>
	<script type="text/javascript">
		var weightData={
			labels: ["K/U",
			"T/I",
			"Comm",
			"App",
			"Final"
			],
			datasets: [
			{
				data: <?=json_encode(jsMap($user->weighting[$course]))?>,
				backgroundColor: [
				"#f1c40f",
				"#2ecc71",
				"#9b59b6",
				"#e67e22",
				"#95a5a6",
				],
				hoverBackgroundColor: [
				"#f1c40f",
				"#2ecc71",
				"#9b59b6",
				"#e67e22",
				"#95a5a6"
				]
			}]
		};
	</script>
	<!-- <script type="text/javascript" src="/js/vendor/foundation.min.js"></script> -->
</head>
<body>

	<div class="row wrapper">
		<div class="nav-side">
			<ul class="nav-container">
				<li class="nav-header">TeachAssist</li>
				<?php for($i=0; $i<$user->courseCount; $i++): ?>
					<li class="nav-element<?=$user->getCourse($i, 'id')==$course? ' active':''?>">
						<a href="dashboard.php?course=<?=$user->getCourse($i,'id')?>">
							<?php $mark=$user->getAverage($user->getCourse($i, 'id'))?>
							<?php $mark = $mark ? $mark : $user->getLastMark($user->getCourse($i, 'id'))?>
							<p class="course-mark"><?=round($mark*100, 1)?>%</p>
							<p class="course-name"><?=$user->getCourse($i, 'name')?></p>
						</a>
					</li>
				<?php endfor;?>
				<!-- <li class="nav-element">
					<p class="course-mark">7.5%</p>
					<p class="course-name">English</p>
				</li>
				<li class="nav-element"></li> -->
			</ul>
			<!-- side navigation -->
		</div>
		<div class="content">
			<ul class="top-bar">
				<li class="top-element">
					<p class="now-viewing">Now Viewing: <?=$user->toName($course)?></p>
				</li>
				<li class="top-element">
					<!-- <a href="action.php?action=logout"> -->
					<a href="action.php?action=logout">
						<i class="icon ion-log-out"></i>
						Logout as <?=$user->credentials['username']?>
					</a>
					<!-- </a> -->
				</li>
			</ul>
			<hr/>
			<div class="module overview">
				<div class="block term">
					<div class="circle"><?=round($user->getTermAverage($course)*100,2)?>%</div>
					<p class="achivement">Term Mark
						<div class="tooltip"><i class="icon ion-ios-help-outline"></i>
							<span class="tooltiptext">Ultimately the number shown on TeachAssist is the correct one.</span>
						</div>
					</p>
				</div>
				<div class="block course">	
					<!-- includes exams -->
					<div class="circle"><?=round($user->getCourseAverage($course)*100,2)?>%</div>
					<p class="achivement">Course Mark</p>
				</div>
				<div class="block status">
					<div class="circle <?=$user->status[$course]?>">
						<i class="icon <?=$user->status[$course]=='updated' ? 'ion-ios-checkmark-empty' : 'ion-ios-bolt'?>"></i>
					</div>
					<p><?=$user->status[$course]=='updated' ? 'Up to date' : 'Marks hidden'?></p>
					<!-- up-to-date or hidden	 -->
				</div>
			</div>

			<div class="module assignments" id="assignments">
				<div class="module-header">Assignments
					<input class="search" placeholder="Search" />
				</div>
				<table>
					<tbody class='list'>
						<?php foreach($user->assignments[$course] as $assignment):?>
							<tr>
								<td class="name"><?=$assignment[0]?></td>
								<td class="ku"><?=$assignment[1]['nice']?></td>
								<td class="ti"><?=$assignment[2]['nice']?></td>
								<td class="comm"><?=$assignment[3]['nice']?></td>
								<td class="app"><?=$assignment[4]['nice']?></td>
								<td class="final"><?=$assignment[5]['nice']?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>

<!-- 			<div class="chart-container">
				<div class="chart">
					<div class="chart-header">Achievement</div>
					<canvas height="50" width="200" id="achievementLine"></canvas>
				</div>
				<div class="chart">
					<div class="chart-header">Weighting</div>
					<canvas height="50" width="200" id="weightDonut"></canvas>
				</div>
			</div> -->
		</div>
		<script type="text/javascript" src="/js/app.js"></script>
	</body>
	</html>
	<?php $_SESSION['data']=serialize($user);?>