<?php

namespace App\Photography;


class Search extends DB
{

    /* ******************************** ALL CATEGORY ******************************** */
    public static function getAllCategory($datas = null)
    {
        $getjsonData = json_decode($datas);
        $startIndex = $getjsonData->StartIndex;
        $records = $getjsonData->RecordsToBeShown;

        if (($startIndex != "") && ($records != "")) {
            $limit = "LIMIT $startIndex, $records";
        } else {
            $limit = "";
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
    /* ******************************** ALL CATEGORY ******************************** */

    /* ******************************** ALL BLOG ******************************** */
    public static function getAllBlog($datas = null)
    {
        $getjsonData = json_decode($datas);
        $startIndex = $getjsonData->StartIndex;
        $records = $getjsonData->RecordsToBeShown;

        if (($startIndex != "") && ($records != "")) {
            $limit = "LIMIT $startIndex, $records";
        } else {
            $limit = "";
        }
        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
      T.full_name, T.username, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
      T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
      a.photo_width, a.photo_height, b.category_name, a.username, c.full_name, a.blog_url_slug, 
      a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
      a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
      INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
      c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.username, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
      a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
      INNER JOIN user_details c ON a.username=c.username )T  ORDER BY T.blog_date DESC " . $limit;

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
    /* ******************************** ALL BLOG ******************************** */

    /* ******************************** CHECK CATEGORY ******************************** */

    public static function getCategory($datas = null)
    {
        $getjsonData = json_decode($datas);
        $categoryName = $getjsonData->CategoryName;
        $sql = "SELECT * FROM mas_category WHERE category_name = ?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", str_replace("-", " ", $categoryName));
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** CHECK CATEGORY ******************************** */

    /* ******************************** CHECK USER ******************************** */

    public static function getUser($datas = null)
    {
        $getjsonData = json_decode($datas);
        $userName = $getjsonData->Username;
        $sql = "SELECT T.username FROM (SELECT username FROM admin_login_access UNION ALL SELECT username 
                FROM user_login_access)T WHERE T.username=?";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->free_result();
        return $numRows;
    }

    /* ******************************** CHECK USER ******************************** */

    /* ******************************** BLOG POSTS SEARCH ******************************** */
    /* ******************************** BY CATEGORY ******************************** */
    public static function getBlogByCategory($datas = null)
    {
        $getjsonData = json_decode($datas);
        $categoryName = $getjsonData->CategoryName;
        $startIndex = $getjsonData->StartIndex;
        $records = $getjsonData->RecordsToBeShown;

        if (($startIndex != "") && ($records != "")) {
            $limit = "LIMIT $startIndex, $records";
        } else {
            $limit = "";
        }
        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
                T.full_name, T.username, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
                T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
                a.photo_width, a.photo_height, b.category_name, a.username, c.full_name, a.blog_url_slug, 
                a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
                a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
                c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.username, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
                a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN user_details c ON a.username=c.username )T WHERE T.category_name=? ORDER BY T.blog_date DESC " . $limit;
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", str_replace("-", " ", $categoryName));
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
    /* ******************************** BY CATEGORY ******************************** */

    /* ******************************** BY USER ******************************** */
    public static function getBlogByUser($datas = null)
    {
        $getjsonData = json_decode($datas);
        $userName = $getjsonData->Username;
        $startIndex = $getjsonData->StartIndex;
        $records = $getjsonData->RecordsToBeShown;

        if (($startIndex != "") && ($records != "")) {
            $limit = "LIMIT $startIndex, $records";
        } else {
            $limit = "";
        }
        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
                T.full_name, T.username, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
                T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
                a.photo_width, a.photo_height, b.category_name, a.username, c.full_name, a.blog_url_slug, 
                a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
                a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
                c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.username, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
                a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
        INNER JOIN user_details c ON a.username=c.username )T WHERE T.username=? ORDER BY T.blog_date DESC " . $limit;
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", $userName);
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
    /* ******************************** BY USER ******************************** */


    /* ******************************** BY USER ******************************** */
    public static function getBlogBySlug($datas = null)
    {
        $getjsonData = json_decode($datas);
        $urlSlug = $getjsonData->URLSlug;
        $startIndex = $getjsonData->StartIndex;
        $records = $getjsonData->RecordsToBeShown;

        if (($startIndex != "") && ($records != "")) {
            $limit = "LIMIT $startIndex, $records";
        } else {
            $limit = "";
        }
        $sql = "SELECT T.blog_id, T.blog_title, T.blog_descr, T.photo_width, T.photo_height, T.category_name, 
                T.full_name, T.username, T.blog_url_slug, T.photo_extension, T.photo_mime_type, T.photo_path, 
                T.photo_encrypted_str, T.blog_date, T.blog_status FROM (SELECT a.blog_id, a.blog_title, a.blog_descr,
                a.photo_width, a.photo_height, b.category_name, a.username, c.full_name, a.blog_url_slug, 
                a.photo_extension, a.photo_mime_type, a.photo_path, a.photo_encrypted_str, a.blog_date, 
                a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
                INNER JOIN admin_login_access c ON a.username=c.username UNION ALL SELECT a.blog_id, a.blog_title, a.blog_descr, a.photo_width, a.photo_height, b.category_name, 
                c.user_first_name||' ' ||c.user_middle_name||' '||c.user_last_name as full_name, a.username, a.blog_url_slug, a.photo_extension, a.photo_mime_type, a.photo_path, 
                a.photo_encrypted_str, a.blog_date, a.blog_status FROM blog_post a INNER JOIN mas_category b ON a.blog_category_id=b.category_id
        INNER JOIN user_details c ON a.username=c.username )T WHERE T.blog_url_slug=? ORDER BY T.blog_date DESC " . $limit;
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->bind_param("s", $urlSlug);
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
    /* ******************************** BY USER ******************************** */
    /* ******************************** BLOG POSTS SEARCH ******************************** */
}
