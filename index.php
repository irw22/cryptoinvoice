<?php require_once 'db/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <base href="http://localhost/cryptoinvoice/index.php">
    <title>CryptoInvoice</title>
    <link rel="stylesheet" href="styles/styles.css" type="text/css" />
</head>
<body>
<div class="wrap">
    <?php if(isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); }
          if($_SESSION['user']==0) {include ('views/login.php');}
          else { ?>
            <div class="menuBar">
                <a href="?view=create">Create new invoice</a>
                <a href="?view=list">View my invoices</a>
                <a href="db/logout.db.php" class="right">Logout</a>
            </div>
          <?php if (isset($_GET['view'])) { $view = $db->makeSafe($_GET['view']); }
                else                      { $view = 'create'; }
                
                if (file_exists($viewURL = 'views/'.$view.'.php')) { include ($viewURL); }
             } ?>
</div>
</body>
</html>