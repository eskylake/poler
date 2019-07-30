<?php
namespace App\core;

use PDO;

/**
 * The base database class that creates a connection to the database using PDO driver.
 * It prevents database duplication.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */

class DB
{
    /**
     * @var PDO $connection Database connection.
     */
    private $connection;

    /**
     * @static $_instance Class instance.
     */
    private static $_instance;

    /**
     * @var string $dbhost Database host address.
     */
    private $dbhost;

    /**
     * @var string $dbuser Database user.
     */
    private $dbuser;

    /**
     * @var string $dbpass Database password.
     */
    private $dbpass;

    /**
     * @var string $dbname Database name.
     */
    private $dbname;

    /**
     * @var array $attrs PDO default attributes to handle error excptions and prevent SQL Injection.
     */
    private $attrs = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return as array
        PDO::ATTR_EMULATE_PREPARES => false, // Prevent sql injection while preparing data
    ];

    /**
     * Get an instance of DB class.
     * 
     * @return DB $_instance instance of DB class.
     */
    public static function getInstance(): DB
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Connect to database using PDO object.
     * 
     * @return void
     * 
     * @throws Exception if any PDOException is catched while connecting to the database.
     */
    private function __construct()
    {
        try {
            $this->setConnectionInfo();
            $dsn = "mysql:host={$this->dbhost};dbname={$this->dbname}";
            $this->connection = new PDO($dsn, $this->dbuser, $this->dbpass, $this->attrs);
        } catch(PDOException $e) {
            throw new \Exception("Failed to connect to DB: " . $e->getMessage());
        }
    }

    /**
     * Fill all database properties defined in the class by getting their values from env.
     * 
     * @return void
     */
    private function setConnectionInfo()
    {
        $this->dbhost = env('DB_HOST');
        $this->dbname = env('DB_NAME');
        $this->dbuser = env('DB_USERNAME');
        $this->dbpass = env('DB_PASSWORD');
    }

    /** 
     * __Clone is empty to prevent duplication of connection.
     * 
     * @return void
     */
    private function __clone(){}

    /**  
     * Get the connection.
     * 
     * @return PDO $this->connection Connection instance of database.
     */
    public function getConnection()
    {
        return $this->connection;
    }
}