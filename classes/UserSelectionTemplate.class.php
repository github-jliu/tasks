<?php
include_once(dirname(__FILE__) . '/FormTemplate.class.php');

class UserSelectionTemplate extends FormTemplate {
	public function html(array $userOpt, $currUserId) {
		$currUserId = (string)$currUserId;
		$currUserName = (string)@$userOpt[$currUserId];

		if ( $currUserName != '' ) {
			unset($userOpt[$currUserId]);

			$html = "<h2>Tasks for $currUserName";
			$newListOpt = array('-1' => 'Change user...');
			foreach($userOpt as $value => $text) {
				$newListOpt[$value] = $text;
			}

			$html .= ' ' . $this->htmlForSelect('changeuser', $newListOpt, '');
			$html .= "</h2>";
			return $html;
		}
	}
}
?>
