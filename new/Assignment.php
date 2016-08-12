<?php

class Assignment{
	private $marks=array();
	//ku ti comm app, a +ve real number
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
}
?>