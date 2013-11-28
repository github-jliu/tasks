<?php
include_once('./globalInit.inc.php');

$checked = (string)$_POST['checked'];
$id = (string)$_POST['id'];
if ( $checked != '' && $id != '' ) {
	include_once dirname(__FILE__) . '/classes/TaskConstants.class.php';
	if ( $checked == 'Y' ) {
		$status = TaskConstants::STATUS_COMPLETE;
		$text = 'complete';
	}
	else {
		$status = TaskConstants::STATUS_INCOMPLETE;
		$text = 'incomplete';
	}

	$s = 'update task set current_status_id = :current_status_id where task_id = :task_id';
	$b = array('current_status_id' => $status, 'task_id' => $id);
	$r = array();

	$db->runCommand($s, $b, $r);
	$db->commit();
}

include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('msg', "Task marked as $text");
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
