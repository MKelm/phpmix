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
$db = new \PixelCounter\Database($config["database"]);

$values = array(
  -6 => 0,
  -5 => 0,
  -4 => 0,
  -3 => 0,
  -2 => 0,
  -1 => 0,
  -0 => 0,
);
if (!empty($_GET["uris"])) {
  if ($_GET["uris"] == "hits") {
    $values = $db->getTopUriHitsList(7);
    $valueKeys = array_keys($values);
  } else {
    $values = $db->getTopUriVitiorsList(7);
    $valueKeys = array_keys($values);
  }
} else {
  $valueKeys = array_keys($values);
  foreach ($valueKeys as $key) {
    if (!empty($_GET["hits"])) {
      $values[$key] = $db->getHitsAmountByDayFrame($key - 1, $key);
    } else {
      $values[$key] = $db->getVisitsAmountByDayFrame($key - 1, $key);
    }
  }
}

$fontSize = 5;
$imgWidth = 320;
$imgHeight = 240;
$columnPadding = 8;

$columnAmount = count($values);
$columnWidth = $imgWidth / $columnAmount;

$im        = imagecreate($imgWidth, $imgHeight);
$gray      = imagecolorallocate($im, 0xcc, 0xcc, 0xcc);
$gray_lite = imagecolorallocate($im, 0xee, 0xee, 0xee);
$gray_dark = imagecolorallocate($im, 0x7f, 0x7f, 0x7f);
$white     = imagecolorallocate($im, 0xff, 0xff, 0xff);
$black     = imagecolorallocate($im, 0x00, 0x00, 0x00);

imagefilledrectangle($im, 0, 0, $imgWidth, $imgHeight, $white);

$maxV = 0;
for($i =  0; $i < $columnAmount; $i++)
  $maxV = max($values[$valueKeys[$i]], $maxV);

for ($i = 0; $i < $columnAmount; $i++) {
  $columnHeight = ($imgHeight / 100) * (($values[$valueKeys[$i]] / $maxV) *100);

  $x1 = $i * $columnWidth;
  $y1 = $imgHeight - $columnHeight;
  $x2 = (($i + 1) * $columnWidth) - $columnPadding;
  $y2 = $imgHeight;

  imagefilledrectangle($im, $x1, $y1, $x2, $y2, $gray);

  imageline($im,$x1,$y1,$x1,$y2,$gray_lite);
  imageline($im,$x1,$y2,$x2,$y2,$gray_lite);
  imageline($im,$x2,$y1,$x2,$y2,$gray_dark);

  imagestring($im, $fontSize,
    $x1 + $columnWidth / 2 - imagefontwidth($fontSize),
    $imgHeight - imagefontheight($fontSize),
    $values[$valueKeys[$i]], $black
  );
  if (empty($_GET["uris"])) {
    imagestringup(
      $im, $fontSize,
      $x1 + $columnWidth / 2 - imagefontheight($fontSize),
      $imgHeight - imagefontheight($fontSize) * 2,
      date("Y-m-d", time() + $valueKeys[$i] * 86400), $black
    );
  } else {
    imagestringup(
      $im, $fontSize,
      $x1 + $columnWidth / 2 - imagefontheight($fontSize),
      $imgHeight - imagefontheight($fontSize) * 2,
      $valueKeys[$i], $black
    );
  }
}

header("Content-type: image/png");
imagepng($im);
