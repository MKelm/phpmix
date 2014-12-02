<?
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
if (!defined("BG_INDEX"))
  die("Access denied");

$selectedTag = !empty($_GET["tag"]) ? $_GET["tag"] : "";
if (!empty($selectedTag)) {
  $taggedImages = $galleryDB->getImagesByTag($selectedTag);
} else {
  $taggedImages = array();
}

// default view folder / gallery

// get users for header navigation
$users = $galleryDB->getUsers();
if (!empty($users)) {
  foreach ($users as $key => $user) {
    if ($galleryDB->countGalleries($user["id"]) == 0) {
      unset($users[$key]);
    }
  }
  $selectedUserId = !empty($_GET["user"]) ? $_GET["user"] : current($users)["id"];
} else {
  $selectedUserId = 0;
}

// get galleries for gallery navigation
if (!empty($selectedUserId)) {
  $galleries = $galleryDB->getGalleries($selectedUserId);
  if (!empty($galleries)) {
    foreach ($galleries as $key => $gallery) {
      if ($galleryDB->countImages($gallery["id"]) == 0) {
        unset($galleries[$key]);
      }
    }
    $selectedGalleryId = !empty($_GET["gallery"]) ?
      $_GET["gallery"] : current($galleries)["id"];
  } else {
    $selectedGalleryId = 0;
  }
}

// get images by selected gallery
if (!empty($selectedGalleryId)) {
  $images = $galleryDB->getImages($selectedGalleryId);
} else {
  $images = array();
}

// get tags by selected gallery
if (!empty($selectedGalleryId)) {
  $galleryTags = $galleryDB->getGalleryTags($selectedGalleryId);
} else {
  $galleryTags = array();
}
