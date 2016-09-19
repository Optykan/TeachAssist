<?php
class Database{
	private $db;

	public function pull(){
		
	}
	private function connect(){ 
		$this->db=pg_connect(getenv("DB_URL"));
	}

}
?>