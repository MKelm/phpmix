<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace PixelCounter;

include_once(__DIR__."/form.php");

class Login extends Form {
  protected $_database;
  protected $_dbTableName;

  public function __construct($database, $userTableName) {
    $this->_database = $database;
    $this->_dbTableName = $userTableName;
    $this->_formFields = array("name", "password");
  }

  protected function _getPasswordHash($password) {
    return hash("sha256", $password, false);
  }

  public function perform() {
    if (count($this->_formErrors) == 0) {
      try {
        $result = $this->_database->select(
          $this->_dbTableName,
          array("name"),
          array(
            array("name", "=", $this->_formValues["name"]),
            array("password", "=", $this->_getPasswordHash(
                                     $this->_formValues["password"]
                                   )
            )
          )
        );
        if (empty($result) || count($result) > 1) {
          $this->_formErrors["name"] = true;
          $this->_formErrors["password"] = true;
        } else if (count($result) == 1 && !empty($result[0]["name"])) {
          return true;
        }
      } catch (\Exception $e) {
        $this->_formErrors["name"] = true;
        $this->_formErrors["password"] = true;
      }
    }
    return false;
  }
}
