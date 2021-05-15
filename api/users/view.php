<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();


$users = new Users($db);
// get id of user
$data = json_decode(file_get_contents("php://input"));

$users->id = $data->id;
// read the details of user
$users->readOne();

if($users->name!=null){
    // create array
    $users_arr = array(
        "id" =>  $users->id,
        "name" => $users->name,
        "email" => $users->email,
        "birthday" => $users->birthday,
        "gender" => $users->gender,
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($users_arr);
}

else{
    http_response_code(404);
    echo json_encode(array("message" => "User does not exist."));
}
?>