<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database and user object
include_once '../config/database.php';
include_once '../objects/users.php';

// instantiate and initialize
$database = new Database();
$db = $database->getConnection();
$users = new Users($db);

// Search users
$stmt = $users->read();
$num = $stmt->rowCount();

// check if any
if($num>0){

    // users array
    $users_arr=array();
    $users_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $user_item=array(
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "birthday" => $birthday,
            "gender" => $gender
        );

        array_push($users_arr["records"], $user_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    echo json_encode($users_arr);
}else{
    
    http_response_code(404);
    echo json_encode(
        array("message" => "No users found.")
    );
}
