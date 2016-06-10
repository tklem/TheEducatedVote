<?php
class Database
{
    private $servername = "localhost"; //domain
    private $username = "root"; //username for database
    private $password = "Arken1854Chick705"; //password for database
    private $database = "educatedVotePatrons"; //database name

    private static $m_pInstance; //instance of this class to be returned
    private $connection; //connection to the database established in the construct

    private function __construct() //establish connection, limits object instantiation to within the class
    {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->database) or
        die("Unable to connect to server and/or database: " . $this->connection->connect_error);

        $this->connection->set_charset("utf8") or
        die("Unable to set charset to utf8: " . $this->connection->connect_error);
    }

    public static function getInstance() // Getter method for creating/returning the single instance of this class
    {
        if (!self::$m_pInstance)
        {
            self::$m_pInstance = new Database();
        }
        return self::$m_pInstance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone(){}

    public function __destruct() //closes the connection at the end of script. Redundant, but good practice.
    {
        $this->connection->close();
    }
}
?>
