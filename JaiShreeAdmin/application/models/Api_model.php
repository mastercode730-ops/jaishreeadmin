<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{
    public function signup()
    {
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile');
        $password_input = $this->input->post('password');
        $reffer_code = $this->input->post('reffer_code');

        $password = password_hash($password_input, PASSWORD_DEFAULT);

        if (empty($name)) {
            $result = array('status' => 0, 'msg' => 'Please enter name!!!');
            return $result;
        }
        if (empty($mobile)) {
            $result = array('status' => 0, 'msg' => 'Please enter mobile!!!');
            return $result;
        }
        if (empty($password)) {
            $result = array('status' => 0, 'msg' => 'Please enter password!!!');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('mobile' => $mobile));
        $add_wallet_price = $this->db->get_where('game_setting');

        if ($id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'User is already exist!!!');
            return $result;
        }

        if (!empty($reffer_code)) {

        $reffer_code_verify = $this->db->get_where('users', array('my_reffer_code' => $reffer_code));
            if ($reffer_code_verify->num_rows() > 0) {
                $this->db->where('my_reffer_code', $reffer_code)->update('users', array('wallet' => $reffer_code_verify->row('wallet') + $add_wallet_price->row('reffer_amount')));
                $msg = "Refer Commission." . $add_wallet_price->row('reffer_amount') . " via a refferal";
                $this->db->insert('transaction_history', array('user_id' => $reffer_code_verify->row('id'), 'add_amount' => $add_wallet_price->row('reffer_amount'), 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'))); // reffer sender user
            } else {
                $result = array('status' => 0, 'msg' => 'Reffer code is not valid!!!');
                return $result;
            }

            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['password'] = $password;
            $data['reffer_code'] = $reffer_code;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
        } else {

            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['password'] = $password;
            $data['reffer_code'] = $reffer_code;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
        }

        $this->db->insert('users', $data);
        $insert_id = $this->db->insert_id();
        // $this->db->insert('transaction_history', array('user_id' => $insert_id, 'add_amount' => $add_wallet_price->row('reffer_amount'), 'remaining_amount' => $add_wallet_price->row('reffer_amount'), 'status' => 2)); // reffer receiver user
        $this->db->where('id', $insert_id)->update('users', array('my_reffer_code' => $insert_id + 10000));
        $signup_user_data = $this->db->get_where('users', array('id' => $insert_id));

        foreach ($signup_user_data->result() as $row) {
            $data_arr = array(
                'id' => $row->id,
                'name' => $row->name,
                'mobile' => $row->mobile,
                'reffer_code' => $row->reffer_code,
                'my_reffer_code' => $row->my_reffer_code,
                'bank_name' => $row->bank_name,
                'account_no' => $row->account_no,
                'ifsc_code' => $row->ifsc_code,
                'holder_name' => $row->holder_name,
                'upi_no' => $row->upi_no,
                'paytm_no' => $row->paytm_no,
                'payment_type' => $row->payment_type,
                'wallet' => $row->wallet,
                'date' => $row->date,
                'time' => $row->time,
            );
        }

        $result = array('status' => 1, 'msg' => 'User Signup Successfull...!!!', 'data' => $data_arr);
        return $result;
    }
    
    public function login()
    {
        $OTP_AVAIALBLE = false;
        $name = "User";
        $mobile = $this->input->post('mobile');
        $password_input = $this->input->post('otp');
        //After Otp section
        if(isset($password_input) && $password_input != ""){
            $OTPExist = $this->db->limit(1)->order_by('id','DESC')->get_where('user_login', array('phone' => $mobile, 'otp' => $password_input));
            if($OTPExist->num_rows() >0){
                $purpose = $OTPExist->result()[0]->purpose;
                $userid = $OTPExist->result()[0]->userid;
                if($purpose == 0){
                    $result = array('status' => 1, 'msg' => 'Login successfully', 'id' => $userid);
                    return $result;
                }else{
                    $WELCOME_BNUS = 10;
                    $add_wallet_price = $this->db->get_where('game_setting');
                    $WELCOME_BNUS = $add_wallet_price->row('reffer_amount');
                    $reffer_code = $this->input->post('refferal');
                    if (!empty($reffer_code)) {
                        $reffer_code_verify = $this->db->get_where('users', array('my_reffer_code' => $reffer_code));
                        if ($reffer_code_verify->num_rows() > 0) {
                            // $this->db->where('my_reffer_code', $reffer_code)->update('users', array('wallet' => $reffer_code_verify->row('wallet') + $add_wallet_price->row('reffer_amount')));
                            // $msg = "Refer Commission";
                            // $this->db->insert('transaction_history', array('user_id' => $reffer_code_verify->row('id'), 'add_amount' => $add_wallet_price->row('reffer_amount'), 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'),'purpose'=>'credit'));
                        } else {
                            $result = array('status' => 0, 'msg' => 'Reffer code is not valid.');
                            return $result;
                        }
                    }else{
                        $reffer_code = "";
                    }
                    
                    $aid = "1";
                    if($this->input->post('agentId') && $this->input->post('agentId') !=""){
                        $aid = $this->input->post('agentId');
                    }
                    $data['name'] = $name;
                    $data['mobile'] = $mobile;
                    $data['my_reffer_code'] = $mobile;
                    $data['aid'] = $aid;
                    $data['reffer_code'] = $reffer_code;
                    $data['wallet'] = $WELCOME_BNUS;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['status'] = 0;
                    $this->db->insert('users', $data);
                    $insert_id = $this->db->insert_id();
                    $msg = "Welcome Bonus";
                    $this->db->insert('transaction_history', array('user_id' => $insert_id, 'add_amount' => $WELCOME_BNUS, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'),'purpose' => 'credit'));
                    $result = array('status' => 1, 'msg' => 'Login successfully','id' => $insert_id);
                    return $result;
                }
            }else{
                $result = array('status' => 0, 'msg' => 'Invalid OTP');
                return $result;
            }
        }
        
        //Otp send section
        if (empty($mobile)) {
            $result = array('status' => 0, 'msg' => 'Please enter mobile!!!');
            return $result;
        }
        $id_verify = $this->db->get_where('users', array('mobile' => $mobile));
        // $otp = 1234;
        $otp = rand(1000,9999);
        $userid = 0;
        $purpose = 0;
        
        if($OTP_AVAIALBLE){
//         $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://login.99smsservice.com/sms/api?action=send-sms&api_key=ckpDTUp6cnVPcnZhQUdvQ2ppTz0%3D&to='.$mobile.'&from=HARIOK&sms=Dear%20User%2C%0A%0AYour%20OTP%20is%20'.$otp.'%20Valid%20for%2010%20minutes.%20Please%20do%20not%20share%20this%20OTP.%0A%0ARegards%0AAghori%20Matka%0A%0Ahariom&p_entity_id=1101778010000075980&temp_id=1107171109383082610',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Cookie: laravel_session=eyJpdiI6IlZSK2RuTGJubzNqaGxSV2VBWm1ST2c9PSIsInZhbHVlIjoiUFVzU1wvZ3Y0TkVjQSttTExmZlNOaUhKOWNENktJSnFPUmRRanUxWHl2WHhCOHBwZzAwVDNHNDR6ZXlrWWtIYWxZdW1tMTlxd013UGhkWGNWWWgwXC80QT09IiwibWFjIjoiOTQzZGQ3MmVjYjljMzcwNTBiNGM0ZmI0OGJiMmJkMjMzMmY2MWU2MDZjMDg3ODg4MWFmZDg1NTE1NDFjZDQxNSJ9'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);

// echo $response;

//         $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://www.fast2sms.com/dev/bulkV2?authorization=65J0twILNfMioxOhjmgD4RryuXdTBSHbFsZWkVzEeC7c82pQKYYpdOZHv9ohEBcNeUfASbXWijzKtVFy&route=otp&variables_values='.$otp.'&flash=0&numbers='.$mobile.'&schedule_time=',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Cookie: XSRF-TOKEN=eyJpdiI6IlBlN0lyZXU3U3BOTFVzeHk3RzNuRFE9PSIsInZhbHVlIjoiWUNnY1VhRkllK01ySFVCWDh0S01PSm1MemN4ZklBSFphS2txMmxRXC9lTHZtcnFyMEJ6aVYxdU5NbHVGeVZjb3hSYTdkQmNjNEIzNTE3RlREbDRwOG9RPT0iLCJtYWMiOiJjNjRkZDhkNDcwYjgwZjk4MWI5YTQyNmIxZTVkMjQ5MzEyNzdlYzYyOWJmZjk1N2ViZjE0NDVlODQ4NzY5YWVkIn0%3D; aEhtueNWkWrMfy3ejzqhKHK7gbcgnRmoZwvI63N5=eyJpdiI6IktVaksyZVhSeFFCeW51K2NWU3NLN2c9PSIsInZhbHVlIjoiYWRuWHRaUGZhdTRtMCtqTGZ1cXdqdUpvQVhjNHNSVDMxTlE5eVAxdElCUWE0TDdRTXhSTjV3SDNjekdcL1BlRHJwV2o5VG9rR2RpK25YcmJGWVZDSGtyTnRnWXIycStOVFM3QXRPR2picCtqclBkbmluZXZXWkZnc0RcL0hZU0wwXC82RTFsTGFXWmpZRzBLTmZncDB3MUNHMWhISlIzUjhIU2NnWjZKalpPVkcxMnBhdDZcL1czXC9zelNyc21kbFY1WHBIc2NyMEFGMWoya0I4aFVrdEFzWnAwWWxBelNCVzhQdTY3MFNOaWIxK3o2WUNOTWNSOWJNUGZXdTd3bUJkUWh4ckZwck5yTzltdWJmRmFXQWY3T2lKZ29oTGVqWEJ3R3lQaSt3N1RyQzJkQjBBS21EK2dyamR6a1NYRFUxMzVvQzR2aUNtWEJOb1hKT210RXhOenFoVFA0bUFURGhFcjY5a2VRRVJ0K0RCVjlmYmFMMFlEUDd3anVlZFY2ZGZsVWR1ZDlMR3hXMmVWUFAxb0RNZkFGR3ZlcWsyMm9CbUFYcWljZ2dKSG90WHdnS3Zhd1V1OWFMSjR3NHhVakdCaFhENFFCU296cG1tK0xOQ2QxekNBSG00UVNLSnNsZkNIMlNXTVB2eFZWU2lPZk1Rb0hIWnJmUFQxOXh1emZWK1Fra04xS3dMQ1VxU2JTTmtLXC96RUZ4MEZZUXlnbDN2MmppUFpkNTFlZTBsemp3eE00MmJNTm1XSEZ4bE0zYUlnd2UrIiwibWFjIjoiNGI3MzFjOTVlYzM3ZTE1ZjE2NDRkODFjMWI1M2Y2YjhkNDI1YzM5OTM3YTVkZDIyZmQzN2NlZTQzMDE0Mzg3OSJ9; laravel_session=eyJpdiI6IjFcL1J4RjRpSGZ5T3plNDRKM1lRRVBBPT0iLCJ2YWx1ZSI6IkNvRE4xSEFadnJzUXh2bXZUaVdFcHFoSnhTN3BJdmhVbnVkMGtcL0tNZXRyaENUVDZ2cUVsTG9uc2pHYnBadFQrU2NhWXk3WkFYN0Y0OWI1TFV5MXV0UT09IiwibWFjIjoiMzg2N2RmMDE4YTA4YjM2YTdkMWQ3MWE5MTQ0OWNjYTQyN2JhOGM4NjVmODBmNGE5MDhiOWIyZDA0ZWI2NzQ3MSJ9'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v1.finixpay.co/api/v1/dltsmsotp/initiate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "otp":"'.$otp.'",
    "mobile":"'.$mobile.'",
    "companyname":"Mahadev Company"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'X-API-Key: $2y$10$wFqKOvHLOoD3usx3RoraZ.x.vM2ZSidCzE9eZLxw2qk9YGi8rRk/K'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
        if ($id_verify->num_rows() > 0) {
            $purpose = 0;
            $userid = $id_verify->result()[0]->id;
        }else{
            $purpose = 1;
        }
        if($OTP_AVAIALBLE){
            $result = array('status' => 1, 'msg' => 'OTP Send Successfully. OTP: ' . $otp, 'purpose' => $purpose, 'otp' => $otp);
        }else{
            $result = array('status' => 1, 'msg' => 'OTP Send Successfully. OTP: ' . $otp, 'purpose' => $purpose, 'otp' => $otp);
        }
        $data['phone'] = $mobile;
        $data['userid'] = $userid;
        $data['otp'] = $otp;
        $data['purpose'] = $purpose;
        $data['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert('user_login', $data);
        return $result;
    }

    public function profile()
    {
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }
        $users_data = $this->db->select('id, name, mobile, reffer_code, wallet')->get_where('users', array('id' => $user_id));
        $accounts = $this->db->select('bank_name, account_no, ifsc_code, holder_name, upi_no, paytm_no')->get_where('users', array('id' => $user_id))->row();
        $ssetting = $this->db->get_where('game_setting', array('id' => 1))->row();
        if ($users_data->num_rows() > 0) {

            foreach ($users_data->result() as $item) {
                $arr[] = array(
                    'id' => $item->id,
                    'name' => $item->name,
                    'mobile' => $item->mobile,
                    'wallet' => (number_format($item->wallet,0)),
                    'reffer_code' => $item->reffer_code,
                    'account' => $accounts,
                    'setting' => $ssetting
                );
            }

            $result = array('status' => 1, 'msg' => 'Profile Data Search Successful...!!!!', 'data' => $arr);
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'User ID Not Matched');
            return $result;
        }
    }

    public function terms_condition()
    {
        $users_data = $this->db->get_where('t&c');
        if ($users_data->num_rows() > 0) {

            $array = array(
                'id' => $users_data->row('id'),
                'description' => $users_data->row('description')
            );

            $result = array('status' => 1, 'msg' => 'Data Find Successful...!!!!', 'data' => $array);
            return $result;
        } else {

            $array = array(
                'id' => 0,
                'description' => 0
            );

            $result = array('status' => 0, 'msg' => 'Data not found', 'data' => $array);
            return $result;
        }
    }

    public function privacy_policy()
    {
        $users_data = $this->db->get_where('privacy_policy');
        if ($users_data->num_rows() > 0) {

            $array = array(
                'id' => $users_data->row('id'),
                'description' => $users_data->row('description')
            );

            $result = array('status' => 1, 'msg' => 'Data Find Successful...!!!!', 'data' => $array);
            return $result;
        } else {

            $array = array(
                'id' => 0,
                'description' => 0
            );

            $result = array('status' => 0, 'msg' => 'Data Not Found', 'data' => $array);
            return $result;
        }
    }

    public function about_us()
    {
        $users_data = $this->db->get_where('about_us');
        if ($users_data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Data Find Successful...!!!!', 'data' => $users_data->row());
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Data Not Found');
            return $result;
        }
    }

    public function add_bank()
    {
        $user_id = $this->input->post('user_id');
        $account_type = $this->input->post('account_type'); // 1 = bank details | 2 = upi details
        $upi_type = $this->input->post('upi_type'); // 1 = google pay | 2 = phone pay | 2 = paytm
        $bank_name = $this->input->post('bank_name');
        $account_no = $this->input->post('account_no');
        $ifsc_code = $this->input->post('ifsc_code');
        $holder_name = $this->input->post('holder_name');
        $upi_no = $this->input->post('upi_no');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }

        $users_data = $this->db->get_where('users', array('id' => $user_id));
        if (!$users_data->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!! Your user id is not valid');
            return $result;
        }

        if ($account_type == 1) {
            $data['bank_name'] = $bank_name;
            $data['holder_name'] = $holder_name;
            $data['ifsc_code'] = $ifsc_code;
            $data['account_no'] = $account_no;
            // $data['user_id'] = $user_id;
        }

        if ($account_type == 2 && $upi_type == 1) {
            $data['google_pay'] = $upi_no;
        }

        if ($account_type == 2 && $upi_type == 2) {
            $data['phone_pe'] = $upi_no;
        }

        if ($account_type == 2 && $upi_type == 3) {
            $data['paytm_no'] = $upi_no;
        }

        $check_id = $this->db->get_where('users', array('id' => $user_id));

        if ($check_id->num_rows() > 0) {
            $this->db->where('id', $user_id)->update('users', $data);

            $get_data = $this->db->get_where('users', array('id' => $user_id));

            $get_data_arr[] = array(
                'id' => $get_data->row('id'),
                'user_id' => $get_data->row('id'),
                'bank_name' => $get_data->row('bank_name'),
                'ac_holder_name' => $get_data->row('holder_name'),
                'bank_ac_no' => $get_data->row('account_no'),
                'transaction_id' => "",
                'ifsc_code' => $get_data->row('ifsc_code'),
                'phone_pe' => $get_data->row('phone_pe'),
                'google_pay' => $get_data->row('google_pay'),
                'paytm' => $get_data->row('paytm_no')
            );

            if ($this->db->affected_rows() > 0) {
                $result = array('status' => 1, 'msg' => 'Successful..!!! Your bank details has been added', 'data' => $get_data_arr);
                return $result;
            } else {
                $result = array('status' => 0, 'msg' => 'Sorry...!!! request failed');
                return $result;
            }
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
            return $result;
        }
    }

    public function update_profile()
    {
        $user_id = $this->input->post('user_id');
        $name = $this->input->post('name');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter user id');
            return $result;
        }

        if (empty($name)) {
            $result = array('status' => 0, 'msg' => 'Please enter name');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        if ($id_verify->num_rows() > 0) {
            $this->db->where('id', $user_id)->update('users', array('name' => $name));
            $get_updated_name = $this->db->get_where('users', array('id' => $user_id))->row('name');
            $result = array('status' => 1, 'msg' => 'Successful...!!! Your name has been updated', 'name' => $get_updated_name);
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Your user id is not valid');
            return $result;
        }
    }
    public function updatefcm()
    {
        $user_id = $this->input->post('user_id');
        $token = $this->input->post('token');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter user id');
            return $result;
        }

        if (empty($token)) {
            $result = array('status' => 0, 'msg' => 'Please enter token');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        if ($id_verify->num_rows() > 0) {
            $this->db->where('id', $user_id)->update('users', array('fcm_token' => $token));
            $result = array('status' => 1);
            return $result;
        } else {
            $result = array('status' => 0);
            return $result;
        }
    }

    public function check_account()
    {
        $user_id = $this->input->post('user_id');
        $data_get = $this->db->get_where('users', array('id' => $user_id));

        if (!$data_get->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Bank not added');
            return $result;
        }

        if (empty($data_get->row('bank_name')) && empty($data_get->row('google_pay')) && empty($data_get->row('phone_pe')) && empty($data_get->row('paytm_no'))) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Details not added');
            return $result;
        }

        $bank_details_arr = array(
            'user_name' => $data_get->row('holder_name'),
            'bank_name' => $data_get->row('bank_name'),
            'bank_ac_no' => $data_get->row('account_no'),
            'ifsc_code' => $data_get->row('ifsc_code')
        );

        if (!empty($data_get->row('google_pay'))) {
            $status_google_pay = 1;
        } else {
            $status_google_pay = 0;
        }
        if (!empty($data_get->row('phone_pe'))) {
            $status_phone_pe = 1;
        } else {
            $status_phone_pe = 0;
        }
        if (!empty($data_get->row('paytm_no'))) {
            $status_paytm = 1;
        } else {
            $status_paytm = 0;
        }

        $gpay_image_get = $this->db->get_where('payments', array('id' => 1));
        $phone_pay_image_get = $this->db->get_where('payments', array('id' => 2));
        $paytm_image_get = $this->db->get_where('payments', array('id' => 3));

        $upi_details = array(
            array(
                'type' => 1,
                'name' => 'GooglePay',
                'number' => $data_get->row('google_pay'),
                'company_img' => $gpay_image_get->row('upi_company_img'),
                'status' => $status_google_pay
            ),
            array(
                'type' => 2,
                'name' => 'PhonePay',
                'number' => $data_get->row('phone_pe'),
                'company_img' => $phone_pay_image_get->row('upi_company_img'),
                'status' => $status_phone_pe
            ),
            array(
                'type' => 3,
                'name' => 'Paytm',
                'number' => $data_get->row('paytm_no'),
                'company_img' => $paytm_image_get->row('upi_company_img'),
                'status' => $status_paytm
            )
        );

        $get_help_no = $this->db->get_where('game_setting', array('contact_info'));

        if ($data_get->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'help_no' => $get_help_no->row('contact_info'), 'bank_details' => $bank_details_arr, 'upi_details' => $upi_details);
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! data not found');
            return $result;
        }
    }

    public function check_reffer_code()
    {
        $reffer_code = $this->input->post('reffer_code');

        if (empty($reffer_code)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }

        $users_data = $this->db->get_where('users', array('my_reffer_code' => $reffer_code));
        if ($users_data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Reffer Code Validate successful..!!');
            return $result;
        }

        $result = array('status' => 0, 'msg' => 'Reffer Code Not Valid..!!');
        return $result;
    }

    public function help()
    {
        $get_data = $this->db->get_where('game_setting');

        if ($get_data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $get_data->row('contact_info'));
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry!!! data not found');
            return $result;
        }
    }

    public function add_money_details()
    {
        $get_data = $this->db->get_where('game_setting');
        $get_payment = $this->db->select('id, name, upi_num, account_number, ifsc_code, bank_name, account_holder_name, qr_img, upi_company_img')->get_where('payments');

        $get_help_no = $this->db->get_where('game_setting');

        if ($get_payment->num_rows() > 0) {

            $data_arr = array(
                'min_deposit' => $get_data->row('deposit_amount'),
                'min_withdrawal' => $get_data->row('withdrawal_amount'),
                'withdrawal_hold_amt' => $get_data->row('withdrawal_hold_amt'),
                'payment_accounts' => $get_payment->result(),
            );

            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'help_no' => $get_help_no->row('contact_info'), 'data' => $data_arr);
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! data not found');
            return $result;
        }
    }

    public function add_money()
    {
        $user_id = $this->input->post('user_id');
        $transaction_id = $this->input->post('transaction_id');
        $screenshot_img = $this->input->post('screenshot_img');
        $amount = $this->input->post('amount');
        $upi_no = $this->input->post('upi_no');
        $payment_type = $this->input->post('payment_type'); // 1 = google pay | 2 = phone pay | 3 = paytm | 4 = bank
        $payment_status = $this->input->post('payment_status'); // 1 = add money request | 2 = withdrawal request

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter user id');
            return $result;
        }

        if (empty($amount)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter your amount');
            return $result;
        }

        if (empty($payment_type)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter payment type');
            return $result;
        }

        $get_payment_table = $this->db->where_not_in('transaction_id', "")->where('transaction_id', $transaction_id)->get('payment_request');
        if ($payment_status == "1") {
            if ($get_payment_table->num_rows() > 0) {
                $result = array('status' => 0, 'msg' => 'Sorry...!!! UTR number is invalid');
                return $result;
            }
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        $get_minimum_withwrawal_amt = $this->db->get_where('game_setting');

        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
            return $result;
        }
        
        $aid = $id_verify->row('aid');

        if ($payment_status == 1) {

            if ($get_minimum_withwrawal_amt->row('deposit_amount') > $amount) {
                $result = array('status' => 0, 'msg' => "Sorry...!!! please enter minimum deposit balance " . $get_minimum_withwrawal_amt->row('deposit_amount'));
                return $result;
            }

            $msg = "Your have sent  Rs." . $amount . " wallet request successfully!";
            $this->db->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 2, 'date' => date("Y-m-d"), 'time' => date('H:i:s'))); // reffer sender user    
        }

        if ($payment_status == 2) {
            if ($get_minimum_withwrawal_amt->row('withdrawal_amount') > $amount) {
                $result = array('status' => 0, 'msg' => "Sorry...!!! please enter minimum withdrawal balance " . $get_minimum_withwrawal_amt->row('withdrawal_amount'));
                return $result;
            }
            if ($id_verify->row('wallet') < $amount) {
                $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient');
                return $result;
            }

            if ($id_verify->row('wallet') - $amount < $get_minimum_withwrawal_amt->row('withdrawal_hold_amt')) {
                $result = array('status' => 0, 'msg' => 'Sorry...!!! you must have ' . $get_minimum_withwrawal_amt->row('withdrawal_hold_amt') . ' rupees in your wallet');
                return $result;
            }

            $msg = "Your Rs." . $amount . " withdrawal request sent successfully!";
            $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 4, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
        }

        if ($payment_type == 1) {

            if ($payment_status == 2) {

                if (!empty($user_id) && !empty($amount)) {

                    $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $amount));

                    $insert_data = array(
                        'google_pay' => $upi_no,
                        'transaction_id' => $transaction_id,
                        'amount' => $amount,
                        'user_id' => $user_id,
                        'date' => date("Y-m-d"),
                        'time' => date("H:i:s"),
                        'type' => 2,
                    );
                }
            } else {

                $insert_data = array(
                    'google_pay' => $upi_no,
                    'transaction_image' => base64_upload($screenshot_img, "./assets/images"),
                    'transaction_id' => $transaction_id,
                    'amount' => $amount,
                    'user_id' => $user_id,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s"),
                    'type' => 1,
                );
            }
        }

        if ($payment_type == 2) {

            if ($payment_status == 2) {

                if (!empty($user_id) && !empty($amount)) {

                    $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $amount));

                    $insert_data = array(
                        'phone_pe' => $upi_no,
                        'transaction_id' => $transaction_id,
                        'amount' => $amount,
                        'user_id' => $user_id,
                        'date' => date("Y-m-d"),
                        'time' => date("H:i:s"),
                        'type' => 2,
                    );
                }
            } else {

                $insert_data = array(
                    'phone_pe' => $upi_no,
                    'transaction_image' => base64_upload($screenshot_img, "./assets/images"),
                    'transaction_id' => $transaction_id,
                    'amount' => $amount,
                    'user_id' => $user_id,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s"),
                    'type' => 1,
                );
            }
        }

        if ($payment_type == 3) {

            if ($payment_status == 2) {

                if (!empty($user_id) && !empty($amount)) {

                    $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $amount));

                    $insert_data = array(
                        'paytm' => $upi_no,
                        'transaction_id' => $transaction_id,
                        'amount' => $amount,
                        'user_id' => $user_id,
                        'date' => date("Y-m-d"),
                        'time' => date("H:i:s"),
                        'type' => 2,
                    );
                }
            } else {

                $insert_data = array(
                    'paytm' => $upi_no,
                    'transaction_image' => base64_upload($screenshot_img, "./assets/images"),
                    'transaction_id' => $transaction_id,
                    'amount' => $amount,
                    'user_id' => $user_id,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s"),
                    'type' => 1,
                );
            }
        }

        if ($payment_type == 4) {

            if ($payment_status == 2) {

                if (!empty($id_verify->row('bank_name') && !empty($id_verify->row('account_no')) && !empty($id_verify->row('ifsc_code')) && !empty($id_verify->row('holder_name')))) {

                    $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $amount));
                    
                    $insert_data = array(
                        'bank_name' => $id_verify->row('bank_name'),
                        'transaction_id' => $transaction_id,
                        'bank_ac_no' => $id_verify->row('account_no'),
                        'ifsc_code' => $id_verify->row('ifsc_code'),
                        'ac_holder_name' => $id_verify->row('holder_name'),
                        'amount' => $amount,
                        'user_id' => $user_id,
                        'date' => date("Y-m-d"),
                        'time' => date("H:i:s"),
                        'type' => 2
                    );
                } else {
                    $result = array('status' => 0, 'msg' => 'Sorry...!!! please add your bank');
                    return $result;
                }
            }
        }
        
        $insert_data['aid'] = $aid;

        $this->db->insert('payment_request', $insert_data);

        $updated_wallet = $this->db->get_where('users', array('id' => $user_id));

        if ($this->db->affected_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!! payment request', 'wallet' => $updated_wallet->row('wallet'));
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! request failed');
            return $result;
        }
    }
    public function withdraw_money()
    {
        $user_id = $this->input->post('user_id');
        $transaction_id = date("YmdHis");
        $number = $this->input->post('number');
        $amount = $this->input->post('amount');
        $bank_name = $this->input->post('bank_name');
        $ac_holder_name = $this->input->post('ac_holder_name');
        $bank_ac_no = $this->input->post('bank_ac_no');
        $ifsc_code = $this->input->post('ifsc_code');
        $payment_type = $this->input->post('type'); // 1 = google pay | 2 = phone pay | 3 = paytm | 4 = bank
        $payment_status = 2; // 1 = add money request | 2 = withdrawal request
        
        // $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter user id');
        //     return $result;
            
        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter user id');
            return $result;
        }

        if (empty($amount)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter your amount');
            return $result;
        }

        if (empty($payment_type)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter payment type');
            return $result;
        }
        
        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        $get_minimum_withwrawal_amt = $this->db->get_where('game_setting');

        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
            return $result;
        }
        $aid = $id_verify->row('aid');
        if ($get_minimum_withwrawal_amt->row('withdrawal_amount') > $amount) {
            $result = array('status' => 0, 'msg' => "Sorry...!!! please enter minimum withdrawal balance " . $get_minimum_withwrawal_amt->row('withdrawal_amount'));
            return $result;
        }
        if ($id_verify->row('wallet') < $amount) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient');
            return $result;
        }

        $msg = "Your Rs." . $amount . " withdrawal request sent successfully!";
        $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 4, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
        
        $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $amount));
        $insert_data = array(
            'transaction_id' => $transaction_id,
            'amount' => $amount,
            'user_id' => $user_id,
            'date' => date("Y-m-d"),
            'time' => date("H:i:s"),
            'bank_name' => $payment_type,
            'bank_ac_no' => $number,
            'type' => 2,
        );
        if($payment_type == "Bank"){
            $insert_data['bank_name'] = $bank_name;
            $insert_data['ac_holder_name'] = $ac_holder_name;
            $insert_data['bank_ac_no'] = $bank_ac_no;
            $insert_data['ifsc_code'] = $ifsc_code;

            // Also save/update the user's profile with their bank details
            $this->db->where('id', $user_id)->update('users', array(
                'bank_name' => $bank_name,
                'account_no' => $bank_ac_no,
                'ifsc_code' => $ifsc_code,
                'holder_name' => $ac_holder_name
            ));
        }
        $insert_data['aid'] = $aid;

        $this->db->insert('payment_request', $insert_data);

        $updated_wallet = $this->db->get_where('users', array('id' => $user_id));

        if ($this->db->affected_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Withdrawal Successfully added', 'wallet' => $updated_wallet->row('wallet'));
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! request failed');
            return $result;
        }
    }
    // public function add_moneyAutomatic()
    // {
    //     $user_id = $this->input->post('user_id');
    //     $amount = $this->input->post('amount');
    //     $transaction_id = "MDVMTKA".date("YmdHis");
    //     if (empty($user_id)) {
    //         $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter user id');
    //         return $result;
    //     }

    //     if (empty($amount)) {
    //         $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter your amount');
    //         return $result;
    //     }

    //     $id_verify = $this->db->get_where('users', array('id' => $user_id));

    //     $get_minimum_withwrawal_amt = $this->db->get_where('game_setting');

    //     if (!$id_verify->num_rows() > 0) {
    //         $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
    //         return $result;
    //     }
    //     if ($get_minimum_withwrawal_amt->row('deposit_amount') > $amount) {
    //         $result = array('status' => 0, 'msg' => "please enter minimum deposit balance " . $get_minimum_withwrawal_amt->row('deposit_amount'));
    //         return $result;
    //     }
    //     $insert_data = array(
    //         'transaction_id' => $transaction_id,
    //         'amount' => $amount,
    //         'user_id' => $user_id,
    //         'date' => date("Y-m-d"),
    //         'time' => date("H:i:s"),
    //         'type' => 1,
    //         'status' => 0,
    //     );

    //     $this->db->insert('payment_request', $insert_data);
    //     $email = "user@gmail.com";
    //     $mobile = "1234567890";
    //     // $amount = 1;
    //     //Initiate payment request
    //     $curl = curl_init();
    //     $TOKEN = '$2y$10$j24R3lDVi54vEIw35aBERORqPIZRmd5R0aqsqNawbq7ElCZKmR/I.';
    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => "https://merchant.rudraxpay.com/api/pg/phonepe/initiate",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => "{\n  \"token\": \"$TOKEN\",\n  \"orderid\": \"$transaction_id\",\n  \"amount\": \"$amount\",\n  \"userid\": \"RXP10089\",\n  \"customer_name\": \"$user_id\",\n  \"customer_email\": \"$email\",\n  \"mobile\": \"$mobile\",\n  \"callback_url\": \"https://user.aghorimatka.com/dashboard\",\n  \"udf1\": \"user defined field 1 (max 25 char)\",\n  \"udf2\": \"user defined field 2 (max 25 char)\",\n  \"udf3\": \"user defined field 3 (max 25 char)\"\n}",
    //         CURLOPT_HTTPHEADER => [
    //             "Accept: */*",
    //             "Content-Type: application/json"
    //         ],
    //      ]);
    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     curl_close($curl);
    //     if ($err) {
    //         $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','err' => $err);
    //         return $result;
    //     } else {
    //         $res = json_decode($response);
    //         if($res->status == true){
    //             $result = array('status' => 1, 'msg' => 'Successful...!!! payment initiated', 'url' => $res->url,'data'=>$res);
    //             return $result;
    //         }else{
    //             $result = array('status' => 0, 'msg' => $res->message);
    //             return $result;
    //         }
    //     }
    // }
    public function add_moneyAutomatic()
    {
        $user_id = $this->input->post('user_id');
        $amount = $this->input->post('amount');
        $transaction_id = "MDVMTKA".date("YmdHis");
        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter user id');
            return $result;
        }

        if (empty($amount)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter your amount');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        $get_minimum_withwrawal_amt = $this->db->get_where('game_setting');

        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
            return $result;
        }
        if ($get_minimum_withwrawal_amt->row('deposit_amount') > $amount) {
            $result = array('status' => 0, 'msg' => "please enter minimum deposit balance " . $get_minimum_withwrawal_amt->row('deposit_amount'));
            return $result;
        }
        $insert_data = array(
            'transaction_id' => $transaction_id,
            'amount' => $amount,
            'user_id' => $user_id,
            'date' => date("Y-m-d"),
            'time' => date("H:i:s"),
            'type' => 1,
            'status' => 0,
        );
        $this->db->insert('payment_request', $insert_data);
        $email = "user@gmail.com";
        $mobile = "1234567890";
        $aidd = $id_verify->row('aid');
        if($aidd != "" && $aidd != "1"){
            // $TOKEN = "980a496a-da69-41df-9dd9-09ef4237c02a";
            $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $aidd))->row();
            if($admin_data->upigatewaytoken !=""){
                $agentIds = $admin_data->upigatewaytoken;
                $TOKEN = $agentIds;
            }
            // $amount = 1;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://merchant.upigateway.com/api/create_order",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n  \"key\": \"$TOKEN\",\n  \"client_txn_id\": \"$transaction_id\",\n  \"amount\": \"$amount\",\n  \"p_info\": \"Recharge\",\n  \"customer_name\": \"$user_id\",\n  \"customer_email\": \"$email\",\n  \"customer_mobile\": \"$mobile\",\n  \"redirect_url\": \"https://user.matkamahadev.com/dashboard\",\n  \"udf1\": \"user defined field 1 (max 25 char)\",\n  \"udf2\": \"user defined field 2 (max 25 char)\",\n  \"udf3\": \"user defined field 3 (max 25 char)\"\n}",
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json"
                ],
             ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','err' => $err);
                return $result;
            } else {
                $res = json_decode($response);
                // echo $response;
                if($res->status == true){
                    $result = array('status' => 1, 'msg' => 'Successful...!!! payment initiated', 'url' => $res->data->payment_url);
                    return $result;
                }else{
                    $result = array('status' => 0, 'msg' => "Something wents wrong! Contact Admin");
                    return $result;
                }
            }
        }
        // $amount = 5;
        //Initiate payment request
        $flagPayment = $get_minimum_withwrawal_amt->row('gateway');
        if($flagPayment == 1){
            $aidd = $id_verify->row('aid');
            // $TOKEN = "980a496a-da69-41df-9dd9-09ef4237c02a";
            $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $aidd))->row();
            if($admin_data->upigatewaytoken !=""){
                $agentIds = $admin_data->upigatewaytoken;
                $TOKEN = $agentIds;
            }
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://merchant.upigateway.com/api/create_order",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n  \"key\": \"$TOKEN\",\n  \"client_txn_id\": \"$transaction_id\",\n  \"amount\": \"$amount\",\n  \"p_info\": \"Recharge\",\n  \"customer_name\": \"MDMTKA_$user_id\",\n  \"customer_email\": \"$email\",\n  \"customer_mobile\": \"$mobile\",\n  \"redirect_url\": \"https://user.matkamahadev.com/dashboard\",\n  \"udf1\": \"user defined field 1 (max 25 char)\",\n  \"udf2\": \"user defined field 2 (max 25 char)\",\n  \"udf3\": \"user defined field 3 (max 25 char)\"\n}",
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json"
                ],
             ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','err' => $err);
                return $result;
            } else {
                $res = json_decode($response);
                if($res->status == true){
                    $result = array('status' => 1, 'msg' => 'Successful...!!! payment initiated', 'url' => $res->data->payment_url);
                    return $result;
                }else{
                    $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','aa'=>$res);
                    return $result;
                }
            }
        }
        
        if($flagPayment == 2){
            $curl = curl_init();
            $post_data = [
                'customer_mobile' => $mobile,
                'user_token' => '7a3d263fb4a19ba2e37ea5a0555fff60',
                'amount' => $amount,
                'order_id' => $transaction_id,
                'redirect_url' => 'https://user.aghorimatka.com/dashboard'
            ];
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://pay0.shop/api/create-order",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($post_data),
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/x-www-form-urlencoded"
                ],
             ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','err' => $err);
                return $result;
            } else {
                if(!$response){
                    return array('status' => 0, 'msg' => 'Something wents wrong');
                }
                $res = json_decode($response);
                if($res->status == true){
                    $result = array('status' => 1, 'msg' => 'Successful...!!! payment initiated', 'url' => $res->result->payment_url);
                    return $result;
                }else{
                    $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin');
                    return $result;
                }
            }
        }
        
        if($flagPayment == 3){
            $curl = curl_init();

            curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://merchant.rudraxpay.com/api/pg/phonepe/initiate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "token": "$2y$10$j24R3lDVi54vEIw35aBERORqPIZRmd5R0aqsqNawbq7ElCZKmR/I.",
    "userid": "RXP10089",
    "amount": '.$amount.',
    "mobile": "1234567890",
    "name": "Mia Khalifa",
    "orderid": "'.$transaction_id.'",
    "callback_url": "https://user.aghorimatka.com/dashboard"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
            
            $err = curl_error($curl);
            $response = curl_exec($curl);
            
            curl_close($curl);

            if ($err) {
                $result = array('status' => 0, 'msg' => 'Something wents wrong! Contact Admin','err' => $err);
                return $result;
            } else {
                if(!$response){
                    return array('status' => 0, 'msg' => 'Something wents wrong');
                }
                $res = json_decode($response);
                if($res->status == true){
                    $result = array('status' => 1, 'msg' => 'Successful...!!! payment initiated', 'url' => $res->url);
                    return $result;
                }else{
                    $result = array('status' => 0, 'msg' => $res->message);
                    return $result;
                }
            }
        }
        
    }

    public function add_moneyCallback($status,$client_txn_id,$utr){
        $result = array('status' => 0, 'msg' => 'Not found!!');
        if($status == "success"){
            $id_verify = $this->db->get_where('payment_request', array('transaction_id' => $client_txn_id, 'status' => 0));
            if($id_verify->num_rows() > 0){
                $user_id = $id_verify->row('user_id');
                $amount = $id_verify->row('amount');
                $this->db->set('wallet', 'wallet + ' . (int)$amount, FALSE)->where('id', $user_id)->update('users');
                $this->db->where('transaction_id', $client_txn_id)->update('payment_request', array('status' => 1,'bank_ac_no' => $utr));
                $result = array('status' => 1, 'msg' => 'Successfully added');
                $msg = "Deposit Point Accepted";
                $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose' => 'credit'));
            }
        }elseif($status == "failure"){
            $id_verify = $this->db->get_where('payment_request', array('transaction_id' => $client_txn_id, 'status' => 0));
            if($id_verify->num_rows() > 0){
                $user_id = $id_verify->row('user_id');
                $amount = $id_verify->row('amount');
                $this->db->where('transaction_id', $client_txn_id)->update('payment_request', array('status' => 2));
                $result = array('status' => 1, 'msg' => 'Successfully added');
            }
        }
        return $result;
    }
    // public function add_moneyCallback($status,$client_txn_id,$utr){
    //     $result = array('status' => 0, 'msg' => 'Not found!!');
    //     if($status == "success"){
    //         $id_verify = $this->db->get_where('payment_request', array('transaction_id' => $client_txn_id, 'status' => 0));
    //         if($id_verify->num_rows() > 0){
    //             $user_id = $id_verify->row('user_id');
    //             $amount = $id_verify->row('amount');
    //             $this->db->set('wallet', 'wallet + ' . (int)$amount, FALSE)->where('id', $user_id)->update('users');
    //             $this->db->where('transaction_id', $client_txn_id)->update('payment_request', array('status' => 1,'bank_ac_no' => $utr));
    //             $result = array('status' => 1, 'msg' => 'Successfully added');
    //             $msg = "Deposit Point Accepted";
    //             $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose' => 'credit'));
    //         }
    //     }elseif($status == "failure"){
    //         $id_verify = $this->db->get_where('payment_request', array('transaction_id' => $client_txn_id, 'status' => 0));
    //         if($id_verify->num_rows() > 0){
    //             $user_id = $id_verify->row('user_id');
    //             $amount = $id_verify->row('amount');
    //             $this->db->where('transaction_id', $client_txn_id)->update('payment_request', array('status' => 2));
    //             $result = array('status' => 1, 'msg' => 'Successfully added');
    //         }
    //     }
    //     return $result;
    // }
    public function reffer_rules()
    {
        $get_data = $this->db->get_where('reffer_rule');

        if ($get_data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $get_data->row());
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Data not found!!!');
            return $result;
        }
    }

    public function game_list()
    {
        // upcomming game logic
        $get_data = $this->db->get_where('create_game', array('status' => 0));
        // $get_data = $this->db->get_where('create_game', array('status' => 0, 'start_date >=' => date('Y-m-d')));
        // echo json_encode(array('status' => 0, 'start_date' => date('Y-m-d'), 'end_date >=' => date('Y-m-d'), 'start_time >' => date('H:i:s')));
        if (!$get_data->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'No game avilable!!!');
            // return $result;
        }
        $upcomming_arr = [];
        $running_arr = [];
        
        $banner = $this->db->get_where('banner_image', array('status' => 0));
        $noticeData = $this->db->get_where('heading_text', array('id' => 1));

        foreach ($get_data->result() as $row_item) {
            $DateTime = $row_item->start_date." ".$row_item->start_time;
            $DateTimeEnd = $row_item->end_date." ".$row_item->end_time;
            
            // Create DateTime objects from these strings
            $startDateTime = new DateTime($DateTime);
            $endDateTime = new DateTime($DateTimeEnd);
            
            // Get the current date and time as a DateTime object
            $currentDateTime = new DateTime();
            
            // Compare the DateTime objects
            if ($startDateTime > $currentDateTime || $endDateTime < $currentDateTime) {
                $game_name = $this->db->order_by('id', 'DESC')->get_where('game', array('id' => $row_item->game_id));
                $game_nameResult = $this->db->order_by('id', 'DESC')->get_where('game_result', array('game_id' => $row_item->game_id, 'date' => date('Y-m-d')) , 1);
                
                $resultthis = "XX";
                $resultthisold = "XX";
                if($game_nameResult->num_rows() > 0){
                    $resultthis = $game_nameResult->row('number') > 9 ? $game_nameResult->row('number') : "0".$game_nameResult->row('number');
                }
                
                $game_nameResultTh = $this->db->order_by('id', 'DESC')->limit(1)->get_where('game_result', array('game_id' => $row_item->game_id,'date' => date('Y-m-d', strtotime('-1 day'))));
                
                if($game_nameResultTh->num_rows() > 0){
                    $resultthisold = $game_nameResultTh->row('number') > 9 ? $game_nameResultTh->row('number') : "0".$game_nameResultTh->row('number');
                }
                $upcomming_arr[] = array(
                    'game_id' => $row_item->id,
                    'match_id' => $row_item->game_id,
                    'game_name' => $game_name->row('game_name'),
                    'sequence' => $game_name->row('squence'),
                    'start_date' => $row_item->start_date,
                    'start_time' => date("h:i A", strtotime($row_item->start_time)),
                    'end_date' => $row_item->end_date,
                    'end_time' => date("h:i A", strtotime($row_item->end_time)),
                    'result_date' => $row_item->result_date,
                    'result_time' => date("h:i A", strtotime($row_item->result_time)),
                    'result' => $resultthis,
                    'result_old' => $resultthisold,
                    'active' => false
                );
            }
        }
        

        // running game logic
        // $get_data2 = $this->db->get_where('create_game', array('status' => 0, 'start_date' => date('Y-m-d'), 'end_date >=' => date('Y-m-d'), 'start_time <=' => date('H:i:s')));
        $this->db->where('status', 0); // Running games only
        $this->db->where('start_date <=', date('Y-m-d')); // Start date is today or earlier
        $this->db->where('end_date >=', date('Y-m-d')); // End date is today or later
        
        // Combine start time and end time with date
        $current_datetime = date('Y-m-d H:i:s');
        $this->db->where('CONCAT(start_date, " ", start_time) <=', $current_datetime); // Started already
        $this->db->where('CONCAT(end_date, " ", end_time) >=', $current_datetime); // Not ended yet
        
        $get_data2 = $this->db->get('create_game');


        if (!$get_data2->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'No game avilable!!!');
            // return $result;
        }

        foreach ($get_data2->result() as $row_item2) {
            $game_name2 = $this->db->order_by('id', 'DESC')->get_where('game', array('id' => $row_item2->game_id));
            if($game_name2->row('game_name') == "null" || $game_name2->row('game_name') == null){
            }else{
                $game_nameResult = $this->db->order_by('id', 'DESC')->get_where('game_result', array('game_id' => $row_item2->game_id, 'date' => date('Y-m-d')) , 1);
                $resultthis = "XX";
                $resultthisold = "XX";
                if($game_nameResult->num_rows() > 0){
                    $resultthis = $game_nameResult->row('number') > 9 ? $game_nameResult->row('number') : "0".$game_nameResult->row('number');
                }
                $game_nameResultTh = $this->db->order_by('id', 'DESC')->limit(1)->get_where('game_result', array('game_id' => $row_item->game_id,'date' => date('Y-m-d', strtotime('-1 day'))));
                if($game_nameResultTh->num_rows() > 0){
                    $resultthisold = $game_nameResultTh->row('number') > 9 ? $game_nameResultTh->row('number') : "0".$game_nameResultTh->row('number');
                }
            $upcomming_arr[] = array(
                'game_id' => $row_item2->id,
                'match_id' => $row_item2->game_id,
                'game_name' => $game_name2->row('game_name'),
                'sequence' => $game_name2->row('squence'),
                'start_date' => $row_item2->start_date,
                'start_time' => date("h:i A", strtotime($row_item2->start_time)),
                'end_date' => $row_item2->end_date,
                'end_time' => date("h:i A", strtotime($row_item2->end_time)),
                'result_date' => $row_item2->result_date,
                'result_time' => date("h:i A", strtotime($row_item2->result_time)),
                'result' => $resultthis,
                'result_old' => $resultthisold,
                'active' => true
            );
            }
        }
        
        $this->db->select('game_result.*, game.game_name');
        $this->db->from('game_result');
        $this->db->join('game', 'game.id = game_result.game_id', 'left');
        $this->db->order_by('game_result.id', 'DESC');
        $this->db->limit(1);
        $lastResult = $this->db->get();

        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'LastResult' => $lastResult->result(), 'notice'=>$noticeData->result(),'running_game' => $running_arr, 'upcomming_game' => $upcomming_arr, 'banner' => $banner->result());
        return $result;

        //-------------------------------#New backup end#-------------------------------//
    }
    public function formatDate($dateTime,$format) {
    // Create a DateTime object from the input string
    $date = new DateTime($dateTime);
    
    // Return the formatted date in dd-mm-YYYY format
    return $date->format($format);
    }
    public function game_chart()
    {
        $game_id = $this->input->post('game_id');
        if (empty($game_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Please enter Game id');
            return $result;
        }
        // running game logic
        $this->db->where('game_id', $game_id); // Add the condition
        $this->db->order_by('id', 'DESC'); // Order by ID in descending order
        $get_data2 = $this->db->get('game_result'); // Fetch the data
        $GAMECHART = [];
        $get_data = $this->db->get_where('game', array('id' => $game_id));
        if($get_data->num_rows() == 0){
            $result = array('status' => 0, 'msg' => 'No data found!', 'chart' => $GAMECHART);
            return $result;
        }
        foreach ($get_data2->result() as $row_item) {
            $DateTime = $this->formatDate($row_item->date." ".$row_item->time, "d M Y");
            $resultthis = "XX";
            $GAMECHART[] = array(
                'date' => $DateTime,
                'number' => $row_item->number > 9 ? $row_item->number : "0".$row_item->number,
            );
        }
        
        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'chart' => $GAMECHART, 'name'=>$get_data->row('game_name'));
        return $result;

        //-------------------------------#New backup end#-------------------------------//
    }

//     public function game_bet()
//     {
//         $user_id = $this->input->post('user_id');
//         $game_id = $this->input->post('game_id');
//         $game_type = $this->input->post('game_type');
//         $bet_numbers_arr = $this->input->post('bet_numbers');
//         $grand_total = $this->input->post('grand_total');

//         if (empty($user_id)) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
//             return $result;
//         }

//         if (empty($game_id)) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game id!!!');
//             return $result;
//         }

//         // if (empty($game_type)) {
//         //     $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game type!!!');
//         //     return $result;
//         // }

//         if (empty($bet_numbers_arr)) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter bet numbers!!!');
//             return $result;
//         }

//         if ($bet_numbers_arr == "[]") {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter bet numbers!!!');
//             return $result;
//         }

//         $id_verify = $this->db->get_where('users', array('id' => $user_id, 'status' => 0));
//         if (!$id_verify->num_rows() > 0) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid user id!!!');
//             return $result;
//         }

//         // if ($id_verify->row('wallet') < $grand_total) {
//         //     $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
//         //     return $result;
//         // }
        
//         $gameId_verify = $this->db->get_where('create_game', array('id' => $game_id, 'status' => 0));

//         if (!$gameId_verify->num_rows() > 0) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid game id!!!');
//             return $result;
//         }
//         $GameDataById = $gameId_verify->result();
//         $MotiJodiEnd = false;
        
//         // Combine the date and time into a valid DateTime string.
//         $endDateTime = $gameId_verify->row('end_date') . ' ' . $gameId_verify->row('end_time');
//         $resultDateTime = $gameId_verify->row('result_date') . ' ' . $gameId_verify->row('result_time');
//         if(strtotime($endDateTime) < time()){
//             $result = array('status' => 0, 'msg' =>"Betting not Allowed!");
//             return $result;
//         }
        
//         // Compare with the current date and time.
//         if (strtotime($resultDateTime) < time()) {
//             $MotiJodiEnd = true;  // Set $MotiJodiEnd to true if the event has ended.
//         }
//         $motijodimax = $this->db->get_where('game_setting');
//         $MaxValueForMotiJodi = $motijodimax->row('motijodimax');
        
//         $AGENTID = $id_verify->row('aid');
//         $get_AgentData = $this->Crud_model->fetch('admin_login', array('id' => $AGENTID));
//         if($AGENTID && $get_AgentData){
//             $MaxValueForMotiJodi = $get_AgentData->row('motijodimax');
//         }
//         $REFFERALCommission = $motijodimax->row('commission_amount');
//         $MotiJodiNumber = [];
//         $gameName_verify = $this->db->get_where('game', array('id' => $GameDataById[0]->game_id));
//         $GameNameRes = $gameName_verify->result();
//         $grand_total = 0;
//         $grand_totals = $this->input->post('grand_total');
//         $GameName = $GameNameRes[0]->game_name;
//         $game_type = 1;
//         $aid = $id_verify->row('aid');
//         foreach (json_decode($bet_numbers_arr) as $row) {
//             $game_type = $row->type;
//             if($game_type != 1 || $game_type != '1'){
//                 $row->bet_numbers = $row->bet_numbers-1;
//             }
//             if($row->bet_numbers == 0){
//                 $row->bet_numbers = '100';
//             }
//             // $result = array('status' => 0, 'msg' => 'Illegel Betting!!!','eeee'=>$row->bet_money,'w'=>is_float($row->bet_money-1));
//             // return $result;
//             if ($row->bet_money < 1 || $row->bet_numbers > 100 || is_float($row->bet_money-1)) {
//                 $result = array('status' => 0, 'msg' => 'Illegel Betting!!!','w'=>$row->bet_money,'ww'=>is_float($row->bet_money));
//                 return $result;
//             }
            
//             if($row->type == 0){
//                 $TypeError = true;
//                 $result = array('status' => 0, 'msg' =>"Illegel Betting");
//                 return $result;
//             }
//             $motijogiblabla = 0;
//             $flaaag = false;
//             if($MotiJodiEnd){
//                 $motijogiblabla = 1;
//                 $this->db->select_sum('money');
//                 $gameId_verifyFor = $this->db->get_where('game_bet', array(
//                     'game_number' => $row->bet_numbers, 
//                     'user_id' => $user_id,
//                     'game_id' => $game_id,
//                     'type' => $game_type,
//                     'motijodi' => 1,
//                     'game_game_id' => $GameDataById[0]->game_id
//                 ));
                
//                 $sumMoney = $gameId_verifyFor->row()->money;
//                 // echo $sumMoney;
//                 if ($row->bet_money > $MaxValueForMotiJodi || $sumMoney >= $MaxValueForMotiJodi) {
//                     array_push($MotiJodiNumber,$row->bet_numbers);
//                 }else{
//                     $flaaag = true;
//                 }
//             }else{
//                 $flaaag = true;
//             }
//             if($flaaag){
//                 $data_insert[] = array(
//                     'user_id' => $user_id,
//                     'game_id' => $game_id,
//                     'game_game_id' => $GameDataById[0]->game_id,
//                     'type' => $game_type,
//                     'motijodi' => $motijogiblabla,
//                     'game_number' => $row->bet_numbers,
//                     'money' => $row->bet_money,
//                     'aid' => $aid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                 );
//                 $grand_total += intval($row->bet_money);
                
//                 $GametYPEnam = "Jodi";
//                 if($game_type == 2){
//                     $GametYPEnam = "Ander Haruf";
//                 }
//                 if($game_type == 3){
//                     $GametYPEnam = "Bahar Haruf";
//                 }
                
//                 if ($id_verify->row('wallet') < $grand_total) {
//                     $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
//                     return $result;
//                 }
//                 // $betNumbers = $row->bet_numbers>9?$row->bet_numbers:'0'.$row->bet_numbers;
//                 // $msg = $GameName." - ".$GametYPEnam." - ".$betNumbers;
//                 // $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $row->bet_money, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose' => 'debit'));
//             }
//         }
        
//         if ($id_verify->row('wallet') < $grand_totals) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
//             return $result;
//         }
        
//         if ($MotiJodiEnd && count($MotiJodiNumber) > 0) {
//             $data = "👉🏻 लास्ट टाइम में एक जोड़ी आप सिर्फ '.$MaxValueForMotiJodi.' into खेल सकते हो । 👉🏻 अब दूसरी जोड़ियां लगा सकते हो या फिर टाइम से लगाया करो ।🙏🏻
// धन्यवाद 🙏🏻";
//             $result = array('status' => 0, 'msg' =>$data);
//             return $result;
//         }
//         $this->db->insert_batch('game_bet', $data_insert);
//         $REFFERALCommissions = $id_verify->row('commission_percentage');
//         if($REFFERALCommissions != ""){
//                 if($REFFERALCommissions > 0){
//                     $FinalRefferAmount = ($REFFERALCommissions/100)*$grand_total;
//                     $user_idreffer= $id_verify->row('id');
//                     $this->db->where('id', $user_idreffer)->update('users', array('wallet' => $id_verify->row('wallet') + $FinalRefferAmount));
//                     $msg = "Commission";
//                     $this->Crud_model->insert('transaction_history', array('user_id' => $user_idreffer, 'add_amount' => $FinalRefferAmount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose'=>'credit'));
//                 }
//         }
        
//         $rfferalCode = $id_verify->row('reffer_code');
//         if($rfferalCode != ""){
//             $id_verifyrEFFERAL = $this->db->get_where('users', array('my_reffer_code' => $rfferalCode, 'status' => 0));
//             if ($id_verifyrEFFERAL->num_rows() > 0) {
//                 // if($id_verifyrEFFERAL->row('commission_percentage') != "" && $id_verifyrEFFERAL->row('commission_percentage') >0){
//                 //     $REFFERALCommission = $id_verifyrEFFERAL->row('commission_percentage');
//                 // }
//                 if($REFFERALCommission >0){
//                     $FinalRefferAmount = ($REFFERALCommission/100)*$grand_total;
//                     $user_idreffer= $id_verifyrEFFERAL->row('id');
//                     $this->db->where('id', $user_idreffer)->update('users', array('wallet' => $id_verifyrEFFERAL->row('wallet') + $FinalRefferAmount));
//                     $msg = "Refer Commission";
//                     $this->Crud_model->insert('transaction_history', array('user_id' => $user_idreffer, 'add_amount' => $FinalRefferAmount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose'=>'credit'));
//                 }
//             }
//         }
//         $OldBalance = $id_verify->row('wallet');
//         // $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $grand_total));
//         $this->db->set('wallet', 'wallet - ' . $grand_total, FALSE);
//         $this->db->where('id', $user_id);
//         $this->db->update('users');
//         $msg = "You have placed Rs." . $grand_total . " on ".$GameName;
//         $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $grand_total, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'game_details' => json_encode($data_insert), 'purpose' => 'debit', 'remaining_amount'=>$OldBalance, 'button_status' => 1));

//         if ($this->db->affected_rows() > 0) {
//             $result = array('status' => 1, 'msg' => 'Successfully Game OK!');
//             return $result;
//         } else {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! something went wrong');
//             return $result;
//         }
//     }

public function game_bet()
    {
        $user_id = $this->input->post('user_id');
        $game_id = $this->input->post('game_id');
        $game_type = $this->input->post('game_type');
        $bet_numbers_arr = $this->input->post('bet_numbers');
        $grand_total = $this->input->post('grand_total');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
            return $result;
        }

        if (empty($game_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game id!!!');
            return $result;
        }

        // if (empty($game_type)) {
        //     $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game type!!!');
        //     return $result;
        // }

        if (empty($bet_numbers_arr)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter bet numbers!!!');
            return $result;
        }

        if ($bet_numbers_arr == "[]") {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter bet numbers!!!');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id, 'status' => 0));
        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid user id!!!');
            return $result;
        }

        // if ($id_verify->row('wallet') < $grand_total) {
        //     $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
        //     return $result;
        // }
        
        $gameId_verify = $this->db->get_where('create_game', array('id' => $game_id, 'status' => 0));

        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid game id!!!');
            return $result;
        }
        $GameDataById = $gameId_verify->result();
        $MotiJodiEnd = false;
        
        // Combine the date and time into a valid DateTime string.
        $endDateTime = $gameId_verify->row('end_date') . ' ' . $gameId_verify->row('end_time');
        $resultDateTime = $gameId_verify->row('result_date') . ' ' . $gameId_verify->row('result_time');
        if(strtotime($endDateTime) < time()){
            $result = array('status' => 0, 'msg' =>"Betting not Allowed!");
            return $result;
        }
        
        // Compare with the current date and time.
        if (strtotime($resultDateTime) < time()) {
            $MotiJodiEnd = true;  // Set $MotiJodiEnd to true if the event has ended.
        }
        $motijodimax = $this->db->get_where('game_setting');
        $MaxValueForMotiJodi = $motijodimax->row('motijodimax');
        
        $AGENTID = $id_verify->row('aid');
        $get_AgentData = $this->Crud_model->fetch('admin_login', array('id' => $AGENTID));
        if($AGENTID && $get_AgentData){
            $MaxValueForMotiJodi = $get_AgentData->row('motijodimax');
        }
        $REFFERALCommission = $motijodimax->row('commission_amount');
        $bidding_amount = $motijodimax->row('bidding_amount');
        $MotiJodiNumber = [];
        $gameName_verify = $this->db->get_where('game', array('id' => $GameDataById[0]->game_id));
        $GameNameRes = $gameName_verify->result();
        $grand_total = 0;
        $grand_totals = $this->input->post('grand_total');
        $GameName = $GameNameRes[0]->game_name;
        $game_type = 1;
        $aid = $id_verify->row('aid');
        foreach (json_decode($bet_numbers_arr) as $row) {
            $game_type = $row->type;
            if($game_type != 1 || $game_type != '1'){
                // $row->bet_numbers = $row->bet_numbers-1;
            }
            if($row->bet_numbers == 0){
                $row->bet_numbers = '100';
            }
            // $result = array('status' => 0, 'msg' => 'Illegel Betting!!!','eeee'=>$row->bet_money,'w'=>is_float($row->bet_money-1));
            // return $result;
            if($bidding_amount>$row->bet_money){
                $result = array('status' => 0, 'msg' => 'Min. Bid amount is '.$bidding_amount.'rs');
                return $result;
            }
            if ($row->bet_money < 1 || $row->bet_numbers > 100 || is_float($row->bet_money-1)) {
                $result = array('status' => 0, 'msg' => 'Illegel Betting!!!','w'=>$row->bet_money,'ww'=>is_float($row->bet_money));
                return $result;
            }
            
            if($row->type == 0){
                $TypeError = true;
                $result = array('status' => 0, 'msg' =>"Illegel Betting");
                return $result;
            }
            $motijogiblabla = 0;
            $flaaag = false;
            if($MotiJodiEnd){
                $motijogiblabla = 1;
                $this->db->select_sum('money');
                $gameId_verifyFor = $this->db->get_where('game_bet', array(
                    'game_number' => $row->bet_numbers, 
                    'user_id' => $user_id,
                    'game_id' => $game_id,
                    'type' => $game_type,
                    'motijodi' => 1,
                    'game_game_id' => $GameDataById[0]->game_id
                ));
                
                $sumMoney = $gameId_verifyFor->row()->money;
                // echo $sumMoney;
                if ($row->bet_money > $MaxValueForMotiJodi || $sumMoney >= $MaxValueForMotiJodi) {
                    array_push($MotiJodiNumber,$row->bet_numbers);
                }else{
                    $flaaag = true;
                }
            }else{
                $flaaag = true;
            }
            if($flaaag){
                $data_insert[] = array(
                    'user_id' => $user_id,
                    'game_id' => $game_id,
                    'game_game_id' => $GameDataById[0]->game_id,
                    'type' => $game_type,
                    'motijodi' => $motijogiblabla,
                    'game_number' => $row->bet_numbers,
                    'money' => $row->bet_money,
                    'aid' => $aid,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $grand_total += intval($row->bet_money);
                
                $GametYPEnam = "Jodi";
                if($game_type == 2){
                    $GametYPEnam = "Ander Haruf";
                }
                if($game_type == 3){
                    $GametYPEnam = "Bahar Haruf";
                }
                
                if ($id_verify->row('wallet') < $grand_total) {
                    $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
                    return $result;
                }
                // $betNumbers = $row->bet_numbers>9?$row->bet_numbers:'0'.$row->bet_numbers;
                // $msg = $GameName." - ".$GametYPEnam." - ".$betNumbers;
                // $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $row->bet_money, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose' => 'debit'));
            }
        }
        
        if ($id_verify->row('wallet') < $grand_totals) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
            return $result;
        }
        
        if ($MotiJodiEnd && count($MotiJodiNumber) > 0) {
            $data = "👉🏻 लास्ट टाइम में एक जोड़ी आप सिर्फ '.$MaxValueForMotiJodi.' into खेल सकते हो । 👉🏻 अब दूसरी जोड़ियां लगा सकते हो या फिर टाइम से लगाया करो ।🙏🏻
धन्यवाद 🙏🏻";
            $result = array('status' => 0, 'msg' =>$data);
            return $result;
        }
        $this->db->insert_batch('game_bet', $data_insert);
        $REFFERALCommissions = $id_verify->row('commission_percentage');
        if($REFFERALCommissions != ""){
                if($REFFERALCommissions > 0){
                    $FinalRefferAmount = ($REFFERALCommissions/100)*$grand_total;
                    $user_idreffer= $id_verify->row('id');
                    $this->db->where('id', $user_idreffer)->update('users', array('wallet' => $id_verify->row('wallet') + $FinalRefferAmount));
                    $msg = "Commission";
                    $this->Crud_model->insert('transaction_history', array('user_id' => $user_idreffer, 'add_amount' => $FinalRefferAmount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose'=>'credit'));
                }
        }
        
        $rfferalCode = $id_verify->row('reffer_code');
        if($rfferalCode != ""){
            $id_verifyrEFFERAL = $this->db->get_where('users', array('my_reffer_code' => $rfferalCode, 'status' => 0));
            if ($id_verifyrEFFERAL->num_rows() > 0) {
                // if($id_verifyrEFFERAL->row('commission_percentage') != "" && $id_verifyrEFFERAL->row('commission_percentage') >0){
                //     $REFFERALCommission = $id_verifyrEFFERAL->row('commission_percentage');
                // }
                if($REFFERALCommission >0){
                    $FinalRefferAmount = ($REFFERALCommission/100)*$grand_total;
                    $user_idreffer= $id_verifyrEFFERAL->row('id');
                    $this->db->where('id', $user_idreffer)->update('users', array('wallet' => $id_verifyrEFFERAL->row('wallet') + $FinalRefferAmount));
                    $msg = "Refer Commission";
                    $this->Crud_model->insert('transaction_history', array('user_id' => $user_idreffer, 'add_amount' => $FinalRefferAmount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose'=>'credit'));
                }
            }
        }
        $OldBalance = $id_verify->row('wallet');
        // $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') - $grand_total));
        $this->db->set('wallet', 'wallet - ' . $grand_total, FALSE);
        $this->db->where('id', $user_id);
        $this->db->update('users');
        $msg = "You have placed Rs." . $grand_total . " on ".$GameName;
        $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $grand_total, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'game_details' => json_encode($data_insert), 'purpose' => 'debit', 'remaining_amount'=>$OldBalance, 'button_status' => 1));

        if ($this->db->affected_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successfully Game OK!');
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! something went wrong');
            return $result;
        }
    }
    public function game_bet_cancel()
    {
        $user_id = $this->input->post('user_id');
        $game_id = $this->input->post('game_id');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
            return $result;
        }

        if (empty($game_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game id!!!');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id, 'status' => 0));
        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid user id!!!');
            return $result;
        }
        
        $gameId_verify = $this->db->get_where('game_bet', array('id' => $game_id, 'status' => 0));
        $GameDataById = $gameId_verify->result();
        $type = $GameDataById[0]->type;
        $amount = $GameDataById[0]->money;
        $createGameId = $GameDataById[0]->game_id;
        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry, Game Over.');
            return $result;
        }
        $this->db->where('status', 0);
        $this->db->where('id', $createGameId);
        $current_datetime = date('Y-m-d H:i:s');
        $this->db->where('CONCAT(result_date, " ", result_time) <=', $current_datetime); // Not ended yet
        $LastCheckTime = $this->db->get('create_game');
        
        if($LastCheckTime->num_rows() > 0){
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Game Time is over');
            return $result;
        }
        $this->db->where('id', $game_id)->update('game_bet', array('status' => 2,'cancel_at' => date('Y-m-d H:i:s')));
        $msg = ($type == 1 ? "JODI" : ($type == 2 ? "ANDER H" : "BAHAR H"))." Cancel ";
        $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') + $amount));
        $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'button_status' => 1, 'purpose' => 'credit'));
        
        if ($this->db->affected_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Bet cancel!');
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! something went wrong');
            return $result;
        }
    }
    
//     public function my_bets(){
//         $user_id = $this->input->post('user_id');

//         if (empty($user_id)) {
//             $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
//             return $result;
//         }
        
//         // $this->db->select('game_bet.*, game.game_name');
//         // $this->db->from('game_bet');
//         // $this->db->join('game', 'game.id = game_bet.game_game_id', 'left');
//         // $this->db->where('game_bet.user_id', $user_id);
//         // $this->db->order_by('game_bet.id', 'DESC');
//         // $gameId_verify = $this->db->get();
//         $current_datetime = date('Y-m-d H:i:s');
// $this->db->select('
//     game_bet.*, 
//     game.game_name, 
//     CASE 
//         WHEN CONCAT(create_game.result_date, " ", create_game.result_time) <= "' . $current_datetime . '" THEN 1 
//         ELSE 0 
//     END AS isResult', 
//     false
// );
// $this->db->from('game_bet');
// $this->db->join('game', 'game.id = game_bet.game_game_id', 'left');
// $this->db->join('create_game', 'create_game.id = game_bet.game_id', 'left'); // Corrected join condition
// $this->db->where('game_bet.user_id', $user_id);
// $this->db->where('game_bet.status !=', '2');
// $this->db->order_by('game_bet.id', 'DESC');
// $gameId_verify = $this->db->get();




        
//         if (!$gameId_verify->num_rows() > 0) {
//             $result = array('status' => 0, 'msg' => 'No data found!');
//             return $result;
//         }
//         $data_arr = [];
//         foreach($gameId_verify->result() as $row_item){
//             $data_arr[] = array(
//                 'id' => $row_item->id,
//                 'type' => $row_item->type,
//                 'user_id' => $row_item->user_id,
//                 'game_id' => $row_item->game_id,
//                 'isResult' => $row_item->isResult,
//                 'game_game_id' => $row_item->game_game_id,
//                 'game_number' => $row_item->game_number,
//                 'money' => $row_item->money,
//                 'motijodi' => $row_item->motijodi,
//                 'status' => $row_item->status,
//                 'created_at' => $this->formatDate($row_item->created_at, "d M Y h:i A"),
//                 'game_name' => $row_item->game_name
//             );
//         }
//         $result = array('status' => 1, 'data' => $data_arr ,'msg' => 'Successful...');
//         return $result;
//     }

public function my_bets(){
        $user_id = $this->input->post('user_id');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
            return $result;
        }
        
        // $this->db->select('game_bet.*, game.game_name');
        // $this->db->from('game_bet');
        // $this->db->join('game', 'game.id = game_bet.game_game_id', 'left');
        // $this->db->where('game_bet.user_id', $user_id);
        // $this->db->order_by('game_bet.id', 'DESC');
        // $gameId_verify = $this->db->get();
        $current_datetime = date('Y-m-d H:i:s');
$this->db->select('
    game_bet.*, 
    game.game_name, 
    CASE 
        WHEN CONCAT(create_game.result_date, " ", create_game.result_time) <= "' . $current_datetime . '" THEN 1 
        ELSE 0 
    END AS isResult', 
    false
);
$this->db->from('game_bet');
$this->db->join('game', 'game.id = game_bet.game_game_id', 'left');
$this->db->join('create_game', 'create_game.id = game_bet.game_id', 'left'); // Corrected join condition
$this->db->where('game_bet.user_id', $user_id);
$this->db->where('game_bet.status !=', '2');
$this->db->order_by('game_bet.id', 'DESC');
$gameId_verify = $this->db->get();




        
        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'No data found!');
            return $result;
        }
        $data_arr = [];
        foreach($gameId_verify->result() as $row_item){
            $gmanumber = $row_item->game_number;
            if($row_item->type == "3" || $row_item->type == "2"){
                if($row_item->game_number == "100"){
                    $gmanumber = "0";
                }else{
                    // $gmanumber = $gmanumber+1;
                }
                $gmanumber = '0'.$gmanumber;
            }
            $data_arr[] = array(
                'id' => $row_item->id,
                'type' => $row_item->type,
                'user_id' => $row_item->user_id,
                'game_id' => $row_item->game_id,
                'isResult' => $row_item->isResult,
                'game_game_id' => $row_item->game_game_id,
                'game_number' => $gmanumber,
                'money' => $row_item->money,
                'motijodi' => $row_item->motijodi,
                'status' => $row_item->status,
                'created_at' => $this->formatDate($row_item->created_at, "d M Y h:i A"),
                'game_name' => $row_item->game_name
            );
        }
        $result = array('status' => 1, 'data' => $data_arr ,'msg' => 'Successful...');
        return $result;
    }
    
    public function my_bets_cancel(){
        $user_id = $this->input->post('user_id');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter user id!!!');
            return $result;
        }
        
        $this->db->select('game_bet.*, game.game_name');
        $this->db->from('game_bet');
        $this->db->join('game', 'game.id = game_bet.game_game_id', 'left');
        $this->db->where('game_bet.user_id', $user_id);
        $this->db->where('game_bet.status', 2);
        $this->db->order_by('game_bet.cancel_at', 'DESC');
        $gameId_verify = $this->db->get();
        
        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'No data found!');
            return $result;
        }
        $data_arr = [];
        foreach($gameId_verify->result() as $row_item){
            $data_arr[] = array(
                'id' => $row_item->id,
                'type' => $row_item->type,
                'user_id' => $row_item->user_id,
                'game_id' => $row_item->game_id,
                'game_game_id' => $row_item->game_game_id,
                'game_number' => $row_item->game_number,
                'money' => $row_item->money,
                'motijodi' => $row_item->motijodi,
                'status' => $row_item->status,
                'created_at' => $this->formatDate($row_item->cancel_at, "d M Y h:i A"),
                'game_name' => $row_item->game_name
            );
        }
        $result = array('status' => 1, 'data' => $data_arr ,'msg' => 'Successful...');
        return $result;
    }
    
    public function game_details()
    {
        $game_id = $this->input->post('game_id');

        if (empty($game_id)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game id!!!');
            return $result;
        }

        $this->db->select('create_game.*, game.game_name');
        $this->db->from('create_game');
        $this->db->join('game', 'game.id = create_game.game_id', 'left');
        $this->db->where('create_game.id', $game_id);
        $gameId_verify = $this->db->get();

        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid game id!!!');
            return $result;
        }
        $result = array('status' => 1, 'data' => $gameId_verify->result() ,'msg' => 'Successful...');
        return $result;
    }

    public function get_profile()
    {
        $user_id = $this->input->post('user_id');

        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter user id');
            return $result;
        }

        $id_verify = $this->db->get_where('users', array('id' => $user_id, 'status' => 0));

        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid user id!!!');
            return $result;
        }

        $game_setting = $this->db->get_where('game_setting');

        $get_version = $this->db->get_where('version_code');
        $banner_image = $this->db->get_where('banner_image', array('status' => 0));
        $heading_text = $this->db->get_where('heading_text');
        $contact_info = $this->db->get_where('game_setting');

        $data_arr = array(
            'id' => $id_verify->row('id'),
            'name' => $id_verify->row('name'),
            'mobile' => $id_verify->row('mobile'),
            'reffer_code' => $id_verify->row('reffer_code'),
            'my_reffer_code' => $id_verify->row('my_reffer_code'),
            'bank_name' => $id_verify->row('bank_name'),
            'account_no' => $id_verify->row('account_no'),
            'ifsc_code' => $id_verify->row('ifsc_code'),
            'holder_name' => $id_verify->row('holder_name'),
            'upi_no' => $id_verify->row('upi_no'),
            'google_pay' => $id_verify->row('google_pay'),
            'phone_pe' => $id_verify->row('phone_pe'),
            'paytm_no' => $id_verify->row('paytm_no'),
            'payment_type' => $id_verify->row('payment_type'),
            'wallet' => $id_verify->row('wallet'),
            'date' => $id_verify->row('date'),
            'status' => $id_verify->row('status'),
            'time' => $id_verify->row('time'),
            'url' => $get_version->row('url'),
            'version_code' => $get_version->row('virsion_code'),
        );

        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'banner_image' => $banner_image->result(), 'text_msg' => $heading_text->row('text_msg'), 'help_number' => $contact_info->row('contact_info'), 'min_bidding_amt' => $game_setting->row('bidding_amount'), 'data' => $data_arr);
        return $result;
    }

    public function game_rule()
    {
        $user_id = $this->input->post('user_id');
        $id_verify = $this->db->get_where('users', array('id' => $user_id));
        $data = $this->db->get_where('game_rule', array('id' => 1));
        if ($id_verify->num_rows() > 0) {
            $aid = $id_verify->row('aid');
            $aid_verify = $this->db->get_where('admin_login', array('id' => $aid));
            // echo $aid;
            if($aid_verify->num_rows() > 0){
                $jodirate = $aid_verify->row('winning_amount')*100;
                $hurfrate =  $aid_verify->row('withdrawal_hold_amt')*100;
                $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $data->row(),'jodirate'=>number_format($jodirate,2),'hurfrate'=>number_format($hurfrate,2));
                return $result;
            }else{
                $result = array('status' => 0, 'msg' => 'Sorry...!!! Agent not found');
                return $result;
            }
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! User not found');
            return $result;
        }
    }

    public function transaction_history()
    {
        $user_id = $this->input->post('user_id');
        $data = $this->db->order_by('id', 'DESC')->get_where('transaction_history', array('user_id' => $user_id));

        foreach ($data->result() as $row) {
            $get_user = $this->db->get_where('users', array('id' => $row->user_id));

            $data_details = array();
            if ($row->game_details !== null && $row->purpose != "kabad") {
                foreach (json_decode($row->game_details) as $row_item) {
                    // Assuming 'data_get' is a helper function that retrieves data from a query
$createGameQuery = data_get('create_game', 'id', $row_item->game_id);

if ($createGameQuery && $createGameQuery->num_rows() > 0) {
    // Now you can safely call row() on the result
    $gameId = $createGameQuery->row()->game_id;
    
    // Now fetch the 'game_name' safely
    $gameQuery = data_get('game', 'id', $gameId);

    if ($gameQuery && $gameQuery->num_rows() > 0) {
        $gameName = $gameQuery->row()->game_name;
    } else {
        // Handle case where the game query returns no result
        $gameName = 'Unknown';  // Default value or an error message
    }
} else {
    // Handle case where the create_game query returns no result
    $gameName = 'Unknown';  // Default value or an error message
}

$data_details[] = array(
    'game_name' => $gameName,
    'number' => $row_item->game_number,
    'money' => $row_item->money,
);

                }
            }

            $data_arr[] = array(
                'id' => $row->id,
                'user_name' => $get_user->row('name'),
                'add_amount' => $row->add_amount,
                'description' => $row->description,
                'status' => $row->status,
                'date' => $this->formatDate($row->date, "d M Y"),
                'time' => $this->formatDate($row->time, "h:i A"),
                'comment' => $row->comment,
                'button_status' => $row->button_status,
                'purpose' => $row->purpose,
                'game_details' => $data_details,
            );
        }

        if ($data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $data_arr);
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Data not found');
            return $result;
        }
    }

    public function game_result()
    {
        $date = $this->input->post("date");

        if (empty($date)) {
            $result = array('status' => 0, 'msg' => 'Please enter date');
            return $result;
        }

        $data_get = $this->db->get_where('create_game', array('start_date' => $date));

        if (!$data_get->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Data not found');
            return $result;
        }

        foreach ($data_get->result() as $row) {
            $get_game_result = $this->db->get_where("game_result", array('create_game_id' => $row->id));

            if ($get_game_result->num_rows() > 0) {
                $game_time = date("h:i A", strtotime($get_game_result->row('time')));
            } else {
                $game_time = date("h:i A", strtotime($row->result_time));
            }

            $game_name = $this->db->get_where('game', array('id' => $row->game_id));

            $array[] = array(
                'id' => $row->id,
                'game_name' => $game_name->row('game_name'),
                'time' => $game_time,
                'number' => $get_game_result->row('number'),
            );
        }

        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $array);
        return $result;
    }

    public function get_qr_details()
    {
        $query = $this->db->get_where('payments', array('status' => 0));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $data = array(
                'id' => $row->id,
                'name' => $row->name,
                'upi_num' => $row->upi_num,
                'account_number' => $row->account_number ?? '',
                'ifsc_code' => $row->ifsc_code ?? '',
                'bank_name' => $row->bank_name ?? '',
                'account_holder_name' => $row->account_holder_name ?? '',
                'qr_img' => !empty($row->qr_img) ? base_url($row->qr_img) : '',
                'upi_company_img' => !empty($row->upi_company_img) ? base_url($row->upi_company_img) : ''
            );
            return array('status' => 1, 'data' => $data);
        } else {
            $query_all = $this->db->get('payments');
            if ($query_all->num_rows() > 0) {
                $row = $query_all->row();
                $data = array(
                    'id' => $row->id,
                    'name' => $row->name,
                    'upi_num' => $row->upi_num,
                    'account_number' => $row->account_number ?? '',
                    'ifsc_code' => $row->ifsc_code ?? '',
                    'bank_name' => $row->bank_name ?? '',
                    'account_holder_name' => $row->account_holder_name ?? '',
                    'qr_img' => !empty($row->qr_img) ? base_url($row->qr_img) : '',
                    'upi_company_img' => !empty($row->upi_company_img) ? base_url($row->upi_company_img) : ''
                );
                return array('status' => 1, 'data' => $data);
            }
            return array('status' => 0, 'msg' => 'No active QR code details found');
        }
    }

    public function submit_qr_payment()
    {
        $user_id = $this->input->post('user_id');
        $amount = $this->input->post('amount');
        $transaction_id = $this->input->post('transaction_id');

        if (empty($user_id)) {
            return array('status' => 0, 'msg' => 'User ID is required');
        }
        if (empty($amount) || !is_numeric($amount) || floatval($amount) <= 0) {
            return array('status' => 0, 'msg' => 'Valid recharge amount is required');
        }

        $ss_path = '';
        if (!empty($_FILES['transaction_image']['name'])) {
            $upload_dir = 'uploads/payment_screenshots/';
            if (!is_dir('./' . $upload_dir)) {
                mkdir('./' . $upload_dir, 0777, true);
            }
            $ss_path = do_upload($upload_dir, 'transaction_image', 'jpeg|png|jpg|webp');
            if (!is_string($ss_path) || strpos($ss_path, 'uploads/') !== 0) {
                return array('status' => 0, 'msg' => 'Screenshot upload failed: ' . $ss_path);
            }
        } else {
            return array('status' => 0, 'msg' => 'Payment screenshot file is required');
        }

        $user = $this->db->get_where('users', array('id' => $user_id));
        $aid = ($user->num_rows() > 0) ? $user->row('aid') : 1;

        $txn_ref = !empty($transaction_id) ? $transaction_id : 'QR' . date("YmdHis");

        $insert_data = array(
            'user_id'           => $user_id,
            'amount'            => $amount,
            'transaction_id'    => $txn_ref,
            'transaction_image' => $ss_path,
            'status'            => 0,
            'type'              => 1,
            'date'              => date("Y-m-d"),
            'time'              => date("H:i:s"),
            'aid'               => $aid
        );

        $this->db->insert('payment_request', $insert_data);

        if ($this->db->affected_rows() > 0) {
            return array('status' => 1, 'msg' => 'Payment request submitted successfully! Pending admin approval.');
        } else {
            return array('status' => 0, 'msg' => 'Failed to submit payment request. Please try again.');
        }
    }
}
