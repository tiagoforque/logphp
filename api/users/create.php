<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();

$users = new Users($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->birthday) &&
    !empty($data->gender)
){


    $users->name = $data->name;
    $users->email = $data->email;
    $users->birthday = $data->birthday;
    $users->gender = $data->gender;

    $err = "";
    $timestamp = strtotime($data->birthday);
    if(!$timestamp){
        $err .="Birthdate is not a valid date. ";
    }
    $query = "SELECT id FROM genders WHERE `id`='$data->gender'";

    // query
    $stmt = $db->prepare($query);
    $stmt->execute();
    if(!$stmt->fetchAll()){
        $err .="Gender id is not valid. ";
    }
    if($err==""){
        // create the user
        if($users->create()){

            http_response_code(201);
            echo json_encode(array("message" => "User was created."));
        }

        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create user."));
        }
    }else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user.$err"));
    }
}

else{

    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>