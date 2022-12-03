<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\Category;

if ($authenticationToken) {
    if ($validToken) {
        if (array_key_exists("category_id", $_GET)) {

            $categoryID = trim($_GET['category_id']);
            $requestParams = array("CategoryID" => $categoryID);
            $details = Category::getSelectedCategory(json_encode($requestParams));
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
            } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
                if ($getCountRecord > 0) {
                    $dataCred = json_decode(file_get_contents("php://input"));
                    $category = trim(removeHTMLEntities($dataCred->Category));
                    $hcategory = trim(removeHTMLEntities($dataCred->HCategory));

                    if (empty($category)) {
                        $categoryErr = "Required";
                    } else {
                        $categoryCred = array("Category" => $category, "HCategory" => $hcategory);
                        $getCountRecord = Category::getDupCategory(json_encode($categoryCred));
                        if ($getCountRecord > 0) {
                            $categoryErr = "Category exists";
                        }
                    }
                    if ($categoryErr == "") {
                        $updateCredentials = array("Category_id" => $categoryID, "Category" => $category);
                        $updateStatus = Category::updateCategory(json_encode($updateCredentials));

                        if ($updateStatus > 0) {
                            http_response_code(201);
                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully updated category";
                        } else {
                            http_response_code(500);
                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to update";
                        }
                    } else {
                        http_response_code(400);
                        $dataErrs = array("Category" => $categoryErr);
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
                        $updateCredentials = array("CategoryID" => $categoryID, "Status" => $status);
                        $updateStatus = Category::categoryStatusUpdate(json_encode($updateCredentials));

                        if ($updateStatus > 0) {
                            http_response_code(201);
                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully changed";
                        } else {
                            http_response_code(500);
                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to change";
                        }
                    } else {
                        http_response_code(400);
                        $dataErrs = array("Categoryname" => $categoryNameErr, "Status" => $statusErr);
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
                    $details = Category::getAllCategory(json_encode($credential));
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
                $details = Category::getAllCategory();
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
            } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
                $dataCred = json_decode(file_get_contents("php://input"));

                $category = trim(removeHTMLEntities($dataCred->Category));

                $categoryID = date("YmdHis");

                if (empty($category)) {
                    $categoryErr = "Required";
                } else {
                    $categoryCred = array("Category" => $category, "HCategory" => "");
                    $getCountRecord = Category::getDupCategory(json_encode($categoryCred));
                    if ($getCountRecord > 0) {
                        $categoryErr = "Category exists";
                    }
                }
                if ($categoryErr == "") {
                    $insertCredentials = array("Category_id" => $categoryID, "Category" => $category);
                    $insertStatus = Category::createCategory(json_encode($insertCredentials));

                    if ($insertStatus > 0) {
                        http_response_code(201);
                        $response['statusCode'] = 201;
                        $response['success'] = "Successfully created category";
                    } else {
                        http_response_code(500);
                        $response['statusCode'] = 500;
                        $response['message'] = "Server error";
                        $response['error'] = "Failed to create";
                    }
                } else {
                    http_response_code(400);
                    $dataErrs = array("Category" => $categoryErr);
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
