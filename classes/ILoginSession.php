<?php
interface ILoginSession {
	public function getCurrentUserId();
	public function setCurrentUserId($userid);
	public function getCurrentListId();
	public function setCurrentListId($listId);
}
?>
