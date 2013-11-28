<?php
include_once('./globalInit.inc.php');


include_once(dirname(__FILE__) . '/classes/UserAccount.class.php');
$user= new UserAccount($db);

$user->setUserId($user->generateNewUserId());


include_once(dirname(__FILE__) . '/classes/UserFormTemplate.class.php');
$template = new UserFormTemplate();
print $template->html($user);

include_once('./globalEnd.inc.php');

?>
