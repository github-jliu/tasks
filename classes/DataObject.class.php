<?php
class DataObject {
	private $conn;

	public function __construct(IDatabaseConnection $conn) {
		$this->conn = $conn;
	}

	/**
	 *
	 * @return IDatabaseConnection
	 */
	protected function getDatabaseConn() {
		return $this->conn;
	}


	protected function objectExists($objectId) {
		$objectDataArr = $this->getObjectDataForObjectId($objectId);
		return ($objectDataArr && !empty($objectDataArr ));
	}

	protected $arrCachedObjectData = array();
	protected function getObjectDataForObjectId($objectId) {
		$objectId = (int)$objectId;

		if (array_key_exists($objectId, $this->arrCachedObjectData) ) {
			return $this->arrCachedObjectData[$objectId];
		}
		else {
			$arr = $this->queryObjectDataFromDb($this->getDatabaseConn(), $objectId);
			if ( !empty($arr) ) {
				$this->arrCachedObjectData[$objectId] = $arr[0];
				return $arr[0];
			}
		}
	}
	
	protected function queryObjectDataFromDb(IDatabaseConnection $db, $objectId) {
		return array();
	}

	protected function executeSave(IDatabaseConnection $db, $s, array $b, array & $return) {
		$success = $this->getDatabaseConn()->runCommand($s, $b, $return);
		if ( $success ) {
			$this->getDatabaseConn()->commit();
		}
		return $success;
	}
}
?>
