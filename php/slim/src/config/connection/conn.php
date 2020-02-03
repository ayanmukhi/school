<?php
namespace config\connection;

use PDO;

class dbconnection {

    public function connect() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "schoolname";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        return $conn;
    }
  }
  
?>