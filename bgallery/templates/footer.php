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

function tplTagsSortAssoc(&$tags) {
  $sortedKeys = array_keys($tags);
  sort($sortedKeys);
  $result = array();
  foreach ($sortedKeys as $key) {
    $result[$key] = $tags[$key];
  }
  $tags = $result;
  return true;
}
?>
<!-- tags list -->
<? if (!empty($tagsList) && count($tagsList) > 0) { ?>
<div class="btn-group" role="group" aria-label="...">
<? tplTagsSortAssoc($tagsList);
  $maxAmount = 0;
  foreach ($tagsList as $tag => $amount) {
    if ($maxAmount < $amount)
      $maxAmount = $amount;
  }
  $fsizePerAmount = 3 / $maxAmount;
  foreach ($tagsList as $tag => $amount) {
    $fSize = $amount * $fsizePerAmount;
    ?>
<a role="button" class="btn btn-default" style="font-size: <?=$fSize?>em"><?=$tag?></a>
<? } ?>
</div>
<? } ?>
