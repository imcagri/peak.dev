<?php
require_once 'head.php';

if(!isset($_SESSION['id'])){
	header('Location: login.php');
}

$user = new User();
$gift = new Gift();

//just prevention, multiple create session for a same user.
$usrInfo = $user->get($_SESSION['id'])[0];
if($usrInfo['status'] == 0){
	$user->logout($usrInfo['id']);
	header('Location: login.php');
}

$pendingGiftsForMe = $gift->getPendingGifts($_SESSION['id']);
?>
<!doctype html>
<html>
<head>
	<title>Demo</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	
	<script src='js/jquery.min.js'></script>
	<script src='js/general.js'></script>
</head>
<body>


	<br/>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-4'>
				<div>
					<label><?php echo $_SESSION['name'];?></label>
					<span>$<?php echo $user->get($_SESSION['id'])[0]['coin'];?></span>
					<a href="logout.php">Logout</a>
				</div>
				<br/>
				<div style="overflow-y: scroll;">
					<?php foreach($user->get() as $user):?>
						<?php if($user['id'] != $_SESSION['id']): ?>
							<div class='user'>
								<input type='hidden' name='status' status=<?php echo $user['status']; ?> />
								<div style='display:none' class='user-id'><?php echo $user['id']; ?></div>
								<!-- user index includes by js in general.js -->
								<div class='user-index'></div>
								
								<div class='user-image'>
									<img class='img' src="img/user/<?=$user['img']?>.jpg" />
								</div>
								
								<div class='user-name-price'>
									<div><?=$user['name']?></div>
									<div class='user-price'>$<?=$user['coin']?></div>
								</div>
								
								<div class='user-gift'>
									Send<br/> Gift
								</div>
							</div>
							
							<div style='clear:both;'></div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class='col-lg-4'>
			<?php if(!empty($pendingGiftsForMe)): ?>
				<h3>Pending Gifts</h3>
				
				<div id='pending-gifts'>
					<?php foreach($pendingGiftsForMe as $gift):?>
						<div class='pending-gift'>
							<div style='display:none' class='pending-gift-id'><?=$gift['giftListId']?></div>
							<div class='pending-gift-img'><img class='img' src='img/gift/<?=$gift['giftImage']?>.png' alt='<?=$gift['giftTitle']?>'/></div>
							<div class='pending-gift-sender'><?=$gift['senderName']?></div>
							<div class='pending-gift-accept' choice='1'>Accept</div>
							<div class='pending-gift-dismiss' choice='0'>Dismiss</div>
						</div>
						<div style='clear:both;'></div>
					<?php endforeach;?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div id='gift-window'>
		<div style='display:none' id='receiver-user-id'></div>
		<?php foreach(GiftTypes::get() as $giftType):?>
			<div class='gift'>
				<div style='display:none' class='gift-id'><?=$giftType['id']?></div>
				<div class='gift-title'><?/*=$giftType['title']*/?></div>
				<div class='gift-image'><img class='img' src='img/gift/<?=$giftType['img']?>.png' /></div>
				<div class='gift-price'>$<?=$giftType['price']?></div>
				<div class='gift-send-button'>Send</div>
			</div>
			<div style='clear:both;'></div>
		<?php endforeach; ?>
	</div>

	<script>
	  window.fbAsyncInit = function() {
		FB.init({
		  appId      : '587242578104808',
		  xfbml      : true,
		  version    : 'v2.5'
		});
	  };

	  (function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "//connect.facebook.net/en_US/sdk.js";
		 fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>
</body>
</html>



