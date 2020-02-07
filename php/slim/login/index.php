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

//login a customer
$app->post('/api/v1/students', function(Request $request, Response $response)
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

    $stmt = $conn->prepare("SELECT sic FROM student WHERE email = :username AND password = :password");
    
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $result = $stmt->fetch(); // set the resulting array to associative
        $sic = $result['sic'];
        $token = $jwt->jwttokenencryption($sic);
        $name = "jwttoken";
        $value = $token;
        setcookie($name, $value, time() + (86400 * 30), "/", true); // 86400 = 1 day
        return $response->withJson(["success"=>true, "data"=>$result, "token"=>$token]);

    } else {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(["success"=>false, "message"=>"credentials dosent match each other"]);
    }
    
});
$app->any('/tokens', function(Request $request, Response $response) {
    
    $jwt = new config\jwt();

    $vars = json_decode($request->getBody());
    
        
    $newresponse = $response->withStatus(400);
    $tokens= json_decode($jwt->jwttokendecryption($vars->token));
    return $newresponse->withJson(["verified"=>"NOT OKAY", "token"=>$tokens->verification]);
    // if($jwt->jwttokendncryption($vars->token)) {
    //     return $response->withJson(["verified"=>"OK"]);
    // }
    // $newresponse = $response->withStatus(400);
    // return $newresponse->withJson(["verified"=>"NOT OKAY"]);
});

$app->run();