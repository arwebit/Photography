<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\Admin;

if (empty($_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $dataCred = json_decode(file_get_contents("php://input"));

        $userName = trim(removeHTMLEntities($dataCred->Username));
        $password = trim(removeHTMLEntities($dataCred->Password));

        if (empty($userName)) {
            $userNameErr = "Required";
        }
        if (empty($password)) {
            $passwordErr = "Required";
        } else {
            $password = encryptDecrypt("encrypt", $password, SECRET_KEY, SECRET_IV);
            $loginCred = array("Username" => $userName, "Password" => $password);
            $checkAccess = Admin::loginAccess(json_encode($loginCred));
            if ($checkAccess == 0) {
                $loginErr = "Username or pasword is wrong";
            } else {
                $userActive = Admin::loginActive(json_encode($loginCred));
                if ($userActive == 0) {
                    $loginErr = "Your account is locked or deactivated";
                } else {
                    $loginErr = "";
                }
            }
        }

        if (($userNameErr == "") && ($passwordErr == "") && ($loginErr == "")) {
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $payload = array('username' => $userName, 'exp' => (time() + JWT_TOKEN_TIME));
            $token = generateJWTToken($headers, $payload, JWT_SECRET_KEY);


            $userCred = array("Username" => $userName);
            $details = Admin::getSelectedUser(json_encode($userCred));
            $getDetails = $details['Data'];

            $successData = array("Token" => $token, "User_details" => $getDetails);
            http_response_code(201);
            $response['statusCode'] = 201;
            $response['message'] = "Successfully logged in";
            $response['data'] = $successData;
        } else {
            http_response_code(400);
            $dataErrs = array("Username" => $userNameErr, "Password" => $passwordErr, "Login" => $loginErr);
            $response['statusCode'] = 400;
            $response['message'] = "Login unsuccessful";
            $response['error'] = $dataErrs;
        }
    } else {
        http_response_code(405);
        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
} else {
    http_response_code(403);
    $response['statusCode'] = 403;
    $response['message'] = "Forbidden";
    $response['error'] = "URL link error";
}

echo json_encode($response, JSON_PRETTY_PRINT);
