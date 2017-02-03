<?php 

class Database{
	private const INI_FILE="config.ini";
	private $settings=array();
	private $conn;
	private $crypt;
	private $username;
	private $insert_statement;

	public function __construct($username, $crypt){
		$this->crypt=$crypt;
		$this->settings=parse_ini_file(INI_FILE);
		$this->conn=new mysqli($this->getVal("DB_URL"), $this->getVal("DB_USER"), $this->getVal("DB_PASS"), $this->getVal("DB_NAME"));
		if($this->$conn->connect_error){
			//failed establishing db_connection
		}
		$this->insert_statement=$this->conn->prepare("INSERT INTO ".$this->getVal("DB_NAME")." (username, data) VALUES (?, ?)");
	}

	private function getVal($key){
		if(isset($this->settings[$key]))
			return $key;
		return null;
	}

	public function insert($data){
		$username=$this->username;
		$this->insert_statement->bind_param("is", $username, $this->encryptData($data));
		$this->insert_statement->execute();
	}

	function encryptData($value){
	   $key = $this->crypt; ///crypt it with the user's password
	   $text = $value;
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
	   return $crypttext;
	}

	function decryptData($value){
	   $key = $this->crypt;
	   $crypttext = $value;
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
	   return trim($decrypttext);
	} 


}