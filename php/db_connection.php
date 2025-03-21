<?php

/**
 * Class Connection
 * 
 * This class is responsible for managing the database connection.
 * It uses private properties to store the connection details and 
 * provides a mechanism to establish a connection to the database.
 */
class Connection {
    /**
     * Database connection configuration.
     *
     * @property object $conn The connection object for the database.
     * @property string $servername The hostname or IP address of the database server.
     * @property string $username The username used to connect to the database.
     * @property string $password The password associated with the database user.
     * @property string $dbname The name of the database to connect to.
     */
    private  $conn;
    private static $servername = "mysql-chatr410.alwaysdata.net" ;
    private static $username = "chatr410"; 
    private static $password = "\$iutinfo";
    private static $dbname = "chatr410_bd";

    /**
     * Constructor method for the database connection class.
     * Initializes the database connection when an instance of the class is created.
     */
    public function __construct() {
        
        try {
            $dsn = "mysql:host=" . self::$servername . ";dbname=" . self::$dbname . ";charset=utf8";
            $this->conn = new PDO($dsn, self::$username, self::$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }
    }

    /**
     * Establishes and returns a database connection.
     *
     * @return PDO|null Returns a PDO instance representing the database connection
     *                  if successful, or null if the connection fails.
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Closes the database connection.
     *
     * This method is responsible for properly closing the connection
     * to the database to free up resources and ensure no lingering
     * connections remain open.
     *
     * @return void
     */
    public function closeConnection(){
        $this->conn = null;
    }

}