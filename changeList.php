<?php
include_once('./globalInit.inc.php');

$listId = (int)$_GET['listid'];

include_once(dirname(__FILE__) . '/classes/LoginSession.class.php');
$logSess = new LoginSession();
$logSess->setCurrentListId($listId);

include_once('./globalEnd.inc.php');

header('Location: index.php');
?>
