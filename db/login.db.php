<?php
if (!isset($_POST['email'])) {header('Location: ../index.php');}
require_once ('session.php');
require_once ('../classes/user.class.php');

$u = new user($db);
$create = $u->login($db->makeSafe($_POST['email']), $db->makeSafe($_POST['pwrd']));

header('Location: ../'); ?>