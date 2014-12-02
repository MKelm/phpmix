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

$fullImagesUrlPrefix = "images/full/";
$thumbImagesUrlPrefix = "images/thumbs/";

function tplGalleryActive($cGalleryId) {
  global $selectedGalleryId;
  if ($cGalleryId == $selectedGalleryId)
    echo " active";
}

if (empty($galleries) && empty($selectedTag)) { ?>
<div class="alert alert-danger" role="alert">No galleries available.</div>
<? } else { ?>
  <!-- gallery panel -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <!-- gallery button group -->
      <? if (empty($selectedTag)) { ?>
      <div class="btn-group" role="group" aria-label="...">
        <? foreach ($galleries as $gallery) { ?>
        <a role="button" class="btn btn-default <?=tplGalleryActive($gallery["id"])?>" href="?user=<?=$selectedUserId?>&gallery=<?=$gallery["id"]?>"><?=$gallery["name"]?></a>
        <? } ?>
      </div>
      <? } else { ?>
        <div class="btn-group" role="group" aria-label="...">
        <a role="button" class="btn btn-default active" href="#">Tag: <?=$selectedTag?></a>
        </div>
      <? } ?>
    </div>
    <div class="panel-body">
      <? if (empty($taggedImages) && empty($images)) { ?>
        <div class="alert alert-danger" role="alert">No images available.</div>
      <? } else if (!empty($taggedImages)) { ?>
      <!-- tagged images output -->
        <!-- gallery grid content -->
        <div class="row">
        <? $count = 0; foreach ($taggedImages as $taggedImage) { ?>
          <div class="col-sm-3">
            <a class="thumbnail fancybox" title="" data-fancybox-group="gallery" href="<?=$taggedImage[1]?>">
              <img src="<?=$taggedImage[0]?>" alt="...">
            </a>
          </div>
        <? if ($count % 4 == 3) { ?>
        </div><div class="row">
        <? } ?>
        <? $count++; } ?>
        </div>
      <? } else { ?>
        <!-- gallery grid content -->
        <div class="row">
        <? $count = 0; foreach ($images as $image) {
          $imageIdName = md5($image["id"].$image["name"].$image["ext"]).$image["ext"]; ?>
          <div class="col-sm-3">
            <a class="thumbnail fancybox" title="<?=$image["name"]?><?=$image["ext"]?>" data-fancybox-group="gallery" href="image.full.<?=$imageIdName?>">
              <img src="image.thumb.<?=$imageIdName?>" alt="<?=$image["name"]?><?=$image["ext"]?>">
            </a>
          </div>
        <? if ($count % 4 == 3) { ?>
        </div><div class="row">
        <? } ?>
        <? $count++; } ?>
        </div>
        <!-- gallery tags button group -->
        <? if (!empty($galleryTags)) { ?>
        <div class="btn-group" role="group" aria-label="...">
          <? foreach ($galleryTags as $galleryTag) { ?>
          <a role="button" class="btn btn-default" href="?tag=<?=$galleryTag?>"><?=$galleryTag?></a>
          <? } ?>
        </div>
        <? } ?>
      <? } ?>
    </div>
  </div>
<? } ?>
