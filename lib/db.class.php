<?php

class Db extends PDO{
	private $dsn = 'mysql:dbname=peak;host=localhost';
	private $user = '********';
	private $pass = '*******';
	private static $dbIns;
	
	function __construct(){
		parent::__construct($this->dsn, $this->user, $this->pass);
	}
	
	public static function getInstance(){
		if(self::$dbIns === null){
			self::$dbIns = new Db();
		}
		return self::$dbIns;
	}
}

?>