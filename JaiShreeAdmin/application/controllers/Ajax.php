<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Crud_model");
    }

    
    public function state_load()
    {
        $id = $this->input->post('id');
        $get_data = $this->Crud_model->fetch('state', array('country_id' => $id))->result();?>
        <option value=''>--Select State name--</option><?php
        foreach ($get_data as $row) :?>
            <option value='<?= $row->id; ?>'><?= $row->state_name;?></option>
        <?php endforeach;
    }
    public function city_load()
    {
        $id = $this->input->post('id');
        $get_data = $this->Crud_model->fetch('city', array('state_id' => $id))->result();?>
        <option value=''>--Select State name--</option><?php
        foreach ($get_data as $row) :?>
            <option value='<?=$row->id; ?>'><?= $row->city_name; ?></option>
        <?php endforeach;
    }
    public function area_load()
    {
        // post(id) show --Select Area name--
        $id = $this->input->post('id');
        $area_id = $this->input->post('area_id');
        $area_id_all = $this->input->post('area_id_all');
        $area_id_dis = $this->input->post('area_id_dis');
        $area_id_ret = $this->input->post('area_id_ret');
        
        if (isset($id)) {
            $get_data = $this->Crud_model->fetch('aria', array('city_id' => $id))->result();?>
            <option value=''>--Select Area name--</option><?php
            foreach ($get_data as $row) :?>
                <option value='<?=$row->id; ?>'><?= $row->area_name; ?></option>
            <?php endforeach;
        }
        // post(ids) hide --Select Area name--
        if (isset($area_id)) 
        {
            $get_data = $this->Crud_model->fetch('aria', array('city_id' => $area_id, 'area_assigned' => 0))->result();
            foreach ($get_data as $row) :?>
                <option value='<?=$row->id; ?>'><?= $row->area_name; ?></option>
            <?php endforeach;
        }
        // post(area_id_all) hide --Select All Area name--
        if(isset($area_id_all)){
            $get_data = $this->Crud_model->fetch('aria', array('city_id' => $area_id_all, 'sales_person_assigned' => 0))->result();
            foreach ($get_data as $row) :?>
                <option value='<?=$row->id; ?>'><?= $row->area_name; ?></option>
            <?php endforeach;
        }

        if(isset($area_id_dis)){
            $get_data = $this->Crud_model->fetch('aria', array('city_id' => $area_id_dis, 'distributor_assigned' => 0))->result();
            foreach ($get_data as $row) :?>
                <option value='<?=$row->id; ?>'><?= $row->area_name; ?></option>
            <?php endforeach;
        }

        if(isset($area_id_ret)){
            $get_data = $this->Crud_model->fetch('aria', array('city_id' => $area_id_ret))->result(); ?>

            <option value=''>--Select Area name--</option><?php
            foreach ($get_data as $row) :?>
                <option value='<?=$row->id; ?>'><?= $row->area_name; ?></option>
            <?php endforeach;
        }
    }
    public function super_st_load()
    {
        $city_id = $this->input->post("st_details");
        $get_data = $this->Crud_model->fetch('super_stockist_details', array('city' => $city_id))->result();?>
        <option value=''>--Select Super Stockist--</option><?php
        foreach ($get_data as $row) :?>
            <option value='<?=$row->id; ?>'><?= $row->contact_person; ?></option>
        <?php endforeach;
    }

    public function distributor_load()
    {
        $city_id = $this->input->post("city_id");
        $get_data = $this->Crud_model->fetch('distributor_details', array('city_id' => $city_id))->result();?>
        <option value=''>--Select Distributor--</option><?php
        foreach ($get_data as $row) :?>
            <option value='<?=$row->id; ?>'><?= $row->contact_person_name; ?></option>
        <?php endforeach;
    }

    public function sales_person_load()
    {
        $area_id = $this->input->post("area_id");
        $data = $this->Crud_model->where_not('area_attribute', array('area_id' => $area_id), 'sales_person_id', 0);
        if($data->num_rows() > 0):
            $idsp = $data->row();
            $sp_list = $this->Crud_model->fetch('sales_person_details', array('id' => $idsp->sales_person_id))->row(); ?>
            <option value=''>--Select Sales Person--</option>
            <option value='<?= $sp_list->id?>'><?= $sp_list->saler_name?></option>
        <?php endif;
    }

    public function load_category()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('category', array('id' => $id))->row();
        $array = array(
            'id' => $id,
            'category_name' => $fetch_data->category_name
        );
        echo json_encode($array);
    }

    public function load_subcat()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('sub_category', array('category_id' => $id, 'status' => 0))->result(); ?>
        <option value=''>--Select Sub category--</option><?php
        foreach ($fetch_data as $row) :?>
            <option value='<?=$row->id; ?>'><?= $row->sub_category_name; ?></option>
        <?php endforeach;
    }

    public function update_subcat()
    {
        $id = $this->input->post("id");
        $select_fetch = $this->Crud_model->fetch("category")->result();
        $fetch_data = $this->Crud_model->fetch('sub_category', array('id' => $id, 'status' => 0))->row();
        $option = "<option>--Please select category--</option>";
        foreach($select_fetch as $row){
            if($row->id == $fetch_data->category_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $option .= "<option value='$row->id' $select > $row->category_name</option>";
        }
        $array = array(
            'id' => $id,
            'sub_category_name' => $fetch_data->sub_category_name,
            'option' => $option
        );
        echo json_encode($array);
    }

    public function update_state()
    {
        $id = $this->input->post("id");
        $select_fetch = $this->Crud_model->fetch("country")->result();
        $fetch_data = $this->Crud_model->fetch('state', array('id' => $id, 'status' => 0))->row();
        $option = "<option>--Please select country--</option>";
        foreach($select_fetch as $row){
            if($row->country_id == $fetch_data->country_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $option .= "<option value='$row->country_id' $select > $row->country_name</option>";
        }
        $array = array(
            'id' => $id,
            'state_name' => $fetch_data->state_name,
            'option' => $option
        );
        echo json_encode($array);
    }


    public function update_country()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('country', array('country_id' => $id, 'status' => 0))->row();
        $array = array(
            'id' => $id,
            'country_name' => $fetch_data->country_name
        );
        echo json_encode($array);
    }

    public function update_city()
    {
        $id = $this->input->post("id");
        $country_id = $this->input->post("country_id");
    
        $select_fetch = $this->Crud_model->fetch("country")->result();
        $select_state = $this->Crud_model->fetch("state")->result();
        $fetch_data = $this->Crud_model->fetch('city', array('id' => $id))->row();
    
        $country_name = "<option>--Please select country--</option>";
        foreach($select_fetch as $row){
            if($row->country_id == $fetch_data->country_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $country_name .= "<option value='$row->country_id' $select > $row->country_name</option>";
        }
    
        $state_name = "<option>--Please select state--</option>";
        foreach($select_state as $row_s){
            if($row_s->id == $fetch_data->state_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $state_name .= "<option value='$row_s->id' $select > $row_s->state_name</option>";
        }
        $array = array(
            'id' => $id,
            'city_name' => $fetch_data->city_name,
            'country_name' => $country_name,
            'state_name' => $state_name
        );
        echo json_encode($array);
    }

    public function update_area()
    {
        $id = $this->input->post("id");
        $country_id = $this->input->post("country_id");
        $select_fetch = $this->Crud_model->fetch("country")->result();
        $select_state = $this->Crud_model->fetch("state")->result();
        $select_city = $this->Crud_model->fetch("city")->result();
        $fetch_data = $this->Crud_model->fetch('aria', array('id' => $id))->row();
        $country_name = "<option>--Please select country--</option>";
        foreach($select_fetch as $row){
            if($row->country_id == $fetch_data->country_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $country_name .= "<option value='$row->country_id' $select > $row->country_name</option>";
        }
    
        $state_name = "<option>--Please select state--</option>";
        foreach($select_state as $row_s){
            if($row_s->id == $fetch_data->state_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $state_name .= "<option value='$row_s->id' $select > $row_s->state_name</option>";
        }
        $city_name = "<option>--Please select city--</option>";
        foreach($select_city as $row_s){
            if($row_s->id == $fetch_data->city_id){
                $select = "selected";
            }else{
                $select = null;
            }
            $city_name .= "<option value='$row_s->id' $select > $row_s->city_name</option>";
        }
        $array = array(
            'id' => $id,
            'area_name' => $fetch_data->area_name,
            'city_name' => $city_name,
            'country_name' => $country_name,
            'state_name' => $state_name
        );
        echo json_encode($array);
    }

    public function update_brand()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('brand', array('id' => $id))->row();
        $array = array(
            'id' => $id,
            'brand_name' => $fetch_data->brand_name
        );
        echo json_encode($array);
    }

    public function update_attribute()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('attribute', array('id' => $id))->row();
        $array = array(
            'id' => $id,
            'attribute_size' => $fetch_data->attribute_size
        );
        echo json_encode($array);
    }

    public function update_gst()
    {
        $id = $this->input->post("id");
        $fetch_data = $this->Crud_model->fetch('gst', array('id' => $id))->row();
        $array = array(
            'id' => $id,
            'gst_rate' => $fetch_data->gst_rate
        );
        echo json_encode($array);
    }
    public function product_img()
    {
        $id = $this->input->post("id");
        $enc = json_encode($id);
        $img = "";
        $ids = "";
        $newdatas = "";
        foreach($id as $new){
            $fetch = $this->Crud_model->fetch("product_gallery", array("id" => $new))->row();
            $newdatas .= "<input type='checkbox' name='check' onclick='onlyOne(this)'>";
            $img .= "<img style='width: 20%;' src=".base_url($fetch->product_img).">";
            $ids .= $new;
        }
        $nnn = array("img" => $img, "id" => $enc, "newdaat" => $newdatas);
        echo json_encode($nnn);
    }

    public function super_stockist_formdata()
    {
        $id = $this->input->post("id");
        $fetch = $this->Crud_model->fetch('super_stockist_details', array('id' => $id))->row();
        echo json_encode($fetch);
    }

    public function testing()
    {
        $product = $this->db->get_where('product', array('category' => 5, 'status' => 0))->result_array();

        echo "<pre>";
        print_r($product);

        $count = count($product);
        if($count > 0){
            for($i = 0; $i < $count; $i++){
                unset($product[$i]['id']);
                unset($product[$i]['category']);
            }
        }
        
        print_r($product);

    }

    public function show_image()
    {
        $img_type = $this->input->post('img_type');
        $table = $this->input->post('table');
        $img_id = $this->input->post('id');

        if($img_type == "gst_img"){
            $fetch = $this->Crud_model->fetch($table, array('id' => $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->gst_pic).">";
            $data = array("img" => $img);
            echo json_encode($data);
        }else if($img_type == "pan_img"){
            $fetch = $this->Crud_model->fetch($table, array('id' => $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->pan_pic).">";
            $data = array("img" => $img);
            echo json_encode($data);
        
        }else if($img_type == "business_img"){
            $fetch = $this->Crud_model->fetch($table, array('id' => $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->nature_buss_pic).">";
            $data = array("img" => $img);
            echo json_encode($data);
        }
        else if($img_type == "fssai_img"){
            $fetch = $this->Crud_model->fetch($table, array('id'=> $img_id))->row();
            $img ="<img width='100%'  src=".base_url($fetch->fssai_pic).">";
            $data = array("img" => $img);
            echo json_encode($data);
        }
        else if($img_type == "adhar_img"){
            $fetch = $this->Crud_model->fetch($table,array('id'=> $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->adhar_pic).">";
            $data = array("img"=>$img);
            echo json_encode($data);
        }
        else if($img_type == "voter_img"){
            $fetch = $this->Crud_model->fetch($table,array('id'=> $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->voter_id_pic).">";
            $data = array("img"=>$img);
            echo json_encode($data);
        }
        else if($img_type == "driving_img"){
            $fetch = $this->Crud_model->fetch($table,array('id'=> $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->voter_id_pic).">";
            $img = "hello";
            $data = array("img"=>$img);
            echo json_encode($data);
        }
        else if($img_type == "driving_idddmg"){
            $fetch = $this->Crud_model->fetch($table,array('id'=> $img_id))->row();
            $img = "<img width='100%' src=".base_url($fetch->driving_pic).">";
            $data = array("img" => $img);
            echo json_encode($data);
        }else{
            echo "false";
        }
    }

    public function testings()
    {
        $data = $this->db->get('sales_person_details')->result();
        $new = array();
        foreach($data as $row){

            $kk = json_decode($row->area_id);
            if(in_array(17, $kk)){ $bb = $row->id; }else{ $bb = null; }

            $new[] = array(
               'id' => $row->id,
               'area_id' => $row->area_id,
               'test' => $bb,
            );
        }
        foreach($new as $rt){
            echo $rt['test'];
            if($rt['test'] !== null){
                $fetsss = $this->db->get_where('sales_person_details', array('id' => $rt['test']))->row();
                echo $fetsss->area_id."<br>";
            }
        }

    }

    public function nmmmmm()
    {
        $sales_person = $this->db->get_where('sales_person_details', array('id' => 9))->result();
        $ne = $this->db->get('distributor_details')->result();

        // $json_ne = array();

        echo "<pre>";
        // foreach($ne as $n){
        //     $json_ne = json_decode($n->area_id);
        //     print_r($json_ne);
        // }

        // $json = array();
        foreach($sales_person as $row){
            $json = json_decode($row->area_id);

            print_r($json);
            
            foreach($json as $c){
                echo $c;
            }
            // $getss = in_array(16, $ne);
            // 
            // print_r($getss);
            
        }

        // foreach($json as $vc){
        //     // $za[] = "";
        //     foreach($json_ne as $za){

        //         print_r($za);
        //     }
        //     print_r($vc);
        //     // $array_intersect = array_intersect($za, $vc);
        //     // print_r($array_intersect);
        // }


    }

    public function testingsssssss()
    {
        $data = $this->db->get('sales_person_details')->result();
        $new = array();
        foreach($data as $row){
            $kk = json_decode($row->area_id);
            if(in_array(17, $kk)){ $bb = $row->id; }else{ $bb = null; }
            $new[] = array('test' => $bb);
        }
        
        foreach($new as $rt){
            if($rt['test'] !== null){
                $fetsss = $this->db->get_where('sales_person_details', array('id' => $rt['test']))->result();
                echo "<pre>";
                print_r($fetsss);
            }
        }
    }

    public function mkjhhj()
    {
        $data = $this->db->get('sales_person_details')->result();
        $new = array();
        foreach($data as $row){
            $kk = json_decode($row->area_id);
            if(in_array(17, $kk)){ $bb = $row->id; }else{ $bb = null; }
            $new[] = array('test' => $bb);
        }
        
        foreach($new as $rt){
            if($rt['test'] !== null){
                $fetsss = $this->db->get_where('sales_person_details', array('id' => $rt['test']))->result();
                // print_r($fetsss);
                $result = array('status' => '0', 'msg' => 'Sales_person_details', 'data' => $fetsss);
                // return $result;
                echo "<pre>";
                print_r($result);
            }
        }

    }

    public function dhkfdssdj()
    {
        $sp_area_id = $this->db->get_where('sales_person_details', array('id' => 11))->row();
        $sales_person_id = $this->db->get_where('area_attribute', array('sales_person_id' => $sp_area_id->id))->result();
        $blank_arr = array();
        foreach($sales_person_id as $sp_id){
            $area_list_idssss = $this->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $sp_id->area_id))->row();
            $id_get = $area_list_idssss->distributor_id;
            array_push($blank_arr, $id_get);
        }
        $final_id = (array_unique($blank_arr));
        print_r($final_id);


    }


    public function dhkfdsdfgdfgdfgsdj()
    {
        $sp_area_id = $this->db->get_where('sales_person_details', array('id' => 11))->row();
        $sales_person_id = $this->db->get_where('area_attribute', array('sales_person_id' => $sp_area_id->id))->result();
        // $blank_arr = array();
        foreach($sales_person_id as $sp_id){
            $area_list_idssss = $this->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $sp_id->area_id))->row();
            $id_get = $area_list_idssss->distributor_id;
            echo $id_get;
            // array_push($blank_arr, $id_get);
        }
        // $final_id = (array_unique($blank_arr));
        // print_r($final_id);


    }


    public function hkdasd()
    {
        $sp_area_id = $this->db->get_where('sales_person_details', array('id' => 11))->row();
        $sales_person_id = $this->db->get_where('area_attribute', array('sales_person_id' => $sp_area_id->id))->result();
        
        // echo "<pre>";
        foreach($sales_person_id as $row){
            // echo $row->area_id;
            $retailer_list = $this->db->get_where('retailer', array('area_id' => $row->area_id))->row();

            print_r($retailer_list);
            // print_r($getssss);
        }
        
        // print_r($sales_person_id);
        
        // $blank_arr = array();
        // foreach($sales_person_id as $sp_id){
        //     $area_list_idssss = $this->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $sp_id->area_id))->row();
        //     $id_get = $area_list_idssss->distributor_id;
        //     array_push($blank_arr, $id_get);
        // }
        // $final_id = (array_unique($blank_arr));
        // print_r($final_id);


    }

    public function jdhdsfjsk()
    {
        // $id_verify = $this->db->get_where('distributor_details', array('id' => 46));
        $id_verify = $this->db->get_where('sales_person_details', array('id' => 11));

        $sales_person_id = $this->db->get_where('area_attribute', array('sales_person_id' => $id_verify->row()->id))->result();

        echo "<pre>";

        
        $blank_arr = array();
        
        foreach ($sales_person_id as $sp_id) {
            
            print_r($sp_id);
            $area_list_idssss = $this->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $sp_id->area_id))->row();
            $id_get = $area_list_idssss->distributor_id;
            array_push($blank_arr, $id_get);
        }
        $final_id = (array_unique($blank_arr));

        echo "<pre><br><br><br>";
        print_r($final_id);

    }

    public function hdhdsfdhskj()
    {
        $id_verify = $this->db->get_where('distributor_details', array('id' => 46));

        $sales_person_id = $this->db->get_where('area_attribute', array('distributor_id' => $id_verify->row()->id))->result();

        echo "<pre>";

        
        foreach($sales_person_id as $row){
            $area_list_idssss = $this->db->where_not_in('distributor_id', 0)->get_where('area_attribute', array('area_id' => $row->area_id))->row();
            print_r($area_list_idssss);

        }
    }



    public function dfhdsasjdh()
    {
        $id_verify = $this->db->get_where('sales_person_details', array('id' => 12));
        if (!$id_verify->num_rows() > 0) {
            $result = array('status' => '0', 'msg' => 'User id is not valid!!!');
            return $result;
        }

        $sales_person_id = $this->db->get_where('area_attribute', array('sales_person_id' => $id_verify->row()->id));

        if($sales_person_id->num_rows() > 0){
            
            $blank_arr = array();
            
            foreach ($sales_person_id->result() as $sp_id) {
                $ret_list_get = $this->db->get_where('retailer', array('area_id' => $sp_id->area_id));
                if($ret_list_get->num_rows() > 0){
                    // print_r($ret_list_get->row());
                    $ret_list = $ret_list_get->row();
                    print_r($ret_list);


                }else{
                    echo "no";
                }

            }

        }else{
            print_r(array('status' => 0, 'msg' => 'Sales person area id not found'));
        }

    }

    public function hhj()
    {
        $sales_person_details = $this->db->get_where('sales_person_details', array('id' => 12));
        
        $area_attribute = $this->Crud_model->where_not('area_attribute', array('distributor_id' => 49), 'distributor_id', 0);
        // print_r(array_filter(array_unique($blank_arr)));
        echo "<pre>";
        print_r($area_attribute->result());

        $blank_arr = array();
        foreach($area_attribute->result() as $row){
            $get_data = $this->db->where_not_in('sales_person_id', 0)->get_where('area_attribute', array('area_id' => $row->area_id));
            if($get_data->num_rows() > 0){
                print_r($get_data->result());
                array_push($blank_arr, $get_data->row('sales_person_id'));
            }
        }

        $sp_id = array_filter(array_unique($blank_arr));

        print_r($sp_id);

    }

    public function hdjshsj()
    {
        $data = $this->db->get_where('distributor_details', array('id' => 49))->row();
        $datassss = $this->Crud_model->fetch('sales_person_details')->result();
        echo "<pre>";

        $match = array_intersect(array(18,15), json_decode($data->area_id));
        print_r($datassss);

        foreach($datassss as $row){
            print_r($row->area_id);
        }
        print_r($match);
    }

    public function dhsdfjk()
    {
        $data = $this->db->get_where('my_cart', array('user_id' => 73, 'user_type' => 1))->result();
        echo "<pre>";
        foreach($data as $row){
            $da[] = $row->cart_qty * $row->product_price;
        }
        // print_r(array_sum($da));

        echo array_sum($da);

        // if(array_sum($da) !== 7058){
        //     echo "yes";
        // }else{
        //     echo "no";
        // }

    }


}
