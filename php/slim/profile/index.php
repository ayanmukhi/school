<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use config\connection as dbconnect;
use config as config;

require './../vendor/autoload.php';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

//default error handlers
$c = new \Slim\Container($configuration);               
$app = new \Slim\App($c);


$app->group('/api/v1/students', function () use ($app) {
    
        //get a record
        $app->get('/{id}', function(Request $request, Response $response, array $args)
        { 

            $jwt = new config\jwt();
            
            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars = substr($header[0],7);
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            }

            $sic = $args['id'];
            if (preg_match("/^\d+$/",$sic) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, 'message'=>'sic must be a number']);
            }

            if( $token->status != "A" and $token->sic != $sic ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"you cann't see other records"]);
            }


            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
             
            

            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $stmt = $conn->prepare("SELECT * FROM student WHERE sic = $sic");
            
            $stmt->execute();
            
            $hobbystmt = $conn->prepare("SELECT * FROM hobby WHERE sic = $sic");
            
            if ($stmt->rowCount() == 1) {  
                // set the resulting array to associative
                $result = $stmt->fetch(PDO::FETCH_ASSOC);  
                $result['dob'] = date("dS-M-Y", strtotime($result['dob']));

                if( $hobbystmt->execute()) {
                    $hobbies = [];
                    while($hobby = $hobbystmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($hobbies,$hobby['hobby_name']);
                    }
                    $result['hobby'] = $hobbies;
                }
                return $response->withJson(['status'=>200, 'result'=>$result]);
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>404, 'message'=>'no records exists with this id']);
            }       
        });


        //get all record
        $app->get('', function(Request $request, Response $response)
        { 

            $jwt = new config\jwt();

            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars = substr($header[0],7);
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            } 


            if( $token->status != "A") {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"only admin can see all records"]);
            }


            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $studentrecords = [];
            $hobbyrecords = [];
            $stmt = $conn->prepare("SELECT * FROM student");
            $stmt->execute();
            
            

            if ($stmt->rowCount() >= 1) {  
                while($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $record['dob'] = date("dS-M-Y", strtotime($record['dob']));
                    $record['hobby'] = [];
                    $hobbystmt = $conn->prepare("SELECT hobby_name FROM hobby WHERE sic = :sic");
                    $hobbystmt->bindParam(":sic", $record['sic']);
                    if($hobbystmt->execute()) {
                        while($hobbyname = $hobbystmt->fetch(PDO::FETCH_ASSOC)){
                            array_push($record['hobby'], $hobbyname['hobby_name']);
                        }
                    }
                    array_push($studentrecords, $record);
                }
                return $response->withJson(['success'=>true, 'data'=>$studentrecords]);
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['success'=>false, 'message'=>'no records exists']);
            }      
        });




        //delete a student record
        $app->delete('/{sic}', function(Request $request, Response $response, array $args) 
        { 

            $jwt = new config\jwt();

            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars = substr($header[0],7);
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            }

            $sic = $args['sic'];
            if (preg_match("/^\d+$/",$sic) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, 'message'=>'sic must be a number']);
            }

            if( $token->status != "A" and $token->sic != $sic ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"only admin can delete other records"]);
            }

            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            
            
            $sql = "DELETE FROM student WHERE sic = $sic";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $hobbysql = "DELETE FROM hobby WHERE sic = $sic";
            $hobbystmt = $conn->prepare($hobbysql);
            $hobbystmt->execute();

            if ($stmt->rowCount() == 1) {
                $newresponse = $response->withStatus(200);
                return $newresponse->withJson(['success'=>true]);
            } else {
                $newresponse =  $response->withStatus(404);
                return $newresponse->withJson(["success"=>false]);
            }
                
        });



        //insert a new student record
        $app->post('', function(Request $request, Response $response)
        {

            $dbobj = new dbconnect\dbconnection();
            $obj = new config\duplicate();
            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());


            $count = 0;
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 19) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate"]);
            }

            $fname = $vars->nameFirst;
            if( preg_match("/^[a-zA-Z]+$/", $fname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"first name is not valid"]);
            }

            $mname = $vars->nameSecond;
            if( preg_match("/^([a-zA-Z]*)$/", $mname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"middle name is not valid"]);
            }

            $tname = $vars->nameThird;
            if( preg_match("/^[a-zA-Z]+$/", $tname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"last name is not valid"]);
            }
            $namestr = "a";
            if( $mname != "") {
                $namestr = $fname." ".$mname." ".$tname;
            } else {
                $namestr = $fname." ".$tname;
            }

            $fatherName = $vars->fatherName;
            if( preg_match("/^[a-zA-Z][a-zA-Z\s]*$/", $fatherName) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"father name is not valid"]);
            }


            $motherName = $vars->motherName;
            if(preg_match("/^[a-zA-Z\s]*$/", $motherName) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"mother name is not valid"]);
            }


            $dob = $vars->date;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $diff->format('%y');
            if( $age > 20 or $age < 15) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"age must be between 15 - 20 years"]);
            }

            $gender = $vars->gender;
            if( preg_match("/^(male|female|other|)$/", $gender) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"gender is invalid"]);
            }



            $streetaddress = $vars->presentAddress;
            if( preg_match("/^([a-zA-Z0-9][a-zA-z0-9,;(\/)(\\)(\\\n)]*|)$/", $streetaddress) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"street name is not valid"]);
            }


            $matricboard = $vars->classX;
            if( preg_match("/^CBSE|ICSE|CHSE$/", $matricboard) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X board is not valid"]);
            }


            $matricroll = $vars->XRoll;
            if( preg_match("/^[a-zA-Z0-9][a-zA-Z0-9]*\/{0,1}[a-zA-Z0-9][a-zA-Z0-9]*$/", $matricroll) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X roll is not valid"]);
            }


            $matricperc = $vars->XPerc;
            if((($matricperc != "") and ( preg_match("/^[0-9]([0-9]{0,1})(((.){1}([0-9]+))|())$/", $matricperc) == false) or ( number_format($matricperc) > 101 ) or ( number_format($matricperc) < 0 )) ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X percentage is not valid", "value"=>number_format($matricperc)]);
            }

            $password = $vars->password;
            if(( preg_match("/(?=[a-z])/", $password) == false) or ( preg_match("/(?=[A-Z])/", $password) == false) or ( preg_match("/(?=[0-9])/", $password) == false) or ( strlen($password) < 3)) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"password is not valid"]);
            } 

            $email = $vars->email;
            if(preg_match("/[a-zA-Z0-9]+@([a-zA-z]+)/", $email) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"username is not valid"]);
            }
            if( $obj->checkemail($email) != -1) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"user with this email is already registered, use a different email"]);
            }

            $phone = $vars->phone;
            if( preg_match("/^[0-9]\d{9}$/", $phone) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"phone number must be of 10 digits"]);
            }
            if( $obj->checkphone($phone) != -1) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"user with this phone number is already registered, use a different phone number"]);
            }

            $statelist = ["NONE","WEST BENGAL","GUJRAT","ODISHA","GOA"];
            $state = $vars->state;
            if( in_array($state, $statelist) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"State is not in the list of options"]);
            }

            $districtlist = ["NONE","Alipurduar","Bankura","Birbhum","Ahmedabad","Amreli","Angul","Balangir","Ganjam","Khordha","North Goa","South Goa"];
            $district = $vars->subcategory;
            if( in_array($district, $districtlist) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"district is not in the list of options"]);
            }
           
            $status =$vars->status;


            $hobbylist = ["cricket", "football", "other"];
            $hobbies = $vars->hobby;
            foreach($hobbies as $val) {
                if( in_array($val,$hobbylist) == false) {
                    $newresponse = $response->withStatus(400);
                    return $newresponse->withJson(["success"=>false, "message"=>$val." is not in the hobby list"]);
                }
            }

            $stmt = $conn->prepare("INSERT INTO student (stu_name, gender, father_name, mother_name, dob, matric_board, matric_roll, matric_perc, password, state, district, street_address, phone, email,status)
            VALUES (:namestr, :gender, :fatherName, :motherName, :dob, :matric_board, :matric_roll, :matric_perc, :password, :state, :district, :street_address, :phone, :email, :status)");
            
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
            $stmt->bindParam(':status', $status);


            $stu_sic = 0;
            $stmt->execute();
            if ($stmt->rowCount() == 1) {

                //fetching the generated sic of newly created record
                $sicstmt = $conn->prepare("SELECT sic FROM student WHERE email = :email");
                $sicstmt->bindParam(':email', $email);
                $res = $sicstmt->execute();

                // $row = $sicstmt->setFetchMode(PDO::FETCH_ASSOC); // set the resulting array to associative
                $sicresult = $sicstmt->fetch(PDO::FETCH_ASSOC);
                $stu_sic = $sicresult["sic"];

                //pushing hobbies to hobby table if any
                if( sizeof($hobbies) > 0) {
                    for( $i = 0 ; $i < sizeof($hobbies) ; $i += 1 ) {
                        $hobby = $hobbies[$i];
                        $hobbystmt = $conn->prepare("INSERT INTO hobby (sic, hobby_name) VALUES ('$stu_sic', '$hobby')");
                        $hobbystmt->execute();
                    }
                }
                return $response->withJson(['success'=>true]);

            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['success'=>false, "stmt"=>$stmt]);
            }
            
        });


        //update a student record
        $app->put('', function(Request $request, Response $response) 
        { 

            $jwt = new config\jwt();

            $vars = json_decode($request->getBody());
            //echo "body".$vars;

            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars = substr($header[0],7);
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            }


            $obj = new config\duplicate(); //getting a instanse of duplicate class
            $dbobj = new dbconnect\dbconnection();
            $jwt = new config\jwt();

            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());
 

            $stu_sic = $vars->sic;
            if (preg_match("/^\d+$/",$stu_sic) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, 'message'=>'sic must be a number']);
            }

            if( $token->status != "A" and $token->sic != $stu_sic ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"you don't have privileges to make changes to other records"]);
            }


            $count = 0;
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 19) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate"]);
            }


            $fname = $vars->nameFirst;
            if( preg_match("/^[a-zA-Z]+$/", $fname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"first name is not valid"]);
            }

            $mname = $vars->nameSecond;
            if( preg_match("/^([a-zA-Z]*)$/", $mname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"middle name is not valid"]);
            }

            $tname = $vars->nameThird;
            if( preg_match("/^[a-zA-Z]+$/", $tname) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"last name is not valid"]);
            }
            $namestr = "a";
            if( $mname != "") {
                $namestr = $fname." ".$mname." ".$tname;
            } else {
                $namestr = $fname." ".$tname;
            }

            $fatherName = $vars->fatherName;
            if( preg_match("/^[a-zA-Z][a-zA-Z\s]*$/", $fatherName) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"father name is not valid"]);
            }
            $motherName = $vars->motherName;
            if( preg_match("/^[a-zA-Z][a-zA-Z\s]*$/", $motherName) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"mother name is not valid"]);
            }


            $dob = $vars->date;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $diff->format('%y');
            if( $age > 20 or $age < 15) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"age must be between 15 - 20 years"]);
            }

            $gender = $vars->gender;
            if( preg_match("/^(male|female|other|)$/", $gender) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"gender is invalid"]);
            }



            $streetaddress = $vars->presentAddress;
            if( preg_match("/^([a-zA-Z0-9][a-zA-z0-9,;(\/)(\\)(\\\n)]*|)$/", $streetaddress) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"street name is not valid"]);
            }


            $matricboard = $vars->classX;
            if( preg_match("/^CBSE|ICSE|CHSE$/", $matricboard) == false ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X board is not valid"]);
            }


            $matricroll = $vars->XRoll;
            if( preg_match("/^[a-zA-Z0-9][a-zA-Z0-9]*\/{0,1}[a-zA-Z0-9][a-zA-Z0-9]*$/", $matricroll) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X roll is not valid"]);
            }


            $matricperc = $vars->XPerc;
            if((($matricperc != "") and ( preg_match("/^[0-9]([0-9]{0,1})(((.){1}([0-9]+))|())$/", $matricperc) == false) or ( number_format($matricperc) > 101 ) or ( number_format($matricperc) < 0 )) ) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"class X percentage is not valid", "value"=>number_format($matricperc)]);
            }

            $password = $vars->password;
            if(( preg_match("/(?=[a-z])/", $password) == false) or ( preg_match("/(?=[A-Z])/", $password) == false) or ( preg_match("/(?=[0-9])/", $password) == false) or ( strlen($password) < 3)) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"password is not valid"]);
            } 
            
            $email = $vars->email;
            if(preg_match("/[a-zA-Z0-9]+@([a-zA-z]+)/", $email) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"username is not valid"]);
            }
            if(($obj->checkemail($email) != -1) and ($obj->checkemail($email) != $stu_sic)) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"record already exist with this email, kindly use a different email register"]);
            }

            $phone = $vars->phone;
            if( preg_match("/^[0-9]\d{9}$/", $phone) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"phone number must be of 10 digits"]);
            }
            if(($obj->checkphone($phone) != -1) and ($obj->checkphone($phone) != $stu_sic)) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"user with this phone number is already registered, use a different phone number"]);
            }

            
            $statelist = ["NONE","WEST BENGAL","GUJRAT","ODISHA","GOA"];
            $state = $vars->state;
            if( in_array($state, $statelist) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"State is not in the list of options"]);
            }

            $districtlist = ["NONE","Alipurduar","Bankura","Birbhum","Ahmedabad","Amreli","Angul","Balangir","Ganjam","Khordha","North Goa","South Goa"];
            $district = $vars->subcategory;
            if( in_array($district, $districtlist) == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["success"=>false, "message"=>"district is not in the list of options"]);
            }

            

            $hobbylist = ["cricket", "football", "other"];
            $hobbies = $vars->hobby;
            foreach($hobbies as $val) {
                if( in_array($val,$hobbylist) == false) {
                    $newresponse = $response->withStatus(400);
                    return $newresponse->withJson(["success"=>false, "message"=>$val." is not in the hobby list"]);
                }
            }
            

            $stmt = $conn->prepare("UPDATE student SET stu_name = :namestr, gender = :gender, father_name = :fatherName, mother_name = :motherName, dob = :dob, matric_board = :matric_board, matric_roll = :matric_roll, matric_perc = :matric_perc, password = :password, state = :state, district = :district, street_address = :street_address, phone = :phone, email = :email, status = :status WHERE sic = :sic");
            
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
            $stmt->bindParam(':sic',$stu_sic);
            $stmt->bindParam(':status', $vars->status);

            if ($stmt->execute()) {
                $hobbydel = "DELETE FROM hobby WHERE sic = $stu_sic";
                $hobbydelstmt = $conn->prepare($hobbydel);
                $hobbydelstmt->execute();

                //pushing hobbies to hobby table if any
                if( sizeof($hobbies) > 0) {
                    for( $i = 0 ; $i < sizeof($hobbies) ; $i += 1 ) {
                        $hobby = $hobbies[$i];
                        $hobbystmt = $conn->prepare("INSERT INTO hobby (sic, hobby_name) VALUES ('$stu_sic', '$hobby')");
                        $hobbystmt->execute();
                    }
                }

                $newresponse = $response->withStatus(200);
                return $newresponse->withJson(['success'=>true, "message"=>'record is successfully updated']);

            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['success'=>false, "message"=>"record with this sic doesnot exists"]);
            }
        });
});

$app->post('/api/v1/login', function(Request $request, Response $response)
{
    $jwt = new config\jwt();
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $vars = json_decode($request->getBody());
    if( array_key_exists('username', $vars) == false || $vars->username == null) {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(['success'=>false, 'message'=>'username is required ']);
    }
    if( array_key_exists('password', $vars) == false || $vars->password == null) {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(['success'=>false, 'message'=>'password is required']);
    }
    $username = $vars->username;
    $password = $vars->password;

    if(preg_match("/[a-zA-Z0-9]+@([a-zA-z]+)/", $username) == false) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["success"=>false, "message"=>"username is not valid"]);
    }

    if(( preg_match("/(?=[a-z])/", $password) == false) or ( preg_match("/(?=[A-Z])/", $password) == false) or ( preg_match("/(?=[0-9])/", $password) == false) or ( strlen($password) < 3)) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["success"=>false, "message"=>"password is not valid"]);
    } 

    $stmt = $conn->prepare("SELECT sic, status FROM student WHERE email = :username AND password = :password");
    
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // set the resulting array to associative
        $sic = $result['sic'];
        $status = $result['status'];
        $token = $jwt->jwttokenencryption($sic, $status);
        $name = "jwttoken";
        $value = $token;
        //  setcookie($name, $value, time() + (86400 * 30), "/", true); // 86400 = 1 day
        return $response->withJson(["success"=>true, "data"=>$result, "token"=>$token]);

    } else {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(["success"=>false, "message"=>"credentials dosent match each other"]);
    }
    
});

    

$app->run();