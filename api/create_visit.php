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

        if ($userRole == 1) {  // ROLES 1 = patient, 2 = Doctor
            $userId = $userAccess->getLoggeUser($bearerToken);
            $statusVisit = '3'; // status

            $database = new Database();
            $db = $database->getConnection();

            $item = new Visit($db);
            $patient = new User($db);

            $patient->id = $userId;

            $stmt = $patient->getPatient();

            $patientResult = $stmt->fetch(PDO::FETCH_ASSOC);
        
            $data = json_decode(file_get_contents("php://input"));

            $item->idPatient = $patientResult['id'];
            $item->idDoctor = $data->id_doctor;
            $item->specialization = $data->specialization;
            $item->statusVisit = $statusVisit;
            $item->date = $data->date;


            if($item->createVisit()){
                http_response_code(200);
                echo 'Your Medic Visit was created successfully.';
            } else{
                http_response_code(200);
                echo 'Your Medic Visit could not be created.';
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