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
	private $flags=array();
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

			//load teachassist reporting marks
			$teachassist=$this->getTeachAssistMark($response);

			$this->numberOfCourses=count($this->courseIds);

			for($i=0; $i<$this->numberOfCourses; $i++){
				if((bool)$this->courseUrls[$i] && strpos($this->courseUrls[$i], 'viewReport') !== false){
					array_push($this->courses, $this->fetchCourseData($this->courseUrls[$i], $this->courseIds[$i], $this->courseNames[$i]));
					array_push($this->flags, 'ok');
					$reportingMark=array();
					preg_match_all('/current\s+?mark\s+?=\s+?([0-9.]+)%/', $teachassist[$i], $reportingMark);
					if($reportingMark!==false){
						$this->courses[$i]->setAverage((float)$reportingMark[1][0]);
					}
				}else{
					//marks are hidden, attempt to load from storage
					if($storage && is_array($storage) && isset($storage[$i])){
						array_push($this->courses, $storage[$i]);
						array_push($this->flags, 'cache,hidden,ok');
						$this->courses[$i]->setAverage(NULL);
					}else{
						array_push($this->courses, NULL);
						array_push($this->flags, 'nocache,hidden');
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
	public function getCourse($course){
		if(is_int($course)){
			return $this->courses[$course];
		}
		$courseIds=array_flip($this->courseIds);
		return $this->courses[$courseIds[$course]] ?: false;
	}
	public function getCourseCount(){
		return $this->numberOfCourses;
	}
	public function getFlags($course){
		return array_flip(explode(',', $this->flags[$course]));
	}
	public function getFirstCourse(){
		foreach($this->courses as $key=>$value){
			if(!is_null($value))
				return $key;
		}
		return 0;
	}

}
?>