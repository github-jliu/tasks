<?php
include_once(dirname(__FILE__) . '/ITaskList.php');
include_once(dirname(__FILE__) . '/DataObject.class.php');
include_once(dirname(__FILE__) . '/TaskConstants.class.php');

class TaskList extends DataObject implements ITaskList {
	private $listId, $listName;

	public function getListId() {
		return $this->listId;
	}
	public function getListName() {
		return $this->listName;
	}
	public function setListId($value) {
		$this->listId = (int)$value;
	}
	public function setListName($value) {
		$this->listName = (string)$value;
	}


	public function generateNewTaskListId() {
		$arr = $this->getDatabaseConn()->query('select task_list_seq.nextval as list_id from dual');
		return $arr[0]['LIST_ID'];
	}
	protected function loadTaskListDataForListId($listId) {

	}

	public function getTaskDependencies() {
		$arr = $this->getDatabaseConn()->query(
				'select td.*
					from
					task_dependency td,
					task tn,
					task tp
					where
					tn.task_id = td.next_task_id
					and
					tp.task_id = td.needs_preceding_task_id
					order by
					td.relationship_type_id,
					--td.next_task_id,
					tp.due_date,
					tp.task_desc
					--td.needs_preceding_task_id
					');
//		print_r($arr);exit;

		$taskDependencies = array();
		$proceedingTasks = array();
		$nextTaskRevisions = array();
		//$taskDependencies = array();
		//$proceedingTasks = array();
		foreach($arr as $dependency) {
			$childId = $dependency['NEXT_TASK_ID'];
			$parentId = $dependency['NEEDS_PRECEDING_TASK_ID'];
			$type = $dependency['RELATIONSHIP_TYPE_ID'];

			switch( $type ) {
				case TaskConstants::RELATIONSHIP_TASK_DEPENDENCY:
					if ( !isset($taskDependencies[$parentId])) {
						$taskDependencies[$parentId] = array();
					}
					$taskDependencies[$parentId][] = $childId;

					if ( !isset($proceedingTasks[$childId])) {
						$proceedingTasks[$childId] = array();
					}
					$proceedingTasks[$childId][] = $parentId;
					break;
				case TaskConstants::RELATIONSHIP_NEW_MILESTONE_VERSION:
					if ( !isset($nextTaskRevisions[$parentId])) {
						$nextTaskRevisions[$parentId] = array();
					}
					$nextTaskRevisions[$parentId][] = $childId;
					break;
			}
		}

		return array(
			$taskDependencies, $proceedingTasks, $nextTaskRevisions
		);
	}
	public function getTaskDependenciesForNextTaskId($nextTaskId, $relationshipTypeId) {
		$arr = $this->getDatabaseConn()->query(
				'select *
					from
					task_dependency td
					where
					td.next_task_id = :next_task_id
					and
					td.relationship_type_id = :relationship_type_id',
				array(
					'next_task_id' => $nextTaskId,
					'relationship_type_id' => $relationshipTypeId
				));
		return $arr;
	}
	public function getTaskDependenciesForPrecedingTaskId($precedingTaskId, $relationshipTypeId) {
		$arr = $this->getDatabaseConn()->query(
				'select *
					from
					task_dependency td
					where
					td.needs_preceding_task_id = :needs_preceding_task_id
					and
					td.relationship_type_id = :relationship_type_id',
				array(
					'needs_preceding_task_id' => $precedingTaskId,
					'relationship_type_id' => $relationshipTypeId
				));
		return $arr;
	}
	public function getTasksForTaskTree() {
		$arr = $this->getDatabaseConn()->query('
			select t.*, to_char(due_date, \'DD-Mon-YYYY\') as due_date_text,
			tl.list_name
			from
			task t,
			task_list tl
			where
			tl.list_id = t.list_id
			order by tl.list_name, t.due_date asc, t.task_desc asc');
		return $arr;
	}

	public function getTasksForUserAssignments() {
		$arr = $this->getDatabaseConn()->query('
			select t.*, to_char(due_date, \'DD-Mon-YYYY\') as due_date_text,
			tl.list_name
			from
			task t,
			task_list tl
			where
			tl.list_id = t.list_id
			order by t.due_date asc, tl.list_name, t.task_desc asc');
		return $arr;
	}

	public function getTasksForAllLists() {
		$arr = $this->getDatabaseConn()->query('
			select t.*, to_char(due_date, \'DD-Mon-YYYY\') as due_date_text,
			tl.list_name
			from
			task t,
			task_list tl
			where
			tl.list_id = t.list_id
			order by tl.list_name, t.task_desc asc');
		return $arr;
	}


	public function save(array & $return) {
		$success = false;

		$listId = (int)$this->getListId();
		$b = array(
			'list_id' => $listId,
			'list_name' => $this->getListName());

		if ( !$this->objectExists($listId)) {
			$s = 'insert into task_list(
				list_id,
				list_name)
				values(
				:list_id,
				:list_name)';
		}
		else {
			$s = 'update task_list
				set
				list_name = :list_name
				where list_id = :list_id';
		}
		return $this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}

	protected function queryObjectDataFromDb(IDatabaseConnection $db, $objectId) {
		$objectId = (int)$objectId;
		return $db->query('select * from task_list where list_id = :list_id', array('list_id' => $objectId));
	}
}
?>
