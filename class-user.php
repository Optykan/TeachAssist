<?php
session_start();
class User{
	private $connection;
	private $curl;
	private $data;
	private $id;
	private $url="https://ta.yrdsb.ca/yrdsb/index.php";
	private $credentials=array();
	private $courses=array();

	private function connect(){
		// $this->connection=pg_connect();
	}

	public function __construct($username, $password=null){
		// $this->connect();
		$this->curl=curl_init();

		curl_setopt($this->curl, CURLOPT_URL, $this->url);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $username.'.txt');
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $username.'.txt');

		$this->credentials['username']=$username;
		$this->credentials['password']=$password;
	}

	public function fetch(){
		$vars='subject_id=0&username='.$this->credentials['username'].'&password='.$this->credentials['password'].'&submit=Login';

		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $vars);
		$response=curl_exec($this->curl);
		curl_close($this->curl);

		$matches=array();
		preg_match('/listReports\.php\?student_id=(.*)/', $response, $matches);
		var_dump($matches);
		if(isset($matches[1]))
			$this->id=$matches[1];
		else
			$this->id=null;
		unset($matches);

		preg_match_all('/([A-Z]{3}[0-9][A-Z][0-z]-[0-9]{2}) : (.*)/', $response, $matches);
		$this->courses=array_slice($matches, 1);
		echo '<pre>';
		var_dump($response);
		echo '</pre>';
		//viewReport\.php\?subject_id=[0-9]+&student_id=[0-9]+
	}


}
/* Username | Password (hashed) | Courses | Marks (array ku/ti/comm/app/other) 
*  reconsider this data structure
*
*
*
	*/
?>