<?php
//this is the object that we will store for caching
class Storage{

	private $courses=array();
	private $storage;
	private $hash;
	private $timestamp;

	public function __construct($courses){
		$this->courses=$courses;
	}
	public function __sleep(){
		$this->storage=gzcompress(serialize($this->courses));
		$this->hash=crc32($this->storage);
		$this->timestamp=time();
		return array('storage', 'hash');
	}

	public function getCourses(){
		if(crc32($this->storage)==$this->hash){
			return unserialize(gzuncompress($this->courses));
		}
		return false;
	}
}

?>