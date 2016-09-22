<?php
require_once '../core/TeachAssist.php';
class Extras{
	public static function redirect($to){
		http_response_code(301);
		header('Location: '.$to);
		exit(0);
	}
	public static function auth($username, $password){
		$ta=new TeachAssist('https://ta.yrdsb.ca/live/', $username);
		return (bool)$ta->auth($username, $password);
	}
}
?>