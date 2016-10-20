<?php

session_start();

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/Server.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
try {
    $v = new LoginView();
    $dtv = new DateTimeView();
    $lv = new LayoutView();
    $s = new Server($v);

    $lv->render($v, $dtv, $s);
}
catch (Exception $e){
    echo $e->getMessage();
}
