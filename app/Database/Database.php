<?php

// a simple class for connecting to and querying a database

namespace Database;

class Database{
    
        private $db;
    
        public function __construct($host, $user, $pass, $db){
            $this->db = new \mysqli($host, $user, $pass, $db);
            if ($this->db->connect_errno) {
                echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
            }
        }
    
        public function query($sql){
            $result = $this->db->query($sql);
            if ($result === false) {
                echo "Query failed: (" . $this->db->errno . ") " . $this->db->error;
            }
            return $result;
        }
    
        public function __destruct(){
            $this->db->close();
        }
}