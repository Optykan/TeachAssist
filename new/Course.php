<?php 

class Course{
	private $id;
	private $name;
	private $map=array();
	private $weighting=array();
	private $achievement=array();
	//teachassist reporting percent

	private $assignments=array();
	private $average;

	public function __construct($courseId, $courseName){
		$this->map=array('ku', 'ti', 'comm', 'app', 'final');
		$this->id=$courseId;
		$this->$name=$courseName;
	}

	private function resolveMap($input){
		if(is_int($input) && $input<count($this->map-1)){
			return $this->nap[$index];
		}else{
			foreach ($this->map as $key => $value) {
				if($value==$which){
					return $key;
				}
			}
		}
		return NULL;
	}
	private function getInternal($index, $array){
		if($array=='assignments'){
			return $this->assignments[$index];
		}
		if($key = $this->resolveMap($which)){
			switch ($array) {
				case 'weighting':
				return $this->weighting[$this->resolveMap($which)];
				break;
				case 'achievement':
				return $this->achievement[$this->resolveMap($which)];
				break;
				default:
				return false;
			}
		}
		return false;
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

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getWeighting($which=NULL){
		if(!isset($which)){
			return $this->weighting;
		}
		return $this->getInternal($which, 'weighting');
	}

	public function getAssignment($which=NULL){
		if(!isset($which)){
			return $this->assignment;
		}
		return $this->getInternal($which, 'assignment');
	}

	public function getAchievement($which=NULL){
		if(!isset($which)){
			return $this->achievement;
		}
		return $this->getInternal($which, 'achievement');
	}

	public function computeAverage(){
		$sum;
		for ($i=0; $i < 5; $i++) { 
			//assuming that we ALWAYS have 5 categories: ku ti comm app final
			$sum+=$this->weighting[$i]*$this->achievement[$i];
		}
		$this->average=$sum;
	}

	public function getAverage(){
		return $this->average;
	}

}
?>