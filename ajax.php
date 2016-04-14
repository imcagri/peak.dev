<?php
require_once 'head.php';

if(!isset($_SESSION['id'])){
	die;
}

$user = new User();
$gift = new Gift();

//responses back process results
function response($data){
	if(is_array($data)){
		header('Content-Type: application/json');
		echo json_encode($data);
	}else{
		echo $data;
	}
	die;
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
	response('method not allowed');
}

switch($_POST['process']){
	case 'sendgift':
		$res = $gift->sendGift($_SESSION['id'], $_POST['receiverUserId'], $_POST['giftid']);
		response($res);
	break;
	case 'acceptordismiss':
		$res = $gift->acceptOrDismissGift($_SESSION['id'], $_POST['giftListId'], $_POST['choice']);
		response($res);
	default:
		response('i don\'t understand');
}



