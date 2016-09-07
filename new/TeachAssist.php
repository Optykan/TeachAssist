<?php
require_once 'Network.php';
require_once 'Course.php';
require_once 'Assignment.php';

class TeachAssist extends Network{
	private $baseUrl;

	public function __construct($base, $cookiejar){
		$this->baseUrl=$base;
		parent::__construct($cookiejar);
	}
	public function auth($username, $password){
		$response=$this->post($this->baseUrl.'index.php', array('subject_id'=>0, 'username'=>$username, 'password'=>$password, 'submit'=>'Login'));
		// if(preg_match("/ta\.yrdsb\.ca\/(gamma-live)?(yrdsb)?\/index\.php/", $this->getLastCurlUrl()){
		if(preg_match("/listReports\.php/", $this->getLastCurlUrl())){
			//this regex should work for everything
			return $response;
		}
		return false;
	}

	public function getUrls($data){
		$matches=array();
		//cases for available, hidden, or empty (???)
		preg_match_all('/(viewReport\.php\?subject_id=[0-9]+&student_id=[0-9]+)|(Please see teacher.*)|(?:<td align="right">\s+<\/td>)/', $data, $matches);
		return $matches[0];
	}
	public function getIds($data){
		$matches=array();
		preg_match_all('/([A-Za-z]{3}[0-9][A-Za-z][0-z]-[0-9]{2}) : (.*)/', $data, $matches);
		return $matches[1];
	}
	public function getNames($data){
		$matches=array();
		preg_match_all('/([A-Za-z]{3}[0-9][A-Za-z][0-z]-[0-9]{2}) : (.*)/', $data, $matches);
		return $matches[2];
	}

	public function fetchCourseData($url, $id, $name){
		$course=new Course($id, $name);

		$dom=new DOMDocument();
		@$dom->loadHTML($this->get($this->baseUrl.'students/'.$url));

		$tables=$dom->getElementsByTagName('table');
		$marks=$tables->item($tables->length-2); //second last table is the one with the overall marks

		if((bool)$marks &&  strpos($marks->textContent, "Student Achievement") !== false){
			//if the weighting table is available

			$marks=$marks->getElementsByTagName("tr"); 

			for ($j=1; $j < $marks->length; $j++) { 
			//the first iteration is simply the table titles so skip that
			//ku/ti/comm/app/other/final, each one of these iterations cycles to the next category
				if($j==5)
					continue; 
					//skip the 'other' category

				$container=$marks->item($j)->getElementsByTagName("td");

				$achievement=floatval($container->item($container->length-1)->textContent)/100;
				$weighting=floatval($container->item(1)->textContent)/100; 

				$course->addAchievement($achievement);
				$course->addWeighting($weighting);
			}
		}

		$assignments=$tables->item(1)->childNodes;

		if(!is_null($assignments)){
			foreach ($assignments as $key=>$assignment) {
				if($key==0)
					continue;
				$res=$this->niceify($assignment->getElementsByTagName('td'));

				if($res!==false){
					$course->addAssignment($res);
				}				
			}	
		}
		return $course;

	}

	//turn a nasty DOMNode thingy into a nice array
	private function niceify($row){
		//this function fills up data really quickly
		$assignment=new Assignment();
		$array=array();

		$continue=false;
		foreach($row as $key=>$item){
			if($continue){
				$continue=false;
				continue;
			}
			if($key==0){
				if(strlen($item->textContent)==13 && preg_match("/^\s+/", $item->textContent) == 1){
					return false;
					//its blank... why? I'm not quite sure
				}else{
					$assignment->setName(preg_replace('/\s+/', ' ', $item->textContent));
					//push the assignment name
				}
			}
			else if($item->getElementsByTagName('table')->length!==0){
				$mark=array();
				$m=array();
				$compare=preg_replace('/\s+/', ' ', $item->getElementsByTagName('table')->item(0)->getElementsByTagName('td')->item(0)->textContent);
				preg_match('/(([0-9.]+) \/ ([0-9.]+) = [0-9.]+%)\s+(weight=([0-9.]+))?/', $compare, $m);

				$assignment->addMark(floatval($m[2]), floatval($m[3]), floatval($m[5] ?: 0));

				$continue=true;
				//if we extracted data, skip the next iteration, which for some reason is always null
			}else{
				$assignment->addBlank();
				// echo "</br>";
			}
		}
		return $assignment;
	}
}
?>