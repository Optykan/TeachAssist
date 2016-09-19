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
		//we get like 66% compression with gzcompress but then down to like 50% because base64
		$this->storage=base64_encode(gzcompress(serialize($this->courses)));
		// $this->storage=serialize($this->courses);
		$this->hash=crc32($this->storage);
		$this->timestamp=time();
		return array('storage', 'hash');
	}

	public function getCourses(){
		if(crc32($this->storage)==$this->hash){
			$serialized=gzuncompress(base64_decode($this->storage));
			// $serialized=$this->storage;
			return unserialize($serialized);
		}
		return false;
	}
}

?>