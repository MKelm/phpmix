<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
define("BG_INDEX", 1);

include_once(__DIR__."/config.php");
session_start();

include_once(__DIR__."/classes/database.php");
$galleryDB = new \BGallery\Database($config["database"]);

$action = !empty($_GET["action"]) ? $_GET["action"] : "";
$actionStatus = false;
if (empty($_SESSION["valid"]) && $action == "login") {
  include_once(__DIR__."/classes/login.php");
  $login = new BGallery\Login($galleryDB);
  if ($login->setFormValues($_POST)) {
    $actionStatus = $login->perform();
    $formValues = $login->getFormValues();
    if ($actionStatus == true) {
      $_SESSION["valid"] = 1;
    }
  } else {
    $formValues = $login->getFormValues();
  }
  $formErrors = $login->getFormErrors();
} else if ($action == "logout") {
  unset($_SESSION["valid"]);
  session_destroy();
}

if (!empty($_SESSION["valid"])) {
  // load contents
  include_once(__DIR__."/loader.php");
}

// get output
$tplLinkBase = ($_SERVER['SERVER_NAME'] == "localhost") ? "/bgallery/" : "/";
include_once(__DIR__."/templates/main.php");
