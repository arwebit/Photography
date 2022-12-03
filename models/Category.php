<?php

namespace App\Photography;

class Category extends DB
{
    /* ******************************** DUPLICATE CATEGORY ******************************** */

    public static function getDupCategory($datas = null)
    {
        $getjsonData = json_decode($datas);
        $categoryName = $getjsonData->Category;
        $hcategoryName = $getjsonData->HCategory;
        $sql = "SELECT * FROM mas_category WHERE category_name = ? AND category_name != ?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("ss", $categoryName, $hcategoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        return $numRows;
    }

    /* ******************************** DUPLICATE CATEGORY ******************************** */


    /* ******************************** INSERT CATEGORY ******************************** */

    public static function createCategory($datas = null)
    {
        $categoryStatus = 1;
        $getjsonData = json_decode($datas);
        $id = $getjsonData->Category_id;
        $categoryName = $getjsonData->Category;

        $categorySQL = "INSERT INTO mas_category VALUES(?,?,?)";
        $stmt = parent::getConnection()->prepare($categorySQL);
        $stmt->bind_param("isi", $id, $categoryName, $categoryStatus);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /* ******************************** INSERT CATEGORY ******************************** */

    /* ******************************** SELECTED CATEGORY ******************************** */

    public static function getSelectedCategory($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $categoryID = $getjsonData->CategoryID;

        $sql = "SELECT * FROM mas_category WHERE category_id=? ";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('i', $categoryID);
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

    /* ******************************** SELECTED CATEGORY DETAILS ******************************** */

    /*  ******************************** CATEGORY DETAILS *********************************/

    public static function getAllCategory($datas = null)
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

        $sql = "SELECT * FROM mas_category ORDER BY category_name " . $limit;
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

    /*     * ******************************* CATEGORY DETAILS ******************************** */

    /*     * ******************************* CATEGORY UPDATE ******************************** */

    public static function updateCategory($datas = null)
    {
        $getjsonData = json_decode($datas);
        $id = $getjsonData->Category_id;
        $categoryName = $getjsonData->Category;

        $categorySQL = "UPDATE mas_category SET category_name=? WHERE category_id=? ";
        $stmt = parent::getConnection()->prepare($categorySQL);
        $stmt->bind_param("si", $categoryName, $id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* CATEGORY UPDATE ******************************** */

    /*     * ******************************* CATEGORY STATUS CHANGE ******************************** */

    public static function categoryStatusUpdate($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $categoryID = $getjsonData->CategoryID;
        $categoryStatus = $getjsonData->Status;
        $sql = "UPDATE mas_category SET category_status=? WHERE category_id=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('ii', $categoryStatus, $categoryID);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* CATEGORY STATUS CHANGE ******************************** */
}
