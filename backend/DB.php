<?php

class DB
{
	private $dsn;
	private $account;
	private $password;
	private $db_array = array(
		"usual"=>array("dsn"=>"mysql:host=127.0.0.1; dbname=chuinfo_empty; port=3306; charset=utf8mb4", "account"=>"USER_NAME", "password"=>"USER_PASSWORD")
	);
	protected $db;

	public function __construct($db = null)
	{
		$this->db_init($db);
		try {
			$this->db = new PDO($this->dsn, $this->account, $this->password);
			$this->db->exec("SET NAMES utf8mb4");
		} catch (Exception $e) {
			echo "Can't connect to DATABASE.";
			echo $e;
			exit;
		}
	}
	private function db_init($db = null){		 
		 $db_data = array();
		 if($db){
		 	$db_data = $this->db_array[$db];
		 }else{
		 	$db_data = reset($this->db_array);
		 }
		 //print_r($db_data);
		 $this->dsn = $db_data["dsn"];
		 $this->account = $db_data["account"];
		 $this->password = $db_data["password"];
	}
}
?>