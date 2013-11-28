<?php
include_once(dirname(__FILE__) . '/FormTemplate.class.php');

class NavBarTemplate extends FormTemplate {
	public function html() {
		$html = '<ul class="nav navbar">
			<li><a href="index.php">Task dependency view</a></li>
			<li><a href="tasksByUser.php">Tasks by user</a></li>
			</ul>';
//			<li><a href="dash.php">Dashboard</a></li>

		return $html;
	}
}
?>
