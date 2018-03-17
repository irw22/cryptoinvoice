<?php

if (!isset($_GET['invoice'])) { header('Location: ../?view=list'); }
else {
    require_once ('session.php');
    require_once ('../classes/invoice.class.php');
    
    $id = $db->makeSafe($_GET['invoice']);
    
    try {
        $sendClass = new sendInvoice($db,$id);
        $sendClass->send();
    }
    catch (Exception $e) { $_SESSION['message'] = $e->getMessage();}
    
    header('Location: ../?view=list');
}


?>