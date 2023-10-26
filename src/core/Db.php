<?php 

namespace App\Core;

class Db {

    private static ?self $instance = null;

    private \PDO $db;
    private function __construct() {
        
        $this->db = new \PDO("mysql:host=mvcframework-mysql-1;dbname=db_mvc","db_mvc", "db_mvc");
    
    }
    
    /**
     * Returns a PDO instance representing a connection to the database.
     *
     * @return \PDO A PDO instance representing a connection to the database.
     */
    public static function getDB(): \PDO 
    {
          if (self::$instance === null) { 
              self::$instance = new self();
          }
          return self::$instance->db;
    } 
    
    final public function __clone() { }
}