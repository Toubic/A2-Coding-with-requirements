<?php


class LayoutView {
  
  public function render(LoginView $v, DateTimeView $dtv) {

      if($v->login())
          $_SESSION['isLoggedIn'] = true;
      if (!isset($_SESSION['isLoggedIn']))
          $_SESSION['isLoggedIn'] = false;
      echo '<!DOCTYPE html>
          <html>
            <head>
              <meta charset="utf-8">
              <title>Login Example</title>
            </head>
            <body>
              <h1>Assignment 2</h1>
              ' . $this->renderIsLoggedIn($_SESSION['isLoggedIn']) . '
              
              <div class="container">
                  ' . $v->response() . '
                  
                  ' . $dtv->show() . '
              </div>
             </body>
          </html>
      ';
  }

  private function renderIsLoggedIn($isLoggedIn) {

      if ($isLoggedIn) {
        return '<h2>Logged in</h2>';

      }
      else {
        return '<h2>Not logged in</h2>';

      }
  }
}
