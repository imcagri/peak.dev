<?php

class giftTypes{
	const TABLE_NAME = 'gift_types';
	/*const GIFT_FLOWER = 1;
	const GIFT_LOVE   = 2;
	const GIFT_COIN   = 3;*/

	private static $dbIns = null;
	
	public static function get($id = null){
		self::$dbIns = Db::getInstance();
		
		$sqlPref =  'SELECT id, title, price, img FROM ' . self::TABLE_NAME;
		
		if($id === null){
			$sql  = $sqlPref . ' ORDER BY `order`';
			$stmt = self::$dbIns->prepare($sql);
		}else{
			$sql = $sqlPref . ' WHERE id=:id';
			$stmt = self::$dbIns->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		}
		
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	
}