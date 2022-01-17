<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once '../security/validate_token.php';
    include_once '../db_config/database.php';
    include_once '../model/clinic.php';
    include_once '../security/user_access.php';
    include_once '../model/user.php';
    
    $bearerToken = getBearerToken(); 
    $isJwtValid = isJwtValid($bearerToken);

    if ($isJwtValid) {
        $userAccess = new UserAccess();
        $userRole = $userAccess->getRole($bearerToken);

        if ($userRole == 2) {  // ROLES 1 = patient, 2 = Doctor
            $userId = $userAccess->getLoggeUser($bearerToken);

            $database = new Database();
            $db = $database->getConnection();

            $item = new Visit($db);
           
            $data = json_decode(file_get_contents("php://input"));

            $item->id = $data->id;
            $item->idPatient = $data->id_patient;
            $item->idDoctor = $data->id_doctor;
            $item->specialization = $data->specialization;
            $item->statusVisit = $data->id_status_visit;
            $item->date = $data->today_visits;

            if($item->createVisit()){
                http_response_code(200);
                echo 'The visit status updated successfully.';
            } else{
                http_response_code(200);
                echo 'The visit status could not be updated.';
            }

  
        } else {
            http_response_code(401);
            echo json_encode(
                array('error' => 'Patients access only')
            );
        }
        

    } else {
        http_response_code(403);
        echo json_encode(
            array('error' => 'Invalid Token')
        );
    }
?>