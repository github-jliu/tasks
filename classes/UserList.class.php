<?php
include_once(dirname(__FILE__) . '/DataObject.class.php');

class UserList extends DataObject {
	public function getAllUserData() {
		return $this->getDatabaseConn()->query('select * from user_account order by first_name');
	}
}
?>
