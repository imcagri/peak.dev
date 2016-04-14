<?php
require_once 'head.php';

$user = new User();
if($user->logout($_SESSION['id'])){
	header('Location: index.php');
	exit;
}else{
	echo 'There is a problem';
}