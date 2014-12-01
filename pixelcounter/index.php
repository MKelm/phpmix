<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
define("PC_INDEX", true);

include_once(__DIR__."/config.php");
session_start();

$action = !empty($_GET["action"]) ? $_GET["action"] : "";
$actionStatus = false;
if (empty($_SESSION["valid"])) {
  if ($action == "login" && !empty($_POST["name"]) && !empty($_POST["password"])
      && isset($config["logins"][$_POST["name"]]) &&
      $config["logins"][$_POST["name"]] == $_POST["password"]) {
    $actionStatus = true;
    $_SESSION["valid"] = 1;
  }
} else if ($action == "logout") {
  unset($_SESSION["valid"]);
  session_destroy();
}

$geoipUpdate = false;
if (!empty($_SESSION["valid"]) && $action == "geoip") {
  include_once(__DIR__."/geoip/update.php");
  $geoipUpdate = true;
}

?><!DOCTYPE html>
<html>
  <head>
    <title>IDX.codelab - Pixel-Counter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/custom.css" />
  </head>
  <body style="padding-top: 70px;">
    <? include_once(__DIR__."/templates/header.php"); ?>
    <div class="container">
      <? if (!empty($_SESSION["valid"]) && $geoipUpdate == true) { ?>
      <div class="alert alert-success" role="alert">GeoIP data have been updated!</div>
      <? } ?>

      <? if (empty($_SESSION["valid"])) {
        include_once(__DIR__."/templates/login.php");
      } else {
        include_once(__DIR__."/templates/bargraphs.php");
      } ?>
    </div>
  </body>
</html>
