<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Webservices extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_model');

        // CORS Headers — allow all origins (dev + Vercel production)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        // Handle preflight OPTIONS request immediately
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
    
    public function signup_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->signup();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function login_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->login();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function mobile_check_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->mobile_check();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function forget_password_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->forget_password();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function profile_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->profile();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function terms_condition_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->terms_condition();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function privacy_policy_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->privacy_policy();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function about_us_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->about_us();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function add_bank_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->add_bank();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function wallet_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->wallet();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function game_list_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_list();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function game_chart_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_chart();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function game_bet_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_bet();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function game_bet_cancel_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_bet_cancel();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function game_details_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_details();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function my_bets_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->my_bets();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function my_bets_cancel_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->my_bets_cancel();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function bets_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->bets();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function show_bets_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->show_bets();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function check_reffer_code_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->check_reffer_code();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function help_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->help();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function add_money_details_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->add_money_details();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    
    public function check_account_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->check_account();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function add_money_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->add_money();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function withdraw_money_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->withdraw_money();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function add_money_gateway_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->add_moneyAutomatic();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function reffer_rules_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->reffer_rules();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function update_profile_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->update_profile();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
    public function updatefcm_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->updatefcm();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function get_profile_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->get_profile();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function game_rule_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_rule();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function transaction_history_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->transaction_history();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }
       
    public function game_result_post()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->api_model->game_result();
        header('Content-type: application/json');
        if ($result['status'] == '1') {
            $result_final = $result;
        } else {
            $result_final = $result;
        }
        $this->response($result_final);
    }

    public function get_qr_details_post()
    {
        header('Content-type: application/json');
        $result = $this->api_model->get_qr_details();
        $this->response($result);
    }

    public function submit_qr_payment_post()
    {
        header('Content-type: application/json');
        $result = $this->api_model->submit_qr_payment();
        $this->response($result);
    }
}
