<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$logSess = new LoginSession();

$user = (string)@$_POST['user'];
$pw = (string)@$_POST['pw'];

if ( $user != '' && $user == 'justin' ) {
	$logSess->setCurrentUserId(1);
	header("Location: index.php");
	exit;
}
$logSess->setCurrentUserId('');
//$logSess->setCurrentListId(1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link href="interface.css" rel="stylesheet"/>
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	</head>
	<body>
		<div class="main">
			<form method="POST" action="">
				<label>User: 
					<input name="user"></input>
				</label>
				<label>
					Password:
					<input type="password" name="pw"></input>
				</label>
				
				<button type="submit">Go</button>
			</form>
		</div>
	</body>
</html>