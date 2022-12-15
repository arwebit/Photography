<?php
require_once "./vendor/autoload.php";
require_once './constants.php';
require_once './customFunctions.php';
require_once './config/header.php';

use App\Photography\Blog;

if ($authenticationToken) {
    if ($validToken) {

        if (!is_dir("blogs")) {
            mkdir("blogs", 0777);
        }

        $mediaPath = "blogs/";

        $filesAllowed = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG");
        if (array_key_exists("blog_id", $_GET)) {
            $blogID = trim($_GET['blog_id']);
            $requestParams = array("BlogID" => $blogID);
            $details = Blog::getSelectedBlog(json_encode($requestParams));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];

            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                if ($getCountRecord > 0) {

                    $response['statusCode'] = 200;
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
            } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
                if ($getCountRecord > 0) {
                    $requestData = json_decode(trim($_REQUEST['requestData']));
                    $files = $_FILES['blogPic'];
                    $title = removeHTMLEntities(trim($requestData->Title));
                    $htitle = removeHTMLEntities(trim($requestData->HTitle));
                    $blogCategory = removeHTMLEntities(trim($requestData->BlogCategory));
                    $description = removeHTMLEntities(trim($requestData->Description));
                    $postUser = removeHTMLEntities(trim($requestData->Login_User));

                    if (empty($title)) {
                        $titleErr = "Required";
                    } else {
                        $titleCred = array(
                            "BlogTitle" => str_replace(" ", "-", strtoupper($title)),
                            "HBlogTitle" => str_replace(" ", "-", strtoupper($htitle))
                        );
                        $getCountRecord = Blog::getDupTitle(json_encode($titleCred));
                        if ($getCountRecord > 0) {
                            $titleErr = "Duplicate title. Try again";
                        } else {
                            $urlSlug = str_replace(" ", "-", strtolower($title));
                        }
                    }
                    if (empty($description)) {
                        $descriptionErr = "Required";
                    } else {
                        if (strlen($description) > 200) {
                            $descriptionErr = "Maximum 200 characters";
                        }
                    }
                    if (empty($blogCategory)) {
                        $blogCategoryErr = "Required";
                    }
                    if (empty($postUser)) {
                        $postUserErr = "Required";
                    }
                    if ($files['size'] > 0) {
                        $fileName = basename($files['name']);
                        $fileSize = $files['size']; // File size in "BYTES"
                        $fileType = $files['type'];
                        $fileTmpName = $files['tmp_name'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!(in_array($fileExtension, $filesAllowed))) {
                            $photoImageErr = "Upload JPEG,JPG,PNG files";
                        } else {
                            if ($fileSize > PIC_MAX_SIZE) {
                                $photoImageErr = "Upload less than or equal to 1 MB";
                            } else {
                                $filename = $blogID . "." . $fileExtension;
                                $sourceProperties = getimagesize($fileTmpName);
                                $sourceImageWidth = $sourceProperties[0];
                                $sourceImageHeight = $sourceProperties[1];
                                $fileLocation = $mediaPath . $filename;
                                $src = imagecreatefromjpeg($fileTmpName);
                                $imageLayer = resizeImage($src, $sourceImageWidth, $sourceImageHeight, $sourceImageWidth, $sourceImageHeight);
                                unlink($fileLocation);
                                if (imagejpeg($imageLayer, $fileLocation)) {
                                    $photoImage = base64_encode(file_get_contents($fileLocation));
                                } else {
                                    $photoImageErr = "File cannot be inserted into folder";
                                }
                            }
                        }
                    } else {
                        foreach ($getDetails as $blogData) {
                            $fileLocation = $blogData['photo_path'];
                            $photoImage = $blogData['photo_encrypted_str'];
                            $fileType = $blogData['photo_mime_type'];
                            $fileExtension = $blogData['photo_extension'];
                            $sourceImageWidth = $blogData['photo_width'];
                            $sourceImageHeight = $blogData['photo_height'];
                        }
                    }

                    if (($titleErr == "") && ($descriptionErr == "") && ($photoImageErr == "") && ($blogCategoryErr == "") && ($postUserErr == "")) {
                        $updateCredentials = array(
                            "Blog_id" => $blogID, "PhotoStr" => $photoImage, "Username" => $postUser, "BlogCategory" => $blogCategory,
                            "PhotoLocation" => $fileLocation, "Title" => $title, "Description" => $description,
                            "PhotoMime" => $fileType, "PhotoExtension" => $fileExtension, "Width" => $sourceImageWidth,
                            "Height" => $sourceImageHeight, "URLSlug" => $urlSlug
                        );
                        $updateStatus = Blog::updateBlog(json_encode($updateCredentials));

                        if ($updateStatus > 0) {

                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully updated post";
                        } else {

                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to update post";
                        }
                    } else {

                        $dataErrs = array(
                            "Photo" => $photoImageErr, "Username" => $postUserErr, "Title" => $titleErr,
                            "BlogCategory" => $blogCategoryErr, "Description" => $descriptionErr
                        );
                        $response['statusCode'] = 400;
                        $response['message'] = "Bad Request";
                        $response['error'] = $dataErrs;
                    }
                } elseif ($getCountRecord == 0) {

                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to change";
                } else {

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
                        $updateCredentials = array("BlogID" => $blogID, "Status" => $status);
                        $updateStatus = Blog::blogStatusUpdate(json_encode($updateCredentials));

                        if ($updateStatus > 0) {

                            $response['statusCode'] = 201;
                            $response['success'] = "Successfully changed";
                        } else {

                            $response['statusCode'] = 500;
                            $response['message'] = "Server error";
                            $response['error'] = "Failed to change";
                        }
                    } else {

                        $dataErrs = array("Photoname" => $blogNameErr, "Status" => $statusErr);
                        $response['statusCode'] = 400;
                        $response['message'] = "Bad Request";
                        $response['error'] = $dataErrs;
                    }
                } elseif ($getCountRecord == 0) {

                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to change";
                } else {

                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } else {

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
                    $details = Blog::getAllBlog(json_encode($credential));
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

                    $dataErrs = array("Records" => $recordsErr, "PageNo" => $pageNoErr, "Message" => "Recorrect error");
                    $response['statusCode'] = 400;
                    $response['message'] = "Bad Request";
                    $response['error'] = $dataErrs;
                }
            } else {

                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } elseif (empty($_GET)) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                $details = Blog::getAllBlog();
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
            } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
                $requestData = json_decode(trim($_REQUEST['requestData']));
                $files = $_FILES['blogPic'];
                $title = removeHTMLEntities(trim($requestData->Title));
                $blogCategory = removeHTMLEntities(trim($requestData->BlogCategory));
                $description = removeHTMLEntities(trim($requestData->Description));
                $postUser = removeHTMLEntities(trim($requestData->Login_User));
                $blogID = date("YmdHis");

                if (empty($title)) {
                    $titleErr = "Required";
                } else {
                    $titleCred = array("BlogTitle" => str_replace(" ", "-", strtoupper($title)), "HBlogTitle" => "");
                    $getCountRecord = Blog::getDupTitle(json_encode($titleCred));
                    if ($getCountRecord > 0) {
                        $titleErr = "Duplicate title. Try again";
                    } else {
                        $urlSlug = str_replace(" ", "-", strtolower($title));
                    }
                }
                if (empty($description)) {
                    $descriptionErr = "Required";
                } else {
                    if (strlen($description) > 200) {
                        $descriptionErr = "Maximum 200 characters";
                    }
                }
                if (empty($blogCategory)) {
                    $blogCategoryErr = "Required";
                }
                if (empty($postUser)) {
                    $postUserErr = "Required";
                }
                if ($files['size'] > 0) {
                    $fileName = basename($files['name']);
                    $fileSize = $files['size']; // File size in "BYTES"
                    $fileType = $files['type'];
                    $fileTmpName = $files['tmp_name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!(in_array($fileExtension, $filesAllowed))) {
                        $photoImageErr = "Upload JPEG,JPG,PNG files";
                    } else {
                        if ($fileSize > PIC_MAX_SIZE) {
                            $photoImageErr = "Upload less than or equal to 1 MB";
                        } else {
                            $filename = $blogID . "." . $fileExtension;
                            $sourceProperties = getimagesize($fileTmpName);
                            $sourceImageWidth = $sourceProperties[0];
                            $sourceImageHeight = $sourceProperties[1];
                            $fileLocation = $mediaPath . $filename;
                            $src = imagecreatefromjpeg($fileTmpName);
                            $imageLayer = resizeImage($src, $sourceImageWidth, $sourceImageHeight, $sourceImageWidth, $sourceImageHeight);
                            if (imagejpeg($imageLayer, $fileLocation)) {
                                $photoImage = base64_encode(file_get_contents($fileLocation));
                            } else {
                                unlink($fileLocation);
                                $photoImageErr = "File cannot be inserted into folder";
                            }
                        }
                    }
                } else {
                    $photoImageErr = "Blog cover image required";
                }

                if (($titleErr == "") && ($descriptionErr == "") && ($photoImageErr == "") && ($blogCategoryErr == "") && ($postUserErr == "")) {
                    $insertCredentials = array(
                        "Blog_id" => $blogID, "PhotoStr" => $photoImage, "Username" => $postUser, "BlogCategory" => $blogCategory,
                        "PhotoLocation" => $fileLocation, "Title" => $title, "Description" => $description,
                        "PhotoMime" => $fileType, "PhotoExtension" => $fileExtension, "Width" => $sourceImageWidth,
                        "Height" => $sourceImageHeight, "URLSlug" => $urlSlug
                    );
                    $insertStatus = Blog::createBlog(json_encode($insertCredentials));

                    if ($insertStatus > 0) {

                        $response['statusCode'] = 201;
                        $response['success'] = "Successfully uploaded post";
                    } else {

                        unlink($fileLocation);
                        $response['statusCode'] = 500;
                        $response['message'] = "Server error";
                        $response['error'] = "Failed to post";
                    }
                } else {

                    $dataErrs = array(
                        "Photo" => $photoImageErr, "Username" => $postUserErr, "Title" => $titleErr,
                        "BlogCategory" => $blogCategoryErr, "Description" => $descriptionErr
                    );
                    unlink($fileLocation);
                    $response['statusCode'] = 400;
                    $response['message'] = "Bad Request";
                    $response['error'] = $dataErrs;
                }
            } else {

                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } else {

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
