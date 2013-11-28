<?php
interface ITaskList {
	public function getListId();
	public function getListName();
	public function setListId($value);
	public function setListName($value);

	public function generateNewTaskListId();
//	public function getTasksForListId($listId);
	public function save(array & $return);
}
?>
