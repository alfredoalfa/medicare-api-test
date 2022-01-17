<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");

    require_once '../security/validate_token.php';
    include_once '../db_config/database.php';
    include_once '../model/clinic.php';
    include_once '../security/user_access.php';

    $bearerToken = getBearerToken(); 
    $isJwtValid = isJwtValid($bearerToken);

    if($isJwtValid) {
        $userAccess = new UserAccess();
        $userRole = $userAccess->getRole($bearerToken);

        if ($userRole == 2) { // ROLES 1 = patient, 2 = Doctor
                  

        $database = new Database();
        $db = $database->getConnection();
    
        $items = new Visit($db);
        $items->date = date('Y-m-d');
        $stmt = $items->getVisitsByDate();
        $itemCount = $stmt->rowCount();
    
            if($itemCount > 0){
                
                $visitArr = array();
                $visitArr["body"] = array();
                $visitArr["itemCount"] = $itemCount;
        
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        "id" => $id,
                        "patient_name" => $name_patient,
                        "patient_lastname" => $lastname_patient,
                        "name_doctor" => $name_doctor,
                        "lastname_doctor" => $lastname_doctor,
                        "specialization" => $specialization,
                        "status_visit" => $status_visit,
                        "today_visits" => $date,
                    );
        
                    array_push($visitArr["body"], $e);
                }
                echo json_encode($visitArr);
            }
        
            else {
                http_response_code(200);
                echo json_encode(
                    array("message" => "No record found.")
                );
            }

        } else {
            http_response_code(401);
            echo json_encode(
                array('error' => 'Doctors access only')
            );
        }

    } else {
        http_response_code(403);
        echo json_encode(
            array('error' => 'Invalid Token')
        );
    }
?>