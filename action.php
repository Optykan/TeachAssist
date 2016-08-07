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
}else if($_POST['action']=='dump'){
	if(isset($_SESSION['data'])){
		// var_dump(gzencode($_SESSION['data'], 9));
		var_dump($_SESSION['data']);

	}else{
		echo 'nothing to dump';
	}
}else if($_GET['action']=='logout'){
	//thanks php manual
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
		session_destroy();
	}
	header("Location: index.php");
}else{
	http_response_code(418);
	echo "No parameters passed.";
}
?>