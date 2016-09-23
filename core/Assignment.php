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
		if($this->total[$index]==0)
			return NULL;
		return $this->marks[$index]/$this->total[$index];
	}
	public function getName(){
		return $this->name;
	}
	public function getFormattedScore($category){
		if(isset($this->marks[$category]) && $this->total[$category] != 0){
			return $this->marks[$category].' / '.$this->total[$category].' = '.round($this->getMark[$category], 2).'%';
		}
		return '';
	}
}
?>