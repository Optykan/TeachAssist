<?php 
session_start();
require_once 'class-user.php';
$user=unserialize($_SESSION['data']);
if($_POST['action']=='login'){
	if($user){ 
		//we have a serialized session object
		header("Location: dashboard.php");
		exit(0);
	}
	//otherwise make a new one
	$user = new User($_POST['username'], $_POST['password']);
	if($user->login()){
		$user->update();
		$_SESSION['data']=serialize($user);
		header("Location: dashboard.php");
		exit(0);
	}
	else{
		header("Location: index.php?error=1");
	}
}
?>