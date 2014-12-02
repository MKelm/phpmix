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

function tplFormGetField($formAction, $fieldName, $defaultValue = "") {
  global $formValues;
  global $action;
  if ($action == $formAction || $defaultValue !== "")
    echo isset($formValues[$fieldName]) ? $formValues[$fieldName] : $defaultValue;
}

function tplFormGetFieldClass($formAction, $fieldName) {
  global $formErrors;
  global $action;
  if ($action == $formAction)
    echo isset($formErrors[$fieldName]) && $formErrors[$fieldName] == true ?
      "has-error has-feedback" : "";
}
?>
<? if ($action == "login" && $actionStatus == false) { ?>
<div class="alert alert-danger" role="alert">Oh, something went wrong.</div>
<? } ?>
<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title"><strong>Login</strong></h3></div>
  <div class="panel-body">
    <form role="form" name="contact" action="?action=login" method="post" accept-charset="utf-8">
      <div class="form-group <?tplFormGetFieldClass("login", "name")?>">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" placeholder="Name" value="<?tplFormGetField("login", "name")?>">
      </div>
      <div class="form-group <?tplFormGetFieldClass("login", "email")?>">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" value="<?tplFormGetField("login", "password")?>">
      </div>
      <button type="submit" class="btn btn-sm btn-default">Login</button>
    </form>
  </div>
</div>
