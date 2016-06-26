<?php 
session_start();
if($_POST['action']=='login'){
	if(isset($_SESSION['data'])){ 
		//we have a serialized session object
		header("Location: dashboard.php");
		exit(0);
	}

	require_once 'class-user.php';
	$user = new User($_POST['username'], $_POST['password']);
	if($user->login()){
		$_SESSION['data']=serialize($user);
		header("Location: dashboard.php");
		exit(0);
	}
}
?>