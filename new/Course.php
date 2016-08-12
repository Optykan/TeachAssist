<?php 

class Course{
	private $id;
	private $name;
	private $weighting=array();
	private $achievement=array();
	private $assignments=array();

	public function __construct($courseId, $courseName){
		$this->id=$courseId;
		$this->$name=$courseName;
	}

	public function addAchievement($mark){
		array_push($this->achievement, $mark);
	}
	public function addWeighting($weight){
		array_push($this->weighting, $weight);
	}
	public function addAssignment($data){
		array_push($this->assignments, $data);
	}

}
?>