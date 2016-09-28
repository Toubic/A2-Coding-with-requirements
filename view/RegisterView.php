<?php

class RegisterView
{
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $message = 'RegisterView::Message';
    private static $submit = 'RegisterView::Submit';
    private $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateRegisterNewUserHTML($message){

        return '
			<form method="post" > 
				<fieldset>
					<legend>Register - enter wanted username and password</legend>
					<p id="' . self::$message . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Enter username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" />
                    <br>
					<label for="' . self::$password . '">Enter password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br>
					<label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />
					<br>
					<input type="submit" name="' . self::$submit . '" value="submit" />
				</fieldset>
			</form>
		';
    }
}