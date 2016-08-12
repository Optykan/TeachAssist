<?php

require_once 'Course.php';

class User extends TeachAssist{
	private $numberOfCourses;
	private $username;
	private $courses=array();
	private $courseUrls=array();
	private $courseIds=array();
	private $courseNames=array();

	public function __construct($username, $password){
		$this->username=$username;
		parent::__construct('https://ta.yrdsb.ca/yrdsb/index.php');
		$this->init();
	}

	private function init($username, $password){
		if($response=$this->auth($username, $password)){
			$this->courseUrls=$this->getUrls($response);
			$this->courseIds=$this->getIds($response);
			$this->courseNames=$this->getNames($response);
			$this->numberOfCourses=count($this->courseIds);
			for ($i=0; $i < $this->numberOfCourses; $i++) { 
				array_push($courses, $this->fetchCourseData($this->courseUrl[$i], $this->courseIds[$i], $this->courseNames[$i]));
			}
		}else{
			die('Login Failed');
		}
	}


	
}
?>