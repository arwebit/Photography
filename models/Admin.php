<?php

namespace App\Photography;


class Admin extends DB
{

    /* ******************************** LOGIN ACCESS ******************************** */

    public static function loginAccess($datas = null)
    {
        $getjsonData = json_decode($datas);
        $username = $getjsonData->Username;
        $password = $getjsonData->Password;
        $sql = "SELECT * FROM admin_login_access WHERE username = ? AND password=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** LOGIN ACCESS ******************************** */
    /* ******************************** LOGIN ACTIVE ******************************** */

    public static function loginActive($datas = null)
    {
        $getjsonData = json_decode($datas);
        $username = $getjsonData->Username;
        $userStatus = 1;
        $sql = "SELECT * FROM admin_login_access WHERE username = ? AND status=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("si", $username, $userStatus);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** LOGIN ACTIVE ******************************** */
    /* ******************************** DUPLICATE USERNAME ******************************** */

    public static function getDupUser($datas = null)
    {
        $getjsonData = json_decode($datas);
        $username = $getjsonData->Username;

        $sql = "SELECT * FROM admin_login_access WHERE username = ? ";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        return $numRows;
    }

    /* ******************************** DUPLICATE USERNAME ******************************** */
    /* ******************************** DUPLICATE MOBILE NUMBER ******************************** */

    public static function getDupMobile($datas = null)
    {
        $getjsonData = json_decode($datas);
        $mobileno = $getjsonData->Mobile_no;
        $hmobileno = $getjsonData->HMobile_no;

        $sql = "SELECT * FROM admin_login_access WHERE contact_no = ? AND contact_no != ?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("ii", $mobileno, $hmobileno);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** DUPLICATE MOBILE NUMBER ******************************** */

    /* ******************************** DUPLICATE EMAIL ID ******************************** */

    public static function getDupEmail($datas = null)
    {
        $getjsonData = json_decode($datas);
        $email = $getjsonData->Email_id;
        $hemail = $getjsonData->HEmail_id;

        $sql = "SELECT * FROM admin_login_access WHERE contact_email = ? AND contact_email != ?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("ss", $email, $hemail);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        $retData = array("Record" => $numRows);
        return $retData;
    }

    /* ******************************** DUPLICATE EMAIL ID ******************************** */

    /* ******************************** INSERT BIO-DATA ******************************** */

    public static function createUser($datas = null)
    {
        $userLoginSQL = "";
        $userVerified = 0;
        $userStatus = 0;
        $getjsonData = json_decode($datas);
        $id = $getjsonData->User_id;
        $username = $getjsonData->Username;
        $password = $getjsonData->Password;
        $firstName = $getjsonData->First_name;
        $middleName = $getjsonData->Middle_name;
        $lastName = $getjsonData->Last_name;
        $email = $getjsonData->Email;
        $mobile = $getjsonData->Mobile;
        $dob = $getjsonData->DOB;
        $address = $getjsonData->Address;

        $userLoginSQL = "INSERT INTO admin_login_access VALUES(?,?,?,?,?)";
        $ulstmt = parent::getConnection()->prepare($userLoginSQL);
        $ulstmt->bind_param("issii", $id, $username, $password, $userVerified, $userStatus);
        $ulretVal = $ulstmt->execute();

        if ($ulretVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /* ******************************** INSERT BIO-DATA ******************************** */

    /* ******************************** SELECTED BIO-DATA DETAILS ******************************** */

    public static function getSelectedUser($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $userName = $getjsonData->Username;

        $sql = "SELECT username, full_name, contact_no, contact_email, status
                FROM  admin_login_access WHERE username=? ";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('s', $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->free_result();
        $retData = array("Record" => $numRows, "Data" => $data);
        return $retData;
    }

    /* ******************************** SELECTED BIO-DATA DETAILS ******************************** */

    /*  ******************************** BIO-DATA DETAILS *********************************/

    public static function getAllUsers($datas = null)
    {
        $limit = "";
        if ($datas != null) {
            $getjsonData = json_decode($datas);
            $startIndex = $getjsonData->StartIndex;
            $records = $getjsonData->RecordsToBeShown;

            if (($startIndex != "") && ($records != "")) {
                $limit = "LIMIT $startIndex, $records";
            } else {
                $limit = "";
            }
        }

        $sql = "SELECT username, full_name, contact_no, contact_email, status
                FROM  admin_login_access ORDER BY full_name " . $limit;
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->free_result();
        $retData = array("Record" => $numRows, "Data" => $data);
        return $retData;
    }

    /*     * ******************************* BIO-DATA DETAILS ******************************** */

    /*     * ******************************* BIO-DATA UPDATE ******************************** */

    public static function updateUser($datas = null)
    {
        $userSQL = "";
        $getjsonData = json_decode($datas);
        $userName = $getjsonData->Username;
        $firstName = $getjsonData->First_name;
        $middleName = $getjsonData->Middle_name;
        $lastName = $getjsonData->Last_name;
        $email = $getjsonData->Email;
        $mobile = $getjsonData->Mobile;
        $dob = $getjsonData->DOB;
        $address = $getjsonData->Address;


        $userSQL = "UPDATE admin_login_access SET user_first_name=?, user_middle_name=?, user_last_name=?,
                    user_mobile=?, user_email=?, user_address=?, user_dob=? WHERE username=?";

        $udstmt = parent::getConnection()->prepare($userSQL);
        $udstmt->bind_param(
            "sssissss",
            $firstName,
            $middleName,
            $lastName,
            $mobile,
            $email,
            $address,
            $dob,
            $userName
        );
        $retVal = $udstmt->execute();
        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* BIO-DATA UPDATE ******************************** */

    /*     * ******************************* BIO-DATA STSTUS CHANGE ******************************** */

    public static function userStatusUpdate($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $userName = $getjsonData->Username;
        $userStatus = $getjsonData->Status;
        $sql = "UPDATE admin_login_access SET status=? WHERE username=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('is', $userStatus, $userName);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* BIO-DATA STSTUS CHANGE ******************************** */
}
