<div class="box c">
    <h3>Invoices Dashboard</h3>
<?php require_once 'classes/invoice.class.php';
      $list = new invoiceList($db);
      $list->byUser();
      if ($list->count() == 0) echo "You haven't created any invoices yet.";
      else { echo "You have created <b>".$list->count()."</b> invoices:<br/><br/>"; ?>
            
        <table>
            <thead>
                <th>Title</th>
                <th>Status</th>
                <th>Sent to</th>
                <th>Amount</th>
                <th></th>
            </thead>
            <tbody>
            <?php foreach ($list->getList() as $inv) { ?>
             <tr><td align='left'><?= $inv['title']; ?></td>
                 <td><?= $inv['status']; ?></td>
                 <td><?= $inv['payer_email']; ?></td>
                 <td align='right'><?= $inv['fiatSym']." ".number_format($inv['amount'],2); ?></td>
                 <td>
                    <a href='?view=confirm&invoice=<?= $inv['id']; ?>'><button>View</button></a>
                 </td></tr>
            <?php } ?>
            </tbody>
        </table>
            
            
        <?php } ?>
</div>