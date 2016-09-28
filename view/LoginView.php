<?php

session_start();

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

    function __construct() {
        $this->conn = pg_connect("host=ec2-54-75-228-51.eu-west-1.compute.amazonaws.com port=5432 dbname=dfamvr9489el11 user=bkmuesonvzihku password= SQ9eCnS1Y0UqO9t0qZ0clDO4nn sslmode=require");
        if(!$this->conn)
            die("Could not connect to database: ".mysqli_connect_error());
    }

    private function register() {

        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();
        if( isset($username) && isset($password)) {

            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            $query = pg_query($this->conn, $sql);
        }
    }

    public function login(){

        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();

        if(isset($username) && isset($password)) {

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

            $query = pg_query($this->conn, $sql);

            $result = pg_fetch_object($query);

            if($result)
                return true;
            else
                return false;
        }
    }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {

		$message = '';
        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();

        if($username === ""){
            $message = "Username is missing";
        }
        if($username !== "" && $password === ""){
            $message = "Password is missing";
        }
        if($username !== "" && $password !== "" && strlen($username) > 0 && strlen($password) > 0) {

            if ($_SESSION['isLoggedIn']) {
                $message = "Welcome";
                $_SESSION['isLoggedIn'] = true;
                $response = $this->generateLogoutButtonHTML($message);
                return $response;
            }
            else {
                $message = "Wrong name or password";
            }
        }
        elseif($this->isLoggedOut()) {
            $message = "Bye bye!";
            $_SESSION['isLoggedIn'] = false;
            header("Refresh:1");

        }
        elseif($_SESSION['isLoggedIn']){
            $response = $this->generateLogoutButtonHTML($message);
            return $response;
        }
		$response = $this->generateLoginFormHTML($message);
        $_SESSION['isLoggedIn'] = false;

		return $response;
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
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
        if(isset($_POST[self::$name]))
            return $_POST[self::$name];
	}
    private function getRequestPassword() {
        //RETURN REQUEST VARIABLE: PASSWORD
        if(isset($_POST[self::$password]))
            return $_POST[self::$password];
    }
    private function isLoggedOut() {
        //RETURN REQUEST BOOL:
        if(isset($_POST[self::$logout]))
            return true;
        else
            return false;
    }
	
}