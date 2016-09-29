<?php
require_once('RegisterView.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
    private $conn;
    private $rv;

    function __construct() {
        $this->conn = pg_connect("host=ec2-54-75-228-51.eu-west-1.compute.amazonaws.com port=5432 dbname=dfamvr9489el11 user=bkmuesonvzihku password= SQ9eCnS1Y0UqO9t0qZ0clDO4nn sslmode=require");
        if(!$this->conn)
            die("Could not connect to database: ".mysqli_connect_error());
        $this->rv = new RegisterView($this->conn);
    }

    public function login($username, $password){

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

            $query = pg_query($this->conn, $sql);

            $result = pg_fetch_object($query);

            if($result)
                return true;
            else
                return false;
    }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {

        if(!isset($_SESSION['isLoggedIn']))
            $_SESSION['isLoggedIn'] = "No";

		$message = '';
        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();

        $registerUsername = $this->rv->getRegisterUserName();
        $registerPassword = $this->rv->getRegisterPassword();
        $registerRepeatPassword = $this->rv->getRegisterRepeatPassword();


        if(isset($_GET["register"]) && !isset($_SESSION['isOnRegisterPage'])){
            $_SESSION['isOnRegisterPage'] = "Yes";
            $response = $this->rv->generateRegisterNewUserHTML($message);
            return $response;
        }
        if(!isset($_GET["register"]))
            unset($_SESSION['isOnRegisterPage']);
        if(isset($_SESSION['isOnRegisterPage'])){
                if (is_string($registerUsername) && strlen($registerUsername) < 3) {
                    $message = "Username has too few characters, at least 3 characters.<br>";
                }
                if (is_string($registerPassword) && strlen($registerPassword) < 6) {
                    $message .= "Password has too few characters, at least 6 characters.<br>";
                }
                $response = $this->rv->generateRegisterNewUserHTML($message);
                return $response;
        }
        if(!isset($_SESSION['isOnRegisterPage'])) {
            if ($username === "") {
                $message = "Username is missing";
            }
            if ($username !== "" && $password === "") {
                $message = "Password is missing";
            }
            if ($username !== "" && $password !== "" && strlen($username) > 0 && strlen($password) > 0) {
                if ($_SESSION['isLoggedIn'] === "Yes") {
                    $message = "";
                } elseif ($this->login($username, $password)) {
                    $_SESSION['isLoggedIn'] = "Yes";
                    $message = "Welcome";
                } else {
                    $message = "Wrong name or password";
                }
            }

            if ($_SESSION['isLoggedIn'] === "No") {
                $response = $this->generateLoginFormHTML($message);
                $message = "";
                return $response;
            }
            if ($this->isLoggedOut()) {
                $_SESSION['isLoggedIn'] = "No";
                $message = "Bye bye!";
                $response = $this->generateLoginFormHTML($message);
                $message = "";
                return $response;
            }
            if ($_SESSION['isLoggedIn'] === "Yes") {
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
	* Generate HTML code on the output buffer for the logout button
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