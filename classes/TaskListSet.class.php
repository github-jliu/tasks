<?php
include_once(dirname(__FILE__) . '/DataObject.class.php');

class TaskListSet extends DataObject {
	public function getAllTaskListData() {
		return $this->getDatabaseConn()->query('select * from task_list');
	}
}
?>
