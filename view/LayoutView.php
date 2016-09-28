<?php


class LayoutView {
  
  public function render(LoginView $v, DateTimeView $dtv) {


      if ($v->login($v->getRequestUserName(), $v->getRequestPassword()))
          $isLoggedIn = "Yes";
      elseif (!isset($_SESSION['isLoggedIn']) || $v->isLoggedOut())
          $isLoggedIn = "No";
      else
          $isLoggedIn = $_SESSION['isLoggedIn'];
      if($isLoggedIn === "No" && !isset($_GET["register"]))
        $aTag = '<a href="?register">Register a new user</a>';
      else if(isset($_GET["register"]))
          $aTag = '<a href="/1dv610/A2-Coding-with-requirements">Back to login</a>';
      else
          $aTag = "";
      echo '<!DOCTYPE html>
          <html>
            <head>
              <meta charset="utf-8">
              <title>Login Example</title>
            </head>
            <body>
              <h1>Assignment 2</h1>
              ' . $aTag . '
              ' . $this->renderIsLoggedIn($isLoggedIn) . '
              
              <div class="container">
                  ' . $v->response() . '
                  
                  ' . $dtv->show() . '
              </div>
             </body>
          </html>
      ';
  }

  private function renderIsLoggedIn($isLoggedIn) {

      if ($isLoggedIn === "Yes")
          return '<h2>Logged in</h2>';
      if ($isLoggedIn === "No")
          return '<h2>Not logged in</h2>';
      else
          return '<h2>Logged in</h2>';
  }
}
