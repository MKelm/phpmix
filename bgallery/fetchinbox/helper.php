<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
include_once(__DIR__."/../classes/imageresize.php");

function getGalleryTags($body) {
  $hasResult = false;
  $results = array("replace" => null, "add" => null, "remove" => null);
  preg_match_all("/set-tags:([ a-z,A-Z0-9]*)/", $body, $out, PREG_SET_ORDER);
  if (count($out) > 0 && isset($out[0][1])) {
    $results["replace"] = explode(",", str_replace(" ", "", $out[0][1]));
    $hasResult = true;
  }
  preg_match_all("/add-tags:([ a-z,A-Z0-9]*)/", $body, $out, PREG_SET_ORDER);
  if (count($out) > 0 && isset($out[0][1])) {
    $results["add"] = explode(",", str_replace(" ", "", $out[0][1]));
    $hasResult = true;
  }
  preg_match_all("/remove-tags:([ a-z,A-Z0-9]*)/", $body, $out, PREG_SET_ORDER);
  if (count($out) > 0 && isset($out[0][1])) {
    $results["remove"] = explode(",", str_replace(" ", "", $out[0][1]));
    $hasResult = true;
  }
  if ($hasResult == true)
    return $results;
  return null;
}

function getSubjectParts($fromEmail, $subject) {
  $parts = array("email" => $fromEmail, "gallery" => "Mixed");
  if (strpos($subject, " - ") !== false) {
    $parts = explode(" - ", $subject);
    $parts["email"] = $parts[0];
    $parts["gallery"] = $parts[1];
  } else if (strlen($subject) > 0) {
    $parts["gallery"] = $subject;
  }
  return $parts;
}

function getImageFileName($fileName) {
  if (!empty($fileName)) {
    $fileName = strtolower($fileName);
    if (strpos($fileName, ".jpg") !== false) {
      return array(".jpg", str_replace(".jpg", "", $fileName));
    } else if (strpos($fileName, ".jpeg") !== false) {
      return array(".jpeg", str_replace(".jpeg", "", $fileName));
    } else if (strpos($fileName, ".png") !== false) {
      return array(".png", str_replace(".png", "", $fileName));
    } else if (strpos($fileName, ".gif") !== false) {
      return array(".gif", str_replace(".gif", "", $fileName));
    }
  }
  return null;
}

function getImageIdNameFolder($imageIdName) {
  $baseFolders = array("full", "original", "thumbs");
  $imagesDirectory = __DIR__."/../images/";
  $imageIdNameFolder = substr($imageIdName, 0, 2);
  foreach ($baseFolders as $baseFolder) {
    if (file_exists($imagesDirectory.$baseFolder) == false) {
      mkdir($imagesDirectory.$baseFolder, 0777, true);
      chmod($imagesDirectory.$baseFolder, 0777);
    }
    $tmpFile = $imagesDirectory.$baseFolder."/".$imageIdNameFolder;
    if (file_exists($tmpFile) == false) {
      mkdir($tmpFile, 0777, true);
      chmod($tmpFile, 0777);
    }
  }
  return $imageIdNameFolder."/";
}

function saveImageAttachments($attachments, $db, $galleryId, $config) {

  $baseImagesFolder = __DIR__."/../images/";
  $originalImagesFolder = $baseImagesFolder."original/";
  $fullImagesFolder = $baseImagesFolder."full/";
  $thumbsImagesFolder = $baseImagesFolder."thumbs/";

  $attachmentsCount = 0;
  foreach ($attachments as $attachment) {
    $fileName = getImageFileName($attachment["filename"]);

    if ($fileName != null) {
      $fileExt = $fileName[0];
      $fileName = $fileName[1];

      $imageId = $db->getImageByName($galleryId, $fileName, $fileExt);
      if (!empty($imageId))
        continue;

      $imageId = $db->insertImage($galleryId, $fileName, $fileExt);
      if (empty($imageId))
        continue;
      $imageIdName = md5($imageId.$fileName.$fileExt).$fileExt;

      $imageIdNameFolder = getImageIdNameFolder($imageIdName);
      $attachmentsCount++;
      file_put_contents(
        $originalImagesFolder.$imageIdNameFolder.$imageIdName, $attachment["attachment"]
      );

      $image = new \BGallery\ImageResize(
        $originalImagesFolder.$imageIdNameFolder.$imageIdName
      );
      $image->quality_png = 9;
      $image->quality_jpg = 90;
      $width = $image->getSourceWidth();
      $height = $image->getSourceHeight();
      if ($width > $height)
        $image->resizeToWidth($config["fullwidth"]);
      else
        $image->resizeToHeight($config["fullheight"]);
      $image->save($fullImagesFolder.$imageIdNameFolder.$imageIdName);

      $image = new \BGallery\ImageResize(
        $originalImagesFolder.$imageIdNameFolder.$imageIdName
      );
      $image->quality_png = 9;
      $image->quality_jpg = 90;
      $image->resizeToWidth($config["thumbsize"]);
      $image->crop($config["thumbsize"], $config["thumbsize"]);
      $image->save($thumbsImagesFolder.$imageIdNameFolder.$imageIdName);
    }
  }
  return $attachmentsCount;
}

function getImageAttachments($imap, $m, $structure) {
  $attachments = array();

  if (isset($structure->parts) && count($structure->parts)) {
    for($i = 0; $i < count($structure->parts); $i++) {
      $attachments[$i] = array(
        'is_attachment' => false,
        'filename' => '',
        'name' => '',
        'attachment' => ''
      );

      if($structure->parts[$i]->ifdparameters) {
        foreach($structure->parts[$i]->dparameters as $object) {
          if(strtolower($object->attribute) == 'filename') {
              $attachments[$i]['is_attachment'] = true;
              $attachments[$i]['filename'] = $object->value;
          }
        }
      }

      if($structure->parts[$i]->ifparameters) {
        foreach($structure->parts[$i]->parameters as $object) {
          if(strtolower($object->attribute) == 'name') {
            $attachments[$i]['is_attachment'] = true;
            $attachments[$i]['name'] = $object->value;
          }
        }
      }

      if($attachments[$i]['is_attachment']) {
        $attachments[$i]['attachment'] = imap_fetchbody($imap, $m, $i+1);
        if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
          $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
        }
        elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
          $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
        }
      }
    }
  }

  foreach ($attachments as $key => $attachment) {
    $fileName = getImageFileName($attachment['filename']);
    if ($fileName === null) {
      unset($attachments[$key]);
    }
  }

  return $attachments;
}
