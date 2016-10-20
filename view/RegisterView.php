<?php

/** Class view for registration
 * Class RegisterView
 */

class RegisterView
{
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';
    private static $register = 'RegisterView::Register';
    private $conn;

    /**
     * RegisterView constructor.
     * @param $conn, connection to the database
     */

    function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateRegisterNewUserHTML($message){

        // If valid username has been entered but password is missing then fill in the username again automatically:
        if(isset($_POST[self::$name]) && strlen($_POST[self::$name]) >= 3 && $_POST[self::$password] === "")
            $username = $_POST[self::$name];
        // If short username has been entered but passwords are valid then fill in the username again automatically:
        elseif(isset($_POST[self::$password]) && $_POST[self::$password] === $_POST[self::$passwordRepeat] &&
            strlen($_POST[self::$password]) >= 6 &&
            strlen($_POST[self::$name]) <=3 && strlen($_POST[self::$name]) > 0){
            $username = $_POST[self::$name];
        }
        // If valid username has been entered but passwords are invalid then fill in the username again automatically:
        elseif(isset($_POST[self::$password]) && $_POST[self::$password] === $_POST[self::$passwordRepeat] &&
            strlen($_POST[self::$password]) < 6 && strlen($_POST[self::$password]) > 0 &&
            strlen($_POST[self::$name]) >= 3){
            $username = $_POST[self::$name];
        }
        // If valid username has been entered but passwords are unequal then fill in the username again automatically:
        elseif(isset($_POST[self::$name]) && strlen($_POST[self::$name]) >= 3 &&
            isset($_POST[self::$password]) && strlen($_POST[self::$password]) >= 6 &&
            isset($_POST[self::$passwordRepeat]) && strlen($_POST[self::$passwordRepeat]) >= 6 &&
            $_POST[self::$password] !== $_POST[self::$passwordRepeat]){
            $username = $_POST[self::$name];
        }
        // If username already exists but passwords are valid, then fill in the username again automatically:
        elseif(isset($_POST[self::$password]) && $_POST[self::$password] === $_POST[self::$passwordRepeat] &&
            strlen($_POST[self::$password]) >= 6 &&
            $this->conn->userExists($_POST[self::$name])) {
            $username = $_POST[self::$name];
        }
        else
            $username = "";

        return '
			<form method="post" > 
				<fieldset>
					<legend>Register - enter wanted username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Enter username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'.$username.'" />
                    <br>
					<label for="' . self::$password . '">Enter password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br>
					<label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />
					<br>
					<input type="submit" name="' . self::$register . '" value="Register" />
				</fieldset>
			</form>
		';
    }

    //CREATE GET-FUNCTIONS TO FETCH REGISTER VARIABLES
    public function getRegisterUserName() {
        //RETURN REQUEST VARIABLE: username
        if(isset($_POST[self::$name]))
            return $_POST[self::$name];
    }
    public function getRegisterPassword() {
        //RETURN REQUEST VARIABLE: password
        if(isset($_POST[self::$password]))
            return $_POST[self::$password];
    }
    public function getRegisterRepeatPassword() {
        //RETURN REQUEST VARIABLE: passwordRepeat
        if(isset($_POST[self::$passwordRepeat]))
            return $_POST[self::$passwordRepeat];
    }
}