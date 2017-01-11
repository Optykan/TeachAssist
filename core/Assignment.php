<?php

class Assignment{
	private $marks=array();
	//ku ti comm app, a +ve real number representing how many marks achieved
	private $total=array();
	//ku ti comm app, a +real number representing the maximum number of marks
	private $weight=array();
	//same thing, a real number representing how much each category is weighted
	private $name;

	public function setName($name){
		$this->name=$name;
	}
	public function addMark($mark, $total, $weight){
		array_push($this->marks, $mark);
		array_push($this->total, $total);
		array_push($this->weight, $weight);
	}
	public function addBlank(){
		array_push($this->marks, NULL);
		array_push($this->total, NULL);
		array_push($this->weight, NULL);
		//to keep everything in line
	}
	public function getWeight($index){
		return isset($this->weight[$index]) ? $this->weight[$index]: NULL;
	}
	public function getNumerator($category){
		//where category is a good ol' 01234
		return $this->marks[$category];
	}
	public function getTotal($category){
		return $this->total[$category];
	}
	public function getMark($index){
		if(!isset($this->total[$index])) return NULL;
		if($this->total[$index]==0)	return NULL;
		return $this->marks[$index]/$this->total[$index];
	}
	public function getName(){
		return $this->name;
	}
	public function getFormattedScore($category){
		switch ($category) {
			case 0:
			$class='ku';
			break;
			case 1:
			$class='ti';
			break;
			case 2:
			$class="comm";
			break;
			case 3:	
			$class="app";
			break;
			case 4:
			$class="final";
			break;
			default:
			$class="ku";
			break;
		}
		if(isset($this->marks[$category]) && $this->total[$category] != 0){
			return '<div class="mark">'.$this->marks[$category].' / '.$this->total[$category]." = <span class='$class'>".round($this->getMark($category)*100, 2)."</span>%</div><div class='weight weight-$class'>{$this->getWeight($category)}</div>";
		}
		return '&nbsp;';
	}
	public function fetchData(){
		return array('name'=>$this->name, 'marks'=>$this->marks, 'total'=>$this->total, 'weight'=>$this->weight);
	}
}
?>