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
        $firstName = trim(removeHTMLEntities($dataCred->First_name));
        $middleName = trim(removeHTMLEntities($dataCred->Middle_name));
        $lastName = trim(removeHTMLEntities($dataCred->Last_name));
        $mobile = trim(removeHTMLEntities($dataCred->Mobile));
        $email = trim(removeHTMLEntities($dataCred->Email));
        $address = trim(removeHTMLEntities($dataCred->Address));
        $dob = trim(removeHTMLEntities($dataCred->DOB));

        $userID = date("YmdHis");

        if (empty($userName)) {
            $userNameErr = "Required";
        } else {
            $userNameCred = array("Username" => $userName);
            $getCountRecord = User::getDupUser(json_encode($userNameCred));
            if ($getCountRecord > 0) {
                $userNameErr = "Duplicate username. Try again";
            }
        }
        if (empty($password)) {
            $passwordErr = "Required";
        } else {
            $password = encryptDecrypt("encrypt", $password, SECRET_KEY, SECRET_IV);
        }

        if (empty($firstName)) {
            $firstNameErr = "Required";
        } else {
            if (!textPatternValidation($firstName, "a-zA-Z ")) {
                $firstNameErr = "Only letters and white space allowed";
            }
        }

        if (empty($middleName)) {
            $middleName = "";
        } else {
            if (!textPatternValidation($middleName, "a-zA-Z ")) {
                $middleNameErr = "Only letters and white space allowed";
            }
        }

        if (empty($lastName)) {
            $lastNameErr = "Required";
        } else {
            if (!textPatternValidation($lastName, "a-zA-Z ")) {
                $lastNameErr = "Only letters and white space allowed";
            }
        }


        if (empty($mobile)) {
            $mobile = "0";
        } else {
            if (!textPatternValidation($mobile, "0-9")) {
                $mobileErr = "Only numeric allowed";
            } else {
                if (strlen($mobile) != 10) {
                    $mobileErr = "Mobile no. must be 10 digits";
                } else {
                    $mobileCred = array("Mobile_no" => $mobile, "HMobile_no" => "");
                    $getCountRecord = User::getDupMobile(json_encode($mobileCred));
                    if ($getCountRecord > 0) {
                        $mobileErr = "Duplicate number. Try again";
                    }
                }
            }
        }
        if (empty($email)) {
            $emailErr = "Required";
        } else {
            if (!emailValidation($email)) {
                $emailErr = "Invalid Email";
            } else {
                $emailCred = array("Email_id" => $email, "HEmail_id" => "");
                $getCountRecord = User::getDupEmail(json_encode($emailCred));
                if ($getCountRecord > 0) {
                    $emailErr = "Duplicate email. Try again";
                }
            }
        }

        if (empty($address)) {
            $address = "";
        }

        if (empty($dob)) {
            $dobErr = "Required";
        } else {
            $dob = date("Y-m-d", strtotime($dob));
        }

        if (($userNameErr == "") && ($passwordErr == "") && ($firstNameErr == "") && ($middleNameErr == "") && ($lastNameErr == "") &&
            ($mobileErr == "") && ($emailErr == "")  && ($dobErr == "")
        ) {
            $insertCredentials = array(
                "User_id" => $userID, "Username" => $userName, "Password" => $password, "First_name" => $firstName,
                "Middle_name" => $middleName, "Last_name" => $lastName, "Email" => $email, "Mobile" => $mobile,
                "DOB" => $dob, "Address" => $address
            );
            $insertStatus = User::createUser(json_encode($insertCredentials));

            if ($insertStatus > 0) {
                http_response_code(201);
                //emailSending("ArWeb", "arwebcs992@gmail.com", $email, $subject, $emailMsg, $certificateAttach);
                $response['statusCode'] = 201;
                $response['success'] = "Successfully registered";
            } else {
                http_response_code(500);
                $response['statusCode'] = 500;
                $response['message'] = "Server error";
                $response['error'] = "Failed to insert";
            }
        } else {
            http_response_code(400);
            $dataErrs = array(
                "Username" => $userNameErr, "Password" => $passwordErr, "First_name" => $firstNameErr,
                "Middle_name" => $middleNameErr, "Last_name" => $lastNameErr, "Email" => $emailErr,
                "Mobile" => $mobileErr, "DOB" => $dobErr
            );
            $response['statusCode'] = 400;
            $response['message'] = "Bad Request";
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
