<?php
    require_once 'classes/formMaster.class.php';
    if(isset($_GET['invoice'])) {                       // Edit existing invoice
        $id = $db->makeSafe($_GET['invoice']);
        $pageTitle = "Edit ";
        $hidden = "<input type='hidden' name='id' value='".$id."'>";
        require_once 'classes/invoice.class.php';       // Needed to look up the details of the invoice to edit
        $form = new formMaster($db,$id);    }
    else {
        $pageTitle = "Create New "; $hidden = "";
        $form = new formMaster($db);        }
?>

<div class="narrow box">
    <h3><?= $pageTitle; ?> CryptoInvoice</h3>    
    <form method="post" action="db/createInvoice.db.php" enctype="multipart/form-data">
        <?php echo $hidden; ?>
        <div>
            Send this invoice to:
            <?php echo $form->input("payer_email", "Email address",true); ?>
        </div>
        <div>
            The title of this invoice is:</td>
            <?php echo $form->input("title", "Title",true); ?>
        </div>
        <div>
            Amount due:
            <?php echo $form->currencySelect("fiat_id",0);          // 0 indicated not crypto
                  echo $form->input("amount", "0.00",true);     ?>
        </div>
        <div>
            I want to be paid in:</td>
            <?php echo $form->currencySelect("crypto_id",1); ?>
        </div>
        <div>Exchange rate based on:<br/>
        <?php echo $form->fxOptionSelect("fx_option",1); ?>
        </div>
        <div>
            Description <i>(optional)</i>:
            <?php echo $form->textarea("description", "Description"); ?>
        </div>    
        <div>
            Reference Number <i>(optional)</i>:
            <?php echo $form->input("users_ref", "Reference"); ?>
        </div>
        <div>
            Attach a PDF document <i>(optional)</i>:<br/>
            <?php echo $form->docs(); ?>
        </div>
            
        <br/>
        <input type="submit" value="Draft Invoice" />
    </form>
</div>