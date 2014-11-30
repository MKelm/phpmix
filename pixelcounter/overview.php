<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
?><!DOCTYPE html>
<html>
  <head>
    <title>IDX.codelab - Pixel-Counter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
  </head>
  <body style="text-align: center; margin-top: 1em;">
    <div class="container">

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Visitors</strong></h3>
            </div>
            <div class="panel-body">
              <img src="bargraph.php" alt="">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Hits</strong></h3>
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

    </div>
  </body>
</html>
