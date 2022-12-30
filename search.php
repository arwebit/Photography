<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\Search;

if (array_key_exists("category", $_GET) && array_key_exists("page", $_GET) && array_key_exists("records", $_GET)) {
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
            $credential = array(
                "StartIndex" => $limitFrom, "RecordsToBeShown" => $records);
            $details = Search::getAllCategory(json_encode($credential));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];
            if ($getCountRecord > 0) {

                $response['statusCode'] = 200;
                $response['records'] = $getCountRecord;
                $response['data'] = $getDetails;
            } elseif ($getCountRecord == 0) {

                $response['statusCode'] = 205;
                $response['message'] = "No Content";
                $response['error'] = "No record found";
            } else {

                $response['statusCode'] = 500;
                $response['message'] = "Internal Server Error";
                $response['error'] = "Server error";
            }
        } else {

            $dataErrs = array("Records" => $recordsErr, "PageNo" => $pageNoErr, "Username" => $userErr);
            $response['statusCode'] = 400;
            $response['message'] = "Bad request";
            $response['error'] = $dataErrs;
        }
    } else {

        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
}else if (array_key_exists("category_name", $_GET) && array_key_exists("page", $_GET) && array_key_exists("records", $_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {

        $categoryName = trim(removeHTMLEntities($_GET['category_name']));
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
        if (empty($categoryName)) {
            $categoryNameErr = "Required";
        } else {
            $catCred = array("CategoryName" => str_replace(" ", "-", $categoryName));
            $categoryExists = Search::getCategory(json_encode($catCred));
            if ($categoryExists == 0) {
                $categoryNameErr = "No category found";
            }
        }
        if ($categoryNameErr == "" && $recordsErr == "" && $pageNoErr == "") {
            $credential = array(
                "StartIndex" => $limitFrom, "RecordsToBeShown" => $records,
                "CategoryName" => str_replace(" ", "-", $categoryName)
            );
            $details = Search::getBlogByCategory(json_encode($credential));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];
            if ($getCountRecord > 0) {

                $response['statusCode'] = 200;
                $response['records'] = $getCountRecord;
                $response['data'] = $getDetails;
            } elseif ($getCountRecord == 0) {

                $response['statusCode'] = 205;
                $response['message'] = "No Content";
                $response['error'] = "No record found";
            } else {

                $response['statusCode'] = 500;
                $response['message'] = "Internal Server Error";
                $response['error'] = "Server error";
            }
        } else {

            $dataErrs = array(
                "Records" => $recordsErr, "PageNo" => $pageNoErr,
                "Category" => $categoryNameErr
            );
            $response['statusCode'] = 400;
            $response['message'] = "Bad request";
            $response['error'] = $dataErrs;
        }
    } else {

        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
} else if (array_key_exists("user_name", $_GET) && array_key_exists("page", $_GET) && array_key_exists("records", $_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {

        $userName = trim(removeHTMLEntities($_GET['user_name']));
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
        if (empty($userName)) {
            $userNameErr = "Required";
        } else {
            $userCred = array("Username" => $userName);
            $userExists = Search::getUser(json_encode($userCred));
            if ($userExists == 0) {
                $userErr = "No user found";
            }
        }
        if ($userErr == "" && $recordsErr == "" && $pageNoErr == "") {
            $credential = array(
                "StartIndex" => $limitFrom, "RecordsToBeShown" => $records,
                "Username" => $userName
            );
            $details = Search::getBlogByUser(json_encode($credential));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];
            if ($getCountRecord > 0) {

                $response['statusCode'] = 200;
                $response['records'] = $getCountRecord;
                $response['data'] = $getDetails;
            } elseif ($getCountRecord == 0) {

                $response['statusCode'] = 205;
                $response['message'] = "No Content";
                $response['error'] = "No record found";
            } else {

                $response['statusCode'] = 500;
                $response['message'] = "Internal Server Error";
                $response['error'] = "Server error";
            }
        } else {

            $dataErrs = array("Records" => $recordsErr, "PageNo" => $pageNoErr, "Username" => $userErr);
            $response['statusCode'] = 400;
            $response['message'] = "Bad request";
            $response['error'] = $dataErrs;
        }
    } else {

        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
} else if (array_key_exists("slug", $_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {

        $urlSlug = trim(removeHTMLEntities($_GET['slug']));

        if (empty($urlSlug)) {
            $urlSlugErr = "Required";
        }
        if ($urlSlugErr == "") {
            $credential = array(
                "StartIndex" => $limitFrom, "RecordsToBeShown" => $records,
                "URLSlug" => $urlSlug
            );
            $details = Search::getBlogBySlug(json_encode($credential));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];
            if ($getCountRecord > 0) {

                $response['statusCode'] = 200;
                $response['records'] = $getCountRecord;
                $response['data'] = $getDetails;
            } elseif ($getCountRecord == 0) {

                $response['statusCode'] = 205;
                $response['message'] = "No Content";
                $response['error'] = "No record found";
            } else {

                $response['statusCode'] = 500;
                $response['message'] = "Internal Server Error";
                $response['error'] = "Server error";
            }
        } else {

            $dataErrs = array("URLSlug" => $urlSlugErr);
            $response['statusCode'] = 400;
            $response['message'] = "Bad request";
            $response['error'] = $dataErrs;
        }
    } else {

        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
} else {

    $response['statusCode'] = 403;
    $response['message'] = "Forbidden";
    $response['error'] = "URL link error";
}

echo json_encode($response, JSON_PRETTY_PRINT);
