<?php
include_once('./globalInit.inc.php');

$returnVals = array();


include_once(dirname(__FILE__) . '/classes/UserAccount.class.php');
$user = new UserAccount($db);
$user->setUserId(1);
$user->setEmail('king.funk@gmail.com');
$user->setFirstName('Justin');
$user->setLastName('Liu');
$bSuccess = $user->save($returnVals);

$user = new UserAccount($db);
$user->setUserId(2);
$user->setEmail('viet.tran@uhn.on.ca');
$user->setFirstName('Viet');
$user->setLastName('Tran');


$bSuccess = $user->save($returnVals);


include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$list = new TaskList($db);
$list->setListId(1);
$list->setListName('Testis cancer upgrade');
$list->save($returnVals);

//include_once(dirname(__FILE__) . '/classes/Goal.class.php');
//$list = new Goal($db);
//$list->setGoalId(1);
//$list->setGoalName('Finish something');
//$list->setGoalCreatorUserId(1);
//$list->setParentListId(1);
//$list->save($returnVals);
//
//include_once(dirname(__FILE__) . '/classes/Task.class.php');
//$list = new Task($db);
//$list->setTaskId(1);
//$list->setTaskDescription('Model RPLND pathology findings table');
//$list->setAssignedToUserId(1);
//$list->setCreatorUserId(1);
//$list->setCurrentStatusId(1);
//$list->setParentListId(1);
//$list->save($returnVals);
//

include_once('./globalEnd.inc.php');

?>
