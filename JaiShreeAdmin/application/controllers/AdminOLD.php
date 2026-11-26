<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Crud_model");
        $this->load->helper('cookie');
    }

    public function index()
    {
        $password_plain = $this->input->post('password');
        $email = $this->input->post('email');
        $password_data = $this->db->get('admin_login')->row();
        $submit = $this->input->post('submit');

        if (isset($submit)) {

            if ($password_data->email == $email) {

                if (password_verify($password_plain, $password_data->password)) {
                    $this->session->set_userdata('user_type', 'admin');
                    $this->session->set_userdata('login', 1);
                    if ($this->session->userdata('login') == 1) {
                        redirect(base_url() . 'admin/dashboard', 'refersh');
                    } else {
                        redirect(base_url() . 'login/admin', 'refresh');
                    }
                } else {
                    redirect(base_url() . 'login/admin', 'refresh');
                }
            }
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $this->load->view('admin_login');
    }

    public function login()
    {
        $this->load->view('admin_login');
    }

    public function logout()
    {
        $this->session->unset_userdata('login');
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
    }

    public function update_profile()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $username = $this->input->post("email");
        $password = $this->input->post("password");
        if ($password && $username !== "") {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $data['email'] = $username;
            $data['password'] = $password_hash;
            $this->Crud_model->update('admin_login', 'id', 1, $data);
            if ($this->db->affected_rows() > 0) {
                $this->session->unset_userdata('login');
                if (!$this->session->userdata('login') == 1) {
                    redirect(base_url() . 'login/admin', 'refresh');
                }
            }
        } else {
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }
    }

    public function dashboard()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        $this->load->view('templete', $page_data);
    }

    // public function all_country($ids = "")
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }

    //     $submit = $this->input->post("submit");
    //     $update_submit = $this->input->post("update_submit");
    //     $sid = $this->input->get("sid");
    //     if (isset($sid)) {
    //         $this->Crud_model->update_custom('country', 'country_id', 'status', $sid);
    //         redirect(base_url() . 'admin/all_country', 'refresh');
    //     }

    //     if (isset($submit)) {
    //         $data['country_name'] = strtoupper($this->input->post('country_name'));
    //         $this->Crud_model->insert('country', $data);
    //         $page_data['alert'] = "successfull";
    //         redirect(base_url() . 'admin/all_country', 'refresh');
    //     }

    //     if (isset($update_submit)) {
    //         $data_update['country_name'] = strtoupper($this->input->post("country_name"));
    //         $this->Crud_model->update('country', 'country_id', $ids, $data_update);
    //         redirect(base_url() . 'admin/all_country', 'refresh');
    //     }

    //     $page_data['get_modal'] = $this->Crud_model->fetch('country')->result();
    //     $page_data['page_title'] = "Country List";
    //     $page_data['page_name'] = "country_list";
    //     $this->load->view('templete', $page_data);
    // }

    // public function states($ids = "")
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }

    //     $submit = $this->input->post("submit");
    //     $sid = $this->input->get('sid');
    //     if (isset($sid)) {
    //         $this->Crud_model->update_custom('state', 'id', 'status', $sid);
    //         redirect(base_url() . 'admin/states', 'refresh');
    //     }

    //     if (isset($submit)) {
    //         $data = array(
    //             'country_id' => $this->input->post('country_id'),
    //             'state_name' => strtoupper($this->input->post('state_name'))
    //         );
    //         $this->Crud_model->insert('state', $data);
    //         redirect(base_url() . 'admin/states', 'refresh');
    //     }

    //     $update_submit = $this->input->post("update_submit");

    //     if (isset($update_submit)) {
    //         $data_update['country_id'] = strtoupper($this->input->post('country_id'));
    //         $data_update['state_name'] = strtoupper($this->input->post('state_name'));
    //         $this->Crud_model->update('state', 'id', $ids, $data_update);
    //         redirect(base_url() . 'admin/states', 'refresh');
    //     }

    //     $page_data['country_name'] = $this->Crud_model->join();
    //     $page_data['country'] = $this->Crud_model->fetch('country')->result();
    //     $page_data['page_title'] = "State List";
    //     $page_data['page_name'] = "state";
    //     $this->load->view('templete', $page_data);
    // }

    // public function city($ids = "")
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }

    //     $update_submit = $this->input->post("update_submit");
    //     $submit = $this->input->post("submit");
    //     $sid = $this->input->get('sid');
    //     if (isset($sid)) {
    //         $this->Crud_model->update_custom('city', 'id', 'status', $sid);
    //         redirect(base_url() . 'admin/city', 'refresh');
    //     }

    //     $data['country_id'] = $this->input->post('country_id');
    //     $data['state_id'] = $this->input->post('state_id');
    //     $data['city_name'] = strtoupper($this->input->post('city_name'));

    //     if (isset($submit)) {
    //         $this->Crud_model->insert('city', $data);
    //         redirect(base_url() . 'admin/city', 'refresh');
    //     }

    //     if (isset($update_submit)) {
    //         $this->Crud_model->update('city', 'id', $ids, $data);
    //         redirect(base_url() . 'admin/city', 'refresh');
    //     }

    //     $page_data['city'] = $this->Crud_model->fetch('city')->result();
    //     $page_data['country'] = $this->Crud_model->fetch('country')->result();
    //     $page_data['page_title'] = "City List";
    //     $page_data['page_name'] = "city";
    //     $this->load->view('templete', $page_data);
    // }

    // public function area($ids = "")
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }

    //     $submit = $this->input->post("submit");
    //     $update_submit = $this->input->post("update_submit");
    //     $sid = $this->input->get('sid');
    //     if (isset($sid)) {
    //         $this->Crud_model->update_custom('aria', 'id', 'status', $sid);
    //         redirect(base_url() . 'admin/area', 'refresh');
    //     }

    //     $data = array(
    //         'country_id' => $this->input->post('country_id'),
    //         'state_id' => $this->input->post('state_id'),
    //         'city_id' => $this->input->post('city_id'),
    //         'area_name' => strtoupper($this->input->post('area_name'))
    //     );

    //     if (isset($submit)) {
    //         $this->Crud_model->insert('aria', $data);
    //         redirect(base_url() . 'admin/area', 'refresh');
    //     }
    //     if (isset($update_submit)) {
    //         $this->Crud_model->update('aria', 'id', $ids, $data);
    //         redirect(base_url() . 'admin/area', 'refresh');
    //     }

    //     $page_data['aria'] = $this->Crud_model->join_area();
    //     $page_data['country'] = $this->Crud_model->fetch('country')->result();
    //     $page_data['page_title'] = "Aria List";
    //     $page_data['page_name'] = "area";
    //     $this->load->view('templete', $page_data);
    // }

    public function all_users()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('users', 'id', 'status', $sid);
            redirect(base_url() . 'admin/all_users', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $data_id = $this->input->post('id_hidden');
            $wallet_get = $this->Crud_model->fetch('users', array('id' => $data_id));
            $datas['wallet'] = $this->input->post('amount') + $wallet_get->row('wallet');

            $this->Crud_model->update('users', 'id', $data_id, $datas);

            $msg = "Your, Rs." . $this->input->post('amount') . " added on wallet by team";
            $this->Crud_model->insert('transaction_history', array('user_id' => $data_id, 'add_amount' => $this->input->post('amount'), 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));

            redirect(base_url() . 'admin/all_users', 'refresh');
        }

        $submit_cut = $this->input->post('submit_cut');

        if (isset($submit_cut)) {
            $id_hidden_cut = $this->input->post('id_hidden_cut');
            $wallet_gets = $this->Crud_model->fetch('users', array('id' => $id_hidden_cut));
            $datass['wallet'] = $wallet_gets->row('wallet') - $this->input->post('amount');
            $this->Crud_model->update('users', 'id', $id_hidden_cut, $datass);
            $msg = "Your, Rs." . $this->input->post('amount') . " has been deducted by team";
            $this->Crud_model->insert('transaction_history', array('user_id' => $id_hidden_cut, 'add_amount' => $this->input->post('amount'), 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
            redirect(base_url() . 'admin/all_users', 'refresh');
        }

        $page_data['users'] = $this->Crud_model->fetch('users', 'DESC');
        $page_data['page_title'] = "All Users";
        $page_data['page_name'] = "all_users";
        $this->load->view('templete', $page_data);
    }

    public function inactive_users()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('users', 'id', 'status', $sid);
            redirect(base_url() . 'admin/inactive_users', 'refresh');
        }

        $page_data['users'] = $this->Crud_model->fetch('users', array('status' => 1), 'DESC');
        $page_data['page_title'] = "Inactive Users";
        $page_data['page_name'] = "inactive_users";
        $this->load->view('templete', $page_data);
    }

    public function active_users()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('users', 'id', 'status', $sid);
            redirect(base_url() . 'admin/active_users', 'refresh');
        }

        $page_data['users'] = $this->Crud_model->fetch('users', array('status' => 0), 'DESC');
        $page_data['page_title'] = "Active Users";
        $page_data['page_name'] = "active_users";
        $this->load->view('templete', $page_data);
    }

    public function referred_users()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $reffer_id = $this->input->get('reffer_id');

        $page_data['users'] = $this->Crud_model->fetch('users', array('status' => 0, 'reffer_code' => $reffer_id), 'DESC');
        $page_data['page_title'] = "Referred Users";
        $page_data['page_name'] = "referred_users";
        $this->load->view('templete', $page_data);
    }

    public function pending_req()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $id = $this->input->get('id');
        $ids = $this->input->get('ids');
        $comment_id = $this->input->post('comment_id');
        $update = $this->input->post('update');
        $comment = $this->input->post('comment');

        if (isset($update)) { // reject
            $wallets = $this->Crud_model->fetch('payment_request', array('id' => $comment_id));
            $this->Crud_model->update('payment_request', 'id', $comment_id, array('status' => 2, 'comment' => $comment, 'date' => date("Y-m-d"), 'time' => date("H:i")));
            $msg = "Wallet Request for Rs." . $wallets->row('amount') . " has been rejected by team";
            $this->Crud_model->insert('transaction_history', array('user_id' => $wallets->row('user_id'), 'comment' => $comment, 'add_amount' => $wallets->row('amount'), 'description' => $msg, 'status' => 3, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
            redirect(base_url('admin/pending_req'), 'refresh');
        }

        if (isset($id)) { // accept
            $this->Crud_model->update('payment_request', 'id', $id, array('status' => 1));
            $get_user = $this->Crud_model->fetch('users', array('id' => $ids));
            $wallets = $this->Crud_model->fetch('payment_request', array('id' => $id));

            if (!empty($get_user->row('reffer_code'))) {
                $get_commition = $this->Crud_model->fetch('game_setting', array('commission_amount'));
                $get_refferal_user = $this->Crud_model->fetch('users', array('my_reffer_code' => $get_user->row('reffer_code')));
                $total_commition_amount = ($wallets->row('amount') * $get_commition->row('commission_amount') / 100) + $get_refferal_user->row('wallet');
                $this->Crud_model->update('users', 'my_reffer_code', $get_user->row('reffer_code'), array('wallet' => $total_commition_amount));
                $this->Crud_model->update('users', 'id', $ids, array('wallet' => $wallets->row('amount') + $get_user->row('wallet')));

                $msg = "You have earned Rs." . ($wallets->row('amount') * $get_commition->row('commission_amount') / 100) . " via a commission";

                $this->Crud_model->insert('transaction_history', array('user_id' => $get_refferal_user->row('id'), 'add_amount' => ($wallets->row('amount') * $get_commition->row('commission_amount') / 100), 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
            } else {
                $this->Crud_model->update('users', 'id', $ids, array('wallet' => $wallets->row('amount') + $get_user->row('wallet')));
            }

            $msg = "Your, Rs." . $wallets->row('amount') . " has been added successfully";
            $this->Crud_model->insert('transaction_history', array('user_id' => $ids, 'add_amount' => $wallets->row('amount'), 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
            redirect(base_url('admin/pending_req'), 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 0, 'type' => 1), 'DESC');
        $page_data['page_title'] = "Pending Request";
        $page_data['page_name'] = "pending_request";
        $this->load->view('templete', $page_data);
    }

    public function accept_req()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 1, 'type' => 1), 'DESC');
        $page_data['page_title'] = "Accepted Request";
        $page_data['page_name'] = "accept_request";
        $this->load->view('templete', $page_data);
    }

    public function reject_req()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 2, 'type' => 1), 'DESC');
        $page_data['page_title'] = "Rejected Request";
        $page_data['page_name'] = "reject_request";
        $this->load->view('templete', $page_data);
    }

    public function pending_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $id = $this->input->get('id');
        $ids = $this->input->post('ids');
        $idss = $this->input->post('idss');
        $update_submit = $this->input->post('update_submit');
        $comment = $this->input->post('comment');
        $wallet = $this->Crud_model->fetch('payment_request', array('id' => $idss));
        $user = $this->Crud_model->fetch('users', array('id' => $ids));

        if (isset($update_submit)) {
            $this->Crud_model->update('payment_request', 'id', $idss, array('status' => 2, 'comment' => $comment, 'date' => date("Y-m-d"), 'time' => date("H:i")));
            $this->Crud_model->update('users', 'id', $ids, array('wallet' => $wallet->row('amount') + $user->row('wallet')));
            $msg = "Your, Rs." . $wallet->row('amount') . " withdrawal request has been Rejected by team ";
            $this->Crud_model->insert('transaction_history', array('user_id' => $ids, 'add_amount' => $wallet->row('amount'), 'description' => $msg, 'status' => 3, 'date' => date('Y-m-d'), 'comment' => $comment, 'time' => date('H:i:s')));
            redirect(base_url() . 'admin/pending_withdraw', 'refresh');
        }

        if (isset($id)) {
            $this->Crud_model->update('payment_request', 'id', $id, array('status' => 1));
            $wallet = $this->Crud_model->fetch('payment_request', array('id' => $id));
            $msg = " Rs." . $wallet->row('amount') . " has been successfully transfered";
            $this->Crud_model->insert('transaction_history', array('user_id' => $ids, 'add_amount' => $wallet->row('amount'), 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
            redirect(base_url() . 'admin/pending_withdraw', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 0, 'type' => 2), 'DESC');
        $page_data['page_title'] = "Pending Withdraw";
        $page_data['page_name'] = "pending_withdraw";
        $this->load->view('templete', $page_data);
    }

    public function accept_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 1, 'type' => 2));
        $page_data['page_title'] = "Accepted Withdraw";
        $page_data['page_name'] = "accept_withdraw";
        $this->load->view('templete', $page_data);
    }

    public function reject_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payment_request', array('status' => 2, 'type' => 2), 'DESC');
        $page_data['page_title'] = "Rejected Withdraw";
        $page_data['page_name'] = "reject_withdraw";
        $this->load->view('templete', $page_data);
    }

    public function heading_text()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('heading_text', 'id', 1, array('text_msg' => $this->input->post('heading_text')));
            redirect(base_url() . 'admin/heading_text', 'refresh');
        }

        $page_data['page_title'] = "Heading Text";
        $page_data['page_name'] = "heading_text";
        $this->load->view('templete', $page_data);
    }

    // public function add_money($id)
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }

    //     $submit = $this->input->post('submit');
    //     if (isset($submit)) {

    //         // $this->Crud_model->update('users', 'id', );
    //         // $this->Crud_model->update_custom('users', 'id', 'status', $sid);
    //         redirect(base_url() . 'admin/users', 'refresh');
    //     }
    //     // $page_data['users'] = $this->Crud_model->fetch('users');
    //     // $page_data['page_title'] = "Users";
    //     // $page_data['page_name'] = "users";
    //     $this->load->view('templete', $page_data);
    // }

    // public function add_game()
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }
    //     $submit = $this->input->post('submit');
    //     if (isset($submit)) {

    //         // $start_num = $this->input->post('start_num');
    //         // $end_num = $this->input->post('end_num');
    //         // $number = json_encode(range($start_num, $end_num));

    //         $data = array(
    //             'game_name' => $this->input->post('game_name'),
    //             'start_num' => $this->input->post('start_num'),
    //             'end_num' => $this->input->post('end_num'),
    //             'start_date' => date_insert(),
    //             'end_date' => date_insert(),
    //             'start_time' => time_insert(),
    //             'end_time' => time_insert(),
    //             'bet_money' => $this->input->post('bet_money'),
    //             'wining_price' => $this->input->post('wining_price'),
    //             'result_date' => time_insert(),
    //             'result_time' => time_insert()
    //         );
    //         $this->Crud_model->insert('game', $data);
    //         redirect(base_url() . 'admin/games', 'refresh');
    //     }
    //     $page_data['page_title'] = "Create Game";
    //     $page_data['page_name'] = "create_game";
    //     $this->load->view('templete', $page_data);
    // }

    public function terms_condition()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('t&c', 'id', 1, array('description' => $this->input->post('description')));
            redirect(base_url() . 'admin/terms_condition', 'refresh');
        }

        $page_data['page_title'] = "Terms Condition";
        $page_data['page_name'] = "terms_condition";
        $this->load->view('templete', $page_data);
    }

    public function privacy_policy()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('privacy_policy', 'id', 1, array('description' => $this->input->post('description')));
            redirect(base_url() . 'admin/privacy_policy', 'refresh');
        }

        $page_data['page_title'] = "Privacy Policy";
        $page_data['page_name'] = "privacy_policy";
        $this->load->view('templete', $page_data);
    }

    public function game_rule()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('game_rule', 'id', 1, array('description' => $this->input->post('description')));
            redirect(base_url() . 'admin/game_rule', 'refresh');
        }

        $page_data['page_title'] = "Game Rules";
        $page_data['page_name'] = "game_rule";
        $this->load->view('templete', $page_data);
    }

    public function reffer_rule()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('reffer_rule', 'id', 1, array('description' => $this->input->post('description')));
            redirect(base_url() . 'admin/reffer_rule', 'refresh');
        }

        $page_data['page_title'] = "Reffer Rules";
        $page_data['page_name'] = "reffer_rule";
        $this->load->view('templete', $page_data);
    }

    public function game_list()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $sid = $this->input->get('sid');
        $ids = $this->input->get('ids');
        if (isset($sid)) {
            $this->Crud_model->update_custom('game', 'id', 'status', $sid);
            redirect(base_url() . 'admin/game_list', 'refresh');
        }

        if (isset($ids)) {
            $this->Crud_model->delete('game', $ids);
            redirect(base_url() . 'admin/game_list', 'refresh');
        }

        $submit = $this->input->post('submit');
        $submit_update = $this->input->post('submit_update');

        if (isset($submit)) {

            $data = array(
                'game_name' => $this->input->post('game_name'),
            );

            $this->Crud_model->insert('game', $data);
            redirect(base_url() . 'admin/game_list', 'refresh');
        }

        if (isset($submit_update)) {

            $id_hidden = $this->input->post('id_hidden');

            $data_update = array(
                'game_name' => $this->input->post('game_name'),
            );

            $this->Crud_model->update('game', 'id', $id_hidden, $data_update);
            redirect(base_url() . 'admin/game_list', 'refresh');
        }

        $page_data['games'] = $this->Crud_model->fetch('game', 'DESC');
        $page_data['page_title'] = "Game";
        $page_data['page_name'] = "game_list";
        $this->load->view('templete', $page_data);
    }

    public function running_game()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post("submit");
        $submit_update = $this->input->post("submit_update");

        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('create_game', 'id', 'status', $sid);
            redirect(base_url('admin/running_game'), 'refresh');
        }

        if (isset($submit)) {
            $game_list_id = $this->input->post("game_list_id");
            $start_date = $this->input->post("start_date");
            $start_time = $this->input->post("start_time");
            $end_date = $this->input->post("end_date");
            $end_time = $this->input->post("end_time");
            $result_date = $this->input->post("result_date");
            $result_time = $this->input->post("result_time");

            $this->Crud_model->insert('create_game', array('game_id' => $game_list_id, 'start_date' => $start_date, 'start_time' => date("H:i:s", strtotime($start_time)), 'end_date' => $end_date, 'end_time' => date("H:i:s", strtotime($end_time)), 'result_date' => $result_date, 'result_time' => date("H:i:s", strtotime($result_time)), 'date' => date("Y-m-d"), 'time' => date("H:i:s")));
            redirect(base_url('admin/running_game'), 'refresh');
        }

        if (isset($submit_update)) {
            $update_id = $this->input->post("id_hidden");
            $start_date = $this->input->post("start_date_update");
            $start_time = $this->input->post("start_time_update");
            $end_date = $this->input->post("end_date_update");
            $end_time = $this->input->post("end_time_update");
            $result_date = $this->input->post("result_date_update");
            $result_time = $this->input->post("result_time_update");

            $this->Crud_model->update('create_game', 'id', $update_id, array('start_date' => $start_date, 'start_time' => date("H:i:s", strtotime($start_time)), 'end_date' => $end_date, 'end_time' => date("H:i:s", strtotime($end_time)), 'result_date' => $result_date, 'result_time' => date("H:i:s", strtotime($result_time)), 'date' => date("Y-m-d"), 'time' => date("H:i:s")));
            redirect(base_url('admin/running_game'), 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => 0), 'DESC');
        $page_data['page_title'] = "Running Game";
        $page_data['page_name'] = "create_game";
        $this->load->view('templete', $page_data);
    }

    public function result_game()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => 1), 'DESC');
        $page_data['page_title'] = "Result Game";
        $page_data['page_name'] = "result_game";
        $this->load->view('templete', $page_data);
    }



    public function hddkjsj()
    {
        $data = $this->Crud_model->fetch('game_bet', array('game_id' => 5), 'DESC');

        echo "<pre>";
        foreach ($data->result() as $row) {
            // print_r($row);
            // echo "<br>";

            $datas[] = $row->game_number;
            // echo $row->game_number;
            // print_r(array_unique(array($row->game_number)));
        }

        print_r(array_unique($datas));

        foreach (array_unique($datas) as $item) {
            // echo $item;
            $get_money = $this->db->get_where('game_bet', array('game_number' => $item));

            $data_sum = array();
            foreach ($get_money->result() as $mm) {
                echo "<br>";
                $data_sum[] = $mm->money;
            }

            $arrs = array();
            foreach ($data_sum as $ss) {
                // print_r(array_sum(array($ss)));
                // print_r(array($ss));

                $arrs = array($ss);

                print_r($arrs);
            }

            echo "<br>";
        }
    }

    public function hdjkfsdjk()
    {
        $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => 5));

        foreach ($game_bet->result() as $row) {
            $number_arr[] = $row->game_number;
        }
        foreach (array_unique($number_arr) as $items) {
            $get_money = $this->Crud_model->fetch('game_bet', array('game_number' => $items, 'game_id' => 5));
            foreach ($get_money->result() as $arr) {
                $user_count[] = $arr->user_id;
            }
        }

        $win_money_added = $this->Crud_model->fetch('game_setting', array('winning_amount'));
        foreach (array_unique($user_count) as $users_arr) {
            $grand_total = $this->db->select_sum('money')->get_where('game_bet', array('user_id' => $users_arr, 'game_id' => 5, 'game_number' => 35));
            echo $grand_total->row('money') * $win_money_added->row('winning_amount') . "<br>";
            // echo $grand_total->row('money') . "<br>";
        }
    }

    public function user_get_ajax()
    {
        $game_id = $this->input->post('game_id');
        $game_number = $this->input->post('game_number');

        $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id, 'game_number' => $game_number));
        $i = 1;
        foreach ($game_bet->result() as $row) {
            $get_users = $this->Crud_model->fetch('users', array('id' => $row->user_id));
            echo "
                <tr>
                <td><b>" . $i++ . "</b></td>
                <td>" . $get_users->row('name') . "</td>
                <td>" . $get_users->row('mobile') . "</td>
                <td>" . $row->money . "</td></tr>";
        }
    }

    public function game_details()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $game_id = $this->input->get('game_id');
        $submit = $this->input->post("submit");
        if (isset($submit)) {
            $number = $this->input->post("number");
            $grand_total = $this->input->post("grand_total");

            if ($number < 100) {
                $this->Crud_model->update("create_game", "id", $game_id, array("status" => 1));
                $this->Crud_model->insert("game_result", array("create_game_id" => $game_id, "number" => $number, "grand_total" => $grand_total, "date" => date("Y-m-d"), "time" => date("H:i:s")));

                $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id));

                if ($game_bet->num_rows() > 0) {

                    foreach ($game_bet->result() as $row) {
                        $number_arr[] = $row->game_number;
                    }
                    foreach (array_unique($number_arr) as $items) {
                        $get_money = $this->Crud_model->fetch('game_bet', array('game_number' => $items, 'game_id' => $game_id));
                        foreach ($get_money->result() as $arr) {
                            $user_count[] = $arr->user_id;
                        }
                    }

                    $win_money_added = $this->Crud_model->fetch('game_setting', array('winning_amount'));
                    foreach (array_unique($user_count) as $users_arr) {
                        $grand_total = $this->db->select_sum('money')->get_where('game_bet', array('user_id' => $users_arr, 'game_id' => $game_id, 'game_number' => $number));
                        $add_wallet_money =  $grand_total->row('money') * $win_money_added->row('winning_amount');
                        $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
                        $msg = "You have earned Rs." . $add_wallet_money . " on game play";
                        $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'))); // reffer sender user
                    }
                }

                redirect(base_url('admin/running_game'), 'refresh');
            } else {
                echo "<script>alert('Please enter valid game number')</script>";
            }
        }

        $page_data['data'] = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id), 'DESC');
        $page_data['page_title'] = "Game Details";
        $page_data['page_name'] = "game_details";
        $this->load->view('templete', $page_data);
    }

    public function wallet_details()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit_update = $this->input->post('submit_update');
        $sid = $this->input->get("sid");

        if (isset($sid)) {
            $this->Crud_model->update_custom('payments', 'id', 'status', $sid);
            redirect(base_url() . 'admin/wallet_details', 'refresh');
        }

        if (isset($submit_update)) {

            $update_id = $this->input->post('id_hidden');

            $update_data = array(
                'name' => $this->input->post('name_update'),
                'upi_num' => $this->input->post('upi_update'),
                'qr_img' => do_upload('assets/images/', 'qr_img', 'jpeg|png|jpg'),
                'upi_company_img' => do_upload('assets/images/', 'company_img', 'jpeg|png|jpg')
            );

            $this->Crud_model->update('payments', 'id', $update_id, $update_data);
            redirect(base_url() . 'admin/wallet_details', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('payments');
        $page_data['page_title'] = "Wallet Details";
        $page_data['page_name'] = "wallet_details";
        $this->load->view('templete', $page_data);
    }

    public function banner_image()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');
        $submit_update = $this->input->post('submit_update');
        $sid = $this->input->get("sid");

        if (isset($sid)) {
            $this->Crud_model->update_custom('banner_image', 'id', 'status', $sid);
            redirect(base_url() . 'admin/banner_image', 'refresh');
        }

        if (isset($submit)) {
            $data = array(
                'banner_img' => do_upload('assets/images/', 'banner_img', 'jpeg|png|jpg')
            );
            $this->Crud_model->insert('banner_image', $data);
            redirect(base_url() . 'admin/banner_image', 'refresh');
        }

        if (isset($submit_update)) {

            $update_id = $this->input->post('id_hidden');

            $update_data = array(
                'banner_img' => do_upload('assets/images/', 'banner_img', 'jpeg|png|jpg')
            );

            $this->Crud_model->update('banner_image', 'id', $update_id, $update_data);
            redirect(base_url() . 'admin/banner_image', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('banner_image');
        $page_data['page_title'] = "Banner Image";
        $page_data['page_name'] = "banner_image";
        $this->load->view('templete', $page_data);
    }

    public function transaction_history()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $user_id = $this->input->get('user_id');

        $submit = $this->input->post('submit_bidding');

        if (isset($submit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('bidding_amount' => $this->input->post('amount')));
        }

        $page_data['data'] = $this->Crud_model->fetch('transaction_history', array('user_id' => $user_id), 'DESC');
        $page_data['page_title'] = "Transaction History";
        $page_data['page_name'] = "transaction_history";
        $this->load->view('templete', $page_data);
    }

    public function reffer_amount()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit_reffer = $this->input->post('submit_reffer');
        $submit_commission = $this->input->post('submit_commission');

        if (isset($submit_reffer)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('reffer_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/reffer_amount', 'refresh');
        }

        if (isset($submit_commission)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('commission_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/reffer_amount', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('game_setting');
        $page_data['page_title'] = "Reffer Amount";
        $page_data['page_name'] = "reffer_amount";
        $this->load->view('templete', $page_data);
    }

    public function payment_setting()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit_bidding = $this->input->post('submit_bidding');
        $submit_deposit = $this->input->post('submit_deposit');
        $submit_withdrawal = $this->input->post('submit_withdrawal');
        $submit_winning = $this->input->post('submit_winning');
        $submit_hold = $this->input->post('submit_hold');

        if (isset($submit_withdrawal)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('withdrawal_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        if (isset($submit_deposit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('deposit_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        if (isset($submit_bidding)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('bidding_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        if (isset($submit_winning)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('winning_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        if (isset($submit_hold)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('withdrawal_hold_amt' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('game_setting');
        $page_data['page_title'] = "Payment Setting";
        $page_data['page_name'] = "payment_setting";
        $this->load->view('templete', $page_data);
    }

    public function help()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit_info');

        if (isset($submit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('contact_info' => $this->input->post('amount')));
            redirect(base_url() . 'admin/help', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('game_setting');
        $page_data['page_title'] = "Help";
        $page_data['page_name'] = "help";
        $this->load->view('templete', $page_data);
    }

    public function about()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('about_us', 'id', 1, array('description' => $this->input->post('description')));
            redirect(base_url() . 'admin/about', 'refresh');
        }

        $page_data['page_title'] = "About";
        $page_data['page_name'] = "about";
        $this->load->view('templete', $page_data);
    }

    public function contact_info()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit_info');

        if (isset($submit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('contact_info' => $this->input->post('amount')));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public function app_update()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit');

        if (isset($submit)) {
            $this->Crud_model->update('version_code', 'id', 1, array('url' => $this->input->post('url'), 'virsion_code' => $this->input->post('version')));
            redirect(base_url('admin/app_update'), 'refresh');
        }

        $page_data['page_title'] = "App Update";
        $page_data['page_name'] = "app_update";
        $this->load->view('templete', $page_data);
    }

    // public function add_super_stockest()
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }
    //     $submit = $this->input->post('submit');

    //     if (isset($submit)) {
    //         $config['upload_path'] = './assets/document/';
    //         $config['allowed_types'] = 'pdf|docx|jpeg|jpg|png';
    //         $config['encrypt_name'] = TRUE;
    //         $this->load->library('upload', $config);
    //         $document = null;
    //         $image = null;
    //         $nature_buss_pic = null;
    //         $gst_pic = null;
    //         $pan_pic = null;
    //         $fssai_pic = null;
    //         if ($this->upload->do_upload('document')) {
    //             $document_upload = $this->upload->data();
    //             $document = "assets/document/" . $document_upload['file_name'];
    //         }

    //         if ($this->upload->do_upload('upload_image')) {
    //             $image_upload = $this->upload->data();
    //             $image = "assets/document/" . $image_upload['file_name'];
    //         }
    //         if ($this->upload->do_upload('gst_pic')) {
    //             $image_upload = $this->upload->data();
    //             $gst_pic = "assets/document/" . $image_upload['file_name'];
    //         }
    //         if ($this->upload->do_upload('pan_pic')) {
    //             $image_upload = $this->upload->data();
    //             $pan_pic = "assets/document/" . $image_upload['file_name'];
    //         }
    //         if ($this->upload->do_upload('fssai_pic')) {
    //             $image_upload = $this->upload->data();
    //             $fssai_pic = "assets/document/" . $image_upload['file_name'];
    //         }
    //         if ($this->upload->do_upload('nature_buss_pic')) {
    //             $image_upload = $this->upload->data();
    //             $nature_buss_pic = "assets/document/" . $image_upload['file_name'];
    //         }

    //         $emp_id = $this->input->post('emp_id');
    //         $data = array(
    //             'cus_id' => $emp_id,
    //             'business_name' => $this->input->post('bussiness_name'),
    //             'gst' => $this->input->post('gst'),
    //             'pan' => $this->input->post('pan'),
    //             'fssai' => $this->input->post('fssai'),
    //             'nature_of_bussiness' => $this->input->post('nature_bussiness'),
    //             'country' => $this->input->post('country_id'),
    //             'state' => $this->input->post('state_id'),
    //             'city' => $this->input->post('city_id'),
    //             'contact_person' => $this->input->post('contact_person'),
    //             'contact_person_no' => $this->input->post('contact_person_number'),
    //             'reson' => $this->input->post('costumer_reason'),
    //             'dob' => $this->input->post('dob'),
    //             'address' => $this->input->post('address'),
    //             'latitude' => $this->input->post('latt'),
    //             'longitude' => $this->input->post('long'),
    //             'max_od' => $this->input->post('max_od'),
    //             'min_od' => $this->input->post('min_od'),
    //             'update_form' => $document,
    //             'aggriment_copy' => $image,
    //             'nature_buss_pic' => $nature_buss_pic,
    //             'gst_pic' => $gst_pic,
    //             'pan_pic' => $pan_pic,
    //             'fssai_pic' => $fssai_pic
    //         );
    //         $this->Crud_model->insert('super_stockist_details', $data);
    //         redirect(base_url() . 'admin/super_stockest', 'refresh');
    //     }

    //     $page_data['country'] = $this->Crud_model->fetch('country')->result();
    //     $page_data['super_stockist_details'] = $this->Crud_model->fetch('super_stockist_details')->result();
    //     $page_data['page_title'] = "Add Super Stockist";
    //     $page_data['page_name'] = "add_super_stockest";
    //     $this->load->view('templete', $page_data);
    // }



    public function demo()
    {
        $page_data['page_name'] = "demo";
        //  $this->load->view('demo');
        $this->load->view('demo', $page_data);
    }
}
