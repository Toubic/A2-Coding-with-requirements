<?php


class Database
{
    private $conn;

    function __construct() {
        $config = !getenv('DB_CREDENTIALS') ? require_once(__DIR__.'/../config.php') : getenv('DB_CREDENTIALS');
        $this->conn = pg_connect($config);
        if(!$this->conn)
            die("Could not connect to database: ".mysqli_connect_error());
    }

    /*** Returns true if logged in successfully else returns false
     * @param $username
     * @param $password
     * @return bool
     */

    public function login($username, $password){

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

        $query = pg_query($this->conn, $sql);

        $result = pg_fetch_object($query);

        if($result)
            return true;
        else
            return false;
    }

    /** Insert a new user to the database
     * @param $username
     * @param $password
     */

    public function insertUserToDatabase($username, $password) {

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        $query = pg_query($this->conn, $sql);
    }

    /** Checks if user exists in the database
     * @param $username, username to check
     * @return bool, true if user exists
     */

    public function userExists($username){

        $sql = "SELECT * FROM users WHERE username='$username'";

        $query = pg_query($this->conn, $sql);

        $result = pg_fetch_object($query);

        if($result)
            return true;
        else
            return false;
    }
}