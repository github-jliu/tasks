<?php
interface IUserAccount {
	public function getUserId();
	public function getEmail();
	public function getFirstName();
	public function getLastName();
	public function setUserId($value);
	public function setEmail($value);
	public function setFirstName($value);
	public function setLastName($value);

	public function generateNewUserId();
	public function loadUserDataForUserId($userId);
	public function save(array & $return);
}
?>
