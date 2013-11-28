<?php
class JsonResponse {
	private $arrResponseProp = array();
	public function setResponseProperty($prop, $value) {
		$this->arrResponseProp[$prop] = $value;
	}
	public function getAsJson() {
		return json_encode($this->arrResponseProp);
	}
}
?>
