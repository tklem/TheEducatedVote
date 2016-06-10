<?php
class DatabaseProcedural
{
    // Store the single instance of Database

    private $servername = "localhost"; //domain
    private $username = "root"; //username for database
    private $password = "Arken1854Chick705"; //password for database
    private $database = "educatedVotePatrons"; //database name

    private static $m_pInstance; //instance of this class to be returned
    private $connection; //connection to the database established in the construct

    private function __construct() //establish connection, limits object instantiation to within the class
    {
        if(!$this->connection = mysqli_connect($this->servername, $this->username, $this->password, $this->database))
        {
            die("Unable to connect to server and/or database: " . $this->connection->connect_error);
        }
        if(!$this->connection->set_charset("utf8"))
        {
            die("Unable to set charset to utf8: " . $this->connection->connect_error);
        }
    }


    public static function getInstance() // Getter method for creating/returning the single instance of this class
    {
        if (!self::$m_pInstance)
        {
            self::$m_pInstance = new DatabaseProcedural();
        }
        return self::$m_pInstance;
    }

    public function query($query) //queries redirect here before being bounced to the database
    {
        return mysqli_query($this->connection, $query);
    }

    public function prepare($preparedStatement)
    {
        return mysqli_prepare($this->connection, $preparedStatement);
    }

    public function mysqli_stmt_bind_param($preparedStatement, $bindings, $paramArray)
    {
        //call_user_func_array(array()) mysqli_stmt_bind_param($preparedStatement, $bindings, $paramArray);
    }

    public function __destruct() //closes the connection at the end of script. Redundant, but good practice. 
    {
        mysqli_close($this->connection);
    }
}
?>

