<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/UserAccount.class.php');
$user = new UserAccount($db);
$user->setUserId(1);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Justin');
$user->setLastName('Liu');

$returnVals = array();
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(2);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Viet');
$user->setLastName('Tran');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(3);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Raymond');
$user->setLastName('Chow');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(4);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Yuliya');
$user->setLastName('Gavrylyuk');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(5);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Mei Fen');
$user->setLastName('Zhao');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(6);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Tran');
$user->setLastName('Truong');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(7);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Chris');
$user->setLastName('Hamill');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(8);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Kelly');
$user->setLastName('Lane');
$bSuccess = $user->save($returnVals);


include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$logSess = new LoginSession();
$currUserId = (int)$logSess->getCurrentUserId();
if ( $currUserId == 0 ) {
	header("Location: login.php");
	exit;
}
$logSess->setCurrentUserId(1);
//$logSess->setCurrentListId(1);


include_once(dirname(__FILE__) . '/classes/TaskListSet.class.php');
$taskListSet = new TaskListSet($db);
$arr = $taskListSet->getAllTaskListData();
$listOpt = array();
foreach( $arr as $list ) {
	$listOpt[$list['LIST_ID']] = $list['LIST_NAME'];
}
asort($listOpt);

$currListId = (string)$logSess->getCurrentListId();
if ( $currListId == '' && !empty($listOpt) ) {
	$currListId = key($listOpt);
	$logSess->setCurrentListId($currListId);
}
include_once(dirname(__FILE__) . '/classes/HeaderTemplate.class.php');
$template = new HeaderTemplate();
$headerHtml = $template->html($listOpt, $currListId);

include_once(dirname(__FILE__) . '/classes/NavBarTemplate.class.php');
$template = new NavBarTemplate();
$navBarHtml = $template->html('Task dependency view');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link href="interface.css" rel="stylesheet"/>
		<link href="css/jquery-ui-1.7.custom.cal.css" rel="stylesheet"/>
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.7.custom.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript" src="js/dependencyView.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>
	</head>
	<body>
		<div class="main">
			<?php print $navBarHtml; ?>
			<div class="clear"></div>
			<?php print $headerHtml; ?>
<!--			<ul id="tl">
			</ul>-->

			<form id="f" method="POST" action="">

			</form>
			
			<ul class="nav">
				<li>
				<a href="#" onclick="return false;" id="addt">Add task</a>
				</li>
				<li>
				<a href="#" onclick="return false;" id="showt">Show completed tasks</a>
				</li>
			</ul>
			<div class="clear"></div>



			<div id="msg"></div>

			<ul id="tasks">
				
			</ul>
		</div>
	</body>
</html>