<?php

if (!defined("PC_INDEX"))
  die("Access denied");

$fileName = "GeoIP.dat.gz";
$urlBase = "http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/";
$dataFolder = __DIR__."/data";

$data = file_get_contents($urlBase.$fileName);
file_put_contents($dataFolder."/".$fileName, $data);

$bufferSize = 4096;
$outputFileName = str_replace('.gz', '', $fileName);

$inputFile = gzopen($dataFolder."/".$fileName, 'rb');
$outputFile = fopen($dataFolder."/".$outputFileName, 'wb');

while (!gzeof($inputFile)) {
  fwrite($outputFile, gzread($inputFile, $bufferSize));
}

fclose($outputFile);
gzclose($inputFile);
