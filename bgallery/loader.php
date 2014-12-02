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

// default view folder / gallery

// get users for header navigation
$users = $galleryDB->getUsers();
if (!empty($users)) {
  $selectedUserId = !empty($_GET["user"]) ? $_GET["user"] : $users[0]["id"];
} else {
  $selectedUserId = 0;
}

// get galleries for gallery navigation
if (!empty($selectedUserId)) {
  $galleries = $galleryDB->getGalleries($selectedUserId);
  if (!empty($galleries)) {
    $selectedGalleryId = !empty($_GET["gallery"]) ?
      $_GET["gallery"] : $galleries[0]["id"];
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
