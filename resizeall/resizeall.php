<?php

$fullSizeWidth = 800;
$fullSizeHeight = 600;

include(__DIR__."/imageresize.php");

$originalFolder = "./original";
$fullFolder = "./full";

$files = scandir($originalFolder);
foreach ($files as $file) {
  if ($file != "." && $file != ".." && !is_dir($originalFolder."/".$file)) {
    $image = new \Eventviva\ImageResize($originalFolder."/".$file);
    $image->quality_png = 9;
    $image->quality_jpg = 90;
    $width = $image->getSourceWidth();
    $height = $image->getSourceHeight();
    if ($width > $height)
      $image->resizeToWidth($fullSizeWidth);
    else
      $image->resizeToHeight($fullSizeHeight);
    $image->save($fullFolder."/".$file);
  }
}
