<?php

class Connection {
    private  $conn;
    private static $servername = "mysql-chatr410.alwaysdata.net" ;
    private static $username = "chatr410"; 
    private static $password = "\$iutinfo";
    private static $dbname = "chatr410_bd";

    public function __construct() {
        
        try {
            $dsn = "mysql:host=" . self::$servername . ";dbname=" . self::$dbname . ";charset=utf8";
            $this->conn = new PDO($dsn, self::$username, self::$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection(){
        $this->conn = null;
    }

}