<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
$config = array();
$config["database"] = array(
  "ip" => "localhost",
  "name" => "bgallery",
  "user" => "bgallery",
  "pass" => "bgallery"
);
$config["inbox"] = array(
  "username" => "user",
  "password" => "password",
  "mailbox" => "{ssl.imap.tld:993/imap/ssl}",
  "fetchmsgs" => 5,
  "deletemsgs" => true
);
$config["images"] = array(
  "fullwidth" => 800,
  "fullheight" => 600,
  "thumbsize" => 200
);
