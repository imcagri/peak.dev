<?php

class User{
	private $dbIns = null;
	const TABLE_NAME = 'users';
	
	function __construct(){
		$this->dbIns = Db::getInstance();
	}
	
	public function get($id = null){ 
	
		$sqlPref =  'SELECT id, name, username, email, status, coin, img FROM ' . self::TABLE_NAME;
		
		if($id === null){
			$sql  = $sqlPref . ' ORDER BY status DESC';
			$stmt = $this->dbIns->prepare($sql);
		}else{
			$sql = $sqlPref . ' WHERE id=:id';
			$stmt = $this->dbIns->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		}
		
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function login($username, $pass){
		$sql = 'SELECT
				id, name, username, email, status, coin, img 
				FROM ' . self::TABLE_NAME . ' 
				WHERE 
					username=:username
				AND
					password=:password
		';
		$stmtLogin = $this->dbIns->prepare($sql);
		$stmtLogin->bindParam(':username', $username, PDO::PARAM_STR);
		$stmtLogin->bindParam(':password', sha1($pass), PDO::PARAM_STR);
		$stmtLogin->execute();
		
		$userInfo = $stmtLogin->fetch(PDO::FETCH_ASSOC);
		
		///user found
		if(isset($userInfo['id'])){
			//updates status state
			$stmt = $this->dbIns->prepare('UPDATE ' . self::TABLE_NAME . ' SET status=1 WHERE id=:id');
			$stmt->bindParam(':id', $userInfo['id'], PDO::PARAM_INT);
			if($stmt->execute()){
				return $userInfo;
			}
		}
		
		//cannot found
		return false;
		
	}
	
	public function logout($userId){
		//updates user status in db, of course these parts added for my demo project
		try{
			$stmt = $this->dbIns->prepare('UPDATE ' . self::TABLE_NAME . ' SET status=0 WHERE id=:id');
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			if($stmt->execute()){
				session_destroy();
				unset($_SESSION);
			}else{
				throw new Exception('err');
			}
		}catch(Exception $error){
			return false;
		}
		
		return true;
		
	}
	
	/*
		type  true: increase coins, false : decrease coins
	*/
	public function changeUserCoin($userId, $price, $type = true){
		try{
			$part = $type ? '+' : '-';
			$sql = '
					UPDATE ' . self::TABLE_NAME . '
					SET coin = coin ' . $part . ' :price
					WHERE id=:id
			';
			$stmt = $this->dbIns->prepare($sql);
			$stmt->bindParam(':price', $price, PDO::PARAM_STR);
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			if(!$stmt->execute()) throw new Exception('err');
		}catch(Exception $error){
			return false;
		}
		
		return true;
	}
	
}
?>