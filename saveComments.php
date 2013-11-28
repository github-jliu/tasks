<?php
include_once('./globalInit.inc.php');

$id = $_POST['id'];
$comments = $_POST['comments'];

$return = array();
$db->runCommand('update task set comments = :comments where task_id = :id', array('id' => $id, 'comments' => $comments), $return );
$db->commit();
include_once(dirname(__FILE__) . '/classes/JsonResponse.class.php');
$j = new JsonResponse();
$j->setResponseProperty('msg', 'Task saved successfully');
print $j->getAsJson();

include_once('./globalEnd.inc.php');

?>
