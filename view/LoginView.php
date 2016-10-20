<?php


/** Class LoginView that presents the login page
 * Class LoginView
 */

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
    private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	public function generateLogoutButtonHTML($message) {
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
	public function generateLoginForm($message) {

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