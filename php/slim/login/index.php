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

    
    
//display a record
$app->get('/api/v1/customer/{id}', function(Request $request, Response $response, array $args)
{ 
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $id = $args['id'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = $id");
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  

        return $response->withJson(['status'=>200, 'data'=>$result]);
        
    } else {
        return $response->withJson(['status'=>404]);
    }

        
});


//display all records
$app->get('/api/v1/customer', function(Request $request, Response $response) 
{ 
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $stmt = $conn->prepare("SELECT * FROM customers");
    $stmt->execute();
    if ($stmt->rowCount() != 0) {
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response->withJson(['status'=>200, 'data'=>$result]);
    } else {
        return $response->withJson(['status'=>404]);
    }
});




//delete a customer record
$app->delete('/api/v1/customer', function(Request $request, Response $response) 
{ 
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $vars = $request->getParams();
    $id = $vars['id'];

    $sql = "DELETE FROM customers WHERE id = $id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "record is delete";
    } else {
        echo "record deletion failed";
    }
        
});


// //delete all records
// $app->delete('/api/v1/customer', function(Request $request, Response $response) 
// { 
//     $dbobj = new dbconnect\dbconnection();
//     $conn = $dbobj->connect();
//     $sql = "DELETE FROM customers";
//     $stmt = $conn->prepare($sql);
        
//     if ($stmt->execute()) {
//         echo "all records are lost";
//     } else {
//         echo "deletion of all records unsuccessful";
//     }
    
// });


//login a customer
$app->post('/api/v1/customer', function(Request $request, Response $response)
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
        return $response->withJson(['status'=>404]);
    }
    
});


//update a customer
$app->put('/api/v1/customer', function(Request $request, Response $response) 
{ 
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $vars = $request->getParams();
        

    $sql = "UPDATE customers SET username=:username, password=:password, phone=:phone WHERE id=:id";
        
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $first_name);
    $stmt->bindParam(':password', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':id', $id);

    $first_name = $vars['username'];
    $last_name = $vars['password'];
    $phone = $vars['phone'];
    $id = $vars['id'];
        
    $stmt->execute();
        
    if ($stmt->rowCount() == 1) {
        echo "update successful";
    } else {
        echo "update unsuccessful";
    }
   
});



$app->run();