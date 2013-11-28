<?php
include_once(dirname(__FILE__) . '/ITask.php');
include_once(dirname(__FILE__) . '/DataObject.class.php');
include_once(dirname(__FILE__) . '/TaskConstants.class.php');

class Task extends DataObject implements ITask {
	private $taskId, $taskDescription, $assignedToUserId,
			$creatorUserId, $currentStatusId, $parentListId, $dueDate, $comments;

	public function getTaskId(){
		return $this->taskId;
	}
	public function getTaskDescription(){
		return $this->taskDescription;
	}
	public function getAssignedToUserId(){
		return $this->assignedToUserId;
	}
	public function getCreatorUserId(){
		return $this->creatorUserId;
	}
	public function getCurrentStatusId() {
		return $this->currentStatusId;
	}
	public function getParentListId(){
		return $this->parentListId;
	}
	public function getDueDate() {
		return $this->dueDate;
	}
	public function getComments() {
		return $this->comments;
	}
	public function setTaskId($value){
		$this->taskId = (int)$value;
	}
	public function setTaskDescription($value){
		$this->taskDescription = (string)$value;
	}
	public function setAssignedToUserId($value){
		$this->assignedToUserId = (int)$value;
	}
	public function setCreatorUserId($value){
		$this->creatorUserId = (int)$value;
	}
	public function setCurrentStatusId($value) {
		$this->currentStatusId = (int)$value;
	}
	public function setParentListId($value){
		$this->parentListId = (int)$value;
	}
	public function setDueDate($value) {
		$this->dueDate = (string)$value;
	}
	public function setComments($value) {
		$this->comments = (string)$value;
	}

	public function generateNewTaskId() {
		$arr = $this->getDatabaseConn()->query('select task_seq.nextval as task_id from dual');
		return $arr[0]['TASK_ID'];
	}
	public function loadTaskDataForTaskId($taskId){
		$arr = $this->queryObjectDataFromDb($this->getDatabaseConn(), $taskId);
		if ( !empty($arr) ) {
			$this->setTaskId($arr[0]['TASK_ID']);
			$this->setTaskDescription($arr[0]['TASK_DESC']);
			$this->setAssignedToUserId($arr[0]['ASSIGNED_TO_USER_ID']);
			$this->setCreatorUserId($arr[0]['CREATED_BY_USER_ID']);
			$this->setCurrentStatusId($arr[0]['CURRENT_STATUS_ID']);
			$this->setParentListId($arr[0]['LIST_ID']);
			$this->setDueDate($arr[0]['DUE_DATE_TEXT']);
			$this->setComments($arr[0]['COMMENTS']);
		}
	}
	public function save(array & $return) {
		$success = false;

		$taskId = (int)$this->getTaskId();
		$b = array(
			'task_id' => $taskId,
			'task_desc' => $this->getTaskDescription(),
			'assigned_to_user_id' => $this->getAssignedToUserId(),
			'created_by_user_id' => $this->getCreatorUserId(),
			'current_status_id' => $this->getCurrentStatusId(),
			'due_date' => $this->getDueDate(),
			'comments' => $this->getComments(),
			'list_id' => $this->getParentListId());

		if ( !$this->objectExists($taskId)) {
			$s = "insert into
				task(
				task_id,
				task_desc,
				assigned_to_user_id,
				created_by_user_id,
				current_status_id,
				list_id,
				due_date,
				comments)
				values(
				:task_id,
				:task_desc,
				:assigned_to_user_id,
				:created_by_user_id,
				:current_status_id,
				:list_id,
				to_date(:due_date, 'DD-MON-YYYY'),
				:comments
				)";
//			print $s;exit;
		}
		else {
			$s = "update task
				set
				task_desc = :task_desc,
				assigned_to_user_id = :assigned_to_user_id,
				created_by_user_id = :created_by_user_id,
				current_status_id = :current_status_id,
				list_id = :list_id,
				due_date = to_date(:due_date, 'DD-MON-YYYY'),
				comments = :comments
				where task_id = :task_id";
		}
		return $this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}
	
	public function delete(array &$return) {
		$success = false;

		$taskId = (int)$this->getTaskId();
		$b = array(
			'task_id' => $taskId );
		
		$s = 'delete from task_dependency where next_task_id = :task_id or needs_preceding_task_id = :task_id';
		$success = $this->executeSave($this->getDatabaseConn(), $s, $b, $return);
		$s = 'delete from task where task_id = :task_id';
		$success = $this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}

	protected function queryObjectDataFromDb(IDatabaseConnection $db, $objectId) {
		$objectId = (int)$objectId;
		return $db->query('select task.*, to_char(due_date, \'DD-Mon-YYYY\') as due_date_text from task where task_id = :task_id', array('task_id' => $objectId));
	}

	private $depData;
	protected function getDependencyData(IDatabaseConnection $db, $objectId) {
		$objectId = (int)$objectId;

		if ( $this->depData == null ) {
			$this->depData = $db->query('select * from task_dependency where next_task_id = :task_id or needs_preceding_task_id = :task_id', array('task_id' => $objectId));
		}
		return $this->depData;
	}

	protected function deleteDependency($nextTaskId, $needsPrecedingTaskId, $relationshipType, & $return) {
		$s = 'delete from task_dependency where needs_preceding_task_id = :needs_preceding_task_id and next_task_id = :next_task_id and relationship_type_id = :relationship_type_id';
		$b = array(
			'next_task_id' => $nextTaskId,
			'needs_preceding_task_id' => $needsPrecedingTaskId,
			'relationship_type_id' => $relationshipType
		);

		$this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}

	protected function addDependency($nextTaskId, $needsPrecedingTaskId, $relationshipType, & $return) {
		$s = 'insert into task_dependency(next_task_id, needs_preceding_task_id, relationship_type_id)
			values( :next_task_id, :needs_preceding_task_id, :relationship_type_id)';
		$b = array(
				'next_task_id' => $nextTaskId,
				'needs_preceding_task_id' => $needsPrecedingTaskId,
				'relationship_type_id' => $relationshipType
			);

		$this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}

	private $parentTasks;
	public function getParentTasks($taskId) {
		$arr = $this->getDependencyData($this->getDatabaseConn(), $taskId);
		if ( $this->parentTasks == null ) {
			$this->parentTasks = array();

			foreach($arr as $i => $record) {
				if ( $record['NEEDS_PRECEDING_TASK_ID'] == $taskId && $record['RELATIONSHIP_TYPE_ID'] == TaskConstants::RELATIONSHIP_TASK_DEPENDENCY ) {
					$this->parentTasks[] = $record['NEXT_TASK_ID'];
				}
			}
		}
		return $this->parentTasks;
	}

	public function updateTaskDependencies($needsPrecedingTaskId, array $new) {
		$old = $this->getParentTasks($needsPrecedingTaskId);

		$return = array();
//PRINT_R($new);
//PRINT_R($old);exit;
		foreach($old as $nextTaskId) {
			if ( !in_array($nextTaskId, $new) ) {
				$this->deleteDependency($nextTaskId, $needsPrecedingTaskId, TaskConstants::RELATIONSHIP_TASK_DEPENDENCY, $return);
			}
		}

		foreach($new as $nextTaskId) {
			if ( $nextTaskId && !in_array($nextTaskId, $old) ) {
				$this->addDependency($nextTaskId, $needsPrecedingTaskId, TaskConstants::RELATIONSHIP_TASK_DEPENDENCY, $return);
			}
		}
		return true;
	}

	private $parentTaskRevisions;
	public function getParentTaskRevisions($childRevTaskId) {
		$arr = $this->getDependencyData($this->getDatabaseConn(), $childRevTaskId);
		if ( $this->parentTaskRevisions == null ) {
			$this->parentTaskRevisions = array();

			foreach($arr as $i => $record) {
				if ( $record['NEXT_TASK_ID'] == $childRevTaskId && $record['RELATIONSHIP_TYPE_ID'] == TaskConstants::RELATIONSHIP_NEW_MILESTONE_VERSION ) {
					$this->parentTaskRevisions[] = $record['NEEDS_PRECEDING_TASK_ID'];
				}
			}
		}
		return $this->parentTaskRevisions;
	}
	public function updateTaskParentRevisions($childRevTaskId, array $new) {
		$old = $this->getParentTaskRevisions($childRevTaskId);

		$return = array();
//PRINT_R($new);
//PRINT_R($old);exit;
		foreach($old as $needsPrecedingTaskId) {
			if ( !in_array($needsPrecedingTaskId, $new) ) {
				$this->deleteDependency($childRevTaskId, $needsPrecedingTaskId, TaskConstants::RELATIONSHIP_NEW_MILESTONE_VERSION, $return);
			}
		}

		foreach($new as $needsPrecedingTaskId) {
			if ( $needsPrecedingTaskId && !in_array($needsPrecedingTaskId, $old) ) {
				$this->addDependency($childRevTaskId, $needsPrecedingTaskId, TaskConstants::RELATIONSHIP_NEW_MILESTONE_VERSION, $return);
			}
		}
		return true;
	}



//	private $proceedingTasks;
//	public function getTasksThatDependOnTaskId($taskId){
//		if ( $this->proceedingTasks == null ) {
//			$this->proceedingTasks = array();
//			$arr = $this->getDatabaseConn()->query('select next_task_id from task_dependency where needs_preceding_task_id = :needs_preceding_task_id and relationship_type_id = :relationship_type_id',
//					array(
//						'needs_preceding_task_id' => $taskId,
//						'relationship_type_id' => TaskConstants::RELATIONSHIP_DEPENDENCY
//					));
//			foreach($arr as $i => $record) {
//				$this->proceedingTasks[] = $record['NEXT_TASK_ID'];
//			}
//		}
//		return $this->proceedingTasks;
//	}
//	public function updateProceedingTasks($taskId, array $new) {
//		$old = $this->getTasksThatDependOnTaskId($taskId);
//
//		$return = array();
//
//		foreach($old as $procId) {
//			if ( !in_array($procId, $new) ) {
//				$s = 'delete from task_dependency where
//					needs_preceding_task_id = :needs_preceding_task_id
//					and
//					next_task_id = :next_task_id
//					and
//					relationship_type_id = :relationship_type_id';
//				$b = array(
//					'next_task_id' => $procId,
//					'needs_preceding_task_id' => $taskId,
//					'relationship_type_id' => TaskConstants::RELATIONSHIP_DEPENDENCY
//				);
//
//				$this->executeSave($this->getDatabaseConn(), $s, $b, $return);
//			}
//		}
//
//		foreach($new as $procId) {
//			if ( !in_array($procId, $old) ) {
//				$s = 'insert into task_dependency(needs_preceding_task_id, next_task_id, relationship_type_id)
//					values( :needs_preceding_task_id, :next_task_id, :relationship_type_id)';
//				$b = array(
//						'next_task_id' => $procId,
//						'needs_preceding_task_id' => $taskId,
//						'relationship_type_id' => TaskConstants::RELATIONSHIP_DEPENDENCY
//					);
//
//				$this->executeSave($this->getDatabaseConn(), $s, $b, $return);
//			}
//		}
//		return true;
//	}
}
?>
