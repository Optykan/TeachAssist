<?php
require_once('Network.php');

class Authentication extends Network{
	private $baseUrl;

	public function __construct($base){
		$this->baseUrl=$base;
		parent::__construct();
	}
	public function auth($username, $password){
		$response=$this->post($this->baseUrl, array('subject_id'=>0, 'username'=>$username, 'password'=>$password, 'submit'=>'Login'));
		if(preg_match("/ta\.yrdsb\.ca\/(gamma-live)?(yrdsb)?\/index\.php/", $this->getLastCurlUrl()){
			//TODO: update the regex for TDSB
			return false;
		}
		return $response;
	}
}
?>