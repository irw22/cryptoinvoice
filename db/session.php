<?php
    set_include_path('C:/wamp64/www/cryptoinvoice/');
    session_start();
    if (!isset($_SESSION['user'])) { $_SESSION['user'] = 0; }
    require_once ('/classes/db.class.php');
    $db  = new Database('localhost','root','','ci');  ?>