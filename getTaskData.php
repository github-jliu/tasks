<?php
include_once('./globalInit.inc.php');

include_once(dirname(__FILE__) . '/classes/Task.class.php');
$task = new Task($db);

$loadTaskId = (string)@$_GET['loadid'];
$forceDepId = (string)@$_GET['depid'];
$forceRevId = (string)@$_GET['revid'];
if ( $loadTaskId == '' ) {
	$task->setTaskId($task->generateNewTaskId());
}
else {
	$task->loadTaskDataForTaskId($loadTaskId);
}

include_once(dirname(__FILE__) . '/classes/UserList.class.php');
include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$list = new UserList($db);
$login = new LoginSession();


include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$taskList = new TaskList($db);
//$tasks = $taskList->getTasksForListId($login->getCurrentListId());
$allTasks = $taskList->getTasksForAllLists();
//print_r($allTasks);exit;



//$parentTaskId = '';
$parentTaskRevId = '';
$dep = array();
if ( $loadTaskId != '' ) {
	$arr = $taskList->getTaskDependenciesForPrecedingTaskId($loadTaskId, TaskConstants::RELATIONSHIP_TASK_DEPENDENCY);
	if ( $arr ) {
		foreach($arr as $record) {
			$dep[] = $record['NEXT_TASK_ID'];
		}
//		$parentTaskId = $arr[0]['NEXT_TASK_ID'];
	}
	$arr = $taskList->getTaskDependenciesForNextTaskId($loadTaskId, TaskConstants::RELATIONSHIP_NEW_MILESTONE_VERSION);
	if ( $arr ) {
		$parentTaskRevId = $arr[0]['NEEDS_PRECEDING_TASK_ID'];
	}
}
else if ( $forceDepId != '' ) {
	$dep[] = $forceDepId;
}

if ( $forceRevId != '' ) {
	$parentTaskRevId = $forceRevId;
	$parentRevTask = new Task($db);
	$parentRevTask->loadTaskDataForTaskId($forceRevId);
	$task->setTaskDescription($parentRevTask->getTaskDescription());
}
//print_r($dep);
//print $parentTaskRevId;exit;



$taskRevOpt = array('' => 'No revision history');
$parentTaskOpt = array('' => 'Select to add a task that depends on the completion of current task...');
$extTaskOpt = array();
foreach( $allTasks as $taskData ) {
	$listId = $taskData['LIST_ID'];
	$listName = $taskData['LIST_NAME'];
	$taskText = $taskData['TASK_DESC'];
	if ( strlen($taskText) > 100 ) {
		$taskText = substr($taskText, 0, 100) . '...';
	}
	if ( $taskData['DUE_DATE_TEXT'] != '' ) {
		$taskText .= ' (due ' . $taskData['DUE_DATE_TEXT'] . ')';
	}

	if ( $listId == $login->getCurrentListId() ) {
		// Only include tasks for task revisions if version regular expression matched
		// Or selected value in db
		if ( ereg('v[0-9\.]+$', $taskData['TASK_DESC']) 
				|| $taskData['TASK_ID'] == $parentTaskRevId ) {
			$taskRevOpt[$taskData['TASK_ID']] = $taskText;
		}

		// Only include incomplete tasks for parent tasks or currently selected parent task
		// Or selected value in db
		if ( $taskData['CURRENT_STATUS_ID'] == TaskConstants::STATUS_INCOMPLETE 
				|| in_array($taskData['TASK_ID'], $dep)) {
			$parentTaskOpt[$taskData['TASK_ID']] = $taskText;
		}
	}
	else {
		// Only include incomplete tasks for parent tasks
		if ( $taskData['CURRENT_STATUS_ID'] == TaskConstants::STATUS_INCOMPLETE ) {
			$extTaskOpt[$taskData['TASK_ID']] = $listName . ' / ' . $taskText;
		}
	}
}
$parentTaskOpt = $parentTaskOpt + $extTaskOpt;
//print_r($parentTaskOpt);exit;




include_once(dirname(__FILE__) . '/classes/TaskListSet.class.php');
$taskListSet = new TaskListSet($db);


include_once(dirname(__FILE__) . '/classes/TaskFormTemplate.class.php');
$template = new TaskFormTemplate();
print $template->html($task, $login, $taskListSet->getAllTaskListData(), $list->getAllUserData(), $taskRevOpt, $parentTaskRevId, $parentTaskOpt, $dep);

include_once('./globalEnd.inc.php');

?>
