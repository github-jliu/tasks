<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/UserAccount.class.php');
$user = new UserAccount($db);
$user->setUserId($_POST['id']);
$user->setEmail($_POST['email']);
$user->setFirstName($_POST['fn']);
$user->setLastName($_POST['ln']);

$returnVals = array();
$user->save($returnVals);

include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('msg', 'User saved successfully');
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
