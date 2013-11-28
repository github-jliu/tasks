<?php
include_once(dirname(__FILE__) . '/FormTemplate.class.php');

class TaskFormTemplate extends FormTemplate {
	public function html(ITask $task, ILoginSession $login, array $lists, array $users, array $taskRevOpt, $parentTaskRevId, array $parentTaskOpt, array $parentTasks) {
		$parentTaskRevId = (string)$parentTaskRevId;

		$listOpt = array();
		foreach( $lists as $list ) {
			$listOpt[$list['LIST_ID']] = $list['LIST_NAME'];
		}
		asort($listOpt);
	
		$userOpt = array();
		foreach( $users as $user ) {
			$userOpt[$user['USER_ID']] = $user['FIRST_NAME'] . ' ' . $user['LAST_NAME'];
		}

		$val = (string)$task->getAssignedToUserId();
		if ( $val == '' ) { $val = $login->getCurrentUserId(); }
		$listval = (string)$task->getParentListId();
		if ( $listval == '' ) { $listval = $login->getCurrentListId(); }

		$html = '
<div id="t" class="t">
	<input type="hidden" name="id" id="id" value="' . $task->getTaskId() . '"/>
	<input type="hidden" name="stat" id="stat" value="' . $task->getCurrentStatusId() . '"/>
	<label>Task: <input type="text" name="desc" id="desc" value="' . htmlentities($task->getTaskDescription()) . '"/></label>
	<label>List: ' . $this->htmlForSelect('listid', $listOpt, $listval) . '</label>
	<label>Assigned to: ' . $this->htmlForSelect('assigned', $userOpt, $val) . '</label>
	<label>Due date: <input type="text" name="due" id="due" value="' . $task->getDueDate() . '"/></label>';

	unset($taskRevOpt[$task->getTaskId()]); // Remove current task from options
	if ( !empty($taskRevOpt) ) {  // More than just default option
		$html .= '<label>This task is a newer revision of a previous task: ' . $this->htmlForSelect('rev', $taskRevOpt, $parentTaskRevId) . '</label>';
	}

	unset($parentTaskOpt[$task->getTaskId()]); // Remove current task from options
	if ( !empty($parentTaskOpt) ) { // More than just default option
		$html .= '<label>Tasks that depend on completing this task: </label>';

		// If number of selected tasks is not equal to number of tasks
		if ( count($parentTasks) + 1 != count($parentTaskOpt) ) {
			// Add empty option, so that extra select field is created
			$parentTasks[] = '';
		}
		foreach($parentTasks as $i => $selectedValue) {
			$i++;
			$html .= '<label class="dep">' . $this->htmlForSelect("dep$i", $parentTaskOpt, $selectedValue) . '</label>';
		}
	}

	$html .= '<label>Notes:</label><label class="dep"><textarea name="comments" id="comments">' . $task->getComments() . '</textarea></label>';


	$html .= '<button type="button" onclick="main.t.save();">Save</button>
	<button type="button" onclick="main.cancel();">Cancel</button>
</div>';

		return $html;
	}
}
?>
