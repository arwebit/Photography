<?php

namespace App\Photography;

class Blog extends DB
{
    /* ******************************** DUPLICATE BLOG TITLE ******************************** */

    public static function getDupTitle($datas = null)
    {
        $getjsonData = json_decode($datas);
        $blogTitle = $getjsonData->BlogTitle;
        $hblogTitle = $getjsonData->HBlogTitle;
        $sql = "SELECT * FROM blog_post WHERE REPLACE(blog_title, ' ', '-') = ? 
                AND REPLACE(blog_title, ' ', '-') != ?  ";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("ss", $blogTitle, $hblogTitle);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** DUPLICATE BLOG TITLE ******************************** */

    /* ******************************** INSERT BLOG ******************************** */

    public static function createBlog($datas = null)
    {
        $blogStatus = 1;
        $postDateTime = date("Y-m-d H:i:s");
        $getjsonData = json_decode($datas);
        $id = $getjsonData->Blog_id;
        $userName = $getjsonData->Username;
        $blogCategory = $getjsonData->BlogCategory;
        $photoStr = $getjsonData->PhotoStr;
        $photoLocation = $getjsonData->PhotoLocation;
        $title = $getjsonData->Title;
        $description = $getjsonData->Description;
        $photoMime = $getjsonData->PhotoMime;
        $photoExtension = $getjsonData->PhotoExtension;
        $photoWidth = $getjsonData->Width;
        $photoHeight = $getjsonData->Height;
        $blogURLSlug = $getjsonData->URLSlug;

        $blogSQL = "INSERT INTO blog_post VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = parent::getConnection()->prepare($blogSQL);
        $stmt->bind_param(
            "isiiisssssssis",
            $id,
            $userName,
            $blogCategory,
            $photoWidth,
            $photoHeight,
            $title,
            $description,
            $photoExtension,
            $photoMime,
            $photoStr,
            $photoLocation,
            $blogURLSlug,
            $blogStatus,
            $postDateTime
        );
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /* ******************************** INSERT BLOG ******************************** */

    /* ******************************** SELECTED BLOG ******************************** */

    public static function getSelectedBlog($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $blogID = $getjsonData->BlogID;

        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
        T.full_name, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
        T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
        a.photo_width, a.photo_height, b.category_name, c.full_name, a.blog_url_slug, 
        a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
        a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
        INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
        c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
         a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
        INNER JOIN user_details c ON a.username=c.username )T WHERE T.blog_id=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('i', $blogID);
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

    /* ******************************** SELECTED BLOG DETAILS ******************************** */

    /*  ******************************** BLOG DETAILS *********************************/

    public static function getAllBlog($datas = null)
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

        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
                T.full_name, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
                T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
                a.photo_width, a.photo_height, b.category_name, c.full_name, a.blog_url_slug, 
                a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
                a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
                c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
                 a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN user_details c ON a.username=c.username )T ORDER BY T.blog_date DESC " . $limit;
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

    /*     * ******************************* BLOG DETAILS ******************************** */

    /*     * ******************************* BLOG UPDATE ******************************** */

    public static function updateBlog($datas = null)
    {
        $getjsonData = json_decode($datas);
        $id = $getjsonData->Blog_id;
        $blogCategory = $getjsonData->BlogCategory;
        $photoStr = $getjsonData->PhotoStr;
        $photoLocation = $getjsonData->PhotoLocation;
        $title = $getjsonData->Title;
        $description = $getjsonData->Description;
        $photoMime = $getjsonData->PhotoMime;
        $photoExtension = $getjsonData->PhotoExtension;
        $photoWidth = $getjsonData->Width;
        $photoHeight = $getjsonData->Height;
        $blogURLSlug = $getjsonData->URLSlug;

        $blogSQL = "UPDATE blog_post SET blog_category_id=?, photo_width=?, photo_height=?,
                    blog_title=?, blog_descr=?, photo_extension=?, photo_mime_type=?,
                    photo_encrypted_str=?, photo_path=?, blog_url_slug=? WHERE blog_id=? ";
        $stmt = parent::getConnection()->prepare($blogSQL);
        $stmt->bind_param(
            "iiisssssssi",
            $blogCategory,
            $photoWidth,
            $photoHeight,
            $title,
            $description,
            $photoExtension,
            $photoMime,
            $photoStr,
            $photoLocation,
            $blogURLSlug,
            $id
        );
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* BLOG UPDATE ******************************** */

    /*     * ******************************* BLOG STATUS CHANGE ******************************** */

    public static function blogStatusUpdate($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $blogID = $getjsonData->BlogID;
        $blogStatus = $getjsonData->Status;
        $sql = "UPDATE blog_post SET blog_status=? WHERE blog_id=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param('ii', $blogStatus, $blogID);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* BLOG STATUS CHANGE ******************************** */
}
