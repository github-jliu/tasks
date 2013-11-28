<?php
include_once('./globalInit.inc.php');

$currUserId = (string)@$_GET['id'];

include_once(dirname(__FILE__) . '/classes/UserList.class.php');
$userList = new UserList($db);
$users = $userList->getAllUserData();
$userOpt = array();
foreach($users as $user) {
	$userOpt[$user['USER_ID']] = $user['FIRST_NAME'] . ' ' . $user['LAST_NAME'];
}
if ( $currUserId == '' ) {
	$currUserId = key($userOpt);
}

include_once(dirname(__FILE__) . '/classes/UserSelectionTemplate.class.php');
$template = new UserSelectionTemplate();
$headerHtml = $template->html($userOpt, $currUserId);

include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$l = new TaskList($db);
$tasks = $l->getTasksForUserAssignments();

list($taskDependencies, $proceedingTasks, $nextTaskRevisions) = $l->getTaskDependencies();

$tasksByTaskId = array();
foreach($tasks as $task) {
	$tasksByTaskId[$task['TASK_ID']] = $task;
}
//print_r($tasksByTaskId);exit;

$tree = array();
$tasksAffectingUser = array();
foreach($tasks as $task) {
	$dueDate = $task['DUE_DATE_TEXT'];

	$newPath = array();

	// First level is tasks with due dates
	if ( $dueDate != '' ) {
		generateTree($tree, $task, $tasksByTaskId, $proceedingTasks, $newPath, $currUserId, $tasksAffectingUser, false);
	}
}
//print_r($tasksAffectingUser);exit;
function generateTree(array & $tree, array $currTask, array & $tasksByTaskId, array & $proceedingTasks, array $path, $currUserId, array & $tasksAffectingUser, $bIncludeAllChildren) {
	$tid = $currTask['TASK_ID'];
	$assignTo = $currTask['ASSIGNED_TO_USER_ID'];
	$statusId = $currTask['CURRENT_STATUS_ID'];
	$task = $currTask['TASK_DESC'];
	
	$newPath = $path;
	$newPath[] = $tid;

	$tree[$tid] = $currTask;
	$children = @$proceedingTasks[$tid];

	// Keep track of tasks that are assigned to user, or have processing task user assignments
	if ( ( $statusId == TaskConstants::STATUS_INCOMPLETE && $assignTo == $currUserId ) || $bIncludeAllChildren ) {
		foreach($newPath as $parentTid) {
			$tasksAffectingUser[$parentTid] = 1;
		}
		
		$bIncludeAllChildren = true;
	}
	
	if ( $children ) {
//		print_r($children);
		$tree[$tid]['ProceedingTasksByTaskId'] = array();
		foreach($children as $childId) {
			// Process only if task ID not yet processed, to avoid circular traversal
			if ( !in_array($childId, $newPath) ) {
				generateTree($tree[$tid]['ProceedingTasksByTaskId'], $tasksByTaskId[$childId], $tasksByTaskId, $proceedingTasks, $newPath, $currUserId, $tasksAffectingUser, $bIncludeAllChildren);
			}
		}
	}
		
}
//print_r($tree);exit;
//print_r($tasksAffectingUser);exit;






reset($tasks);
$firstTask = current($tasks);
$currDueDate = (string)@$firstTask['DUE_DATE_TEXT'];
//print_r($firstTask);
//print $currDueDate;exit;

$printedTasks = array();
$html = '';


foreach($tree as $task) {
	$parentId = $task['TASK_ID'];
	if ( array_key_exists($parentId, $tasksAffectingUser)) {
		printTasksByUser($htmlArr, $task, $tasksByTaskId, $proceedingTasks, array(), $printedTasks, $tasksAffectingUser, true, $currUserId);
	}
	
	

//	$dep = @$taskDependencies[$parentId];
//	// If task has NO dependencies
//	if ( !$dep ) {
//		$htmlArr = array();
//		printTasksByUser($htmlArr, $task, $tasksByTaskId, $proceedingTasks, array(), $printedTasks, $currUserId, $currDueDate, $currPath);
//
//
//	}
}

if ( isset($htmlArr) && count($htmlArr) > 1) { // If more than just heading
	$html .= '<ul id="userTasks">';
	$html .= join($htmlArr);
	$html .= '</ul>';
}

function printTasksByUser(& $htmlArr, array $currTask, array $tasksByTaskId, array $proceedingTasks, array $path, array & $printedTasks, $tasksAffectingUser, $bTraverseChildren, $currUserId) {
	$tid = $currTask['TASK_ID'];
	$task = $currTask['TASK_DESC'];
	$list = $currTask['LIST_NAME'];
	$statusId = $currTask['CURRENT_STATUS_ID'];
	$assignTo = @$currTask['ASSIGNED_TO_USER_ID'];
	$comments = (string)@$currTask['COMMENTS'];

	global $userOpt;
	$assignToName = $userOpt[$assignTo];
	

	$dueDate = (string)@$currTask['DUE_DATE_TEXT'];




	$newPath = $path;
	$newPath[] = $tid;
	$printedTasks[] = $tid;
	
	if ( array_key_exists($tid, $tasksAffectingUser) ) {
		$children = @$currTask['ProceedingTasksByTaskId'];
		if ( $comments != '' ) {
			$comments = nl2br($comments);
		}
		$comments = "<span class=\"comments\" id=\"comments-$tid\">$comments</span>";
		
		
		$assignedTo = "<span class=\"due\"> $assignToName <a href=\"#\" onclick=\"main.c.load($tid);return false;\">Comments</a></span>";
		if ( $dueDate != '') {
			$htmlArr[] = "<li><span class=\"wait box\"></span><h2>$dueDate - $list / $task$assignedTo</h2>$comments</li>";
		}
		else if($children) {
			if ( $statusId != TaskConstants::STATUS_COMPLETE ) {
				$statusClass = 'go';
				$bChildrenCompleted = true;
				foreach($children as $child) {
					if ( $child['CURRENT_STATUS_ID'] != TaskConstants::STATUS_COMPLETE ) {
						$statusClass = 'wait';
					}
				}
			
				if ( $assignTo != $currUserId ) { $statusClass = 'wait'; }
			
				$htmlArr[] = "<li><span class=\"$statusClass box\"></span>$task$assignedTo$comments</li>";
			}
		}
		else if($assignTo != $currUserId && $statusId != TaskConstants::STATUS_COMPLETE ) {
			$htmlArr[] = "<li><span class=\"stop box\"></span>$task$assignedTo$comments</li>";
		}
		else if ($statusId != TaskConstants::STATUS_COMPLETE) {
			$htmlArr[] = "<li><span class=\"go box\"></span>$task$assignedTo$comments</li>";
		}
		
		$children = @$currTask['ProceedingTasksByTaskId'];
		if ( $children && $bTraverseChildren ) {
//			$htmlArr[] = "<ul>";
			foreach($children as $childId => $child) {
				// Process only if task ID not yet processed, to avoid circular traversal
				// Process only if task ID not yet printed, to avoid circular traversal
				if ( !in_array($childId, $newPath) ) {// && !in_array($childId, $printedTasks) ) {
					$htmlArr[] = '<ul>';
					printTasksByUser($htmlArr, $child, $tasksByTaskId, $proceedingTasks, $newPath, $printedTasks, $tasksAffectingUser, (!in_array($childId, $printedTasks)), $currUserId);
					$htmlArr[] = '</ul>';
				}
			}
		}	

	}
}

//print_r($tree);exit;

include_once(dirname(__FILE__) . '/classes/NavBarTemplate.class.php');
$template = new NavBarTemplate();
$navBarHtml = $template->html('Task dependency view');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link href="interface.css" rel="stylesheet"/>
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript" src="js/tasksByUser.js"></script>
	</head>
	<body>
		<div class="main">
			<form id="f">
				<textarea id="comments"></textarea>
				<div><button type="button">Save</button> <button type="button">Cancel</button></div>
			</form>
			<?php print $navBarHtml; ?>
			<div class="clear"></div>
			<?php print $headerHtml; print $html;?>
		</div>
	</body>
</html>