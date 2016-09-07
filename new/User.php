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
		parent::__construct('https://ta.yrdsb.ca/live/', $username);
		$this->coursesFromStorage=$this->retrieve();
		$this->init($username, $password);
	}

	private function init($username, $password){
		$storage=$this->retrieve();

		$response=$this->auth($username, $password);
		if($response){
			$this->courseUrls=$this->getUrls($response);
			$this->courseIds=$this->getIds($response);
			$this->courseNames=$this->getNames($response);
			$this->numberOfCourses=count($this->courseIds);

			for($i=0; $i<$this->numberOfCourses; $i++){
				if((bool)$this->courseUrls[$i] && strpos($this->courseUrls[$i], 'Please') === false){
					array_push($this->courses, $this->fetchCourseData($this->courseUrls[$i], $this->courseIds[$i], $this->courseNames[$i]));
				}else{
					//marks are hidden, attempt to load from storage
					if($storage && is_array($storage)){
						array_push($this->courses, $storage[$i]);
					}else{
						array_push($this->courses, NULL);
					}
				}
			}
		}else{
			die('Login Failed');
		}
	}

	public function store(){
		$this->storage=new Storage($this->courses);
		setcookie('storage', serialize($this->storage), time()+31557600);
	}
	public function retrieve(){
		if($_COOKIE['storage']){
			$storage=unserialize($_COOKIE['storage']);
			$data=$storage->getCourses();
			return $data;
		}
		return false;
	}

}
?>