<?php
include_once('./globalInit.inc.php');

include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$logSess = new LoginSession();
$currlistId = $logSess->getCurrentListId();
$userId = $logSess->getCurrentUserId();

include_once(dirname(__FILE__) . '/classes/UserList.class.php');
$userList = new UserList($db);
$users = $userList->getAllUserData();
$userOpt = array();
foreach($users as $user) {
	$userOpt[$user['USER_ID']] = $user['FIRST_NAME'] . ' ' . $user['LAST_NAME'];
}
//print_r($userOpt);exit;

include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$l = new TaskList($db);
$tasks = $l->getTasksForTaskTree();

list($taskDependencies, $proceedingTasks, $nextTaskRevisions) = $l->getTaskDependencies();

//print_r($proceedingTasks);
//print_r($nextTaskRevisions);
//print_r($taskDependencies);
//print_r($proceedingTasks);
//exit;

$tasksByTaskId = array();
foreach($tasks as $task) {
	$tasksByTaskId[$task['TASK_ID']] = $task;
}
//print_r($tasksByTaskId);exit;

$tree = array();

$includeCompleted = $_GET['showall'];
foreach($tasks as $task) {
	$parentId = $task['TASK_ID'];
	$listId = $task['LIST_ID'];

	$dep = @$taskDependencies[$parentId];
	// If task has NO dependencies
	if ( $listId == $currlistId ) {//&& !$dep ) {
		$bDisplayTree = false;

		if ( !$dep ) {
			$bDisplayTree = true;
		}
		else {
			foreach($dep as $depTaskId) {
				$depTask = $tasksByTaskId[$depTaskId];
				if ( $depTask['LIST_ID'] != $currlistId ) {
					$bDisplayTree = true;
				}
			}
		}

		if ( $bDisplayTree ) {
			tree($tree, $task, $tasksByTaskId, $proceedingTasks, array(), $userOpt, $currlistId, $includeCompleted);
		}
	}
}
//if ( !empty($tree['tasks']) ) {
//	$tree['taskorder'] = array_keys($tree['tasks']);
//}

function tree(array & $node, array $currTask, array $tasksByTaskId, array $proceedingTasks, array $path, array $userOpt, $currlistId, $includeCompleted ) {
	$tid = $currTask['TASK_ID'];

	$task = $currTask['TASK_DESC'];
	if ( $currlistId != $currTask['LIST_ID']) {
		$task = $currTask['LIST_NAME'] . ' / ' . $task;
	}

	if ( !$includeCompleted && $currTask['CURRENT_STATUS_ID'] == TaskConstants::STATUS_COMPLETE ) {
		return;
	}
	
	$assignedToId = (string)@$currTask['ASSIGNED_TO_USER_ID'];
	$assignedTo = '';
	if ( $assignedToId != '' ) {
		$assignedTo = $userOpt[$assignedToId];
	}

	$node['tasks'][$tid] = array(
		'id' => $tid,
		'task' => $task,
		'complete' => ( $currTask['CURRENT_STATUS_ID'] == TaskConstants::STATUS_COMPLETE) ? 'Y' : 'N',
		'due' => @$currTask['DUE_DATE_TEXT'],
		'assignedToId' => $assignedToId,
		'assignedTo' => $assignedTo,
		'tasks' => array()
	);

	$path[] = $tid;
	$children = @$proceedingTasks[$tid];
	if ( $children ) {
		foreach($children as $childId) {
			// Process only if task ID not yet processed, to avoid circular traversal
			if ( !in_array($childId, $path) ) {
				tree($node['tasks'][$tid], $tasksByTaskId[$childId], $tasksByTaskId, $proceedingTasks, $path, $userOpt, $currlistId, $includeCompleted);
			}
		}
	}
	
	// Store order of child tasks as array, since Chrome has JSON issues
	$node['taskorder'] = array_keys($node['tasks']);
}

//print_r($tree);exit;
include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('data', $tree);
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
