<?php
class TaskListFormTemplate {
	public function html(ITaskList $list) {
		$html = '
<div id="l" class="l">
	<input type="hidden" name="id" id="id" value="' . $list->getListId() . '"/>
	<label>List name: <input type="text" name="n" id="n" value="' . $list->getListName() . '"/></label>
	<button type="button" onclick="main.l.save();">Save</button>
	<button type="button" onclick="main.cancel();">Cancel</button>
</div>';

		return $html;
	}
}
?>
