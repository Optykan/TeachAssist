<?php
session_start();
class User{
	private $connection;
	private $curl;
	private $data;
	private $id;
	private $url="https://ta.yrdsb.ca/yrdsb/index.php";
	private $credentials=array();

	private function connect(){
		// $this->connection=pg_connect();
	}

	public function __construct($session, $username=null, $password=null){
		if(isset($session)){
			$this->data=$session;
		}
		// $this->connect();
		$this->curl=curl_init();

		curl_setopt($this->curl, CURLOPT_URL, $this->url);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
//listReports\.php\?student_id=(.*)
		$this->credentials['username']=$username;
		$this->credentials['password']=$password;
	}

	public function fetch(){
		$vars='subject_id=0&username='.$this->credentials['username'].'&password='.$this->credentials['password'].'&submit=Login';


		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $vars);
		$response=curl_exec($this->curl);
		curl_close($this->curl);


		echo "<pre>";
		var_dump($response);
		echo "<pre>";
	}


}
/* Username | Password (hashed) | Courses | Marks (array ku/ti/comm/app/other) 
*  reconsider this data structure
*
*
*
	*/
?>