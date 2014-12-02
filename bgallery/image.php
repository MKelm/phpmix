<?php
session_start();
if (empty($_SESSION["valid"]) || empty($_GET["iname"]) || empty($_GET["ifolder"]))
  die("Access denied");

$imagesDir = __DIR__."/images/".$_GET["ifolder"].
  "/".substr($_GET["iname"], 0, 2)."/";
$imageName = $_GET["iname"];

if (strpos($imageName, ".jpg") !== false || strpos($imageName, ".jpeg")) {
  $type = "image/jpeg";
} else if (strpos($imageName, ".png") !== false) {
  $type = "image/png";
} else if (strpos($imageName, ".gif") !== false) {
  $type = "image/gif";
}

header("Content-Type: ".$type);
header("Content-Length: ".filesize($imagesDir.$imageName));
readfile($imagesDir.$imageName);
