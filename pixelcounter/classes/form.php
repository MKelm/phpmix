<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace PixelCounter;

class Form {

  protected $_formFields;
  protected $_formValues;
  protected $_formErrors;

  public function __construct() {
    $this->_formFields = array();
    $this->_formValues = array();
    $this->_formErrors = array();
  }

  public function setFormValues($formValues) {
    foreach ($this->_formFields as $formField) {
      if (!isset($formValues[$formField]) ||
          strlen($formValues[$formField]) == 0) {
        $this->_formErrors[$formField] = true;
        $this->_formValues[$formField] = "";
      } else {
        $this->_formValues[$formField] = $formValues[$formField];
      }
    }
    return count($this->_formErrors) == 0;
  }

  public function getFormValues() {
    return $this->_formValues;
  }

  public function getFormErrors() {
    return $this->_formErrors;
  }
}
