<?php
interface ITask {
	public function getTaskId();
	public function getTaskDescription();
	public function getAssignedToUserId();
	public function getCreatorUserId();
	public function getCurrentStatusId();
	public function getParentListId();
	public function getDueDate();
	public function getComments();
	public function setTaskId($value);
	public function setTaskDescription($value);
	public function setAssignedToUserId($value);
	public function setCreatorUserId($value);
	public function setCurrentStatusId($value);
	public function setParentListId($value);
	public function setDueDate($value);
	public function setComments($value);

	public function generateNewTaskId();
	public function loadTaskDataForTaskId($taskId);
	public function save(array & $return);
	public function delete(array & $return);

//	public function getTasksThatDependOnTaskId($taskId);
//	public function getTasksThatTaskIdDependsOn($taskId);
}
?>
