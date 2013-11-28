<?php
class FormTemplate {
	protected function htmlForSelect($id, array $options, $selectedVal) {
		$html = "<select id=\"$id\" name=\"$id\">";
		foreach($options as $value => $text) {
			$selected = '';
			if ( (string)$value == (string)$selectedVal ) {
				$selected = ' selected="selected"';
			}
			$value = htmlentities($value);
			$html .= "<option value=\"$value\"$selected>$text</option>";
		}
		$html .= "</select>";

		return $html;
	}
	protected function htmlForCheckboxes($id, array $options, $selectedVals) {
		$html = '';
		foreach($options as $value => $text) {
			$selected = '';
			if ( in_array((string)$value, $selectedVals ) ) {
				$selected = ' checked="checked"';
			}
			$html .= "<label><input type=\"checkbox\" name=\"dep$value\" id=\"dep$value\" value=\"$value\"$selected></input> $text</label>";
		}

		return $html;
	}
}

?>
