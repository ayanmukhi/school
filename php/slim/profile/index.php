<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use config\connection as dbconnect;

require './../vendor/autoload.php';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

//default error handlers
$c = new \Slim\Container($configuration);               
$app = new \Slim\App($c);

$app->group('/api/v1/login/students', function () use ($app) {

        //login a customer
    $app->post('', function(Request $request, Response $response)
    {
        $dbobj = new dbconnect\dbconnection();
        $conn = $dbobj->connect();
        $vars = json_decode(file_get_contents('php://input'));
        $username = $vars[0]->value;
        $password = $vars[1]->value;
        $stmt = $conn->prepare("SELECT * FROM student WHERE email = :username AND password = :password");
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // set the resulting array to associative
            session_start();
            $_SESSION['user'] = $result[0]['stu_name'];
            $_SESSION['sic'] = $result[0]['sic'];
            return $response->withJson(['status'=>200, 'data'=>$result]);

        } else {
            $newresponse = $response->withStatus(401);
            return $newresponse->withJson(['status'=>401]);
        }
        
    });

});

$app->group('/api/v1/profile/students', function () use ($app) {
    
        //display a record
        $app->get('/{id}', function(Request $request, Response $response, array $args)
        { 
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $sic = $args['id'];

            $stmt = $conn->prepare("SELECT * FROM student WHERE sic = $sic");
            $stmt->execute();
            
            $hobbystmt = $conn->prepare("SELECT * FROM hobby WHERE sic = $sic");
            
            if ($stmt->rowCount() == 1) {  
                // set the resulting array to associative
                $result = $stmt->fetch();  
                $dob = date("dS-M-Y", strtotime($result['dob']));

                if( $hobbystmt->execute()) {
                    $hobbies = [];
                    while($hobby = $hobbystmt->fetch()){
                        array_push($hobbies,$hobby);
                    }
                    return $response->withJson(['status'=>200, 'result'=>$result, 'hobby'=>$hobbies, 'date'=>$dob]);
                } else {
                    return $response->withJson(['status'=>200, 'result'=>$result, 'date'=>$dob]);
                }
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404]);
            }       
        });


        //display all record
        $app->get('', function(Request $request, Response $response)
        { 
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $studentrecords = [];
            $hobbyrecords = [];
            $stmt = $conn->prepare("SELECT * FROM student");
            $stmt->execute();
            
            $hobbystmt = $conn->prepare("SELECT * FROM hobby");
            


            if ($stmt->rowCount() >= 1) {  
                while($record = $stmt->fetch()) {
                    array_push($studentrecords, $record);
                }

                if( $hobbystmt->execute()) {
                    while($hobby = $hobbystmt->fetch()){
                        array_push($hobbyrecords,$hobby);
                    }
                    return $response->withJson(['status'=>200, 'result'=>$studentrecords, 'hobby'=>$hobbyrecords]);
                } else {
                    return $response->withJson(['status'=>200, 'result'=>$studentrecords]);
                }
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404]);
            }      
        });




        //delete a customer record
        $app->delete('', function(Request $request, Response $response) 
        { 
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            session_start();
            $sic = $_SESSION['sic'];

            
            $sql = "DELETE FROM student WHERE sic = $sic";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $hobbysql = "DELETE FROM hobby WHERE sic = $sic";
            $hobbystmt = $conn->prepare($hobbysql);
            $hobbystmt->execute();

            if (($stmt->rowCount() == 1 ) and ($hobbystmt->rowCount() == 1)) {
                session_destroy();
                return $response->withJson(['status'=>200]);
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404]);
            }
                
        });



        //insert a new customer record
        $app->post('', function(Request $request, Response $response)
        {
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $vars = json_decode(file_get_contents('php://input'));
            $index = 0;


            $fname = $vars[$index++]->value;
            $mname = $vars[$index++]->value;
            $tname = $vars[$index++]->value;
            $namestr = "a";
            if( $mname != "") {
                $namestr = $fname." ".$mname." ".$tname;
            }
            else {
                $namestr = $fname." ".$tname;
            }

            $fatherName = $vars[$index++]->value;
            $motherName = $vars[$index++]->value;
            $dob = $vars[$index++]->value;

            $gender = "";
            if($vars[6]->name == 'customRadioInline1'){
                $gender = $vars[$index++]->value;
            } else {
                $gender = "";
            }
            
            $streetaddress = $vars[$index++]->value;
            $matricboard = $vars[$index++]->value;
            $matricroll = $vars[$index++]->value;
            $matricperc = $vars[$index++]->value;
            $password = $vars[$index++]->value;
            $email = $vars[$index++]->value;
            $state = $vars[$index++]->value;
            $district = $vars[$index++]->value;
            $phone = $vars[$index++]->value;
            $hobbies = [];
            for( $i = $index; $i < sizeof($vars) ; $i++) {
                if($vars[$i]->name == 'hobby') {
                    array_push($hobbies, $vars[$i]->value);
                }
            }
            $stmt = $conn->prepare("INSERT INTO student (stu_name, gender, father_name, mother_name, dob, matric_board, matric_roll, matric_perc, password, state, district, street_address, phone, email)
            VALUES (:namestr, :gender, :fatherName, :motherName, :dob, :matric_board, :matric_roll, :matric_perc, :password, :state, :district, :street_address, :phone, :email)");
            
            $stmt->bindParam(':namestr', $namestr);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':fatherName', $fatherName);
            $stmt->bindParam(':motherName', $motherName);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':matric_board', $matricboard);
            $stmt->bindParam(':matric_roll', $matricroll);
            $stmt->bindParam(':matric_perc', $matricperc);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':district', $district);
            $stmt->bindParam(':street_address', $streetaddress);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);


            $stu_sic = 0;
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                //fetching the generated sic of newly created record
                $sicstmt = $conn->prepare("SELECT sic FROM student WHERE email = :email");
                $sicstmt->bindParam(':email', $email);
                $res = $sicstmt->execute();
                // $row = $sicstmt->setFetchMode(PDO::FETCH_ASSOC); // set the resulting array to associative
                $sicresult = $sicstmt->fetch();
                $stu_sic = $sicresult["sic"];

                //pushing hobbies to hobby table if any
                if( sizeof($hobbies) > 0) {
                    for( $i = 0 ; $i < sizeof($hobbies) ; $i += 1 ) {
                        $hobby = $hobbies[$i];
                        $hobbystmt = $conn->prepare("INSERT INTO hobby (sic, hobby_name) VALUES ('$stu_sic', '$hobby')");
                        $hobbystmt->execute();
                    }
                }

                //start the user session
                session_start();
                $_SESSION['user'] = $namestr;
                $_SESSION['sic'] = $stu_sic;
                return $response->withJson(['status'=>200]);

            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404]);
            }
            
        });


        //update a customer
        $app->put('', function(Request $request, Response $response) 
        { 
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $vars = json_decode(file_get_contents('php://input'));
            $index = 0;


            $fname = $vars[$index++]->value;
            $mname = $vars[$index++]->value;
            $tname = $vars[$index++]->value;
            $namestr = "a";
            if( $mname != "") {
                $namestr = $fname." ".$mname." ".$tname;
            }
            else {
                $namestr = $fname." ".$tname;
            }

            $fatherName = $vars[$index++]->value;
            $motherName = $vars[$index++]->value;
            $dob = $vars[$index++]->value;

            $gender = "";
            if($vars[6]->name == 'customRadioInline1'){
                $gender = $vars[$index++]->value;
            } else {
                $gender = "";
            }
            
            $streetaddress = $vars[$index++]->value;
            $matricboard = $vars[$index++]->value;
            $matricroll = $vars[$index++]->value;
            $matricperc = $vars[$index++]->value;
            $password = $vars[$index++]->value;
            $email = $vars[$index++]->value;
            $state = $vars[$index++]->value;
            $district = $vars[$index++]->value;
            $phone = $vars[$index++]->value;
            

            $stu_sic = $vars[sizeof($vars) - 1]->value;

            $stmt = $conn->prepare("UPDATE student SET stu_name = :namestr, gender = :gender, father_name = :fatherName, mother_name = :motherName, dob = :dob, matric_board = :matric_board, matric_roll = :matric_roll, matric_perc = :matric_perc, password = :password, state = :state, district = :district, street_address = :street_address, phone = :phone, email = :email WHERE sic = $stu_sic");
            
            $stmt->bindParam(':namestr', $namestr);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':fatherName', $fatherName);
            $stmt->bindParam(':motherName', $motherName);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':matric_board', $matricboard);
            $stmt->bindParam(':matric_roll', $matricroll);
            $stmt->bindParam(':matric_perc', $matricperc);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':district', $district);
            $stmt->bindParam(':street_address', $streetaddress);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);


            
            
            if ($stmt->execute()) {

                $hobbydel = "DELETE FROM hobby WHERE sic = $stu_sic";
                $hobbydelstmt = $conn->prepare($hobbydel);
                $hobbydelstmt->execute();

                $hobbies = [];
                for( $i = $index; $i < sizeof($vars) ; $i++) {
                    if($vars[$i]->name == 'hobby[]') {
                        array_push($hobbies, $vars[$i]->value);
                    }
                }

                //pushing hobbies to hobby table if any
                if( sizeof($hobbies) > 0) {
                    for( $i = 0 ; $i < sizeof($hobbies) ; $i += 1 ) {
                        $hobby = $hobbies[$i];
                        $hobbystmt = $conn->prepare("INSERT INTO hobby (sic, hobby_name) VALUES ('$stu_sic', '$hobby')");
                        $hobbystmt->execute();
                    }
                }
                return $response->withJson(['status'=>200, 'lenght'=>sizeof($hobbies)]);

            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404]);
            }
        });
});




$app->run();