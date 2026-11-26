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

        $reffer_code_verify = $this->db->get_where('users', array('my_reffer_code' => $reffer_code));
        $id_verify = $this->db->get_where('users', array('mobile' => $mobile));
        $add_wallet_price = $this->db->get_where('game_setting');

        if ($id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'User is already exist!!!');
            return $result;
        }

        if (!empty($reffer_code)) {

            if ($reffer_code_verify->num_rows() > 0) {
                $this->db->where('my_reffer_code', $reffer_code)->update('users', array('wallet' => $reffer_code_verify->row('wallet') + $add_wallet_price->row('reffer_amount')));
                $msg = "You have earned Rs." . $add_wallet_price->row('reffer_amount') . " via a refferal";
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
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');

        if (empty($mobile && $password)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }

        $users_data = $this->db->get_where('users', array('mobile' => $mobile, 'status' => 0));

        if (password_verify($password, $users_data->row('password'))) {

            foreach ($users_data->result() as $row) {
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

            $result = array('status' => 1, 'msg' => 'User login successful', 'data' => $data_arr);
            return $result;
        }

        $result = array('status' => 0, 'msg' => 'please enter valid mobile no. and password');
        return $result;
    }

    public function mobile_check()
    {
        $mobile = $this->input->post('mobile');

        if (empty($mobile)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }

        $users_data = $this->db->select('id, name, mobile, my_reffer_code, wallet')->get_where('users', array('mobile' => $mobile));
        if ($users_data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Mobile no. Checked Successful', 'data' => $users_data->row());
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'please enter valid Mobile no.', 'data' => $users_data->row());
            return $result;
        }
    }

    public function forget_password()
    {
        $user_id = $this->input->post('user_id');
        $new_password = $this->input->post('new_password');

        if (empty($user_id && $new_password)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }

        $users_data = $this->db->get_where('users', array('id' => $user_id));

        if ($users_data->num_rows() > 0) {

            $password = password_hash($new_password, PASSWORD_DEFAULT);

            $this->db->where('id', $user_id)->update('users', array('password' => $password));

            $result = array('status' => 1, 'msg' => 'Password Forget Successful...!!!!');
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'User ID Not Matched');
            return $result;
        }
    }

    public function profile()
    {
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            $result = array('status' => 0, 'msg' => 'Please enter required field');
            return $result;
        }
        $users_data = $this->db->select('id, name, mobile, reffer_code')->get_where('users', array('id' => $user_id));
        $accounts = $this->db->select('bank_name, account_no, ifsc_code, holder_name, upi_no, paytm_no')->get_where('users', array('id' => $user_id))->row();
        if ($users_data->num_rows() > 0) {

            foreach ($users_data->result() as $item) {
                $arr[] = array(
                    'id' => $item->id,
                    'name' => $item->name,
                    'mobile' => $item->mobile,
                    // 'username' => $item->username,
                    'reffer_code' => $item->reffer_code,
                    'account' => $accounts
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
        $get_payment = $this->db->select('id, name, upi_num, qr_img, upi_company_img')->get_where('payments');

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

        $id_verify = $this->db->get_where('users', array('id' => $user_id));

        $get_minimum_withwrawal_amt = $this->db->get_where('game_setting');

        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! user id is not valid');
            return $result;
        }

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

                if (!empty($upi_no) && !empty($user_id) && !empty($amount)) {

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

                if (!empty($upi_no) && !empty($user_id) && !empty($amount)) {

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

                if (!empty($upi_no) && !empty($user_id) && !empty($amount)) {

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
                        'type' => 2,
                    );
                } else {
                    $result = array('status' => 0, 'msg' => 'Sorry...!!! please add your bank');
                    return $result;
                }
            }
        }


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
        $get_data = $this->db->get_where('create_game', array('status' => 0));

        $custom_date_time = date("Y-m-d H:i:s");
        $custom_date = date("Y-m-d");

        $data_arr = array();
        foreach ($get_data->result() as $row_item) {
            $start_time_check = date("Y-m-d H:i:s", strtotime($row_item->start_date . " " . $row_item->start_time));
            $end_time_check = date("Y-m-d H:i:s", strtotime($row_item->end_date . " " . $row_item->end_time));
            if ($row_item->start_date == $custom_date || $row_item->end_date >= $custom_date) {
                if ($start_time_check <= $custom_date_time && $end_time_check >= $custom_date_time) {
                    $game_name = $this->db->order_by('id', 'DESC')->get_where('game', array('id' => $row_item->game_id));
                    $data_arr[] = array(
                        'game_name' => $game_name->row('game_name'),
                        'start_date' => $row_item->start_date,
                        'start_time' => date("h:i a", strtotime($row_item->start_time)),
                        'end_date' => $row_item->end_date,
                    );
                }
            }
        }

        if (!count($data_arr) > 0) {
            $result = array('status' => 0, 'msg' => 'No game avilable!!!');
            return $result;
        }

        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $data_arr);
        return $result;


        // foreach ($get_data->result() as $row) {
        //     $game_name = $this->db->order_by('id', 'DESC')->get_where('game', array('id' => $row->game_id));
        //     $data_arr[] = array(
        //         'game_id' => $row->id,
        //         'game_name' => $game_name->row('game_name'),
        //         'start_date' => $row->start_date,
        //         'start_time' => date("h:i a", strtotime($row->start_time)),
        //         'end_date' => $row->end_date,
        //         'end_time' => date("h:i a", strtotime($row->end_time)),
        //         'result_date' => $row->result_date,
        //         'result_time' => date("h:i a", strtotime($row->result_time))
        //     );
        // }
        // } else {
        //     $result = array('status' => 0, 'msg' => 'No game avilable!!!');
        //     return $result;
        // }

    }

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

        if (empty($game_type)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter game type!!!');
            return $result;
        }

        if (empty($bet_numbers_arr)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter bet numbers!!!');
            return $result;
        }

        if (empty($grand_total)) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter grand total amount!!!');
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

        if ($id_verify->row('wallet') < $grand_total) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Your wallet balance is not sufficient!!!');
            return $result;
        }

        $gameId_verify = $this->db->get_where('create_game', array('id' => $game_id, 'status' => 0));

        if (!$gameId_verify->num_rows() > 0) {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! please enter valid game id!!!');
            return $result;
        }

        foreach (json_decode($bet_numbers_arr) as $row) {

            $data_insert[] = array(
                'type' => $game_type,
                'game_number' => $row->bet_numbers,
                'money' => $row->bet_money,
            );
        }

        $msg = "You have placed Rs." . $grand_total . " on Game Play";

        if ($this->db->affected_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!! Your bet applied');
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! something went wrong');
            return $result;
        }
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

        $data = $this->db->get_where('game_rule', array('id' => 1));

        if ($data->num_rows() > 0) {
            $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $data->row());
            return $result;
        } else {
            $result = array('status' => 0, 'msg' => 'Sorry...!!! Data not found');
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
            if ($row->game_details !== null) {
                foreach (json_decode($row->game_details) as $row_item) {
                    $data_details[] = array(
                        'game_name' => data_get('game', 'id', data_get('create_game', 'id', $row_item->game_id)->row('game_id'))->row('game_name'),
                        'number' => $row_item->game_number,
                        'money' => $row_item->money,
                    );
                }
            }

            $data_arr[] = array(
                'date' => $row->date,
                'time' => $row->time,
                'comment' => $row->comment,
                'button_status' => $row->button_status,
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

            if ($get_game_result->num_rows() > 0) {
                $game_time = date("h:i a", strtotime($get_game_result->row('time')));
            } else {
                $game_time = date("h:i a", strtotime($row->result_time));
            }

            $game_name = $this->db->get_where('game', array('id' => $row->game_id));

            $array[] = array(
                'game_name' => $game_name->row('game_name'),
                'time' => $game_time,
                'number' => $get_game_result->row('number'),
            );
        }

        $result = array('status' => 1, 'msg' => 'Successful...!!!', 'data' => $array);
        return $result;
    }
}
