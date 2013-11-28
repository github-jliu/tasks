<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/TaskConstants.class.php');
include_once(dirname(__FILE__) . '/classes/Task.class.php');
$g = new Task($db);
$g->setTaskId($_POST['id']);
$g->setTaskDescription($_POST['desc']);
$g->setAssignedToUserId($_POST['assigned']);
$g->setDueDate($_POST['due']);
$g->setParentListId($_POST['listid']);

$status = (string)$_POST['stat'];
if ($status == '' ) { $status = TaskConstants::STATUS_INCOMPLETE; }
$g->setCurrentStatusId($status);
$g->setComments($_POST['comments']);


include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$logSess = new LoginSession();
$g->setCreatorUserId($logSess->getCurrentUserId());

$returnVals = array();
$g->save($returnVals);


$newTaskDependencies = array();
foreach($_POST as $key => $value) {
	if (ereg("^dep[0-9]+$", $key)) {
		$newTaskDependencies[] = $value;
	}
}
//$newGoalDependencies = array($_POST['dep']);
$g->updateTaskDependencies($_POST['id'], $newTaskDependencies);

$newGoalParentRevisions = array($_POST['rev']);
$g->updateTaskParentRevisions($_POST['id'], $newGoalParentRevisions);


include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('msg', 'Task saved successfully');
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
