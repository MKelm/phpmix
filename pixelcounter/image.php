<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
header('Content-Type: image/gif');
echo base64_decode("R0lGODdhAQABAIAAAPxqbAAAACwAAAAAAQABAAACAkQBADs=");

if (!empty($_GET["time"]) && !empty($_SERVER["HTTP_REFERER"])) {

  include_once(__DIR__."/config.php");
  include_once(__DIR__."/classes/database.php");

  $db = new \PixelCounter\Database($config["database"], false);
  $cookieName = "idxpc_ip";
  if (!empty($_COOKIE[$cookieName])) {
    $ip = $_COOKIE[$cookieName];
  } else {
    $ip = $_SERVER["REMOTE_ADDR"];
  }
  if ($db->checkIpVisit($ip, $config["iplifetime"]) == false) {
    setcookie($cookieName, $ip, time() + $config["iplifetime"], "/");
    $result = $db->insertVisit($ip);
  }
  $db->insertHit($ip);

}

