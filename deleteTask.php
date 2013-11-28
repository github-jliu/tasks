<?php
include_once('./globalInit.inc.php');

$id = (string)$_POST['deleteid'];
if ( $id != '' ) {
	include_once(dirname(__FILE__) . '/classes/TaskConstants.class.php');
	include_once(dirname(__FILE__) . '/classes/Task.class.php');
	$g = new Task($db);
	$g->setTaskId($id);
	
	$returnVals = array();
	$g->delete($returnVals);

	include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
	$j = new JsonResponse();
	$j->setResponseProperty('msg', 'Task deleted successfully');
	print $j->getAsJson();
}

include_once('./globalEnd.inc.php');

?>
