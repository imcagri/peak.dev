<?php

class Gift{
	const TABLE_NAME = 'gifts';
	private $dbIns = null;
	
	function __construct(){
		$this->dbIns = Db::getInstance();
	}
	
	public function get($id = null){
		$sqlPref =  'SELECT * FROM ' . self::TABLE_NAME;
		
		if($id === null){
			$stmt = $this->dbIns->prepare($sqlPref);
		}else{
			$sql = $sqlPref . ' WHERE id=:id';
			$stmt = $this->dbIns->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		}
		
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function isExceedDailyLimit($userId, $receiverUserId){
		$sql = '
			SELECT
				COUNT(id) AS total
			FROM ' . self::TABLE_NAME . '
			WHERE 
				sender_id=:sender_id
			AND
				receiver_id=:receiver_id
			AND	
				date>=(NOW() - INTERVAL 1 DAY)
		';
		$stmt = $this->dbIns->prepare($sql);
		
		$stmt->bindParam(':sender_id', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':receiver_id', $receiverUserId, PDO::PARAM_INT);
		
		$stmt->execute();
		$res = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
		return $res ? true : false;		
	}
	
	private function haveIEnoughMoneyForGift($userId, $giftId){
		$sql = '
			SELECT
				COUNT(u.id) AS total
			FROM users AS u
			WHERE 
				u.coin>=(
					SELECT 
						gf.price
					FROM gift_types AS gf
					WHERE
						gf.id=:gift_id
				)
			AND	
				u.id=:user_id
		';
		$stmt = $this->dbIns->prepare($sql);
		
		$stmt->bindParam(':gift_id', $giftId, PDO::PARAM_INT);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		
		$stmt->execute();
		$res = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
		
		return $res ? true : false;	
	}
	
	
	
	public function sendGift($senderId, $receiverUserId, $giftId){
		try{
			$this->dbIns->beginTransaction();
			
			if($this->isExceedDailyLimit($senderId, $receiverUserId)){
				throw new Exception('Exceed daily gift limit for this user');
			}
			
			if(!$this->haveIEnoughMoneyForGift($senderId, $giftId)){
				throw new Exception('You haven\'t enough money for send this gift');
			}
			
			$sql = 'INSERT INTO ' . self::TABLE_NAME . ' 
				(sender_id, receiver_id, gift_id)
				VALUES
				(:sender_id, :receiver_id, :gift_id)
			';
			
			$stmt = $this->dbIns->prepare($sql);
			$stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
			$stmt->bindParam(':receiver_id', $receiverUserId, PDO::PARAM_INT);
			$stmt->bindParam(':gift_id', $giftId, PDO::PARAM_INT);
			
			if(!$stmt->execute()){
				throw new Exception('cannot send gift');
			}
			
			$user = new User();
			$gift = GiftTypes::get($giftId);
			if(!$res = $user->changeUserCoin($senderId, $gift[0]['price'], false)){
				throw new Exception('cannot decrease user price');
			}
			
			//commit
			$this->dbIns->commit();
			
			return 'Sent Successfully';
		}catch(Exception $e){
			//rollback
			$this->dbIns->rollBack();
			return $e->getMessage();
		}
	}
	
	public function getPendingGifts($userId){
		$sql = '
			SELECT
				g.id AS giftListId,
				us.name AS senderName,
				gt.title AS giftTitle,
				gt.img AS giftImage
			FROM ' . self::TABLE_NAME . ' AS g
			JOIN gift_types AS gt ON gt.id = g.gift_id
			JOIN users AS us ON us.id = g.sender_id
			JOIN users AS ur ON ur.id = g.receiver_id
			WHERE 
				g.pending=1
			AND	
				date>=(NOW() - INTERVAL 1 WEEK)
			AND
				ur.id=:user_id
		';
		
		$stmt = $this->dbIns->prepare($sql);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function acceptOrDismissGift($receiverUserId, $giftListId, $choice){
		$this->dbIns->beginTransaction();
		
		$sql = 'UPDATE ' . self::TABLE_NAME . ' 
				SET pending=:pending
				WHERE id=:gift_list_id
		';
		$stmt = $this->dbIns->prepare($sql);
		
		$pending = 0;
		
		$stmt->bindParam(':gift_list_id', $giftListId, PDO::PARAM_INT);
		$stmt->bindParam(':pending', $pending, PDO::PARAM_INT);
		
		try{
			if(!$stmt->execute()){
				throw new Exception('insert error');
			}
		
			if($choice == 1){ //accepted
				$giftId = $this->get($giftListId)[0]['gift_id'];
				$giftPrice = GiftTypes::get($giftId)[0]['price'];
				
				$user = new User();
				if(!$user->changeUserCoin($receiverUserId, $giftPrice, true)){
					throw new Exception('increase coin error');
				}
			}
			
			$this->dbIns->commit();
			return $choice ? 'Accepted' : 'Dismiss';
		}catch(Exception $e){
			$this->dbIns->rollBack();
			//Log $e->getMessage();
			return 'An error occured';
		}
		
		
	}
	
	
}



