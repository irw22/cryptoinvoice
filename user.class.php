<?php
// Create/edit user profiles, login etc.
class user {
    function __construct(Database $db) {
        $this->db = $db;     }
        
    // Script will attempt to login user; if email doesn't exist, will register user
    public function login($email,$password) {
        $loginCheck = $this->db->row_exists("users",[ "email" => $email , "pwrd" => md5($password) ]);
        if ($loginCheck) {                      // Matched = login
            $_SESSION['user'] = $loginCheck;
            return true; } 
        else {
            $existsCheck = $this->db->row_exists("users",[ "email" => $email ]);
            if ($existsCheck) { // TRUE => Already exists
                $_SESSION['message'] = "<div class='box'>Your password was not recognised - please try again.</div><br/>";
                return false; }
            else {          // New email -> create new account
                $id = $this->db->insert("users", [ "email" => $email, "pwrd" => md5($password) ] );
                $_SESSION['user'] = $id; // Auto login
                return true; }  
        }
    }
}

?>