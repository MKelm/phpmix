<?
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
if (!defined("PC_INDEX"))
  die("Access denied");
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <a class="navbar-brand" href="?">Pixel-Counter</a>
    <? if (!empty($_SESSION["valid"])) { ?>
    <p class="navbar-text">Host: <?=$_SERVER["HTTP_HOST"]?></p>
    <p class="navbar-text">Total Visits: <?=$db->getVisitsAmount()?></p>
    <p class="navbar-text">Total Hits: <?=$db->getHitsAmount()?></p>
    <? } ?>
    <ul class="nav navbar-nav navbar-right">
      <? if (!empty($_SESSION["valid"])) { ?>
      <li><a href="?action=logout">Logout</a></li>
      <li><a href="?action=geoip">Update GeoIP</a></li>
      <? } ?>
      <li><a href="http://idx.shrt.ws">(c) IDX.codelab</a></li>
    </ul>
  </div>
</nav>
