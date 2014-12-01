<?php
/**
 * Pixel-Counter by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace PixelCounter\Database;

class Base {
  protected $_config;
  protected $_connection;
  protected $_last_insert_id;
  protected $_last_affected_rows;
  protected $_last_num_rows;

  public function __construct($config) {
    $this->_config = $config;
    $this->_last_insert_id = 0;
    $this->_last_affected_rows = 0;
    $this->_last_num_rows = 0;
  }

  public function getLastInsertId() {
    return $this->_last_insert_id;
  }

  public function connect() {
    $this->_connection = new \mysqli(
      $this->_config["ip"], $this->_config["user"],
      $this->_config["pass"], $this->_config["name"]
    );
    if ($this->_connection->connect_error) {
      throw new \Exception(
        "Database connection failed: ".$this->_connection->connect_error
      );
    }
    $this->_connection->set_charset("utf8");
  }

  protected function _getColumnsString($table, $columns) {
    $cols = "";
    $hasSeperator = false;
    foreach ($columns as $col) {
      if ($hasSeperator) {
        $cols .= ", ";
      } else {
        $hasSeperator = true;
      }
      if ($table != null) {
        $cols .= $table.".";
      }
      $cols .= $col;
    }
    return $cols;
  }

  protected function _getValuesString($values) {
    $vals = "";
    $hasSeperator = false;
    foreach ($values as $val) {
      if ($hasSeperator) {
        $vals .= ", ";
      } else {
        $hasSeperator = true;
      }
      $vals .= "'".$this->_connection->real_escape_string($val)."'";
    }
    return $vals;
  }

  protected function _getConditionsString($table, $conditions, $where = true) {
    if ($conditions != null && is_array($conditions)) {
      $condString = ($where == true) ? " WHERE " : " AND ";
      foreach ($conditions as $cond) {
        if (($where == true && $condString != " WHERE ") || $where == false) {
          if (count($cond) == 4) {
            $condString .= " ".$cond[0]." ";
            array_shift($cond);
          } else {
            $condString .= " AND ";
          }
        }
        $condString .= sprintf(
          "%s%s%s %s '%s'",
          $table, $table != null ? "." : "",
          $this->_connection->real_escape_string($cond[0]),
          $this->_connection->real_escape_string($cond[1]),
          $this->_connection->real_escape_string($cond[2])
        );
      }
      return $condString;
    }
    return "";
  }

  public function getJoinString($table, $tableColumn, $joinColumn) {
    $result = "";
    if ($table != NULL) {
      $result = sprintf(
        " INNER JOIN %s AS n ON n.%s = m.%s",
        $this->_connection->real_escape_string($table),
        $this->_connection->real_escape_string($tableColumn),
        $this->_connection->real_escape_string($joinColumn)
      );
    }
    return $result;
  }

  public function count($table, $column, $conditions = null,
                        $groupBy = null, $orderBy = null, $limit = null) {
    $sql = sprintf(
      "SELECT COUNT(%s) AS amount, %s FROM %s%s%s%s%s",
      $this->_connection->real_escape_string($column),
      $this->_connection->real_escape_string($column),
      $this->_connection->real_escape_string($table),
      $this->_getConditionsString(null, $conditions),
      !empty($groupBy) ? " GROUP BY ".$groupBy : "",
      !empty($orderBy) ? " ORDER BY ".$orderBy : "",
      !empty($limit) ? " LIMIT ".$limit : ""
    );
    $rs = $this->_connection->query($sql);
    if ($rs === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    } else {
      $this->_last_num_rows = $rs->num_rows;

      $rs->data_seek(0);
      if (empty($groupBy)) {
        $result = 0;
        if ($row = $rs->fetch_assoc()) {
          $result = $row["amount"];
        }
      } else {
        $result = array();
        while ($row = $rs->fetch_assoc()) {
          $result[$row[$column]] = $row["amount"];
        }
      }
      return $result;
    }
    return null;
  }

  public function select($table, $columns, $conditions = null, $orderBy = null,
                         $keyColumn = null, $limit = null,
                         $joinColumns = null, $joinString = null, $joinConditions = null) {

    if ($joinString == null) {
      $sql = sprintf(
        "SELECT %s FROM %s AS m%s%s%s",
        $this->_getColumnsString("m", $columns),
        $this->_connection->real_escape_string($table),
        $this->_getConditionsString("m", $conditions),
        ($orderBy !== null) ? " ORDER BY m.".$orderBy : "",
        ($limit !== null) ? " LIMIT ".$limit : ""
      );
    } else {
      $sql = sprintf(
        "SELECT %s%s%s FROM %s AS m%s%s%s%s",
        $this->_getColumnsString("m", $columns),
        count($joinColumns) > 0 ? ", " : "",
        $this->_getColumnsString("n", $joinColumns),
        $this->_connection->real_escape_string($table),
        $joinString,
        $this->_getConditionsString("m", $conditions),
        $this->_getConditionsString("n", $joinConditions),
        ($orderBy !== null) ? " ORDER BY m.".$orderBy : "",
        ($limit !== null) ? " LIMIT ".$limit : ""
      );
    }

    $rs = $this->_connection->query($sql);
    if ($rs === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    } else {
      $this->_last_num_rows = $rs->num_rows;

      $rs->data_seek(0);
      $result = array();
      while ($row = $rs->fetch_assoc()) {
        if ($keyColumn !== null)
          $result[$row[$keyColumn]] = $row;
        else
          $result[] = $row;
      }
      return $result;
    }
    return null;
  }

  public function insert($table, $data) {
    $sql = sprintf(
      "INSERT INTO %s (%s) VALUES (%s)",
      $this->_connection->real_escape_string($table),
      $this->_getColumnsString(null, array_keys($data)),
      $this->_getValuesString(array_values($data))
    );

    if ($this->_connection->query($sql) === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    } else {
      $this->_last_insert_id = $this->_connection->insert_id;
      $this->_last_affected_rows = $this->_connection->affected_rows;
    }
  }

  public function _getUpdateString($table, $data) {
    $result = "";
    foreach ($data as $key => $value) {
      if ($result != "")
        $result .= ",";
      $result .= sprintf(
        "%s%s%s = %s", $table, ($table !== null) ? "." : "", $key, $value
      );
    }
    return $result;
  }

  public function update($table, $data, $conditions) {
    $sql = sprintf(
      "UPDATE %s AS m SET %s%s",
      $this->_connection->real_escape_string($table),
      $this->_getUpdateString("m", $data),
      $this->_getConditionsString("m", $conditions, $where = true)
    );

    if ($this->_connection->query($sql) === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    } else {
      $this->_last_affected_rows = $this->_connection->affected_rows;
    }
  }

  public function delete($table, $conditions) {
    $sql = sprintf(
      "DELETE FROM %s%s",
      $this->_connection->real_escape_string($table),
      $this->_getConditionsString(null, $conditions, $where = true)
    );

    if ($this->_connection->query($sql) === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    } else {
      $this->_last_affected_rows = $this->_connection->affected_rows;
    }
  }

  public function truncate($table) {
    $sql = sprintf(
      "TRUNCATE %s", $this->_connection->real_escape_string($table)
    );
    if ($this->_connection->query($sql) === false) {
      throw new \Exception(
        "Wrong SQL: $sql Error: ".$this->_connection->error
      );
    }
  }
}
