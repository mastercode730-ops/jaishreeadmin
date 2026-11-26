<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    
    private $serverKey = "G38tPyeXE6ItgdxXGV3WJn3iP0RwqHEANXfAMqsLEqI";
    
    public function sendnotifi() {
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = "AIzaSyCheZKOkQTDnzyODsOA3Pu-Duhq9jeaEL8"; // Ensure this is valid
    $firebaseToken = "ePQqORzwQ9yIWLvHQyHT7Z:APA91bHX3DTAgbIUSfnSSO3y2n1dRp5Ifs9NZqU80uui-lriAyye9qdjEtBZhpEBDU6ai0LU18zrsBGVS-_xN6s8HYV-_2jxi13xzqZbv0RvVv_24572ddE";  
    $title = "note";
    $message = "Test Message";  // Avoid using offensive words in messages

    if (!$firebaseToken || !$title || !$message) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        return;
    }

    $notificationData = [
        "to" => $firebaseToken,
        "notification" => [
            "title" => $title,
            "body"  => $message,
            "sound" => "default"
        ],
        "data" => [
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "id" => uniqid(),
            "status" => "done"
        ]
    ];

    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));

    $response = curl_exec($ch);
    
    if ($response === false) {
        echo json_encode(["status" => "error", "message" => curl_error($ch)]);
    } else {
        echo $response;
    }

    curl_close($ch);
}

//     public function sendNotification() {
//     $url = "https://fcm.googleapis.com/fcm/send";
//     $serverKey = "G38tPyeXE6ItgdxXGV3WJn3iP0RwqHEANXfAMqsLEqI";  // Replace with your actual FCM server key

//     // Get POST data
//     $firebaseToken = $this->input->post('token');  // Token from user device
//     $title = $this->input->post('title');
//     $message = $this->input->post('message');

//     // Debugging: Check received POST data
//     if (!$firebaseToken || !$title || !$message) {
//         echo json_encode(["status" => "error", "message" => "Invalid input"]);
//         return;
//     }

//     // FCM Payload
//     $notificationData = [
//         "to" => $firebaseToken,  // Single Device Token
//         "notification" => [
//             "title" => $title,
//             "body"  => $message,
//             "sound" => "default"
//         ],
//         "data" => [
//             "click_action" => "FLUTTER_NOTIFICATION_CLICK",
//             "id" => uniqid(),
//             "status" => "done"
//         ]
//     ];
    
// //     print_r($notificationData);
// // die();


//     // Headers
//     $headers = [
//         'Authorization: key=' . $serverKey,
//         'Content-Type: application/json'
//     ];

//     // cURL Request
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));

//     $response = curl_exec($ch);
//     if ($response === false) {
//         echo json_encode(["status" => "error", "message" => curl_error($ch)]);
//     } else {
//         echo $response;
//     }
//     curl_close($ch);
// }


    function __construct()
    {
        parent::__construct();
        $this->load->model("Crud_model");
        $this->load->helper('cookie');
        $this->load->model('api_model');
        $this->load->helper('firebase_helper');
    }
    
    public function sendNotification() {
        $deviceToken = $this->input->post('token');  // Token from frontend
        $title = $this->input->post('title');
        $message = $this->input->post('message');

        if (!$deviceToken || !$title || !$message) {
            echo json_encode(["status" => "error", "message" => "Invalid input"]);
            return;
        }

        // Send Notification
        $response = send_firebase_notification($deviceToken, $title, $message);
        
        echo json_encode(["status" => "success", "response" => json_decode($response)]);
    }
    
    public function sendNotificationByfUN($deviceToken,$title,$message) {

        if (!$deviceToken || !$title || !$message) {
            echo json_encode(["status" => "error", "message" => "Invalid input"]);
            return;
        }

        // Send Notification
        return $response = send_firebase_notification($deviceToken, $title, $message);
        
        echo json_encode(["status" => "success", "response" => json_decode($response)]);
    }

    public function index()
    {
        $password_plain = $this->input->post('password');
        $email = $this->input->post('email');

        if ($this->input->server('REQUEST_METHOD') === 'POST' || (!empty($email) && !empty($password_plain))) {
            $password_data = $this->Crud_model->fetch('admin_login', array('email' => $email, 'status'=>1))->row();
            if ($password_data && $password_data->email == $email) {
                if (password_verify($password_plain, $password_data->password)) {
                    $this->session->set_userdata('user_type', 'admin');
                    $this->session->set_userdata('login', 1);
                    $this->session->set_userdata('level', $password_data->level);
                    $this->session->set_userdata('id', $password_data->id);
                    redirect(base_url() . 'admin/dashboard');
                    return;
                }
            }
            $this->session->set_flashdata('error', 'Invalid Email or Password!');
            redirect(base_url() . 'login/admin');
            return;
        }

        $this->load->view('admin_login');
    }

    public function login()
    {
        $this->index();
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
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $aid = false;
        if($admin_data->level == 1){
            $aid = $admin_data->id;
        }
        $submit_cut = $this->input->get('submit');
        if(isset($submit_cut)){
        $this->db->select('
            cg.date,
            COALESCE(SUM(CASE WHEN gb.cancel_at IS NULL THEN gb.money ELSE 0 END), 0) as total_bid_amount,
            (SELECT COALESCE(SUM(th.add_amount), 0) 
 FROM transaction_history th 
 WHERE th.create_game_id IS NOT NULL 
 AND th.date = cg.date) as total_distribute_amount
        ');
        
        $this->db->from('create_game cg');
        $this->db->join('game_bet gb', 'gb.game_id = cg.id', 'left');
        $this->db->join('game_setting gs', '1=1', 'left'); // Assuming `winning_amount` is constant for all games
        $this->db->join('game_result gr', 'gr.create_game_id = cg.id', 'left');
        $this->db->where('cg.status', '1');
        if($aid){
            $this->db->where('gb.aid', $aid);
        }
        $this->db->group_by('cg.date');
        $this->db->order_by('cg.date', 'DESC');
        $this->db->limit(10, 0);


        $page_data['data10Days'] = $this->db->get();
        }
        
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        $page_data['agentId'] = $aid;
        $page_data['level'] = $this->session->userdata('level');
        $this->load->view('templete', $page_data);
    }
    
    public function add_subusers()
    {
    // Ensure the user is logged in
    if (!$this->session->userdata('login')) {
        redirect(base_url() . 'login/admin', 'refresh');
    }

    $submit_cut = $this->input->post('submit');
    $agent = $this->input->get('agent');
     if (isset($submit_cut)) {
        $fname=$this->input->post('name');
        $email=$this->input->post('email');
        $pass = $this->input->post('password');
        $conpass = $this->input->post('cpassword');
        if($fname=='' || $email =='' || $pass=='' || $conpass==''){
            $page_data['error'] = "Fill Required Fields.";
            $page_data['page_name'] = "add_user";
            $page_data['page_title'] = "Add Users";
            $this->load->view('templete', $page_data);
        }else if($pass!=$conpass){
                $page_data['error'] = "Both password should be same.";
                $page_data['page_name'] = "add_user";
                $this->load->view('templete', $page_data);
        }else{
            $datass['password'] = ($conpass);
            $data = [
                'name' => trim($fname),
                'email' => $email,
                'password' => password_hash($conpass, PASSWORD_DEFAULT),
                'created_at'=> date('Y-m-d H:i:s'),
                'status'=>1
            ];
            
            if(isset($agent) && $agent == "yes"){
                $data['aid'] = 0;
                $data['level'] = 1;
                $data['access'] = 'all';
            }elseif($this->session->userdata('level') && $this->session->userdata('level') ==1){
                $data['aid'] = $this->session->userdata('id');
            }
            

            $this->Crud_model->insert('admin_login', $data); 
            
            // Redirect or load success message
            $page_data['success'] = "Sub User added successfully.";
            $page_data['page_title'] = "Add Users";
            $page_data['page_name'] = "add_user";
            $this->load->view('templete', $page_data);
            }
     }else{
    $page_data['page_title'] = "Add Users";
    $page_data['page_name'] = "add_user";
    $this->load->view('templete', $page_data);
     }
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
            $this->Crud_model->insert('transaction_history', array('user_id' => $data_id, 'add_amount' => $this->input->post('amount'), 'purpose'=>'credit', 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));

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
        $submit_ratelst = $this->input->post('submit_ratelist');

        if (isset($submit_ratelst)) {
            $id_hidden_cut = $this->input->post('id');
            $wallet_gets = $this->Crud_model->fetch('users', array('id' => $id_hidden_cut));
            $datass['commission_percentage'] = $this->input->post('commission_percentage');
            $datass['jodi_rate'] = $this->input->post('jodirate');
            $datass['haruf_rate'] = $this->input->post('harufrate');
            $this->Crud_model->update('users', 'id', $id_hidden_cut, $datass);
            redirect(base_url() . 'admin/all_users', 'refresh');
        }
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $master = false;
        $page_data['page_title'] = "All Users";
        $this->db->select('u.*, MAX(ul.otp) AS last_otp');
$this->db->from('users u');
$this->db->join('user_login ul', 'ul.phone = u.mobile', 'left');
$this->db->order_by('u.id', 'DESC'); // Assuming you want to order users, adjust as needed
$this->db->group_by('u.mobile');
$agentId = false;
if($admin_data->level == 1){
    $agentId = $admin_data->id;
    $this->db->where('u.aid', $admin_data->id);
}
$statusbyget = $this->input->get('status');
if(isset($statusbyget) && $statusbyget==1){
    $page_data['page_title'] = "Inactive Users";
    $this->db->where('u.status', 1);
}
if(isset($statusbyget) && $statusbyget==0){
    $page_data['page_title'] = "Active Users";
    $this->db->where('u.status', 0);
}
$query = $this->db->get();
$users_data = $query;
        // $page_data['users'] = $this->Crud_model->fetch('users', 'DESC');
        $page_data['users'] = $users_data;
        $page_data['aid'] = $agentId;
        $page_data['page_name'] = "all_users";
        $this->load->view('templete', $page_data);
    }
    
    public function subusers()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $sid = $this->input->get('sid');
        $agent = $this->input->get('agent');
        if (isset($sid)) {
            $this->Crud_model->update_custom('admin_login', 'id', 'status', $sid);
            redirect(base_url() . 'admin/subusers', 'refresh');
        }
        $arrr = array('id' => $this->session->userdata('id'));
        if (isset($agent) && $agent=='yes') {
            $arrr['level'] = 1;
        }
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        if($admin_data->level==0){
            if (isset($agent) && $agent=='yes') {
                $page_data['users'] = $this->Crud_model->fetch('admin_login', array('level'=>1),'DESC');
            }else{
                $page_data['users'] = $this->Crud_model->fetch('admin_login', 'DESC');
            }
        }else{
             if (isset($agent) && $agent=='yes') {
                $page_data['users'] = $this->Crud_model->fetch('admin_login', array('aid'=>$admin_data->id,'level'=>1),'DESC');
            }else{
                $page_data['users'] = $this->Crud_model->fetch('admin_login', array('aid'=>$admin_data->id), 'DESC');
            }
        }
        $page_data['page_title'] = "All Users";
        $page_data['page_name'] = "all_sub_users";
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

            $msg = "Deposit Point Accepted";
            $this->Crud_model->insert('transaction_history', array('user_id' => $ids, 'add_amount' => $wallets->row('amount'), 'description' => $msg, 'status' => 1, 'date' => date('Y-m-d'), 'time' => date('H:i:s'),'purpose' => 'credit'));
            redirect(base_url('admin/pending_req'), 'refresh');
        }
        
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
$this->db->from('payment_request pr');
$this->db->join('users u', 'pr.user_id = u.mobile', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 0);
$this->db->where('pr.type', 1);
$query = $this->db->get();
$users_data = $query;


        $page_data['data'] = $users_data;
        $page_data['page_title'] = "Pending Request";
        $page_data['page_name'] = "pending_request";
        $this->load->view('templete', $page_data);
    }

    public function accept_req()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
$this->db->from('payment_request pr');
$this->db->join('users u', 'pr.user_id = u.mobile', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 1);
$this->db->where('pr.type', 1);
$query = $this->db->get();
$users_data = $query;

        $page_data['data'] = $users_data;
        $page_data['page_title'] = "Accepted Request";
        $page_data['page_name'] = "accept_request";
        $this->load->view('templete', $page_data);
    }
    public function reject_req()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
$this->db->from('payment_request pr');
$this->db->join('users u', 'pr.user_id = u.mobile', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 2);
$this->db->where('pr.type', 1);
$query = $this->db->get();
$users_data = $query;

        $page_data['data'] = $users_data;
        $page_data['page_title'] = "Rejected Request";
        $page_data['page_name'] = "reject_request";
        $this->load->view('templete', $page_data);
    }
    
    public function add_money_callback()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request['status']);
        parse_str($postdata, $request);
        // $this->Crud_model->update('payments', 'id', 3, array('qr_img' => $postdata, 'upi_company_img' => $request['status']));
        
        $result = $this->api_model->add_moneyCallback($request['status'],$request['client_txn_id'],$request['upi_txn_id']);
        echo json_encode($result);
    }
    public function send_money_callback()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request['client_txn_id']);
        parse_str($postdata, $request);
        // $this->Crud_model->update('payments', 'id', 3, array('qr_img' => $postdata, 'upi_company_img' => $request['status']));
        
        $result = $this->api_model->add_moneyCallback($request['status'],$request['client_txn_id'],$request['upi_txn_id']);
        echo json_encode($result);
    }
    public function send_money_pay0shop_callback()
    {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request['client_txn_id']);
        parse_str($postdata, $request);
        // $this->Crud_model->update('payments', 'id', 3, array('qr_img' => $postdata, 'upi_company_img' => $request['status']));
        if (!isset($request['status']) || !isset($request['order_id'])) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $result = $this->api_model->add_moneyCallback($request['status'],$request['order_id'],"123456789012");
        echo json_encode($result);
    }


    public function pending_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $id = $this->input->get('id');
        $ids = $this->input->get('ids');
        $idss = $this->input->post('idss');
        $update_submit = $this->input->post('update_submit');
        $approve_submit = $this->input->post('approve_submit');
        $comment = $this->input->post('comment');

        // Handle Reject
        if (isset($update_submit)) {
            $ids = $this->input->post('ids');
            $wallet = $this->Crud_model->fetch('payment_request', array('id' => $idss));
            $user = $this->Crud_model->fetch('users', array('id' => $ids));
            $this->Crud_model->update('payment_request', 'id', $idss, array('status' => 2, 'comment' => $comment, 'date' => date("Y-m-d"), 'time' => date("H:i")));
            $this->Crud_model->update('users', 'id', $ids, array('wallet' => $wallet->row('amount') + $user->row('wallet')));
            $msg = "Your Rs." . $wallet->row('amount') . " withdrawal request has been rejected. Reason: " . $comment;
            $this->Crud_model->insert('transaction_history', array('user_id' => $ids, 'add_amount' => $wallet->row('amount'), 'description' => $msg, 'status' => 3, 'date' => date('Y-m-d'), 'comment' => $comment, 'time' => date('H:i:s')));
            redirect(base_url() . 'admin/pending_withdraw', 'refresh');
        }

        // Handle Approve via Modal (with Bank UTR / Reference)
        if (isset($approve_submit)) {
            $approve_id = $this->input->post('approve_id');
            $approve_user_id = $this->input->post('approve_user_id');
            $admin_txn = trim($this->input->post('admin_txn') ?? '');

            $req = $this->Crud_model->fetch('payment_request', array('id' => $approve_id))->row();
            if ($req) {
                $update_data = array('status' => 1);
                if (!empty($admin_txn)) {
                    $update_data['transaction_id'] = $admin_txn;
                    $update_data['comment'] = 'Bank UTR: ' . $admin_txn;
                }
                $this->Crud_model->update('payment_request', 'id', $approve_id, $update_data);

                if (!empty($req->ifsc_code)) {
                    $msg = "Withdrawal of Rs." . $req->amount . " transferred to Bank A/C " . $req->bank_ac_no . " (" . $req->bank_name . ", IFSC: " . $req->ifsc_code . ")" . (!empty($admin_txn) ? " | UTR: " . $admin_txn : "");
                } else {
                    $msg = "Withdrawal of Rs." . $req->amount . " transferred to " . $req->bank_name . " (" . $req->bank_ac_no . ")" . (!empty($admin_txn) ? " | Ref: " . $admin_txn : "");
                }
                $this->Crud_model->insert('transaction_history', array('user_id' => $approve_user_id, 'add_amount' => $req->amount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'purpose' => 'debit'));
            }
            redirect(base_url() . 'admin/pending_withdraw', 'refresh');
        }

        // Handle Approve via Direct Link
        if (isset($id)) {
            $this->Crud_model->update('payment_request', 'id', $id, array('status' => 1));
            $wallet = $this->Crud_model->fetch('payment_request', array('id' => $id))->row();
            if ($wallet) {
                if (!empty($wallet->ifsc_code)) {
                    $msg = "Withdrawal of Rs." . $wallet->amount . " transferred to Bank A/C " . $wallet->bank_ac_no . " (" . $wallet->bank_name . ", IFSC: " . $wallet->ifsc_code . ")";
                } else {
                    $msg = "Withdrawal of Rs." . $wallet->amount . " transferred to " . $wallet->bank_name . " (" . $wallet->bank_ac_no . ")";
                }
                $user_id_for_txn = !empty($ids) ? $ids : $wallet->user_id;
                $this->Crud_model->insert('transaction_history', array('user_id' => $user_id_for_txn, 'add_amount' => $wallet->amount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'purpose' => 'debit'));
            }
            redirect(base_url() . 'admin/pending_withdraw', 'refresh');
        }
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
        $this->db->from('payment_request pr');
        $this->db->join('users u', '(pr.user_id = u.id OR pr.user_id = u.mobile)', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 0);
$this->db->where('pr.type', 2);
$query = $this->db->get();
$users_data = $query;


        $page_data['data'] = $users_data;
        $page_data['page_title'] = "Pending Withdraw";
        $page_data['page_name'] = "pending_withdraw";
        $this->load->view('templete', $page_data);
    }

    public function accept_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
        $this->db->from('payment_request pr');
        $this->db->join('users u', '(pr.user_id = u.id OR pr.user_id = u.mobile)', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 1);
$this->db->where('pr.type', 2);
$query = $this->db->get();
$users_data = $query;


        $page_data['data'] = $users_data;
        $page_data['page_title'] = "Accepted Withdraw";
        $page_data['page_name'] = "accept_withdraw";
        $this->load->view('templete', $page_data);
    }

    public function reject_withdraw()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

$admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $this->db->select('pr.*');
        $this->db->from('payment_request pr');
        $this->db->join('users u', '(pr.user_id = u.id OR pr.user_id = u.mobile)', 'left');
$this->db->order_by('pr.id', 'DESC'); // Assuming you want to order users, adjust as needed
if($admin_data->level == 1){
    $this->db->where('u.aid', $admin_data->id);
}
$this->db->where('pr.status', 2);
$this->db->where('pr.type', 2);
$query = $this->db->get();
$users_data = $query;


        $page_data['data'] = $users_data;
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
    public function monthly_user_list()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $this->load->database();

        // Get all users from the new table
        $users_query = $this->db->get('monthly_user');
        $users = $users_query->result();

        // Calculate total profit/loss for the current month for each user
        $current_month = date('Y-m');
        $this->db->select('user_id, SUM(amount) as total_amount');
        $this->db->where("DATE_FORMAT(result_date, '%Y-%m') =", $current_month);
        $this->db->group_by('user_id');
        $results_query = $this->db->get('user_amounts');
        $monthly_summary = $results_query->result_array();

        $user_summary = [];
        foreach ($monthly_summary as $row) {
            $user_summary[$row['user_id']] = $row['total_amount'];
        }

        $page_data['users'] = $users;
        $page_data['monthly_summary'] = $user_summary;
        $page_data['page_title'] = "User List";
        $page_data['page_name'] = "monthly_user_list";

        $this->load->view('templete', $page_data);
    }

    /**
     * Handles AJAX submission for adding a new user.
     */
    public function add_user()
    {
        if (!$this->input->is_ajax_request() || !$this->session->userdata('login') == 1) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
            exit();
        }

        $this->load->database();
        $name = $this->input->post('name');

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'User name is required.']);
            exit();
        }

        // Check if user name already exists (unique constraint)
        $this->db->where('name', $name);
        $existing_user = $this->db->get('monthly_user');
        if ($existing_user->num_rows() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'A user with this name already exists.']);
            exit();
        }

        $data = ['name' => $name];
        $this->db->insert('monthly_user', $data);

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding user.']);
        }
        exit();
    }

    public function user_data_entry()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $this->load->database();
        $user_id = $this->input->get('user_id');

        if (empty($user_id)) {
            redirect(base_url('admin/monthly_user_list'), 'refresh');
        }

        // Get the month and year from the URL or set defaults
        $month = $this->input->get('month') ?: date('m');
        $year = $this->input->get('year') ?: date('Y');
        
        $first_day_of_month = strtotime($year . '-' . $month . '-01');
        $days_in_month = date('t', $first_day_of_month);

        // Fetch existing data for the user
        $start_date = $year . '-' . $month . '-01';
        $end_date = $year . '-' . $month . '-' . $days_in_month;
        $this->db->where('user_id', $user_id);
        $this->db->where('result_date >=', $start_date);
        $this->db->where('result_date <=', $end_date);
        $page_data['results'] = $this->db->get('user_amounts')->result_array();

        $user_info = $this->db->where('id', $user_id)->get('monthly_user')->row();

        $page_data['user_info'] = $user_info;
        $page_data['user_id'] = $user_id;
        $page_data['month'] = $month;
        $page_data['year'] = $year;
        $page_data['days_in_month'] = $days_in_month;
        $page_data['page_title'] = "Data Entry for " . $user_info->name;
        $page_data['page_name'] = "user_data_entry";

        $this->load->view('templete', $page_data);
    }
    
    /**
     * Handles AJAX submission for saving user amounts.
     * Returns JSON response.
     */
    public function save_user_amounts_ajax()
    {
        if (!$this->input->is_ajax_request() || !$this->session->userdata('login') == 1) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
            exit();
        }

        $this->load->database();
        
        $user_id = $this->input->post('user_id');
        $amounts = $this->input->post('amounts');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

        if (empty($user_id) || empty($amounts)) {
             echo json_encode(['status' => 'error', 'message' => 'Missing data.']);
             exit();
        }

        foreach ($amounts as $day => $amount) {
            $amount = trim($amount);
            $result_date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $this->db->where('user_id', $user_id);
            $this->db->where('result_date', $result_date);
            $existing_record = $this->db->get('user_amounts');

            if (is_numeric($amount)) {
                $data = ['user_id' => $user_id, 'result_date' => $result_date, 'amount' => $amount];
                if ($existing_record->num_rows() > 0) {
                    $this->db->where('user_id', $user_id);
                    $this->db->where('result_date', $result_date);
                    $this->db->update('user_amounts', $data);
                } else {
                    $this->db->insert('user_amounts', $data);
                }
            } elseif (empty($amount) && $existing_record->num_rows() > 0) {
                $this->db->where('user_id', $user_id);
                $this->db->where('result_date', $result_date);
                $this->db->delete('user_amounts');
            }
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.']);
        exit();
    }
    public function delete_user_ajax()
    {
        if (!$this->input->is_ajax_request() || !$this->session->userdata('login') == 1) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
            exit();
        }

        $this->load->database();
        $user_id = $this->input->post('user_id');

        if (empty($user_id)) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is missing.']);
            exit();
        }

        // Delete the user from the monthly_user table
        $this->db->where('id', $user_id);
        $this->db->delete('monthly_user');

        if ($this->db->affected_rows() > 0) {
            // Note: The 'ON DELETE CASCADE' in your user_amounts table will automatically
            // delete all data associated with this user.
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting user. User not found.']);
        }
        exit();
    }
    public function today_game()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post("submit");
        $submit_update = $this->input->post("submit_update");

        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('create_game', 'id', 'status', $sid);
            redirect(base_url('admin/today_game'), 'refresh');
        }

        if (isset($submit)) {
            $game_list_id = $this->input->post("game_list_id");
            $start_date = $this->input->post("start_date");
            $start_time = $this->input->post("start_time");
            $end_date = $this->input->post("end_date");
            $end_time = $this->input->post("end_time");
            $result_date = $this->input->post("result_date");
            $result_time = $this->input->post("result_time");

            $this->Crud_model->insert('create_game', array(
                'game_id' => $game_list_id, 
                'start_date' => $start_date, 
                'start_time' => date("H:i:s", strtotime($start_time)), 
                'end_date' => $end_date, 
                'end_time' => date("H:i:s", strtotime($end_time)), 
                'result_date' => $result_date, 
                'result_time' => date("H:i:s", strtotime($result_time)), 
                'date' => date("Y-m-d"), 
                'time' => date("H:i:s"),
                'status' => 0
            ));
            redirect(base_url('admin/today_game'), 'refresh');
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
            redirect(base_url('admin/today_game'), 'refresh');
        }

        $this->db->where('status', 0); // Active games only
        $this->db->where('start_date <=', date('Y-m-d')); // Start date is today or earlier
        $this->db->where('end_date >=', date('Y-m-d')); // End date is today or later
        $this->db->order_by('id', 'DESC'); // Show newly created games at the top

        $page_data['data'] = $this->db->get('create_game');
        $page_data['page_title'] = "Today Game";
        $page_data['page_name'] = "create_game";
        $this->load->view('templete', $page_data);
    }
    public function pending_game()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post("submit");
        $submit_update = $this->input->post("submit_update");

        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('create_game', 'id', 'status', $sid);
            redirect(base_url('admin/today_game'), 'refresh');
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
            redirect(base_url('admin/today_game'), 'refresh');
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
            redirect(base_url('admin/today_game'), 'refresh');
        }

        // $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => 0, 'end_date <=' => date('Y-m-d')), 'DESC');
                $this->db->where('status', 0); // Running games only
$this->db->where('start_date <=', date('Y-m-d')); // Start date is today or earlier
$this->db->where('end_date >=', date('Y-m-d'));

$page_data['data'] = $this->db->get('create_game');
        $page_data['page_title'] = "Pending Game";
        $page_data['page_name'] = "pending_game";
        $this->load->view('templete', $page_data);
    }

    public function upcomming_game()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $sid = $this->input->get('sid');
        if (isset($sid)) {
            $this->Crud_model->update_custom('create_game', 'id', 'status', $sid);
            redirect(base_url('admin/upcomming_game'), 'refresh');
        }

        $submit_update = $this->input->post("submit_update");
        if (isset($submit_update)) {
            $start_date = $this->input->post("start_date_update");
            $start_time = $this->input->post("start_time_update");
            $end_date = $this->input->post("end_date_update");
            $update_id = $this->input->post("id_hidden");
            $result_date = $this->input->post("result_date_update");
            $end_time = $this->input->post("end_time_update");
            $result_time = $this->input->post("result_time_update");

            $update_data = array(
                'start_date' => $start_date,
                'start_time' => date("H:i:s", strtotime($start_time)),
                'end_date' => $end_date,
                'end_time' => date("H:i:s", strtotime($end_time)),
                'result_date' => $result_date,
                'result_time' => date("H:i:s", strtotime($result_time)),
                'date' => date("Y-m-d"),
                'time' => date("H:i:s")
            );

            $this->Crud_model->update('create_game', 'id', $update_id, $update_data);
            redirect(base_url('admin/upcomming_game'), 'refresh');
        }


        // $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => 0, 'start_date >' => date('Y-m-d')), 'DESC');
        $page_data['data'] = $this->db->get_where('create_game', array('status' => 0, 'start_date >=' => date('Y-m-d')));
        $page_data['page_title'] = "Upcomming Game";
        $page_data['page_name'] = "upcomming_game";
        $this->load->view('templete', $page_data);
    }
    public function bet_history()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $sid = $this->input->get('id');
        if (!isset($sid)) {
            redirect($this->agent->referrer());
        }
        
        $page_data['data'] = $this->db
    ->select('game_bet.*, users.mobile')
    ->from('game_bet')
    ->join('users', 'game_bet.user_id = users.id', 'left') // Add left join
    ->where('game_bet.game_id', $sid) // Add where condition
    ->get();

        $page_data['page_title'] = "Bet History";
        $page_data['page_name'] = "betHistorygame";
        $this->load->view('templete', $page_data);
    }

    // public function result_game()
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }
    //     $limit = 10;
    //     $offset = 0;
    //     $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => '1'), 'DESC', $limit, $offset);

    //     $page_data['page_title'] = "Result Game";
    //     $page_data['page_name'] = "result_game";
    //     $this->load->view('templete', $page_data);
    // }
    public function result_game()
{
    if (!$this->session->userdata('login') == 1) {
        redirect(base_url() . 'login/admin', 'refresh');
    }

    $limit = 20;
    $offset =0;

    // Optimized query with joins and aggregation
    // $this->db->select('cg.id as game_id, 
    //                   cg.status, 
    //                   cg.start_date, 
    //                   cg.start_time, 
    //                   g.game_name as game_name, 
    //                   cg.end_date, 
    //                   cg.end_time, 
    //                   cg.result_date, 
    //                   cg.result_time, 
    //                   COALESCE(SUM(gb.money), 0) as total_bid_amount,
    //                   COALESCE(SUM(gb.money * gs.winning_amount), 0) as total_distribute_amount');
    // $this->db->from('create_game cg');
    // $this->db->join('game_bet gb', 'gb.game_id = cg.id', 'left');
    // $this->db->join('game g', 'g.id = cg.game_id', 'left');
    // $this->db->join('game_setting gs', '1=1', 'left'); // Assuming `winning_amount` is constant for all games
    // $this->db->where('cg.status', '1');
    // $this->db->group_by('cg.id');
    // $this->db->order_by('cg.id', 'DESC');
    // $this->db->limit($limit, $offset);

    // $page_data['data'] = $this->db->get();
    $this->db->select('
    cg.id as game_id, 
    cg.status, 
    cg.start_date, 
    cg.start_time, 
    g.game_name as game_name, 
    cg.end_date, 
    cg.end_time, 
    gr.number, 
    cg.result_date, 
    cg.result_time, 
    COALESCE(SUM(gb.money), 0) as total_bid_amount,
    COALESCE(SUM(CASE WHEN gb.cancel_at IS NOT NULL THEN gb.money ELSE 0 END), 0) as cancel_bet,
    (SELECT COALESCE(SUM(th.add_amount), 0) FROM transaction_history th WHERE th.create_game_id = cg.id) as total_distribute_amount
');
$this->db->from('create_game cg');
$this->db->join('game_bet gb', 'gb.game_id = cg.id', 'left');
$this->db->join('game g', 'g.id = cg.game_id', 'left');
$this->db->join('game_setting gs', '1=1', 'left'); // Assuming `winning_amount` is constant for all games
$this->db->join('game_result gr', 'gr.create_game_id = cg.id', 'left'); // Added game_result join for condition
$this->db->where('cg.status', '1');
$this->db->group_by('cg.id');
$this->db->order_by('cg.id', 'DESC');


$this->db->limit($limit, $offset);

$page_data['data'] = $this->db->get();

    // $page_data['data'] = $this->Crud_model->fetch('create_game', array('status' => '1'), 'DESC', $limit, $offset);

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
        $type = $this->input->post('game_type');
        if($game_number == 0){
            $game_number = 100;
        }
        $agentIdArray = array('game_id' => $game_id, 'type' =>$type, 'game_number' => $game_number, 'status' => 0);
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        if($admin_data->level == 1){
            $agentIds = $admin_data->id;
            $agentIdArray['aid'] = $agentIds;
        }
        $game_bet = $this->Crud_model->fetch('game_bet', $agentIdArray);
        $i = 1;
        foreach ($game_bet->result() as $row) {
            $get_users = $this->Crud_model->fetch('users', array('id' => $row->user_id));
            $MobileNumber = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 0 ? $get_users->row('mobile') : "XXXX";
            $UserNames = $this->session->userdata('level') == 1 ? $get_users->row('name') : "XXXX";
            echo "
                <tr>
                <td><b>" . $i++ . "</b></td>
                <td>" . $get_users->row('name') . "</td>
                <td>" . $MobileNumber . "</td>
                <td>" . $row->money . "</td>
                <td> <a href='".base_url('admin/betdelete?id='.$row->id)."'>Delete</a></td></tr>";
        }
    }
    public function betdelete()
    {
        $betid = $this->input->get('id');
        $game_bet = $this->Crud_model->fetch('game_bet', array('id' => $betid, 'status' => 0));
        if($game_bet->num_rows() > 0){
            
            $data = $game_bet->result()[0];
            $type = $data->type;
            $amount = $data->money;
            $user_id = $data->user_id;
            $gameid = $data->game_id;
            $id_verify = $this->db->get_where('users', array('id' => $user_id, 'status' => 0));
            $this->db->where('id', $betid)->update('game_bet', array('status' => 2,'cancel_at' => date('Y-m-d H:i:s')));
            $msg = "Kabad Cancel ".($type == 1 ? "JODI" : ($type == 2 ? "ANDER H" : "BAHAR H"));
            $this->db->where('id', $user_id)->update('users', array('wallet' => $id_verify->row('wallet') + $amount));
            $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $amount, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'button_status' => 1, 'purpose' => 'credit'));
            redirect(base_url() . 'admin/game_details?game_id='.$gameid, 'refresh');
        }else{
            redirect(base_url() . 'admin/game_details?game_id='.$betid, 'refresh');
        }
        
        // $msg = "Kabad Cancel" . $grand_total . " on Game Play";
        // $this->Crud_model->insert('transaction_history', array('user_id' => $user_id, 'add_amount' => $grand_total, 'description' => $msg, 'status' => 0, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'game_details' => json_encode($data_insert), 'button_status' => 1));
    }

    // public function game_details()
    // {
    //     if (!$this->session->userdata('login') == 1) {
    //         redirect(base_url() . 'login/admin', 'refresh');
    //     }
    //     $TESTING = false;
        
    //     $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();

    //     $game_id = $this->input->get('game_id');
    //     $submit = $this->input->post("submit");
    //     $submitUPdate = $this->input->post("submitUPdate");
    //     $submitUPdateResultDate = $this->input->post("submitUPdateResultDate");
    //     if (isset($submitUPdateResultDate)) {
    //         if($admin_data->level == 0){
    //             $date = $this->input->post("dates");
    //             $this->db->set('date', "'".$date."'", FALSE)->where('create_game_id', $game_id)->update('game_result');
    //         }else{
    //             echo "<script>alert('You are not Authorised')</script>";
    //         }
    //         redirect(base_url('admin/today_game'), 'refresh');
    //     }
    //     if (isset($submit)) {
    //         if($admin_data->level == 0){
    //         $number = $this->input->post("number");
    //         $grand_total = $this->input->post("grand_total");
    //         $game_checkbox = $this->input->post("game_checkbox");
    //         if ($number <= 100) {
    //             $create_game_table = $this->Crud_model->fetch('create_game', array('id' => $game_id));
    //             $game_table = $create_game_table->row();
    //             if ($game_checkbox && !$TESTING) {
    //                 // Get the current date
    //                 $current_date = new DateTime();
    //                 $month_end_date = (clone $current_date)->modify('last day of this month');
                    
    //                 // Determine whether to add 2 days or 1 day
    //                 $days_to_add = ($current_date->diff($month_end_date)->days === 1) ? 2 : 1;

    //                 $insert_data = array(
    //                     'game_id' => $game_table->game_id,
    //                     'start_date' => date_add_days($game_table->start_date, $days_to_add),
    //                     'start_time' => $game_table->start_time,
    //                     'end_date' => date_add_days($game_table->end_date, $days_to_add),
    //                     'end_time' => $game_table->end_time,
    //                     'result_date' => date_add_days($game_table->result_date, $days_to_add),
    //                     'result_time' => $game_table->result_time,
    //                     'res_date' => date_add_days($game_table->res_date, $days_to_add),
    //                     'res_time' => $game_table->res_time,
    //                     'date' => date("Y-m-d"),
    //                     'time' => date("H:i:s")
    //                 );

    //                 $this->db->insert('create_game', $insert_data);
    //             }
    //             $game_idd = $game_table->game_id;
    //             $gameName_verify = $this->Crud_model->fetch('game', array('id' => $game_idd));
    //             $GameNameRes = $gameName_verify->row();
    //             $gameName = $GameNameRes->game_name;
    //             if(!$TESTING){
    //             $this->Crud_model->update("create_game", "id", $game_id, array("status" => 1));
    //             $this->Crud_model->insert("game_result", array("game_id" => $game_idd,"create_game_id" => $game_id, "number" => $number, "grand_total" => $grand_total, "date" => date("Y-m-d"), "time" => date("H:i:s")));
    //             }

    //             $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id,'cancel_at'=>null));

    //             if ($game_bet->num_rows() > 0) {

    //                 foreach ($game_bet->result() as $row) {
    //                     $number_arr[] = $row->game_number;
    //                 }
    //                 foreach (array_unique($number_arr) as $items) {
    //                     $get_money = $this->Crud_model->fetch('game_bet', array('game_number' => $items, 'game_id' => $game_id));
    //                     foreach ($get_money->result() as $arr) {
    //                         $user_count[] = $arr->user_id;
    //                     }
    //                 }

    //                 // Fetch the results from the database
    //                 $win_money_added = $this->Crud_model->fetch('game_setting', array('id' => 1));
    //                 foreach ($win_money_added->result() as $row) {
    //                     $winning_amount = $row->winning_amount;
    //                     $withdrawal_hold_amt = $row->withdrawal_hold_amt;
    //                 }

    //                 // $win_money_added = $win_money_added[0];
    //                 foreach (array_unique($user_count) as $users_arr) {
    //                     $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
    //                     $AGENTID = $get_user_wallet->row('aid');
    //                     $get_AgentData = $this->Crud_model->fetch('admin_login', array('id' => $AGENTID));
    //                     $JodiConst = $get_user_wallet->row('jodi_rate');
    //                     $HarufConst = $get_user_wallet->row('haruf_rate');
    //                     if($AGENTID && $get_AgentData){
    //                         $winning_amount = $get_AgentData->row('winning_amount');
    //                         $withdrawal_hold_amt = $get_AgentData->row('withdrawal_hold_amt');
    //                     }
    //                     for($i=1;$i<=3;$i++){
    //                 if($i == 1){
    //                     $gametype = "JODI";
    //                     if($JodiConst > 0){
    //                         $percentConst = $JodiConst;
    //                     }else{
    //                         $percentConst = $winning_amount;
    //                     }
    //                     $grand_total_money = $this->db->select_sum('money')->get_where('game_bet', ['game_number' => $number, 'user_id' => $users_arr,'game_id' => $game_id, 'type' => $i, 'cancel_at'=>null])->row('money');
    //                 }
    //                 $digits = str_split((string)$number);

    //                 // Assign the split digits to separate variables
    //                     if($number<10){
    //                         $digit1 = "0";
    //                         $digit2 = (string)$number;
    //                     }elseif($number == 100){
    //                         $digit1 = 0;
    //                         $digit2 = 0;
    //                     }else{
    //                         $digit1 = $digits[0];  // First digit
    //                         $digit2 = $digits[1];
    //                     }
    //                 if($i == 2){
    //                     $gametype = "Ander Haruf";
    //                     if($HarufConst >0){
    //                         $percentConst = $HarufConst;
    //                     }else{
    //                         $percentConst = $withdrawal_hold_amt;
    //                     }
    //                     // echo $digit1."|".$digit2;
    //                     // die();
    //                     $this->db->select_sum('money');
    //                     $this->db->like('game_number', $digit1);
    //                     $this->db->where('game_id', $game_id);
    //                     $this->db->where('user_id', $users_arr);
    //                     $this->db->where('type', $i);
    //                     $this->db->where('cancel_at', null);
    //                     if ($digit1 == 1) {
    //                         $this->db->where('game_number !=', '100');
    //                     }
    //                     $grand_total_moneys = $this->db->get('game_bet');
                        
    //                     $grand_total_money = $grand_total_moneys->row('money');
    //                 }
    //                 if($i == 3){
    //                     $gametype = "Bahar Haruf";
    //                     if($HarufConst >0){
    //                         $percentConst = $HarufConst;
    //                     }else{
    //                         $percentConst = $withdrawal_hold_amt;
    //                     }
    //                     $this->db->select_sum('money');
    //                     $this->db->like('game_number', $digit2);
    //                     $this->db->where('game_id', $game_id);
    //                     $this->db->where('user_id', $users_arr);
    //                     $this->db->where('type', $i);
    //                     $this->db->where('cancel_at', null);
    //                     if ($digit2 == 1) {
    //                         $this->db->where('game_number !=', '100');
    //                     }
    //                     $grand_total_moneys = $this->db->get('game_bet');
                        
    //                     $grand_total_money = $grand_total_moneys->row('money');
    //                     // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit2)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at', null)->get('game_bet')->row('money');
    //                 }
    //                     $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        
    //                     $add_wallet_money = $grand_total_money * $percentConst;
    //                     if($add_wallet_money > 0){
    //                         $finalamounthdjdj = $get_user_wallet->row('wallet') + $add_wallet_money;
    //                         // echo "UserId:$users_arr, Amount:$add_wallet_money | Type:$gametype | grandtotal:$grand_total_money | percent:$percentConst | Final amount: $finalamounthdjdj | Digits of hruf: $digit1 | $digit2 <br/>";
    //                         if(!$TESTING){
    //                         $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
    //                         $msg = "Game " . $gameName ." Winning Amount";
    //                         $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'), 'create_game_id'=>$game_id,'remaining_amount' => $get_user_wallet->row('wallet'),'purpose'=>'credit')); // reffer sender user
    //                         }
    //                     }
    //                     }
    //                 }
    //             }
    //             if($TESTING)
    //             return;

    //             redirect(base_url('admin/today_game'), 'refresh');
    //         } else {
    //             echo "<script>alert('Please enter valid game number')</script>";
    //         }
    //         }else{
    //             echo "<script>alert('You are not Authorised')</script>";
    //         }
    //     }
        
    //     if (isset($submitUPdate)) {
    //         if($admin_data->level == 0){
    //         $number = $this->input->post("number");
    //         if ($number <= 100) {
    //             $create_game_table = $this->Crud_model->fetch('create_game', array('id' => $game_id));
    //             $game_table = $create_game_table->row();
                
    //             $game_idd = $game_table->game_id;
    //             $gameName_verify = $this->Crud_model->fetch('game', array('id' => $game_idd));
    //             $GameNameRes = $gameName_verify->row();
    //             $gameName = $GameNameRes->game_name;
                
    //             $existing_record = $this->Crud_model->fetch("game_result", array("create_game_id" => $game_id));

    //             if ($existing_record->num_rows()>0) {
    //                 // Update the existing record
    //                 $this->db->set('number', $number, FALSE)
    //                              ->where('create_game_id', $game_id)
    //                              ->update('game_result');
    //                 // $this->Crud_model->update("game_result", array("create_game_id" => $game_id), array("number" => $number));
    //                 // Fetch all transactions related to the game_id
    //                 $all_transactions = $this->Crud_model->fetch("transaction_history", array("create_game_id" => $game_id));
                    
    //                 foreach ($all_transactions->result() as $alltrn) {
    //                     // Decrease wallet balance for each user
    //                     $this->db->set('wallet', 'wallet - ' . $alltrn->add_amount, FALSE)
    //                              ->where('id', $alltrn->user_id)
    //                              ->update('users');
    //                 }
    //                 // Delete all transaction history entries related to the game_id
    //                 $this->Crud_model->deleteCustom("transaction_history","create_game_id", $game_id);
    //             } else {
    //                 // Insert a new record
    //                 $this->Crud_model->insert("game_result", array(
    //                     "game_id" => $game_idd,
    //                     "create_game_id" => $game_id,
    //                     "number" => $number,
    //                     "grand_total" => $grand_total,
    //                     "date" => date("Y-m-d"),
    //                     "time" => date("H:i:s")
    //                 ));
    //             }

    //             $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id,'cancel_at'=>null));

    //             if ($game_bet->num_rows() > 0) {

    //                 foreach ($game_bet->result() as $row) {
    //                     $number_arr[] = $row->game_number;
    //                 }
    //                 foreach (array_unique($number_arr) as $items) {
    //                     $get_money = $this->Crud_model->fetch('game_bet', array('game_number' => $items, 'game_id' => $game_id, 'cancel_at'=>null));
    //                     foreach ($get_money->result() as $arr) {
    //                         $user_count[] = $arr->user_id;
    //                     }
    //                 }

    //                 // Fetch the results from the database
    //                 $win_money_added = $this->Crud_model->fetch('game_setting', array('id' => 1));
    //                 foreach ($win_money_added->result() as $row) {
    //                     $winning_amount = $row->winning_amount;
    //                     $withdrawal_hold_amt = $row->withdrawal_hold_amt;
    //                 }

    //                 // $win_money_added = $win_money_added[0];
    //                 foreach (array_unique($user_count) as $users_arr) {
    //                     $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
    //                     $JodiConst = $get_user_wallet->row('jodi_rate');
    //                     $HarufConst = $get_user_wallet->row('haruf_rate');
    //                     for($i=1;$i<=3;$i++){
    //                 if($i == 1){
    //                     $gametype = "JODI";
    //                     if($JodiConst > 0){
    //                         $percentConst = $JodiConst;
    //                     }else{
    //                         $percentConst = $winning_amount;
    //                     }
    //                     $grand_total_money = $this->db->select_sum('money')->get_where('game_bet', ['game_number' => $number, 'user_id' => $users_arr,'game_id' => $game_id, 'type' => $i, 'cancel_at'=>null])->row('money');
    //                 }
    //                 $digits = str_split((string)$number);

    //                 // Assign the split digits to separate variables
    //                     if($number<10){
    //                         $digit1 = "0";
    //                         $digit2 = (string)$number;
    //                     }elseif($number == 100){
    //                         $digit1 = 0;
    //                         $digit2 = 0;
    //                     }else{
    //                         $digit1 = $digits[0];  // First digit
    //                         $digit2 = $digits[1];
    //                     }
    //                 if($i == 2){
    //                     $gametype = "Ander Haruf";
    //                     if($HarufConst >0){
    //                         $percentConst = $HarufConst;
    //                     }else{
    //                         $percentConst = $withdrawal_hold_amt;
    //                     }
    //                     $this->db->select_sum('money');
    //                     $this->db->like('game_number', $digit1);
    //                     $this->db->where('game_id', $game_id);
    //                     $this->db->where('user_id', $users_arr);
    //                     $this->db->where('type', $i);
    //                     $this->db->where('cancel_at', null);
    //                     if ($digit1 == 1) {
    //                         $this->db->where('game_number !=', '100');
    //                     }
    //                     $grand_total_moneys = $this->db->get('game_bet');
                        
    //                     $grand_total_money = $grand_total_moneys->row('money');
    //                     // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit1)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at',null)->get('game_bet')->row('money');
    //                 }
    //                 if($i == 3){
    //                     $gametype = "Bahar Haruf";
    //                     if($HarufConst >0){
    //                         $percentConst = $HarufConst;
    //                     }else{
    //                         $percentConst = $withdrawal_hold_amt;
    //                     }
    //                     $this->db->select_sum('money');
    //                     $this->db->like('game_number', $digit2);
    //                     $this->db->where('game_id', $game_id);
    //                     $this->db->where('user_id', $users_arr);
    //                     $this->db->where('type', $i);
    //                     $this->db->where('cancel_at', null);
    //                     if ($digit2 == 1) {
    //                         $this->db->where('game_number !=', '100');
    //                     }
    //                     $grand_total_moneys = $this->db->get('game_bet');
                        
    //                     $grand_total_money = $grand_total_moneys->row('money');
    //                     // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit2)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at',null)->get('game_bet')->row('money');
    //                 }
    //                     $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
    //                     $add_wallet_money = $grand_total_money * $percentConst;
    //                     if($add_wallet_money > 0){
    //                         // $finalamounthdjdj = $get_user_wallet->row('wallet') + $add_wallet_money;
    //                         // echo "Uswer:$users_arr,Amount:$add_wallet_money|Type:$gametype|grandtotal:$grand_total_money|per%%:$percentConst||||$finalamounthdjdj <br/>";
                            
    //                         // $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
    //                         $this->db->set('wallet', 'wallet + ' . $add_wallet_money, FALSE)
    //                              ->where('id', $users_arr)
    //                              ->update('users');
    //                         $msg = "Game " . $gameName ." Winning Amount";
    //                         $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'), 'create_game_id'=>$game_id,'remaining_amount' => $get_user_wallet->row('wallet'),'purpose'=>'credit')); // reffer sender user
    //                     }
    //                     }
    //                 }
    //             }
    //             // return;

    //             redirect(base_url('admin/today_game'), 'refresh');
    //         } else {
    //             echo "<script>alert('Please enter valid game number')</script>";
    //         }
            
    //         }else{
    //             echo "<script>alert('You are not Authorised')</script>";
    //         }
    //     }
        
    //     $page_data['data'] = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id), 'DESC');
    //     $page_data['Agnet'] = $admin_data->level == 0 ? false : $admin_data->id;
    //     $page_data['page_title'] = "Game Details";
    //     $page_data['page_name'] = "game_details";
    //     $this->load->view('templete', $page_data);
    // }
    
    public function game_details()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        $TESTING = false;
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();

        $game_id = $this->input->get('game_id');
        $submit = $this->input->post("submit");
        $submitUPdate = $this->input->post("submitUPdate");
        $submitUPdateResultDate = $this->input->post("submitUPdateResultDate");
        if (isset($submitUPdateResultDate)) {
            if($admin_data->level == 0){
                $date = $this->input->post("dates");
                $this->db->set('date', "'".$date."'", FALSE)->where('create_game_id', $game_id)->update('game_result');
            }else{
                echo "<script>alert('You are not Authorised')</script>";
            }
            redirect(base_url('admin/today_game'), 'refresh');
        }
        if (isset($submit)) {
            if($admin_data->level == 0){
            $number = $this->input->post("number");
            $grand_total = $this->input->post("grand_total");
            $game_checkbox = $this->input->post("game_checkbox");
            if ($number <= 100) {
                $create_game_table = $this->Crud_model->fetch('create_game', array('id' => $game_id));
                $game_table = $create_game_table->row();
                if ($game_checkbox && !$TESTING) {
                    // Get the current date
                    $current_date = new DateTime();
                    $month_end_date = (clone $current_date)->modify('last day of this month');
                    
                    // Determine whether to add 2 days or 1 day
                    $days_to_add = ($current_date->diff($month_end_date)->days === 1) ? 2 : 1;

                    $insert_data = array(
                        'game_id' => $game_table->game_id,
                        'start_date' => date_add_days($game_table->start_date, $days_to_add),
                        'start_time' => $game_table->start_time,
                        'end_date' => date_add_days($game_table->end_date, $days_to_add),
                        'end_time' => $game_table->end_time,
                        'result_date' => date_add_days($game_table->result_date, $days_to_add),
                        'result_time' => $game_table->result_time,
                        'res_date' => date_add_days($game_table->res_date, $days_to_add),
                        'res_time' => $game_table->res_time,
                        'date' => date("Y-m-d"),
                        'time' => date("H:i:s")
                    );

                    $this->db->insert('create_game', $insert_data);
                }
                $game_idd = $game_table->game_id;
                $gameName_verify = $this->Crud_model->fetch('game', array('id' => $game_idd));
                $GameNameRes = $gameName_verify->row();
                $gameName = $GameNameRes->game_name;
                if(!$TESTING){
                $this->Crud_model->update("create_game", "id", $game_id, array("status" => 1));
                $this->Crud_model->insert("game_result", array("game_id" => $game_idd,"create_game_id" => $game_id, "number" => $number, "grand_total" => $grand_total, "date" => date("Y-m-d"), "time" => date("H:i:s")));
                }

                $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id,'cancel_at'=>null));

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

                    // Fetch the results from the database
                    $win_money_added = $this->Crud_model->fetch('game_setting', array('id' => 1));
                    foreach ($win_money_added->result() as $row) {
                        $winning_amount = $row->winning_amount;
                        $withdrawal_hold_amt = $row->withdrawal_hold_amt;
                    }

                    // $win_money_added = $win_money_added[0];
                    foreach (array_unique($user_count) as $users_arr) {
                        $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        $AGENTID = $get_user_wallet->row('aid');
                        $get_AgentData = $this->Crud_model->fetch('admin_login', array('id' => $AGENTID));
                        $JodiConst = $get_user_wallet->row('jodi_rate');
                        $HarufConst = $get_user_wallet->row('haruf_rate');
                        if($AGENTID && $get_AgentData){
                            $winning_amount = $get_AgentData->row('winning_amount');
                            $withdrawal_hold_amt = $get_AgentData->row('withdrawal_hold_amt');
                        }
                        for($i=1;$i<=3;$i++){
                    if($i == 1){
                        $gametype = "JODI";
                        if($JodiConst > 0){
                            $percentConst = $JodiConst;
                        }else{
                            $percentConst = $winning_amount;
                        }
                        $grand_total_money = $this->db->select_sum('money')->get_where('game_bet', ['game_number' => $number, 'user_id' => $users_arr,'game_id' => $game_id, 'type' => $i, 'cancel_at'=>null])->row('money');
                    }
                    $digits = str_split((string)$number);

                    // Assign the split digits to separate variables
                        if($number<10){
                            $digit1 = "0";
                            $digit2 = (string)$number;
                        }elseif($number == 100){
                            $digit1 = 0;
                            $digit2 = 0;
                        }else{
                            $digit1 = $digits[0];  // First digit
                            $digit2 = $digits[1];
                        }
                    if($i == 2){
                        $gametype = "Ander Haruf";
                        if($HarufConst >0){
                            $percentConst = $HarufConst;
                        }else{
                            $percentConst = $withdrawal_hold_amt;
                        }
                        // echo $digit1."|".$digit2;
                        // die();
                        $this->db->select_sum('money');
                        $this->db->like('game_number', $digit1);
                        $this->db->where('game_id', $game_id);
                        $this->db->where('user_id', $users_arr);
                        $this->db->where('type', $i);
                        $this->db->where('cancel_at', null);
                        if ($digit1 == 1) {
                            $this->db->where('game_number !=', '100');
                        }
                        $grand_total_moneys = $this->db->get('game_bet');
                        
                        $grand_total_money = $grand_total_moneys->row('money');
                    }
                    if($i == 3){
                        $gametype = "Bahar Haruf";
                        if($HarufConst >0){
                            $percentConst = $HarufConst;
                        }else{
                            $percentConst = $withdrawal_hold_amt;
                        }
                        $this->db->select_sum('money');
                        $this->db->like('game_number', $digit2);
                        $this->db->where('game_id', $game_id);
                        $this->db->where('user_id', $users_arr);
                        $this->db->where('type', $i);
                        $this->db->where('cancel_at', null);
                        if ($digit2 == 1) {
                            $this->db->where('game_number !=', '100');
                        }
                        $grand_total_moneys = $this->db->get('game_bet');
                        
                        $grand_total_money = $grand_total_moneys->row('money');
                        // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit2)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at', null)->get('game_bet')->row('money');
                    }
                        $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        
                        $add_wallet_money = $grand_total_money * $percentConst;
                        if($add_wallet_money > 0){
                            $finalamounthdjdj = $get_user_wallet->row('wallet') + $add_wallet_money;
                            // echo "UserId:$users_arr, Amount:$add_wallet_money | Type:$gametype | grandtotal:$grand_total_money | percent:$percentConst | Final amount: $finalamounthdjdj | Digits of hruf: $digit1 | $digit2 <br/>";
                            if(!$TESTING){
                            $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
                            $msg = "Game " . $gameName ." Winning Amount";
                            $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'), 'create_game_id'=>$game_id,'remaining_amount' => $get_user_wallet->row('wallet'),'purpose'=>'credit')); // reffer sender user
                            }
                        }
                        }
                    }
                }
                if($TESTING)
                return;

                redirect(base_url('admin/today_game'), 'refresh');
            } else {
                echo "<script>alert('Please enter valid game number')</script>";
            }
            }else{
                echo "<script>alert('You are not Authorised')</script>";
            }
        }
        
        if (isset($submitUPdate)) {
            if($admin_data->level == 0){
            $number = $this->input->post("number");
            if ($number <= 100) {
                $create_game_table = $this->Crud_model->fetch('create_game', array('id' => $game_id));
                $game_table = $create_game_table->row();
                
                $game_idd = $game_table->game_id;
                $gameName_verify = $this->Crud_model->fetch('game', array('id' => $game_idd));
                $GameNameRes = $gameName_verify->row();
                $gameName = $GameNameRes->game_name;
                
                $existing_record = $this->Crud_model->fetch("game_result", array("create_game_id" => $game_id));

                if ($existing_record->num_rows()>0) {
                    // Update the existing record
                    $this->db->set('number', $number, FALSE)
                                 ->where('create_game_id', $game_id)
                                 ->update('game_result');
                    // $this->Crud_model->update("game_result", array("create_game_id" => $game_id), array("number" => $number));
                    // Fetch all transactions related to the game_id
                    $all_transactions = $this->Crud_model->fetch("transaction_history", array("create_game_id" => $game_id));
                    
                    foreach ($all_transactions->result() as $alltrn) {
                        // Decrease wallet balance for each user
                        $this->db->set('wallet', 'wallet - ' . $alltrn->add_amount, FALSE)
                                 ->where('id', $alltrn->user_id)
                                 ->update('users');
                    }
                    // Delete all transaction history entries related to the game_id
                    $this->Crud_model->deleteCustom("transaction_history","create_game_id", $game_id);
                } else {
                    // Insert a new record
                    $this->Crud_model->insert("game_result", array(
                        "game_id" => $game_idd,
                        "create_game_id" => $game_id,
                        "number" => $number,
                        "grand_total" => $grand_total,
                        "date" => date("Y-m-d"),
                        "time" => date("H:i:s")
                    ));
                }

                $game_bet = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id,'cancel_at'=>null));

                if ($game_bet->num_rows() > 0) {

                    foreach ($game_bet->result() as $row) {
                        $number_arr[] = $row->game_number;
                    }
                    foreach (array_unique($number_arr) as $items) {
                        $get_money = $this->Crud_model->fetch('game_bet', array('game_number' => $items, 'game_id' => $game_id, 'cancel_at'=>null));
                        foreach ($get_money->result() as $arr) {
                            $user_count[] = $arr->user_id;
                        }
                    }

                    // Fetch the results from the database
                    $win_money_added = $this->Crud_model->fetch('game_setting', array('id' => 1));
                    foreach ($win_money_added->result() as $row) {
                        $winning_amount = $row->winning_amount;
                        $withdrawal_hold_amt = $row->withdrawal_hold_amt;
                    }

                    // $win_money_added = $win_money_added[0];
                    foreach (array_unique($user_count) as $users_arr) {
                        $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        $JodiConst = $get_user_wallet->row('jodi_rate');
                        $HarufConst = $get_user_wallet->row('haruf_rate');
                        for($i=1;$i<=3;$i++){
                    if($i == 1){
                        $gametype = "JODI";
                        if($JodiConst > 0){
                            $percentConst = $JodiConst;
                        }else{
                            $percentConst = $winning_amount;
                        }
                        $grand_total_money = $this->db->select_sum('money')->get_where('game_bet', ['game_number' => $number, 'user_id' => $users_arr,'game_id' => $game_id, 'type' => $i, 'cancel_at'=>null])->row('money');
                    }
                    $digits = str_split((string)$number);

                    // Assign the split digits to separate variables
                        if($number<10){
                            $digit1 = "0";
                            $digit2 = (string)$number;
                        }elseif($number == 100){
                            $digit1 = 0;
                            $digit2 = 0;
                        }else{
                            $digit1 = $digits[0];  // First digit
                            $digit2 = $digits[1];
                        }
                    if($i == 2){
                        $gametype = "Ander Haruf";
                        if($HarufConst >0){
                            $percentConst = $HarufConst;
                        }else{
                            $percentConst = $withdrawal_hold_amt;
                        }
                        $this->db->select_sum('money');
                        $this->db->like('game_number', $digit1);
                        $this->db->where('game_id', $game_id);
                        $this->db->where('user_id', $users_arr);
                        $this->db->where('type', $i);
                        $this->db->where('cancel_at', null);
                        if ($digit1 == 1) {
                            $this->db->where('game_number !=', '100');
                        }
                        $grand_total_moneys = $this->db->get('game_bet');
                        
                        $grand_total_money = $grand_total_moneys->row('money');
                        // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit1)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at',null)->get('game_bet')->row('money');
                    }
                    if($i == 3){
                        $gametype = "Bahar Haruf";
                        if($HarufConst >0){
                            $percentConst = $HarufConst;
                        }else{
                            $percentConst = $withdrawal_hold_amt;
                        }
                        $this->db->select_sum('money');
                        $this->db->like('game_number', $digit2);
                        $this->db->where('game_id', $game_id);
                        $this->db->where('user_id', $users_arr);
                        $this->db->where('type', $i);
                        $this->db->where('cancel_at', null);
                        if ($digit2 == 1) {
                            $this->db->where('game_number !=', '100');
                        }
                        $grand_total_moneys = $this->db->get('game_bet');
                        
                        $grand_total_money = $grand_total_moneys->row('money');
                        // $grand_total_money = $this->db->select_sum('money')->like('game_number', $digit2)->where('game_id', $game_id)->where('user_id', $users_arr)->where('type', $i)->where('cancel_at',null)->get('game_bet')->row('money');
                    }
                        $get_user_wallet = $this->Crud_model->fetch('users', array('id' => $users_arr));
                        $add_wallet_money = $grand_total_money * $percentConst;
                        if($add_wallet_money > 0){
                            // $finalamounthdjdj = $get_user_wallet->row('wallet') + $add_wallet_money;
                            // echo "Uswer:$users_arr,Amount:$add_wallet_money|Type:$gametype|grandtotal:$grand_total_money|per%%:$percentConst||||$finalamounthdjdj <br/>";
                            
                            // $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
                            $this->db->set('wallet', 'wallet + ' . $add_wallet_money, FALSE)
                                 ->where('id', $users_arr)
                                 ->update('users');
                            $msg = "Game " . $gameName ." Winning Amount";
                            $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'), 'create_game_id'=>$game_id,'remaining_amount' => $get_user_wallet->row('wallet'),'purpose'=>'credit')); // reffer sender user
                        }
                        }
                    }
                }
                // return;

                redirect(base_url('admin/today_game'), 'refresh');
            } else {
                echo "<script>alert('Please enter valid game number')</script>";
            }
            
            }else{
                echo "<script>alert('You are not Authorised')</script>";
            }
        }
        
        $page_data['data'] = $this->Crud_model->fetch('game_bet', array('game_id' => $game_id), 'DESC');
        $page_data['Agnet'] = $admin_data->level == 0 ? false : $admin_data->id;
        $page_data['page_title'] = "Game Details";
        $page_data['page_name'] = "game_details";
        $this->load->view('templete', $page_data);
    }

    public function FunctionName()
    {
        $number = 1;
        $game_id = 9;

        $game_bet = $this->db->get_where('game_bet', array('game_id' => $game_id));

        if ($game_bet->num_rows() > 0) {

            foreach ($game_bet->result() as $row) {
                $number_arr[] = $row->game_number;
            }
            foreach (array_unique($number_arr) as $items) {
                $get_money = $this->db->get_where('game_bet', array('game_number' => $items, 'game_id' => $game_id));
                foreach ($get_money->result() as $arr) {
                    $user_count[] = $arr->user_id;
                }
            }

            $win_money_added = $this->db->get_where('game_setting', array('winning_amount'));
            foreach (array_unique($user_count) as $users_arr) {
                $grand_total = $this->db->select_sum('money')->get_where('game_bet', array('user_id' => $users_arr, 'game_id' => $game_id, 'game_number' => $number));
                $add_wallet_money =  $grand_total->row('money') * $win_money_added->row('winning_amount');
                $get_user_wallet = $this->db->get_where('users', array('id' => $users_arr));
                // $this->Crud_model->update('users', 'id', $users_arr, array('wallet' => $get_user_wallet->row('wallet') + $add_wallet_money));
                $msg = "You have earned Rs." . $add_wallet_money . " on game play";
                // echo $get_user_wallet . "<br>";
                echo $msg;
                // $this->db->insert('transaction_history', array('user_id' => $users_arr, 'add_amount' => $add_wallet_money, 'description' => $msg, 'status' => 1, 'date' => date("Y-m-d"), 'time' => date('H:i:s'))); // reffer sender user
            }
        }
    }

    public function wallet_details()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit_add = $this->input->post('submit_add');
        $submit_update = $this->input->post('submit_update');
        $sid = $this->input->get("sid");
        $did = $this->input->get("did");

        if (isset($sid)) {
            // Deactivate all QR codes first so only one is active at a time
            $this->db->update('payments', array('status' => 1));
            // Activate the selected QR code
            $this->Crud_model->update('payments', 'id', $sid, array('status' => 0));
            redirect(base_url() . 'admin/wallet_details', 'refresh');
        }

        if (isset($did)) {
            $this->Crud_model->delete('payments', $did);
            redirect(base_url() . 'admin/wallet_details', 'refresh');
        }

        if (isset($submit_add)) {
            $name = $this->input->post('name');
            $upi_num = $this->input->post('upi_num');
            $account_number = $this->input->post('account_number');
            $ifsc_code = $this->input->post('ifsc_code');
            $bank_name = $this->input->post('bank_name');
            $account_holder_name = $this->input->post('account_holder_name');

            if (empty($name)) {
                if (!empty($bank_name)) {
                    $name = $bank_name;
                } elseif (!empty($upi_num)) {
                    $name = $upi_num;
                } else {
                    $name = 'Direct Payment';
                }
            }

            $data = array(
                'name' => $name,
                'upi_num' => !empty($upi_num) ? $upi_num : '',
                'account_number' => !empty($account_number) ? $account_number : '',
                'ifsc_code' => !empty($ifsc_code) ? $ifsc_code : '',
                'bank_name' => !empty($bank_name) ? $bank_name : '',
                'account_holder_name' => !empty($account_holder_name) ? $account_holder_name : '',
                'qr_img' => '',
                'upi_company_img' => '',
                'status' => 1
            );

            if (!empty($_FILES['qr_img']['name'])) {
                $qr = do_upload('assets/images/', 'qr_img', 'jpeg|png|jpg|webp');
                if (is_string($qr) && strpos($qr, 'assets/') === 0) {
                    $data['qr_img'] = $qr;
                }
            }
            if (!empty($_FILES['company_img']['name'])) {
                $comp = do_upload('assets/images/', 'company_img', 'jpeg|png|jpg|webp');
                if (is_string($comp) && strpos($comp, 'assets/') === 0) {
                    $data['upi_company_img'] = $comp;
                }
            }

            $this->Crud_model->insert('payments', $data);
            redirect(base_url() . 'admin/wallet_details', 'refresh');
        }

        if (isset($submit_update)) {
            $update_id = $this->input->post('id_hidden');
            $name_update = $this->input->post('name_update');
            $upi_update = $this->input->post('upi_update');
            $bank_name_update = $this->input->post('bank_name_update');

            $update_data = array();
            if (!empty($name_update)) {
                $update_data['name'] = $name_update;
            } elseif (!empty($bank_name_update)) {
                $update_data['name'] = $bank_name_update;
            } elseif (!empty($upi_update)) {
                $update_data['name'] = $upi_update;
            }

            $update_data['upi_num'] = !empty($upi_update) ? $upi_update : '';
            $update_data['account_number'] = $this->input->post('account_number_update') ?? '';
            $update_data['ifsc_code'] = $this->input->post('ifsc_code_update') ?? '';
            $update_data['bank_name'] = $this->input->post('bank_name_update') ?? '';
            $update_data['account_holder_name'] = $this->input->post('account_holder_name_update') ?? '';

            if (!empty($_FILES['qr_img']['name'])) {
                $qr = do_upload('assets/images/', 'qr_img', 'jpeg|png|jpg|webp');
                if (is_string($qr) && strpos($qr, 'assets/') === 0) {
                    $update_data['qr_img'] = $qr;
                }
            }
            if (!empty($_FILES['company_img']['name'])) {
                $comp = do_upload('assets/images/', 'company_img', 'jpeg|png|jpg|webp');
                if (is_string($comp) && strpos($comp, 'assets/') === 0) {
                    $update_data['upi_company_img'] = $comp;
                }
            }

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
        $submit_changemethod = $this->input->post('submit_changemethod');
        

        $numbers = [];
        if (isset($submit_changemethod)) {
            if (!empty($_FILES['csv_file']['tmp_name'])) {
                $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                    foreach ($data as $value) {
                        // Check if value is a phone number
                        if (strpos($value, '+91') !== false || preg_match('/^\d{10}$/', $value)) {
                            $value = preg_replace('/\s+/', '', $value); // Remove spaces
                            if (!strpos($value, '+91')) {
                                $value = '+91'.$value;
                            }
                            $value = str_replace(':::', ',', $value);
                            $numbers[] = $value;
                        }
                    }
                }
                fclose($file);
            }
        }
        
        if (isset($submit_reffer)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('reffer_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/reffer_amount', 'refresh');
        }

        if (isset($submit_commission)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('commission_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/reffer_amount', 'refresh');
        }
        
        $page_data['numbers'] = $numbers;
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
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();
        $agentId = false;
        if($admin_data->level == 1){
            $agentId = $admin_data->id;
        }
        $submit_bidding = $this->input->post('submit_bidding');
        $submit_deposit = $this->input->post('submit_deposit');
        $submit_gateway = $this->input->post('submit_gateway');
        $submit_withdrawal = $this->input->post('submit_withdrawal');
        $submit_motijodimax = $this->input->post('submit_motijodimax');
        $submit_upigateway_token = $this->input->post('submit_upigateway_token');
        if(!$agentId){
            if (isset($submit_withdrawal)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('withdrawal_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }
            if (isset($submit_deposit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('deposit_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }
            if (isset($submit_gateway)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('gateway' => $this->input->post('gateway')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }
            if (isset($submit_bidding)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('bidding_amount' => $this->input->post('amount')));
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }
        }
        
        if (isset($submit_motijodimax)) {
            if(!$agentId){
                $this->Crud_model->update('game_setting', 'id', 1, array('winning_amount' => $this->input->post('Jamount')));
                $this->Crud_model->update('game_setting', 'id', 1, array('withdrawal_hold_amt' => $this->input->post('Hamount')));
                $this->Crud_model->update('game_setting', 'id', 1, array('motijodimax' => $this->input->post('amount')));
            }else{
                $this->Crud_model->update('admin_login', 'id', $agentId, array('winning_amount' => $this->input->post('Jamount')));
                $this->Crud_model->update('admin_login', 'id', $agentId, array('withdrawal_hold_amt' => $this->input->post('Hamount')));
                $this->Crud_model->update('admin_login', 'id', $agentId, array('motijodimax' => $this->input->post('amount')));
            }
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }
        if (isset($submit_upigateway_token)) {
            if($agentId){
                $this->Crud_model->update('admin_login', 'id', $agentId, array('upigatewaytoken' => $this->input->post('token')));
            }
            redirect(base_url() . 'admin/payment_setting', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('game_setting');
        $page_data['page_title'] = "Payment Setting";
        $page_data['page_name'] = "payment_setting";
        $page_data['agentId'] = $agentId;
        $this->load->view('templete', $page_data);
    }

    public function notificationsend()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }
        
        $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row();

        $submit = $this->input->post('submit');
        $error = "";
        if (isset($submit)) {
            $userid = $this->input->post('user');
            $title = $this->input->post('title');
            $Message = $this->input->post('message');
            if($userid > 0){
                //Single User send
                $users = $this->Crud_model->fetch('users',array('id'=>$userid));
                if(($users->row('fcm_token')) && $users->row('fcm_token') != ""){
                    $ResponseSend = $this->sendNotificationByfUN(($users->row('fcm_token')),$title,$Message);
                    echo json_encode($ResponseSend);
                }else{
                    $error = "Firebase Token Not Exist!";
                }
            }else{
                // $users = $this->Crud_model->fetch('users', array('fcm_token !=' => ''));
                // foreach($users->result() as $row){
                //     if(($row->fcm_token) && $row->fcm_token != ""){
                //         $ResponseSend = $this->sendNotificationByfUN($row->fcm_token,$title,$Message);
                //     }else{
                //         $error = "Some user error!";
                //     }
                // }
                $this->db->select('fcm_token');
$this->db->from('users');
$this->db->where("fcm_token !=", ""); // Exclude empty tokens
if($admin_data->level==1){
    $this->db->where("aid", $admin_data->id);
}
$this->db->group_by('fcm_token'); // Group by fcm_token to remove duplicates
$users = $this->db->get();

// $users = $query->result_array();
                $deviceTokens = "";
    
                foreach ($users->result() as $row) {
                    if (!empty($row->fcm_token)) {
                        $deviceTokens .= $row->fcm_token;
                    }
                }

                if (!empty($deviceTokens)) {
                    $ResponseSend = $this->sendNotificationByfUN(($deviceTokens), $title,$Message);
                    // echo json_encode($ResponseSend);
                } else {
                    $error = "No valid users found!";
                }
            }
        }
        if($admin_data->level==1){
            $users = $this->Crud_model->fetch('users',array('aid'=>$admin_data->id));
        }else{
            $users = $this->Crud_model->fetch('users');
        }
        $page_data['page_title'] = "Notification Send";
        $page_data['error'] = $error;
        $page_data['users'] = $users;
        $page_data['page_name'] = "notificationsend";
        $this->load->view('templete', $page_data);
    }

    public function help()
    {
        if (!$this->session->userdata('login') == 1) {
            redirect(base_url() . 'login/admin', 'refresh');
        }

        $submit = $this->input->post('submit_info');

        if (isset($submit)) {
            $this->Crud_model->update('game_setting', 'id', 1, array('contact_info' => $this->input->post('amount'),'startwith' => $this->input->post('startwith'),'endwith' => $this->input->post('endwith')));
            redirect(base_url() . 'admin/help', 'refresh');
        }

        $page_data['data'] = $this->Crud_model->fetch('game_setting');
        $page_data['page_title'] = "Help";
        $page_data['page_name'] = "help";
        $this->load->view('templete', $page_data);
    }

    public function privacy_policy_page()
    {
        $privacy_policy = $this->Crud_model->fetch('privacy_policy');
        $terms_condition = $this->Crud_model->fetch('t&c');
        echo $terms_condition->row('description') . "<br>";
        echo $privacy_policy->row('description') . "<br>";
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

    public function delete_account()
    {
        $this->load->view('delete_account');
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
