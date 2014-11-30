<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace PixelCounter;

include_once(__DIR__."/database/base.php");

class Database extends Database\Base {

  protected $_tableNameVisits = "pc_visits";
  protected $_tableNameHits = "pc_hits";

  function __construct($config, $reset = false) {
    parent::__construct($config);
    $this->connect();
    if ($reset == true) {
      $this->truncate($this->_tableNameVisits);
      $this->truncate($this->_tableNameHits);
    }
  }

  function checkIpVisit($ip, $ipLifetime, $time = false) {
    $uid = md5($ip);
    $limitTime = $time ? $time - $ipLifetime : microtime(true) - $ipLifetime;

    return $this->count(
      $this->_tableNameVisits, "uid",
      array(
        array("uid", "=", $uid),
        array("time", ">=", $limitTime)
      )
    ) > 0;
  }

  function getCountryByIp($ip) {
    include_once(__DIR__."/../geoip/geoip.inc");
    $gi = geoip_open(__DIR__."/../geoip/GeoIP.dat", GEOIP_STANDARD);
    $result = geoip_country_code_by_addr($gi, $ip);
    // geoip_country_name_by_addr($gi, $ip)
    geoip_close($gi);
    return $result;
  }

  function insertVisit($ip = false, $uri = false, $time = false) {
    try {
      $this->insert(
        $this->_tableNameVisits,
        array(
          "uid" => $ip ? md5($ip) : md5($_SERVER["REMOTE_ADDR"]),
          "time" => $time ? $time : microtime(true),
          "country" => $this->getCountryByIp($ip),
          "host" => $_SERVER["HTTP_HOST"],
          "uri" => $uri ? $uri : $_SERVER["REQUEST_URI"]
        )
      );
      return ($this->_last_affected_rows == 1);
    } catch (\Exception $e) {
      return false;
    }
  }

  function insertHit($ip = false, $uri = false, $time = false) {
    try {
      $this->insert(
        $this->_tableNameHits,
        array(
          "uid" => $ip ? md5($ip) : md5($_SERVER["REMOTE_ADDR"]),
          "time" => $time ? $time : microtime(true),
          "host" => $_SERVER["HTTP_HOST"],
          "uri" => $uri ? $uri : $_SERVER["REQUEST_URI"]
        )
      );
      return ($this->_last_affected_rows == 1);
    } catch (\Exception $e) {
      return false;
    }
  }

  function getVisitsAmountByDayFrame($dayOffsetStart, $dayOffsetEnd) {
    return $this->count(
      $this->_tableNameVisits, "time",
      array(
        array("time", ">=", microtime(true) + $dayOffsetStart * 86400),
        array("time", "<", microtime(true) + $dayOffsetEnd * 86400)
      )
    );
  }

  function getHitsAmountByDayFrame($dayOffsetStart, $dayOffsetEnd) {
    return $this->count(
      $this->_tableNameHits, "time",
      array(
        array("time", ">=", microtime(true) + $dayOffsetStart * 86400),
        array("time", "<", microtime(true) + $dayOffsetEnd * 86400)
      )
    );
  }

  function getTopUriVitiorsList($maxAmount) {
    return $this->count(
      $this->_tableNameVisits, "uri", array(), "uri", "amount ASC", $maxAmount
    );
  }

  function getTopUriHitsList($maxAmount) {
    return $this->count(
      $this->_tableNameHits, "uri", array(), "uri", "amount ASC", $maxAmount
    );
  }
}
