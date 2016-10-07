<?php 

class Course{
	private $id;
	private $name;
	private $map=array();
	private $weighting=array();
	private $assignments=array();

	//teachassist reporting numbers
	private $average;
	private $achievement=array();

	//our numbers based on raw data
	private $scraperAverage;
	private $scraperAchievement=array(0,0,0,0,0);


	public function __construct($courseId, $courseName){
		$this->map=array('ku', 'ti', 'comm', 'app', 'final');
		$this->id=$courseId;
		$this->name=$courseName;
	}

	private function resolveMap($input){
		if(is_int($input) && $input<count($this->map)){
			return $this->map[$input];
		}else{
			foreach ($this->map as $key => $value) {
				if($value==$input){
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
		if($key = $this->resolveMap($index)){
			switch ($array) {
				case 'weighting':
				return $this->weighting[$this->resolveMap($index)];
				break;
				case 'achievement':
				return $this->achievement[$this->resolveMap($index)];
				break;
				default:
				return false;
			}
		}
		return false;
	}
	private function hasFinal(){
		foreach($this->assignments as $assignment){
			if($assignment->getMark(4))
				return true;
		}
		return false;
	}

	public function setAverage($average){
		$this->average=$average;
	}

	public function addAchievement($mark){
		array_push($this->achievement, $mark);
	}
	public function addWeighting($weight){
		array_push($this->weighting, $weight);
	}
	public function addAssignment(Assignment $data){
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
		return $this->weighting[$which];
		// return $this->getInternal($which, 'weighting');
	}

	public function getAssignment($which=NULL){
		if(!isset($which)){
			return $this->assignments;
		}
		return $this->assignments[$which] ?: false;
	}

	public function getAchievement($which=NULL){
		if(!isset($which)){
			return $this->achievement;
		}
		return $this->getInternal($which, 'achievement');
	}

	public function computeAverage(){
		$sum=0;
		for ($i=0; $i < 5; $i++) { 
			//assuming that we ALWAYS have 5 categories: ku ti comm app final
			if(!is_null($this->achievement[$i])){
				$sum+=$this->weighting[$i]*$this->achievement[$i];
			}
		}
		$this->average=$sum;
	}

	public function computeScraperAverage(){
		//this is the average based off of our calculations

		//percents
		$marks=array([], [], [], [], []);
		//numbers
		$weight=array([], [], [], [], []);
		//more numbers
		$totalWeight=array(0, 0, 0, 0, 0);

		//this has a runtime of o(scary)
		//if teachers havent inputted at least one mark in each category then we're screwed
		foreach($this->assignments as $assignment){
			for($i=0; $i<5; $i++){
				array_push($marks[$i], $assignment->getMark($i));
				array_push($weight[$i], $assignment->getWeight($i));
				$totalWeight[$i]+=$assignment->getWeight($i);
			}
		}

		//now we have a populated marks array, as well as total weightings for each category
		$max=count($marks[0]);
		foreach($marks as $category=>$mark){
			for($i=0; $i<$max; $i++){
				if(isset($totalWeight[$i]) && !is_null($totalWeight[$i]) && $totalWeight[$i] !== 0 && (bool)$weight[$category][$i]){
					$this->scraperAchievement[$category]+=($weight[$category][$i]/$totalWeight[$category])*$marks[$category][$i];
				}
			}	
		}

		$sum=0;
		$hasFinal=$this->hasFinal();
		if($hasFinal){
			for($i=0; $i<5; $i++){
				if($i==4)
					$sum+=$this->weighting[$i]*$this->scraperAchievement[$i];
				else
					$sum+=$this->weighting[$i]*0.7*$this->scraperAchievement[$i];
			}	
		}
		else{
			for($i=0; $i<4; $i++){
				$sum+=$this->weighting[$i]*$this->scraperAchievement[$i];
			}	
		}
		$this->scraperAverage=$sum;
	}

	public function getScraperAverage(){
		return $this->scraperAverage;
	}

	public function getAverage(){
		return $this->average;
	}

}
?>