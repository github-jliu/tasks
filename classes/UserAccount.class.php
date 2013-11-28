<?php
include_once(dirname(__FILE__) . '/IUserAccount.php');
include_once(dirname(__FILE__) . '/DataObject.class.php');

class UserAccount extends DataObject implements IUserAccount {
	private $userId, $email, $firstName, $lastName;

	public function getUserId() {
		return $this->userId;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function setUserId($value) {
		$this->userId = (int)$value;
	}
	public function setEmail($value) {
		$this->email = (string)$value;
	}
	public function setFirstName($value) {
		$this->firstName = (string)$value;
	}
	public function setLastName($value) {
		$this->lastName = (string)$value;
	}

	public function generateNewUserId() {
		$arr = $this->getDatabaseConn()->query('select user_account_seq.nextval as user_id from dual');
		return $arr[0]['USER_ID'];
	}
	public function loadUserDataForUserId($userId) {

	}
	public function save(array & $return) {
		$success = false;

		$userId = (int)$this->getUserId();
		$b = array(
			'user_id' => $userId,
			'email' => $this->getEmail(),
			'first_name' => $this->getFirstName(),
			'last_name' => $this->getLastName());

		if ( !$this->objectExists($userId)) {
			$s = 'insert into
				user_account(
				user_id,
				email,
				first_name,
				last_name)
				values(
				:user_id,
				:email,
				:first_name,
				:last_name)';
		}
		else {
			$s = 'update user_account
				set
				email = :email,
				first_name = :first_name,
				last_name = :last_name
				where user_id = :user_id';
		}
		return $this->executeSave($this->getDatabaseConn(), $s, $b, $return);
	}

	protected function queryObjectDataFromDb(IDatabaseConnection $db, $objectId) {
		$objectId = (int)$objectId;
		return $db->query('select * from user_account where user_id = :user_id', array('user_id' => $objectId));
	}
}
?>
