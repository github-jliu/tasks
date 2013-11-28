<?php
include_once(dirname(__FILE__) . '/FormTemplate.class.php');

class HeaderTemplate extends FormTemplate {
	public function html(array $listOpt, $currListId) {
		$currListId = (string)$currListId;

		if ( $currListId != '' ) {
			$currListName = (string)@$listOpt[$currListId];

			if ( $currListName != '' ) {
				unset($listOpt[$currListId]);

				$html = "<h2>$currListName";
				$newListOpt = array('-1' => 'Change list...');
				foreach($listOpt as $value => $text) {
					$newListOpt[$value] = $text;
				}
				$newListOpt['-2'] = '';
				$newListOpt['-3'] = 'Add new list...';

				$html .= ' ' . $this->htmlForSelect('changelist', $newListOpt, '');
				$html .= "</h2>";
				return $html;
			}
		}
	}
}
?>
