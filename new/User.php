<?php

require_once 'Course.php';
require_once 'TeachAssist.php';
require_once 'Storage.php';

class User extends TeachAssist{
	private $numberOfCourses;
	private $username;
	private $coursesFromStorage=array();
	private $courses=array();
	private $courseUrls=array();
	private $courseIds=array();
	private $courseNames=array();
	private $storage;

	public function __construct($username, $password){
		$this->username=$username;
		parent::__construct('https://ta.yrdsb.ca/yrdsb/', $username);
		$this->coursesFromStorage=$this->retrieve();
		$this->init($username, $password);
	}

	private function init($username, $password){
		$response=$this->auth($username, $password);
		if($response){
			$this->courseUrls=$this->getUrls($response);
			$this->courseIds=$this->getIds($response);
			$this->courseNames=$this->getNames($response);
			$this->numberOfCourses=count($this->courseIds);
			for ($i=0; $i < $this->numberOfCourses; $i++) { 
				//push each course into our courses array
				array_push($this->courses, $this->fetchCourseData($this->courseUrl[$i], $this->courseIds[$i], $this->courseNames[$i]));
			}
			print_r($this);
		}else{
			die('Login Failed');
		}
	}

	public function store(){
		$this->storage=new Storage($this->courses);
		setcookie('storage', serialize($this->storage), time()+31557600);
	}
	public function retrieve(){
		if($this->storage instanceof Storage){
			$data=$this->storage->getCourses();
			return $data;
		}
		return false;
	}
	
}
?>