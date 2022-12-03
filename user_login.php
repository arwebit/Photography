<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\User;

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
            $checkAccess = User::loginAccess(json_encode($loginCred));
            if ($checkAccess == 0) {
                $loginErr = "Username or pasword is wrong";
            } else {
                $userActive = User::loginActive(json_encode($loginCred));
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
            $successData = array("Username" => $userName, "Token" => $token);
            http_response_code(200);
            $response['statusCode'] = 200;
            $response['message'] = "Records found";
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
