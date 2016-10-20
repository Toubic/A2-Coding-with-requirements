<?php
require_once('RegisterView.php');
require_once(__DIR__.'/../model/Database.php');


/** Class LoginView that is connectedgit  RegisterView
 * Class LoginView
 */

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
    private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
    private static $isLoggedIn = 'isLoggedIn';
    private static $inRegisterView = 'inRegisterView';
    private $rv;
    public $conn;



    function __construct() {
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

		$message = '';

        //Fetch login request variables:
        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();

        //Fetch register reequest variables:
        $registerUsername = $this->rv->getRegisterUserName();
        $registerPassword = $this->rv->getRegisterPassword();
        $registerRepeatPassword = $this->rv->getRegisterRepeatPassword();

        //If in register view:
        if(isset($_GET[self::$inRegisterView])){

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


        //If login view:
        if(!isset($_GET[self::$inRegisterView])) {
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
                $response = $this->generateLoginFormHTML($message);
                $message = "";
                return $response;
            }
            // If logged out:
            if ($this->isLoggedOut()) {
                $_SESSION[self::$isLoggedIn] = "No";
                $message = "Bye bye!";
                $response = $this->generateLoginFormHTML($message);
                $message = "";
                return $response;
            }
            //If logged in successfully:
            if ($_SESSION[self::$isLoggedIn] === "Yes") {
                $response = $this->generateLogoutButtonHTML($message);
                $message = "";
                return $response;
            }
        }

	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the login form
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {

	    // If username has been entered but password is missing then fill in the username again automatically:
        if(isset($_POST[self::$name]))
            $username = $_POST[self::$name];
        else
            $username = "";

		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'.$username.'" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
        if(isset($_POST[self::$name]))
            return $_POST[self::$name];
	}
    public function getRequestPassword() {
        //RETURN REQUEST VARIABLE: PASSWORD
        if(isset($_POST[self::$password]))
            return $_POST[self::$password];
    }
    public function isLoggedOut() {
        //RETURN REQUEST BOOL:
        return isset($_POST[self::$logout]);

    }
}