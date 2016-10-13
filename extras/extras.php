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
	public static function logout(){
		$_SESSION = array();
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
		session_destroy();
	}
	public static function formatStatus($flags){
		$return=array('icon'=>'ion-ios-checkmark-empty', 'status'=>'ok', 'message'=>'Up to date');
		if(isset($flags['hidden'])){
			$return['icon']='ion-ios-bolt';
			$return['message']='Loaded from cache';
			if(!isset($flags['ok'])){
				$return['status']='nok';
				$return['message']='Marks hidden';
			}
		}
		return $return;
	}
	public static function generateChartData($assignments){
		$labels='[';
		$series=array('[', '[', '[', '[', '[');
		foreach($assignments as $assignment){
			$labels.='"'.$assignment->getName().'",';
			for($i=0;$i<5;$i++){
				$series[$i].=round($assignment->getMark($i)*100) ?: 'null';
				$series[$i].=',';
			}
		}
		for($i=0;$i<5;$i++){
			$series[$i]=rtrim($series[$i], ',');
			$series[$i].=']';
		}
		$labels=rtrim($labels, ',');
		$labels.=']';
		return array('labels'=>$labels, 'series'=>$series);
	}
	public static function generatePieData($course){
		$labels='[';
		$categories=['K/U', 'T/I', 'Comm', 'App', 'Final'];
		$series='[';
		for($i=0;$i<5; $i++){
			$weight=$course->getWeighting($i);
			$labels.='"'.$categories[$i].' ('.round($weight*($i<4?70:100), 1).'%)",';
			$series.=$weight.',';
		}
		$labels.=']';
		$series.=']';
		return array('labels'=>$labels, 'series'=>$series);
	}

}
?>