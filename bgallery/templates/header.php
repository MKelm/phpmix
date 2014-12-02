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

function tplUserActive($cUserId) {
  global $selectedUserId;
  if ($cUserId == $selectedUserId)
    echo " class=\"active\"";
}
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <a class="navbar-brand" href="<?=$tplLinkBase?>">BGallery</a>
    <? if (!empty($users)) { ?>
    <ul class="nav navbar-nav">
      <? foreach ($users as $user) { ?>
      <li <?=tplUserActive($user["id"])?>><a href="<?=$tplLinkBase?>user/<?=$user["id"]?>"><?=$user["name"]?></a></li>
      <? } ?>
    </ul>
    <? } ?>
    <ul class="nav navbar-nav navbar-right">
      <? if (!empty($_SESSION["valid"])) { ?>
      <li><a href="<?=$tplLinkBase?>action/logout">Logout</a></li>
      <? } ?>
      <li><a href="http://idx.shrt.ws">(c) IDX.codelab</a></li>
    </ul>
  </div>
</nav>
