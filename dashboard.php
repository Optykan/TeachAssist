<?php
session_start();
if(!isset($_SESSION['data'])){ 
	//direct access without login denied
	header("Location: index.php");
	exit(0);
}
require_once 'class-user.php';
$user=unserialize($_SESSION['data']);
echo '<pre>';
var_dump($user); 
echo '</pre>';
?>