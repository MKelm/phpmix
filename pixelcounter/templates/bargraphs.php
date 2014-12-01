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

include_once(__DIR__."/../config.php");
?>
<? if ($action == "login" && $actionStatus == true) { ?>
<div class="alert alert-success" role="alert">Yes, welcome to Pixel-Counter!</div>
<? } ?>
<div class="row" style="text-align: center">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Top Visits-URIs</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php?uris=visitors" alt="">
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Top Hits-URIs</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php?uris=hits" alt="">
      </div>
    </div>
  </div>
</div>

<div class="row" style="text-align: center">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Visits Per Day</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php" alt="">
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Hits Per Day</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php?hits=1" alt="">
      </div>
    </div>
  </div>
</div>

<div class="row" style="text-align: center">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Visits Per Month</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php?months=1?>" alt="">
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Hits Per Month</strong></h3>
      </div>
      <div class="panel-body">
        <img src="bargraph.php?months=1&hits=1?>" alt="">
      </div>
    </div>
  </div>
</div>
