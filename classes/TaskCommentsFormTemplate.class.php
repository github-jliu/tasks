<?php
include_once(dirname(__FILE__) . '/FormTemplate.class.php');

class TaskCommentsFormTemplate extends FormTemplate {
	public function html(ITask $task) {
		$html = '
<div id="t" class="t">
	<input type="hidden" name="id" id="id" value="' . $task->getTaskId() . '"/>
	<label>Notes:</label><label class="dep"><textarea name="comments" id="comments">' . $task->getComments() . '</textarea></label>';


	$html .= '<button type="button" onclick="main.c.save();">Save</button>
	<button type="button" onclick="main.cancel();">Cancel</button>
</div>';

		return $html;
	}
}
?>
