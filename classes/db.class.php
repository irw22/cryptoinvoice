<?php
// Primary datbaase connection class - passed as an object to all classes requiring database connectivity.
class Database {
    private $host;
    private $user;
    private $password;
    private $db;
    private $mysqli;

    function __construct($host,$user,$pass,$data) {
        $this->host     = $host;
        $this->user     = $user;
        $this->pass     = $pass;
        $this->data     = $data;
        $this->mysqli   = new mysqli($this->host, $this->user, $this->pass, $this->data);    }

    // We can change the following functions if we were ever to change db connection in future
    public function makeSafe($str) { return $this->mysqli->real_escape_string($str); }
    public function insertID()     { return $this->mysqli->insert_id;}
    public function error()        { return $this->mysqli->error;}
    public function query($query)  { $res = $this->mysqli->query($query);
        if(!$res) echo $this->error();
        else      return $res;       }
    
    // We include a selection of common functions for quick use (by other classes) (insert, update, select row by ID)
    // Note that the following require tables to be set up with a primary ID field called `id`
    public function select($table,$fields="*",$where="1=1") {
        $res = $this->query("SELECT $fields FROM $table WHERE $where ");
        $data = array();
        while ($r=mysqli_fetch_assoc($res)) { array_push($data,$r); }
        return $data; }
    public function row_exists($table,$data) {
        $values = "";
        foreach ($data as $key=>$value) { $values .= "`".$key."`='".$value."' AND "; }
        $values = substr($values,0,-4);   // Remove trailing comma
        $res = $this->query("SELECT * FROM $table WHERE $values ");
        if ($res->num_rows == 0) return false;
        elseif ($res->num_rows > 1) return true;
        else {
            return $res->fetch_object()->id;}                  }
    public function insert($table,$data) {
        $res = $this->query("INSERT INTO ".$table." (".implode(",", array_keys($data)).")
                             VALUES ( '".implode("','", $data)."' ) ");
        if (!$res)  return false;
        else        return $this->insertID(); }
    public function update($table, $id, $data) {
        $set = "";
        foreach ($data as $key=>$value) { $set .= $key."='".$value."',"; }
        $set = substr($set,0,-1);   // Remove trailing comma
        $res = $this->query("UPDATE ".$table."
                             SET ".$set."
                             WHERE `id` = ".$id);
        return $res; }

}?>
