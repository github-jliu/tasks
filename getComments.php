<?php
include_once('./globalInit.inc.php');

include_once(dirname(__FILE__) . '/classes/Task.class.php');
$task = new Task($db);

$loadTaskId = (string)@$_GET['loadid'];
if ( $loadTaskId == '' ) {
}
else {
	$task->loadTaskDataForTaskId($loadTaskId);
}

include_once(dirname(__FILE__) . '/classes/TaskCommentsFormTemplate.class.php');
$template = new TaskCommentsFormTemplate();
print $template->html($task);

include_once('./globalEnd.inc.php');

?>
