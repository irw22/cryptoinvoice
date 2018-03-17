<?php
$notFoundMessage = '<div class="narrow box c">The requested invoice was not found. <br/><br/>
                        <a href="?view=list"><button>View your invoices</button></a></div>';

if (!isset($_GET['invoice'])) { echo $notFoundMessage; }
else {
    $id = $db->makeSafe($_GET['invoice']);
    require_once 'classes/invoice.class.php';
        $list = new invoiceList($db);
        $list->byID($id);
        $getList = $list->getList();
        if (count($getList)==0) { echo $notFoundMessage;  }
        else {
            $inv = $getList[0]; ?>

            <div class="narrow box">
                    <table>
                        <tr><td colspan=3 class="title">
                                <?= $inv['title'];?>
                                <?php if ($inv['users_ref']!== "") echo "(".$inv['users_ref'].")"; ?></td></tr>
                        <tr><td class='title'>Status:</td>  <td><?= $inv['status']; ?></td></tr>
                        <tr><td class='title'>Created at:</td>  <td><?= $inv['create_time']; ?></td></tr>
                        <tr><td class='title'>Emailed to:</td>  <td><?= $inv['payer_email']; ?></td></tr>
                        <tr><td class='title'>Amount:</td>      <td><?= $inv['fiatSym']." ".$inv['amount']; ?></td></tr>
                        <tr><td class='title'>Paid in:</td>     <td><?= $inv['cryptoSym']; ?></td></tr>
                        <tr><td class='title'>Exchange rate based on:</td>  <td><?= $inv['fxOption']; ?></td></tr>
                        <tr><td class='title'>Description:</td><td><?= $inv['description']; ?></td></tr>
                        <tr><td class='title'>Attached documents:</td>
                            <td><?php foreach ($inv['docs'] as $d) {
                                            echo "<a target='_blank' href='uploads/".$d."'>Document</a><br/>";  } ?></td></tr>                        
                    </table>
                    
                    <div class="c"><a href="?view=create&invoice=<?= $id; ?>"><button>Edit</button></a>
                    &nbsp; <a href="db/sendInvoice.db.php?invoice=<?= $id; ?>"><button>Send Invoice</button></a></div>
                </div>
<?php } } ?>