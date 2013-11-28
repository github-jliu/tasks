<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/TaskList.class.php');
$list = new TaskList($db);
$list->setListId($_POST['id']);
$list->setListName($_POST['n']);

$returnVals = array();
$list->save($returnVals);

include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('msg', 'List saved successfully');
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
