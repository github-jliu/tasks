<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$list = new TaskList($db);

$list->setListId($list->generateNewTaskListId());


include_once(dirname(__FILE__) . '/classes/TaskListFormTemplate.class.php');
$template = new TaskListFormTemplate();
print $template->html($list);

include_once('./globalEnd.inc.php');

?>
