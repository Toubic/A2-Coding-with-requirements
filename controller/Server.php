<?php
require_once(__DIR__.'/../model/Database.php');
require_once(__DIR__.'/../view/RegisterView.php');

class Server
{
    private static $isLoggedIn = 'isLoggedIn';
    private static $inRegisterView = 'register';
    private $v;
    private $rv;
    public $conn;



    function __construct(LoginView $v) {
        $this->v = $v;
        $this->conn = new Database();
        $this->rv = new RegisterView($this->conn);
    }

    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @return  void BUT writes to standard output and cookies!
     */
    public function response() {

        if(!isset($_SESSION[self::$isLoggedIn]))
            $_SESSION[self::$isLoggedIn] = "No";

        $message = "";
        $response = "";


        //If in register view:
        if(isset($_GET[self::$inRegisterView])){
            $response = $this->registerHandling($message);

        }


        //If in login view:
        if(!isset($_GET[self::$inRegisterView])) {
            $response = $this->loginHandling($message);
        }

        return $response;
    }

    /*** Handles what happens after login attempt
     *
     */

    private function loginHandling($message){

        //Fetch login request variables:
        $username = $this->v->getRequestUserName();
        $password = $this->v->getRequestPassword();

        if ($username === "") {
            $message = "Username is missing";
        }
        if ($username !== "" && $password === "") {
            $message = "Password is missing";
        }
        if ($username !== "" && $password !== "" && strlen($username) > 0 && strlen($password) > 0) {
            if ($_SESSION[self::$isLoggedIn] === "Yes") {
                $message = "";
            } elseif ($this->conn->login($username, $password)) {
                $_SESSION[self::$isLoggedIn] = "Yes";
                $message = "Welcome";
            } else {
                $message = "Wrong name or password";
            }
        }
        //If not logged in:
        if ($_SESSION[self::$isLoggedIn] === "No") {
            $response = $this->v->generateLoginFormHTML($message);
            $message = "";
            return $response;
        }
        // If logged out:
        if ($this->isLoggedOut()) {
            $_SESSION[self::$isLoggedIn] = "No";
            $message = "Bye bye!";
            $response = $this->v->generateLoginFormHTML($message);
            $message = "";
            return $response;
        }
        //If logged in successfully:
        if ($_SESSION[self::$isLoggedIn] === "Yes") {
            $response = $this->v->generateLogoutButtonHTML($message);
            $message = "";
            return $response;
        }

    }

    /*** Handles what happens after registration attempt
     * @return string
     */

    private function registerHandling($message){

        //Fetch register reequest variables:
        $registerUsername = $this->rv->getRegisterUserName();
        $registerPassword = $this->rv->getRegisterPassword();
        $registerRepeatPassword = $this->rv->getRegisterRepeatPassword();

        if (is_string($registerUsername) && strlen($registerUsername) < 3)
            $message = "Username has too few characters, at least 3 characters.<br>";

        if (is_string($registerPassword) && strlen($registerPassword) < 6)
            $message .= "Password has too few characters, at least 6 characters.<br>";

        if($registerPassword !== $registerRepeatPassword)
            $message .= "Passwords do not match.<br>";

        if($this->conn->userExists($registerUsername))
            $message .= "User exists, pick another username.<br>";

        $response = $this->rv->generateRegisterNewUserHTML($message);
        return $response;
    }
}