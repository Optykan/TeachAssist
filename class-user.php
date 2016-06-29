<?php
class User{
	public $weighting=array(); //whats the weight
	public $coursedata=array();//course codes, names, links
	public $achievement=array(); //marks in a decimal
	public $credentials=array();
	public $courseCount;
	private $temp=array(); //for mark comparison
	private $connection; //for pgsql resource
	private $data; //raw data dump, not yet used
	private $handle; //cURL handle
	private $courses=array(); //not used yet
	private $lastUpdated;

	public function __construct($username=null, $password=null){
		// $this->connect();
		$this->credentials['username']=$username;
		$this->credentials['password']=$password;
		$this->handle=curl_init();
		curl_setopt($this->handle, CURLOPT_VERBOSE, TRUE);
		// curl_setopt($this->handle, CURLOPT_HEADER, 1);
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->handle, CURLOPT_COOKIEJAR, $this->credentials['username'].'.txt'); //there's probably a security hole in here somewhere
		curl_setopt($this->handle, CURLOPT_COOKIEFILE, $this->credentials['username'].'.txt'); //but teachassist seems to cover it, just reauth every time
	}
	public function __sleep(){
		curl_close($this->handle);
		return array_keys(get_object_vars($this));
	}
	public function __wakeup(){
		if(isset($this->credentials['password']) && isset($this->credentials['username'])){
			$this->__construct($this->credentials['username'], $this->credentials['password']);
		}
		$this->update();
	}
	public function getCourse($index, $prop){
		switch ($prop) {
			case 'name':
			return $this->coursedata[1][$index];
			break;
			case 'id':
			return $this->coursedata[0][$index];
			break;
			default:
				# code...
			break;
		}
		return false;
	}
	public function toName($course){
		$index=array_search($course, $this->coursedata[0]);
		return $this->coursedata[1][$index];
	}
	public function getTermAverage($course){
		if(!isset($this->achievement[$course][1]))
			return false;
		$total=0;
		for($i=1; $i<= 4; $i++){ 
		//we're assuming that there's only ku/ti/comm/app (oops?)
			$total+=$this->achievement[$course][$i]*$this->weighting[$course][$i];
		}
		return $total;
	}
	public function getCourseAverage($course){
		if(!isset($this->achievement[$course][1]))
			return false;
		$total=0;
		$examWeighting=end($this->weighting[$course]);
		for($i=1; $i<=4; $i++){ //hopefully it ends at app
			$total+=$this->achievement[$course][$i]*round((1.0-$examWeighting)*$this->weighting[$course][$i], 2);
		}
		$total+=end($this->achievement[$course])*$examWeighting;
		return $total;
	}
	public function getAverage($course){
		if(end($this->achievement[$course]) != 0){
			//we're trusting that nobody get's a 0 on their exam
			return $this->getCourseAverage($course);
		}
		return $this->getTermAverage($course);
	}

	public function getLastMark($course){
		$last=end($this->achievement[$course]);
		if(!isset($last)){
			return 0;
		}
		return $last;
	}

	public function login(){
		$response=$this->curl('post', "https://ta.yrdsb.ca/yrdsb/index.php", array('subject_id'=>0, 'username'=>$this->credentials['username'], 'password'=>$this->credentials['password'], 'submit'=>'Login'));
		if(preg_match("/ta\.yrdsb\.ca\/(gamma-live)?(yrdsb)?\/index\.php/", curl_getinfo($this->handle)['url']) === 1){
			//cURL was not redirected to the dashboard
			return false;
		}
		return $response;
	}

	public function update($force=false){
		var_dump($this->lastUpdated);
		if($force || time()-$this->lastUpdated>600){ //10 minute cache (41400)
			header("X-Load-From-Cache: False");
			//but somehow time is appearing 12 hours in the past?
			$this->fetch();
		}else{
			header("X-Load-From-Cache: True");
		}
	}

	private function error($message){
		echo $message;
	}

	private function curl($method, $url, $prop){
		$prop=http_build_query($prop);
		if($method=='post'){
			curl_setopt($this->handle, CURLOPT_URL, $url);
			curl_setopt($this->handle, CURLOPT_POST, 1);
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, $prop);
		}else{
			curl_setopt($this->handle, CURLOPT_URL, $url.$prop);
			curl_setopt($this->handle, CURLOPT_POST, 0);
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, null);
		}
		return curl_exec($this->handle);
		// return $result;
	}

	public function fetch(){ //master reload EVERYTHING
		$response=$this->login();
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
			@$dom->loadHTML($this->curl('get', 'https://ta.yrdsb.ca/gamma-live/students/'.$this->coursedata[2][$i], array())); //suppress the mass of commas
			$tables=$dom->getElementsByTagName('table');
			$marks=$tables->item($tables->length-2);

			if(strpos($marks->textContent, "Student Achievement") === false)
				continue; //marks have been hidden 

			$marks=$marks->getElementsByTagName("tr"); //overwrite because the original is not important

			$this->achievement[$this->coursedata[0][$i]]=array(); //we're using the course name as the index
			$this->weighting[$this->coursedata[0][$i]]=array();
			for ($j=1; $j < $marks->length; $j++) { //the first iteration is simply the table titles so skip that
				//ku/ti/comm/app/other/final, each one of these iterations cycles to the next category
				if($j==5)
					continue; //skip the 'other' category
				$container=$marks->item($j)->getElementsByTagName("td");
				$this->achievement[$this->coursedata[0][$i]][$j]=floatval($container->item($container->length-1)->textContent)/100; //mark is always the last one
				
				$this->weighting[$this->coursedata[0][$i]][$j]=floatval($container->item(1)->textContent)/100; //weighting is always the second one
			}
		}

		// foreach($this->courses as $course){
		// 	echo '<pre>';
		// 	var_dump($course);
		// 	echo '</pre>';
		// }
		$this->courseCount=count($this->coursedata);
		$this->lastUpdated=time();
	}
}
/* Username | Password (hashed) | coursedata | Marks (array ku/ti/comm/app/other) 
*  reconsider this data structure
*
*
*
	*/
?>