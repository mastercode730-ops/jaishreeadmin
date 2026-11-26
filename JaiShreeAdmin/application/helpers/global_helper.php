<?php

function php_insert()
{
    date_default_timezone_set("Asia/Kolkata");
    $date = date("d M, Y h:i a");
    return $date;
}

function get_single_data($table = "", $id = "")
{
    $CI = &get_instance();
    $result = $CI->db->get_where($table, array('id' => $id));
    if ($result->num_rows() > 0) {
        return $result->row();
    }
    return false;
}

function data_get($table = "", $where = "", $id = "")
{
    $CI = &get_instance();
    $result = $CI->db->get_where($table, array($where => $id));
    if ($result->num_rows() > 0) {
        return $result;
    }
    return false;
}

function date_add_days($input_date, $days, $format = "Y-m-d")
{
    $newDate = date('Y-m-d', strtotime($input_date . ' + ' . $days . ' days'));
    return $newDate;
}

function id_verify($table = "", $id = "")
{
    $CI = &get_instance();
    $result = $CI->db->get_where($table, array('id' => $id))->num_rows();
    return $result;
}

function affected_rows()
{
    $CI = &get_instance();
    $result = $CI->db->affected_rows();
    return $result;
}

function insert_id()
{
    $CI = &get_instance();
    $result = $CI->db->insert_id();
    return $result;
}

function last_query()
{
    $CI = &get_instance();
    $result = $CI->db->last_query();
    return $result;
}

function get_unique_data()
{
    $CI = &get_instance();

    $sp_area_id = $CI->db->get_where('sales_person_details', array('id' => 11))->row();
    $sales_person_id = $CI->db->get_where('area_attribute', array('sales_person_id' => $sp_area_id->id))->result();
    // echo "<pre>";
    $blank_arr = array();
    foreach ($sales_person_id as $sp_id) {
        $area_list_idssss = $CI->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $sp_id->area_id))->row();
        $id_get = $area_list_idssss->distributor_id;
        array_push($blank_arr, $id_get);
    }
    $final_id = (array_unique($blank_arr));
    print_r($final_id);
}

function time_expressions()
{

    date_default_timezone_set("Asia/Kolkata");

    $time = date("H");
    /* Set the $timezone variable to become the current timezone */
    $timezone = date("e");
    /* If the time is less than 1200 hours, show good morning */
    if ($time < "12") {
        return "Good morning";
    } else
    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
    if ($time >= "12" && $time < "17") {
    return "Good afternoon";
    } else
    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
    if ($time >= "17" && $time < "19") {
    return "Good evening";
    } else
    /* Finally, show good night if the time is greater than or equal to 1900 hours */
    if ($time >= "19") {
    return "Good night";
    }
}

function do_upload($path = '', $image = '', $allow_extension = '')
{
    $CI = &get_instance();
    $config['upload_path'] = './' . $path;
    $config['allowed_types'] = $allow_extension;
    $config['encrypt_name'] = TRUE;
    $CI->load->library('upload', $config);

    if ($CI->upload->do_upload($image)) {
        $document_upload = $CI->upload->data();
        $document = $path . $document_upload['file_name'];
        return $document;
    }else{
        return $CI->upload->display_errors();
    }
}

function base64_upload($file = null, $upload_path = null)
{
    if ($file !== null) {
        $image = base64_decode($file);
        $image_name = md5(uniqid(rand(), true));
        $filename = $image_name . '.' . 'jpeg';
        $path = $upload_path;
        if ($file !== "") {
            file_put_contents($path . $filename, $image);
            return $path . $filename;
        }
    }
}

function back($step){
    return "<script>history.go(-$step)</script>";
}
