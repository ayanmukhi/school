<?php
namespace config;

use config\connection as dbconnect;
use PDO;

class duplicate 
{

    public function checkemail($email) 
    {
        $dbobj = new dbconnect\dbconnection();
        $conn = $dbobj->connect();

        $stmt = $conn->prepare("SELECT sic FROM student WHERE email = :email");
        $stmt->bindParam(':email', $email);
        
        if($stmt->execute() and $stmt->rowCount() == 1) {
            $sicresult = $stmt->fetch();
            return $sicresult["sic"];
        } else {
            return -1;
        }
    }
    public function checkphone($phone) 
    {
        $dbobj = new dbconnect\dbconnection();
        $conn = $dbobj->connect();

        $stmt = $conn->prepare("SELECT sic FROM student WHERE phone = :phone");
        $stmt->bindParam(':phone', $phone);
        
        if($stmt->execute() and $stmt->rowCount() == 1) {
            $sicresult = $stmt->fetch();
            return $sicresult['sic'];
            
        } else {
            return -1;
        }
    }
}
  
?>