<?php
session_start();
class User{
	private $connection;
	private $data;
	private $id;
	private $url="https://ta.yrdsb.ca/yrdsb/index.php";
	private $credentials=array();
	private $coursedata=array();
	private $handle;
	private $courses=array();
	private $achievement=array();

	private function connect(){
		// $this->connection=pg_connect();
	}

	public function __construct($username, $password=null){
		// $this->connect();
		$this->credentials['username']=$username;
		$this->credentials['password']=$password;
		$this->handle=curl_init();
		// curl_setopt($handle, CURLOPT_VERBOSE, TRUE);
		// curl_setopt($this->handle, CURLOPT_HEADER, 1);
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->handle, CURLOPT_COOKIEJAR, $this->credentials['username'].'.txt');
		curl_setopt($this->handle, CURLOPT_COOKIEFILE, $this->credentials['username'].'.txt');
	}

	private function curl($method, $url, $prop, $handle){
		$prop=http_build_query($prop);
		if($method=='post'){
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_POST, 1);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $prop);
		}else{
			curl_setopt($handle, CURLOPT_URL, $url.$prop);
			curl_setopt($handle, CURLOPT_POST, 0);
			curl_setopt($handle, CURLOPT_POSTFIELDS, null);
		}
		return curl_exec($handle);
		// return $result;
	}
	public function fetch(){

		//exec main page
		$response=$this->curl('post', $this->url, array('subject_id'=>0, 'username'=>$this->credentials['username'], 'password'=>$this->credentials['password'], 'submit'=>'Login'), $this->handle);

		//fetch links
		$matches=array();
		preg_match_all('/([A-Z]{3}[0-9][A-Z][0-z]-[0-9]{2}) : (.*)/', $response, $matches);
		$this->coursedata=array_slice($matches, 1);
		preg_match_all('/(viewReport\.php\?subject_id=[0-9]+&student_id=[0-9]+)|(Please see teacher.*)/', $response, $matches);
		array_push($this->coursedata, $matches[0]);

		//parse links
		for ($i=0; $i < count($this->coursedata[2]); $i++) { 
			if(strpos($this->coursedata[2][$i], "Please") !== false)
				continue; // Marks have been hidden, ie: "Please see your teacher for..."

			$dom=new DOMDocument();
			@$dom->loadHTML($this->curl('get', 'https://ta.yrdsb.ca/gamma-live/students/'.$this->coursedata[2][$i], array(), $this->handle)); //suppress
			$tables=$dom->getElementsByTagName('table');
			$marks=$tables->item($tables->length-2);

			if(strpos($marks->textContent, "Student Achievement") === false)
				continue; //marks have been hidden 

			$marks=$marks->getElementsByTagName("tr"); //overwrite because the original is not important

			$this->achievement[$this->coursedata[0][$i]]=array(); //we're using the course name as the index
			for ($j=1; $j < $marks->length; $j++) { //the first iteration is simply the table titles so skip that
				//ku/ti/comm/app/other/final, each one of these iterations cycles to the next category
				$container=$marks->item($j)->getElementsByTagName("td");
				$this->achievement[$this->coursedata[0][$i]][$j]=$container->item($container->length-1)->textContent;
			} //GOT IT WOOOO
		}
		echo "<pre>";
		var_dump($this->achievement);
		echo "</pre>";

		// foreach($this->courses as $course){
		// 	echo '<pre>';
		// 	var_dump($course);
		// 	echo '</pre>';
		// }

	}


}
/* Username | Password (hashed) | coursedata | Marks (array ku/ti/comm/app/other) 
*  reconsider this data structure
*
*
*
	*/
?>