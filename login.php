<?php
require_once 'head.php';

if(isset($_SESSION['id'])){
	header('Location: index.php');
}

$user = new User();
if($_POST){
	if($info = $user->login(trim($_POST['inputUsername']), $_POST['inputPass'])){
		foreach($info as $k=>$v){
			$_SESSION[$k] = $v;
		}
		header('Location: index.php');
	}else{
		$msg = 'User cannot found';
	}
}
?>
<html>
<head>
	<title>Login</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
<br/>

<form method='post'>
	<div class='container'>
	
		<?php if(isset($msg)): ?>
			<div class='row'>
				<div class='col-lg-3'>
					<div class="alert alert-danger" role="alert"><?=$msg?></div>
				</div>
			</div>
		<?php endif;?>
		
		<div class='row'>
			<div class='col-lg-1'>
				<h4>Username</h4>
			</div>
			<div class='col-lg-2'>
				<input type='text' name='inputUsername' class='form-control'/>
			</div>
		</div>
		<div class='row'>
			<div class='col-lg-1'>
				<h4>Password</h4>
			</div>
			<div class='col-lg-2'>
				<input type='password' name='inputPass'  class='form-control'/>
			</div>
		</div>
		<div class='row'>
			<div class='col-lg-3'>
				<button type='submit' class='center-block form-control'>Login</button>
			</div>
		</div>
		
		<div>
		<h2>Giriş yapılabilecek kullanıcı adları</h2>
			<?php foreach($user->get() as $user):?>
				<?php echo $user['username'];?>, 
			<?php endforeach; ?>
			<h2>Parola</h2>
			123456
		</div>
		
	</div> <!-- container -->
</form>



</body>
</html>