<?php
// Create or edit an invocie
class createInvoice {
    function __construct(Database $db) {
        $this->db = $db;     }
        
    public function create($data) {
        $c = $this->db->insert("invoices",$data);
        $this->id = $c;
        return $c;  }

    public function edit($data,$id) {
        $c = $this->db->update("invoices",$id,$data);
        $this->id = $id;
        return $c;  }
    
    public function uploadedDocument($file_name) {
        $d = $this->db->insert("docs",["invoice"=>$this->id, "url"=>$file_name]);
        return $d;
    }
}

class sendInvoice {
    function __construct(Database $db,$id) {
        $this->db = $db;
        $this->id = $db->makeSafe($id);
        
        if (!$this->checkAuth()) {throw new Exception('Not found or permission denied.');}
    }
    
    private function checkAuth() {
         $res = $this->db->query("SELECT i.id
                                    FROM invoices i
                                   WHERE i.user = ".$_SESSION['user']
                                   ." AND i.id = ".$this->id);
         if ($res->num_rows==0) return false;
         else return true;
    }

    public function send() {
        
        // TO DO: Actually send the email!!
        
        // Update the database...
        $update = $this->db->update("invoices",$this->id,["status_id"=>2]);
        $_SESSION['message'] = 'Invoice sent.';
        return $update;
    }
}

// Retrieve list of invoices. Note user must have create the invoice to be able to see it.
class invoiceList {
    private $list = array();
    
    function __construct(Database $db) {
        $this->db = $db;   }
        
    private function gatherData($filter) {
        // NB: we always have the $_SESSION['user'] filter to prevent unauthorised viewing of other users' invoices
        $res = $this->db->query("SELECT i.*, f.symbol AS fiatSym, c.symbol AS cryptoSym, o.display AS fxOption, s.status
                                   FROM invoices i
                                    LEFT JOIN currencies f ON f.id = i.fiat_id
                                    LEFT JOIN currencies c ON c.id = i.crypto_id
                                    LEFT JOIN fx_options o ON o.id = i.fx_option
                                    LEFT JOIN status_ref s ON s.id = i.status_id
                                   WHERE i.user = ".$_SESSION['user']." ".$filter
                                   ." ORDER BY i.create_time DESC");
        while ($r=$res->fetch_assoc()) {
            $r['docs'] = $this->findDocs($r['id']);     // Array; find any documents
            array_push($this->list,$r);}                // Add to stack
    }

    private function findDocs($invoice_id) {
        $docs = array();         // We use an array to allow for the possibility for multiple documents
        $res = $this->db->query("SELECT `url` FROM docs WHERE invoice = ".$invoice_id);
        while ($r=$res->fetch_assoc()) {array_push($docs,$r['url']);}
        return $docs;
    }

    public function byUser() { $this->gatherData(""); }
    public function byID($id)   { $this->gatherData("AND i.id = ".$id); }

    public function getList() { return ($this->list);}
    public function count() { return count($this->list); }
    
}

class docs {

    function __construct(Database $db,$invoice_id) {
        $this->db = $db;
        $this->id = $invoice_id;}
        
    
}

?>
