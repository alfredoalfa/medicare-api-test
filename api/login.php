<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once '../security/validate_token.php';
include_once '../db_config/database.php';
include_once '../model/user.php';

$database = new Database();
$db = $database->getConnection();

$items = new User($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$data = json_decode(file_get_contents("php://input", true));

    $items->username = $data->username;
    $items->password = $data->password;

    $stmt = $items->getUSerLogin();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row < 1) {
        http_response_code(404);
        echo json_encode(
            array('error' => 'Invalid User')
        );
	} else {
        $user = array ('id'=>$row['id'],'username'=>$row['username'], 'role'=>$row['id_user_role']);
		$headers = array('alg'=>'HS256','typ'=>'JWT');
		$payload = array('user'=>$user, 'exp'=>(time() + 10800)); // 3 hours

		$jwt = generateJwt($headers, $payload);
		echo json_encode(array('token' => $jwt));
    }
}
