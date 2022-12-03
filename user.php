<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\User;

if ($authenticationToken) {
    if ($validToken) {
        if (array_key_exists("username", $_GET)) {

            $userName = trim($_GET['username']);
            $requestParams = array("Username" => $userName);
            $details = User::getSelectedUser(json_encode($requestParams));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];

            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                if ($getCountRecord > 0) {
                    http_response_code(200);
                    $response['statusCode'] = 200;
                    $response['data'] = $getDetails;
                } elseif ($getCountRecord == 0) {
                    http_response_code(205);
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found";
                } else {
                    http_response_code(500);
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
                if ($getCountRecord > 0) {
                    $dataCred = json_decode(file_get_contents("php://input"));

                    $firstName = trim(removeHTMLEntities($dataCred->First_name));
                    $middleName = trim(removeHTMLEntities($dataCred->Middle_name));
                    $lastName = trim(removeHTMLEntities($dataCred->Last_name));
                    $mobile = trim(removeHTMLEntities($dataCred->Mobile));
                    $hmobile = trim(removeHTMLEntities($dataCred->HMobile));
                    $email = trim(removeHTMLEntities($dataCred->Email));
                    $hemail = trim(removeHTMLEntities($dataCred->HEmail));
                    $address = trim(removeHTMLEntities($dataCred->Address));
                    $dob = trim(removeHTMLEntities($dataCred->DOB));

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
                                $mobileCred = array("Mobile_no" => $mobile, "HMobile_no" => $hmobile);
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
                            $emailCred = array("Email_id" => $email, "HEmail_id" => $hemail);
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

                    if (($firstNameErr == "") && ($middleNameErr == "") && ($lastNameErr == "") && ($mobileErr == "") &&
                        ($emailErr == "")  && ($dobErr == "")
                    ) {
                        $updateCredentials = array(
                            "Username" => $userName, "First_name" => $firstName,  "Middle_name" => $middleName, "Last_name" => $lastName,
                            "Email" => $email, "Mobile" => $mobile, "DOB" => $dob, "Address" => $address
                        );
                        $updateStatus = User::updateUser(json_encode($updateCredentials));

                        if ($updateStatus > 0) {
                            http_response_code(201);
                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully updated";
                        } else {
                            http_response_code(500);
                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to update";
                        }
                    } else {
                        http_response_code(400);
                        $dataErrs = array(
                            "Username" => $userNameErr, "First_name" => $firstNameErr, "Middle_name" => $middleNameErr,
                            "Last_name" => $lastNameErr, "Email" => $emailErr, "Mobile" => $mobileErr, "DOB" => $dobErr
                        );
                        $response['statusCode'] = 400;
                        $response['message'] = "Bad Request";
                        $response['error'] = $dataErrs;
                    }
                } elseif ($getCountRecord == 0) {
                    http_response_code(205);
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to update";
                } else {
                    http_response_code(500);
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === "PUT") {
                if ($getCountRecord > 0) {
                    $dataCred = json_decode(file_get_contents("php://input"));

                    $status = trim(removeHTMLEntities($dataCred->Status));

                    if (empty($status) && $status != 0) {
                        $statusErr = "Required";
                    }

                    if ($statusErr == "") {
                        $updateCredentials = array("Username" => $userName, "Status" => $status);
                        $updateStatus = User::userStatusUpdate(json_encode($updateCredentials));

                        if ($updateStatus > 0) {
                            http_response_code(201);
                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully changed";
                        } else {
                            http_response_code(500);
                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to changed";
                        }
                    } else {
                        http_response_code(400);
                        $dataErrs = array("Username" => $userNameErr, "Status" => $statusErr);
                        $response['statusCode'] = 400;
                        $response['message'] = "Bad Request";
                        $response['error'] = $dataErrs;
                    }
                } elseif ($getCountRecord == 0) {
                    http_response_code(205);
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to change";
                } else {
                    http_response_code(500);
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } else {
                http_response_code(405);
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } elseif (array_key_exists("page", $_GET) && array_key_exists("records", $_GET)) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                $pageNo = trim($_GET['page']);
                $records = trim($_GET['records']);
                if (empty($pageNo) && $pageNo != 0) {
                    $pageNoErr = "Required";
                }
                if (empty($records) && $records != 0) {
                    $recordsErr = "Required";
                } else {
                    $limitFrom = ($pageNo - 1) * $records;
                }

                if ($recordsErr == "" && $pageNoErr == "") {
                    $credential = array("StartIndex" => $limitFrom, "RecordsToBeShown" => $records);
                    $details = User::getAllUsers(json_encode($credential));
                    $getCountRecord = $details['Record'];
                    $getDetails = $details['Data'];
                    if ($getCountRecord > 0) {
                        http_response_code(200);
                        $response['statusCode'] = 200;
                        $response['records'] = $getCountRecord;
                        $response['data'] = $getDetails;
                    } elseif ($getCountRecord == 0) {
                        http_response_code(205);
                        $response['statusCode'] = 205;
                        $response['message'] = "No Content";
                        $response['error'] = "No record found";
                    } else {
                        http_response_code(500);
                        $response['statusCode'] = 500;
                        $response['message'] = "Internal Server Error";
                        $response['error'] = "Server error";
                    }
                } else {
                    http_response_code(400);
                    $dataErrs = array("Records" => $recordsErr, "PageNo" => $pageNoErr, "Message" => "Recorrect error");
                    $response['statusCode'] = 400;
                    $response['message'] = "Bad Request";
                    $response['error'] = $dataErrs;
                }
            } else {
                http_response_code(405);
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } elseif (empty($_GET)) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                $details = User::getAllUsers();
                $getCountRecord = $details['Record'];
                $getDetails = $details['Data'];
                if ($getCountRecord > 0) {
                    http_response_code(200);
                    $response['statusCode'] = 200;
                    $response['records'] = $getCountRecord;
                    $response['data'] = $getDetails;
                } elseif ($getCountRecord == 0) {
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found";
                } else {
                    http_response_code(500);
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } else {
                http_response_code(405);
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } else {
            http_response_code(403);
            $response['statusCode'] = 403;
            $response['message'] = "Forbidden";
            $response['error'] = "Missing URL";
        }
    } else {
        http_response_code(401);
        $response['statusCode'] = 401;
        $response['message'] = "Access denied";
        $response['error'] = "Unauthorized token";
    }
} else {
    http_response_code(422);
    $response['statusCode'] = 422;
    $response['message'] = "Missing token";
    $response['error'] = "Please provide authentication token";
}

echo json_encode($response, JSON_PRETTY_PRINT);
