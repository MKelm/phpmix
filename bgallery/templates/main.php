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
?>
<!DOCTYPE html>
<html>
  <head>
    <title>BGallery</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="index,follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?=$tplLinkBase?>bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$tplLinkBase?>bootstrap/custom.css">
    <link rel="stylesheet" type="text/css" href="<?=$tplLinkBase?>fancybox/jquery.fancybox.css" />
    <script type="text/javascript" src="<?=$tplLinkBase?>bootstrap/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$tplLinkBase?>fancybox/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="<?=$tplLinkBase?>fancybox/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".fancybox").fancybox();
      });
    </script>
  </head>
  <body>
    <? include_once(__DIR__."/header.php") ?>
    <div class="container">
      <? if (!empty($_SESSION["valid"]) && empty($selectedUserId)) { ?>
      <div class="alert alert-danger" role="alert">No users available.</div>
      <? } ?>
      <? if (!empty($_SESSION["valid"])) {
        include_once(__DIR__."/gallery.php");
        include_once(__DIR__."/footer.php");
      } else {
        include_once(__DIR__."/login.php");
      } ?>
    </div>
  </body>
</html>
