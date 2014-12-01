<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
include_once(__DIR__."/config.php");
include_once(__DIR__."/classes/database.php");
$db = new \PixelCounter\Database($config["database"]);

?><!DOCTYPE html>
<html>
  <head>
    <title>IDX.codelab - Pixel-Counter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
  </head>
  <body style="text-align: center; padding-top: 70px;">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <a class="navbar-brand" href="?">Pixel-Counter</a>
        <p class="navbar-text">Host: <?=$_SERVER["HTTP_HOST"]?></p>
        <p class="navbar-text">Total Visitors: <?=$db->getVisitsAmount()?></p>
        <p class="navbar-text">Total Hits: <?=$db->getHitsAmount()?></p>
        <p class="navbar-text navbar-right"><a href="http://idx.shrt.ws">(c) IDX.codelab</a></p>
      </div>
    </nav>

    <div class="container">

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Top Visitor-URIs</strong></h3>
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

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Visitors last Week</strong></h3>
            </div>
            <div class="panel-body">
              <img src="bargraph.php" alt="">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Hits last Week</strong></h3>
            </div>
            <div class="panel-body">
              <img src="bargraph.php?hits=1" alt="">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Visitors last 6 Months</strong></h3>
            </div>
            <div class="panel-body">
              <img src="bargraph.php?months=1" alt="">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Hits last 6 Months</strong></h3>
            </div>
            <div class="panel-body">
              <img src="bargraph.php?months=1&hits=1" alt="">
            </div>
          </div>
        </div>
      </div>

    </div>
  </body>
</html>
