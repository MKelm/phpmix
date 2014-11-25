<?php

include(__DIR__."/imageresize.php");

if (count($argv) == 3) {

  $destSizeWidth = 800;
  $destSizeHeight = 600;

  $sourceFolder = $argv[1];
  $destinationFolder = $argv[2];

  $files = scandir($sourceFolder);
  foreach ($files as $file) {
    if ($file != "." && $file != ".." && !is_dir($sourceFolder."/".$file)) {
      $image = new \Eventviva\ImageResize($sourceFolder."/".$file);
      $image->quality_png = 9;
      $image->quality_jpg = 90;
      $width = $image->getSourceWidth();
      $height = $image->getSourceHeight();
      if ($width > $height)
        $image->resizeToWidth($destSizeWidth);
      else
        $image->resizeToHeight($destSizeHeight);
      $image->save($destinationFolder."/".$file);
    }
  }

}
