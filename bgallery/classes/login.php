<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace BGallery;

if (!defined("BG_INDEX"))
  die("Access denied");

include_once(__DIR__."/form.php");

class Login extends Form {
  protected $_database;

  public function __construct($database) {
    $this->_database = $database;
    $this->_formFields = array("name", "password");
  }

  protected function _getPasswordHash($password) {
    return hash("sha256", $password, false);
  }

  public function perform() {
    if (count($this->_formErrors) == 0) {
      try {
        $result = $this->_database->getUserByLogin(
          $this->_formValues["name"],
          $this->_getPasswordHash($this->_formValues["password"])
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
