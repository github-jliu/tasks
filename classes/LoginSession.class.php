<?php
include_once(dirname(__FILE__) . '/ILoginSession.php');

class LoginSession implements ILoginSession {
	private $sess = array();

	public function __construct() {
		session_start();
		$this->sess = & $_SESSION;
	}
	public function getCurrentUserId() {
		return @$this->sess['TASKS_CURRENT_USER_ID'];
	}
	public function setCurrentUserId($userid) {
		$this->sess['TASKS_CURRENT_USER_ID'] = $userid;
	}
	public function getCurrentListId() {
		return @$this->sess['TASKS_CURRENT_LIST'];
	}
	public function setCurrentListId($listId) {
		$this->sess['TASKS_CURRENT_LIST'] = $listId;
	}
}
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
