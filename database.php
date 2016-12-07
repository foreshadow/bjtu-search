<?php

class Database {

    protected $mysqli;
    protected $statement;

    function __construct() {
        $this->mysqli = new mysqli('localhost', 'root', '9602252335', 'pdsp3');
        if (mysqli_connect_errno()) {
            throw new Exception('Connect failed: ' . mysqli_connect_error());
        }
    }

    function __destruct() {
        if ($this->mysqli) {
            if ($this->statement) {
                $this->statement->close();
            }
            $this->mysqli->close();
        }
    }

    function prepare($query) {
        if ($this->statement) {
            $this->statement->close();
        }
        $this->statement = $this->mysqli->prepare($query);
        if (!$this->statement) {
            throw new Exception('Prepare failed: ' . $this->mysqli->error);
        }
        return $this->statement;
    }
}

?>
