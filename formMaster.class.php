<?php

class formMaster {
    function __construct(Database $db, $id=0) {
        $this->db   = $db;
        if($id==0) {$this->data = array();}     // A new form, not editing old data
        else {
            $find = new invoiceList($db);
            $find->byID($id);
            if ($find->count()==0) $this->data = array();  // Couldn't find it; start again
            else {$this->data = $find->getList()[0];}
        }
    }
    
    public function input($db_field,$placeholder,$required=false) {
        if (array_key_exists($db_field,$this->data)) $value = "value='".$this->data[$db_field]."'";
        else $value = "";
        if ($required) $req = " required"; else $req = "";
        return "<input type='text' name='".$db_field."' placeholder='".$placeholder."' ".$value.$req."> ";
    }
    
    public function textarea($db_field,$placeholder) {
        if (array_key_exists($db_field,$this->data)) $value = $this->data[$db_field];
        else $value = "";
        return "<textarea name='".$db_field."' placeholder='".$placeholder."'>".$value."</textarea> ";
    }
    
    private function select($db_field,$assoc) {
        if(array_key_exists($db_field,$this->data)) {$opt = $this->data[$db_field];}
        else $opt = 0;
        $sel = "<select name='".$db_field."'>";
        foreach($assoc as $r) {
            $sel .= "<option value='".$r['id']."'";
            if ($r['id']==$opt) $sel .= " selected='selected'";
            $sel .= ">".$r['display']."</option>";        }
        $sel .= "</select>";
        return $sel;
    }
    
    public function currencySelect($db_field,$crypto) {
        $res = $this->db->query("SELECT `id`, CONCAT(`name`,' (',`symbol`,')') AS display
                                 FROM currencies
                                 WHERE `crypto` = ".$crypto);
        $assoc = array();
        while($r=$res->fetch_assoc()) {array_push($assoc,$r);}
        return $this->select($db_field,$assoc); }

    public function fxOptionSelect($db_field) {
        $res = $this->db->query("SELECT `id`,`display` FROM fx_options");
        $assoc = array();
        while($r=$res->fetch_assoc()) {array_push($assoc,$r);}
        return $this->select($db_field,$assoc); }
    
    public function docs() {
        if (!array_key_exists('docs',$this->data) or count($this->data['docs'])==0) {   // No doc yet uploaded
            return '<input type="file" name="doc">';    }
        else {
            $a = "";
            foreach ($this->data['docs'] as $d) {
                $a .= "<a target='_blank' href='uploads/".$d."'>Document</a><br/>";  }
            return $a;
        }
    }
    
}?>