<?php
if (!isset($_POST)) {header('Location: ../?view=list');}
require_once ('session.php');
require_once ('../classes/invoice.class.php');

// Add/remove fields we need/don't need
unset($_POST['submit']);

$i = new createInvoice($db);

if (isset($_POST['id'])) {
    $id=$db->makeSafe($_POST['id']);
    $create = $i->edit($_POST,$id);  }
else                     {
    $_POST['user']          = $_SESSION['user'];
    $_POST['create_time']   = date("Y-m-d H:i:s");     // Do we want a "modified" time for edit requests?
    $_POST['notify_url']    = "www";                   // What is this for?
    $_POST['lang']          = "EN";                    // What is this for?
    $_POST['status_id']        = 1;      // Draft
    $create = $i->create($_POST);
    $id = $create; }

// Upload file, if sent...
if(isset($_FILES["doc"]) and $_FILES["doc"]["error"] !== 4) {     // No need to run this part if no document was uploaded!
    $target_dir = "../uploads/";        // Directory to store uploaded files
    $file_type  = strtolower(pathinfo($_FILES["doc"]["name"],PATHINFO_EXTENSION));
    if ($file_type !== 'pdf')  {
        $_SESSION['message'] = 'File must be a PDF document';}
    else {
        $new_name = $_SESSION['user']."_".time().".".$file_type;
        $new_path = $target_dir.$new_name;

        if (move_uploaded_file($_FILES["doc"]["tmp_name"], $new_path)) {
            $save = $i->uploadedDocument($new_name); // Add record to the datbase
            if (!$save) {$_SESSION['message'] = 'There was a problem communicating with the database.'; }}
        else {
            $_SESSION['message'] = 'There was an error uploading your file (Error code: '.$_FILES["doc"]["error"].')';}
    }
}
 
header('Location: ../?view=confirm&invoice='.$id); ?>