<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
include_once(__DIR__."/config.php");
include_once(__DIR__."/classes/database.php");

// perform visitor / hits simulation with empty db for debugging purposes
$db = new \PixelCounter\Database($config["database"], true);

// get 10 visitor ips
$visitorIps = array();
for ($i = 0; $i < 10; $i++) {
  $visitorIps[] = "87.184.179.".$i;
}

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

// get random 10 uris
$uris = array();
for ($i = 0; $i < 10; $i++) {
  $uris[] = "/".generateRandomString(10);
}

// perform 500 hits with random ips and uris
for ($i = 1; $i < 501; $i++) {

  // get random time
  $dayTime = microtime(true) - rand(0, 7 * 86400);

  // get random ip
  $ipIdx = rand(0, count($visitorIps) - 1);
  $ip = $visitorIps[$ipIdx];
  $cookieName = "idxpc_ip_".$ipIdx;
  if (!empty($_COOKIE[$cookieName])) {
    $ip = $_COOKIE[$cookieName];
  } else {
    $ip = $visitorIps[$ipIdx];
  }

  // get visit/hit on random uri
  $uriIdx = rand(0, count($uris)-1);

  // set visit with random day time
  if ($db->checkIpVisit($ip, $config["iplifetime"], $dayTime) == false) {
    setcookie($cookieName, $ip, $dayTime + $config["iplifetime"], "/");
    $result = $db->insertVisit($ip, $uris[$uriIdx], $dayTime);
  }

  $db->insertHit($ip, $uris[$uriIdx], $dayTime);

  usleep(1000);
}
