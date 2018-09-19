  <?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  class User extends CI_Controller{

    function __construct(){
      parent::__construct();
      $this->load->library(array('form_validation','pagination','email'));
      $this->load->helper(array('form', 'url', 'captcha'));
      $this->load->model(array('M_user','M_product','M_pagination', 'M_product_category', 'M_product_sub_category', 'M_quotation', 'M_quotation_detail','M_supplier_gallery_pic','M_captcha'));
    }
    function admin_dashboard_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || $user_level != 0) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $this->load->view('template/back_admin/admin_head');
      $this->load->view('template/back_admin/admin_navigation');
      $this->load->view('template/back_admin/admin_sidebar');
      $this->load->view('private/admin_dashboard');
      $this->load->view('template/back_admin/admin_foot');
    }
    function supplier_dashboard_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || ($user_level != 1 && $user_level != 3)) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $this->load->view('template/back/head_back');
      $this->load->view('template/back/sidebar_back');
      $this->load->view('private/dashboard_supplier');
      $this->load->view('template/back/foot_back');
    }
    
    /* function public_supplier_list_view() digunakan untuk menampilkan supplier list
    kepada public (non member, member)*/
    function public_supplier_list_view(){


      //mengambil nilai page dari url
      $page = $this->input->get('per_page');
      $this->M_pagination->set_config("",12,"","","","","");
      /* mengecek apakah nilai dari form pencarian ada atau tidak, jika ada maka
      supplier list akan menampilkan supplier berdasarkan CompanyName*/
      if (!empty($this->input->get('search_value'))) {
        $search_value = $this->input->get('search_value');
        $data['search_value'] = $search_value;

        $user_rules['filter_value'] =  array( 'search_value'=>$search_value, 'user_level'=>"1 OR user_tb.UserLevel = 3");
        $this->M_user->set_search_user($user_rules);
        $get_supplier = $this->M_user->get_user();
        $this->M_pagination->set_config(
          "","","","","","","","User/public_supplier_list_view?search_value=".$search_value,
          $get_supplier->num_rows()
        );
        $config = $this->M_pagination->get_config();
        $offset = $this->M_pagination->get_offset($page);
        $user_rules['limit'] = $config["per_page"];
        $user_rules['offset'] = $offset;
        $this->M_user->set_search_user($user_rules);
        $get_supplier = $this->M_user->get_user();
        $data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
        $data['breadcrumb'] .= "<li class='active'>"."Search for '".$search_value."''</li>";
      }
      else {
        $user_rules['filter_value'] =  array('user_level'=>"1 OR user_tb.UserLevel = 3");
        $this->M_user->set_search_user($user_rules);
        $get_supplier = $this->M_user->get_user();
        $this->M_pagination->set_config(
          "","","","","","","","user/public_supplier_list_view",
          $get_supplier->num_rows()
        );
        $config = $this->M_pagination->get_config();
        $offset = $this->M_pagination->get_offset($page);
        $user_rules['limit'] = $config["per_page"];
        $user_rules['offset'] = $offset;
        $this->M_user->set_search_user($user_rules);
        $get_supplier = $this->M_user->get_user();
        $data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
        $data['breadcrumb'] .= "<li class='active'>All Supplier</li>";
      }
      $this->pagination->initialize($config);
      $data['supplier'] = $get_supplier->result();
      $str_links = $this->pagination->create_links();
      $data["links"] = explode('&nbsp;',$str_links );

      $this->M_product_category->set_search_product_category();
      $get_product_category = $this->M_product_category->get_product_category();

      $this->M_product_sub_category->set_search_product_sub_category();
      $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();

      $data_nav['product_category'] = $get_product_category->result();
      $data_nav['product_sub_category'] = $get_product_sub_category->result();
      // if ($this->session->userdata('user_id')) {
      // $buyer_id = $this->session->userdata('user_id');
      // $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$buyer_id);
      // $data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
      // $data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
      // }
      $head_data['page_title'] = "Dinilaku";
      $this->load->view('template/front/head_front',$head_data);
      $this->load->view('template/front/navigation',$data_nav);
      $this->load->view('public/supplier/supplier_list',$data);
      $this->load->view('template/front/foot_front');
    }
    function supplier_mini_site_view(){
      $page = $this->input->get('per_page');
      $supplier_id = $this->input->get('supplier_id');
      $product_rules['join']['other_table_columns'] = " ,user_tb.*, productpic_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
      $product_rules['join']['join_table'] = " INNER JOIN user_tb INNER JOIN productpic_tb INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
      ON product_tb.Id = productpic_tb.ProductId
      AND user_tb.Id = product_tb.SupplierId
      AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
      AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
      $product_rules['group_by'] = ' productpic_tb.ProductId ';
      $product_rules['filter_value'] =  array('user_id'=>$supplier_id);
      $this->M_product->set_search_product($product_rules);
      $get_product = $this->M_product->get_product();
      $this->M_pagination->set_config(
        "",12,"","","","","","User/supplier_mini_site_view?supplier_id=".$supplier_id,
        $get_product->num_rows()
      );
      $config = $this->M_pagination->get_config();
      $offset = $this->M_pagination->get_offset($page);
      $product_rules['limit'] = $config["per_page"];
      $product_rules['offset'] = $offset;
      $this->M_product->set_search_product($product_rules);
      $get_product = $this->M_product->get_product();

      // $user_rules['join']['other_table_columns'] = " ,suppliergallerypic_tb.* ";
      // $user_rules['join']['join_table'] = " INNER JOIN suppliergallerypic_tb
      // ON user_tb.Id = suppliergallerypic_tb.SupplierId ";
      $supplier_gallery_pic_rules['filter_value'] =  array('user_id'=>$supplier_id);
      $this->M_supplier_gallery_pic->set_search_supplier_gallery_pic($supplier_gallery_pic_rules);
      $get_supplier_gallery_pic = $this->M_supplier_gallery_pic->get_supplier_gallery_pic();

      $user_rules['filter_value'] =  array('user_id'=>$supplier_id, 'user_level'=>1);
      $this->M_user->set_search_user($user_rules);
      $get_supplier = $this->M_user->get_user();

      $data['supplier_gallery_pic'] = $get_supplier_gallery_pic->result();
      $data['product'] = $get_product->result();
      $data['supplier'] = $get_supplier->result();
      $this->pagination->initialize($config);
      $str_links = $this->pagination->create_links();
      $data["links"] = explode('&nbsp;',$str_links );
      $this->M_product_category->set_search_product_category();
      $get_product_category = $this->M_product_category->get_product_category();

      $this->M_product_sub_category->set_search_product_sub_category();
      $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();

      $data_nav['product_category'] = $get_product_category->result();
      $data_nav['product_sub_category'] = $get_product_sub_category->result();
      // if ($this->session->userdata('user_id')) {
      // $buyer_id = $this->session->userdata('user_id');
      // $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$buyer_id);
      // $data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
      // $data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
      // }
      $head_data['page_title'] = "Dinilaku";
      $this->load->view('template/front/head_front',$head_data);
      $this->load->view('template/front/navigation',$data_nav);
      $this->load->view('public/supplier/supplier_mini_site',$data);
      $this->load->view('template/front/foot_front');
    }

    function supplier_account_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || ($user_level != 1 && $user_level != 3)) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $user_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_user->set_search_user($user_rules);
      $get_supplier = $this->M_user->get_user();
      $data['user'] = $get_supplier->result();

      $supplier_gallery_pic_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_supplier_gallery_pic->set_search_supplier_gallery_pic($supplier_gallery_pic_rules);
      $get_supplier_gallery_pic = $this->M_supplier_gallery_pic->get_supplier_gallery_pic();
      $data['supplier_gallery_pic'] = $get_supplier_gallery_pic->result();

      $this->load->view('template/back/head_back');
      $this->load->view('template/back/sidebar_back');
      $this->load->view('private/supplier_account/supplier_account',$data);
      $this->load->view('template/back/foot_back');
    }
    function update_company_profile(){
      $supplier_id = $this->session->userdata('user_id');
      $config['upload_path']   = './assets/supplier_upload/';
      $config['allowed_types'] = 'gif|jpg|png|pdf';
      $config['overwrite'] = TRUE;
      $config['max_size']      = 1000;
      //$config['max_width']     = 1024;
      //$config['max_height']    = 1000;

      $this->load->library('upload', $config);
      $this->upload->do_upload('profile_image');
      $profile_image_lama = $this->input->post('profile_image_lama');
      $profile_image_file = $this->upload->data();
      if (!empty($profile_image_file['file_name'])){
        $profile_image = $profile_image_file['file_name'];
        $this->session->set_userdata('profile_image',$profile_image);
      }else{
        $profile_image = $profile_image_lama;
      }

      $data = array(
        'FirstName' => $this->input->post('first_name'),
        'LastName' => $this->input->post('last_name'),
        'CompanyName' => $this->input->post('company_name'),
        'Address' => $this->input->post('address'),
        'City' => $this->input->post('city'),
        'ZipCode' => $this->input->post('zip_code'),
        'Province' => $this->input->post('province'),
        'State' => $this->input->post('state'),
        'Phone' => $this->input->post('phone'),
        'CompanyDescription' => $this->input->post('company_description'),
        'ProfileImage' => $profile_image

      );
      $this->session->set_userdata('first_name',$this->input->post('first_name'));
      $this->session->set_userdata('company_name',$this->input->post('company_name'));
      $this->M_user->update_user($data,$supplier_id);
      redirect('User/supplier_account_view');
    }
    function update_certificate_license(){
      $supplier_id = $this->session->userdata('user_id');
      $config['upload_path']   = './assets/supplier_upload/';
      $config['allowed_types'] = 'gif|jpg|png|pdf';
      $config['overwrite'] = TRUE;
      $config['max_size']      = 1000;
      //$config['max_width']     = 1024;
      //$config['max_height']    = 1000;

      $this->load->library('upload', $config);
      $this->upload->do_upload('siup');
      $siup_lama = $this->input->post('siup_lama');
      $siup_file = $this->upload->data();
      if (!empty($siup_file['file_name']) AND $siup_file['file_name'] ){
        $siup = $siup_file['file_name'];
      }else{
        $siup = $siup_lama;
      }
      $this->upload->do_upload('tdp');
      $tdp_lama = $this->input->post('tdp_lama');
      $tdp_file = $this->upload->data();
      if (!empty($tdp_file['file_name']) AND $tdp_file['file_name'] != $siup_file['file_name']){
        $tdp = $tdp_file['file_name'];
      }else{
        $tdp = $tdp_lama;
      }
      $data = array(
        'Npwp' => $this->input->post('npwp'),
        'Siup' => $siup,
        'Tdp' => $tdp
      );
      // echo "<pre>";
  		// print_r($data);
  		// echo "</pre>";exit();
      $this->M_user->update_user($data,$supplier_id);
      redirect('User/supplier_account_view');
    }
    function update_supplier_gallery()  {
      $supplier_id = $this->session->userdata('user_id');
      $supplier_gallery_pic = $this->input->post('file');
      // echo "<pre>";
  		// print_r($this->input->post('file'));
  		// echo "</pre>";exit();
      $this->M_supplier_gallery_pic->update_supplier_gallery_pic($supplier_id,$supplier_gallery_pic);
      redirect('User/supplier_account_view');
    }
    function supplier_upload_siup(){
      $id = $this->session->userdata('user_id');
      $config['upload_path']   = './assets/suplier_upload/';
      $config['allowed_types'] = 'gif|jpg|png|pdf';
      $config['overwrite'] = TRUE;
      $config['max_size']      = 1000;
      //$config['max_width']     = 1024;
      //$config['max_height']    = 1000;
      $this->load->library('upload', $config);
      //$this->upload->do_upload('siup');

      if ( ! $this->upload->do_upload('siup')){
        $status = "error";
        $msg = $this->upload->display_errors();
      }
      else{
        $dataupload = $this->upload->data();
        $status = "success";
        $msg = $dataupload['file_name']." berhasil diupload";
      }
      $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
      $siup_lama = $this->input->post('siup_lama');
      $siup =   $this->upload->data('file_name');

      if (!empty($siup)){
        $siup_baru = $siup;
      }else{
        $siup_baru = $siup_lama;
      }
      $data = array('Siup' => $siup_baru);
      $this->M_register->edit_member_db($data,$id);
      //redirect('Suplier/suplier_edit');
    }

    function suplier_upload_tdp(){
      $id = $this->session->userdata('user_id');
      $config['upload_path']   = './assets/suplier_upload/';
      $config['allowed_types'] = 'gif|jpg|png|pdf';
      $config['overwrite'] = TRUE;
      $config['max_size']      = 1000;
      //$config['max_width']     = 1024;
      //$config['max_height']    = 1000;
      $this->load->library('upload', $config);
      //$this->upload->do_upload('tdp');

      if ( ! $this->upload->do_upload('tdp')){
        $status = "error";
        $msg = $this->upload->display_errors();
      }
      else{
        $dataupload = $this->upload->data();
        $status = "success";
        $msg = $dataupload['file_name']." berhasil diupload";
      }
      $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
      $tdp_lama = $this->input->post('tdp_lama');
      $tdp =   $this->upload->data('file_name');

      if (!empty($tdp)){
        $tdp_baru = $tdp;
      }else{
        $tdp_baru = $tdp_lama;
      }
      $data = array('Tdp' => $tdp_baru);
      $this->M_register->edit_member_db($data,$id);
      //redirect('Suplier/suplier_edit');
    }
    function add_supplier_gallery_pic(){
      $config['upload_path']   = './assets/supplier_upload';
      $config['allowed_types'] = 'gif|jpg|png|ico|pdf|docx';
      $config['max_size']             = 6000;
      //mengganti nama asli file menjadi cstom
      $new_name = time().$_FILES["userfiles"]['name'];
      $config['file_name'] = $new_name;
      $this->load->library('upload',$config);
      if($this->upload->do_upload('userfiles')){
        $token=$this->input->post('token_foto');
        $nama=$this->upload->data('file_name');
      }
      $data = $nama.",".$token;
      echo json_encode($data);
    }
    function remove_supplier_gallery_pic_button(){
      $supplier_gallery_pic_id=$this->input->post('supplier_gallery_pic_id');
      $this->db->where('Id', $supplier_gallery_pic_id);
      $this->db->delete('suppliergallerypic_tb');
    }
    function remove_supplier_gallery_pic(){
      $nama=$this->input->post('nama');
      if(file_exists($file='./assets/supplier_upload/'.str_replace(' ', '_', $nama))){
        unlink($file);
      }
      echo "{}";
    }

    function member_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || $user_level != 0) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $filter_num = 0;
      if (!empty($this->input->get())) {
        if (!empty($this->input->get('user_level'))) {
          $user_level = $this->input->get('user_level');
          $user_level = ($user_level == -1) ? "-1 OR user_tb.UserLevel <> 0 " : $user_level ;
          $filter_num = ($user_level == -1) ? $filter_num+0 : $filter_num+1 ;
          $user_rules['filter_value']['user_level'] = $user_level;
          $data['user_level'] = $this->input->get('user_level');
        }
        if (!empty($this->input->get('search_company_name'))) {
          $search_company_name = $this->input->get('search_company_name');
          $user_rules['filter_value']['search_value'] = $search_company_name;
          $filter_num = !empty($search_company_name) ? $filter_num+1 : $filter_num+0 ;
          $data['search_company_name'] = $this->input->get('search_company_name');
        }
        $this->M_user->set_search_user($user_rules);
      } else {
        $user_rules['filter_value']['user_level'] = "1 OR user_tb.UserLevel = 2 OR user_tb.UserLevel = 3";
        $this->M_user->set_search_user($user_rules);
      }

      //$this->M_user->set_search_user($user_rules);
      $data['filter_num']= $filter_num;
      $get_member = $this->M_user->get_user();
      $data['member'] = $get_member->result();
      $this->load->view('template/back_admin/admin_head');
      $this->load->view('template/back_admin/admin_navigation');
      $this->load->view('template/back_admin/admin_sidebar');
      $this->load->view('private/member/member',$data);
      $this->load->view('template/back_admin/admin_foot');
    }

    function get_member_json(){
      // !empty() ? $this->input->get('user_level') : "" ;
      //$user_rules['filter_value'] =  array('search_value'=>1);;
      if (!empty($this->input->get())) {
        $user_rules['filter_value']['user_level'] = $this->input->get('user_level');
        $this->M_user->set_search_user($user_rules);
      } else {
        $this->M_user->set_search_user();
      }

      //$this->M_user->set_search_user($user_rules);
      $get_member = $this->M_user->get_user();
      // print_r($get_product_category->row());exit();
      $baris = $get_member->result();
      $data = array();
      foreach ($baris as $bar) {
        $row = array(
          "CompanyName" => $bar->CompanyName,
          "Email" => $bar->Email,
          "Phone" => $bar->Phone,
          "City" => $bar->City,
          "State" => $bar->State,
          "DetailButton" => '<a   id="id_detail" class="btn btn-info id_detail" ><span class="fa fa-fw fa-search" >
          </span></a>'
        );
        $data[] = $row;
      }
      $output = array(
        "draw" => 0,
        "recordsTotal" => $get_member->num_rows(),
        "recordsFiltered" => $get_member->num_rows(),
        "data" => $data
      );
      echo json_encode($output);
    }


    function login(){

      $email = $this->input->post('email');
      $password = sha1($this->input->post('password'));

      $user_rules['filter_value'] =  array('email'=>$email, 'password'=>$password);
      $this->M_user->set_search_user($user_rules);

      $get_member = $this->M_user->get_user();
      $num_rows = $get_member->num_rows();
      $row = $get_member->row();

      if ($num_rows > 0 AND ($row->UserLevel == 1 OR $row->UserLevel == 3 )) {
       // echo "Supplier";exit;
        // $user_rules['filter_value'] =  array('supplier_id'=>$row->Id, 'user_level'=>1);
        // $this->M_user->set_search_user($user_rules);
        // $get_supplier = $this->M_user->get_user();
        // $data['supplier'] = $get_supplier->result();
        $this->session->set_userdata('user_id',$row->Id);
        $this->session->set_userdata('user_level',$row->UserLevel);
        $this->session->set_userdata('company_name',$row->CompanyName);
        $this->session->set_userdata('profile_image',$row->ProfileImage);
        $this->session->set_userdata('last_name',$row->LastName);
        redirect('User/supplier_dashboard_view');
      }
      elseif ($num_rows > 0  AND $row->UserLevel == 2) {
        //echo "Buyer";exit;
        // $user_rules['filter_value'] =  array('supplier_id'=>$row->Id, 'user_level'=>0);
        // $this->M_user->set_search_user($user_rules);
        // $get_buyer = $this->M_user->get_user();
        // $data['buyer'] = $get_buyer->result();
        $this->session->set_userdata('user_id',$row->Id);
        $this->session->set_userdata('user_level',$row->UserLevel);
        $this->session->set_userdata('company_name',$row->CompanyName);
        $this->session->set_userdata('profile_image',$row->ProfilImage);
        $this->session->set_userdata('last_name',$row->LastName);
        redirect('Home/index');
      }
      elseif ($num_rows > 0 AND $row->UserLevel == 0) {

        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";exit();
        // 	$get_supplier = $this->M_user->get_member(1,0,$row->IdMember);
        // $data['supplier'] = $get_supplier->result();
        //echo "admin";exit();
        $this->session->set_userdata('user_id',$row->Id);
        $this->session->set_userdata('user_level',$row->UserLevel);
        // 	$this->session->set_userdata('company_name',$row->CompanyName);
        $this->session->set_userdata('profile_image',$row->ProfileImage);
        // 	$this->session->set_userdata('first_name',$row->FirstName);
        redirect('User/admin_dashboard_view');
      }
      else {
        echo "sinf ada";exit();
        redirect('Home/index');
      }


    }

    function logout(){
      $this->session->sess_destroy();
      redirect('Home/index');
    }

    function buyer_account_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || ($user_level != 2 && $user_level != 3)) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $user_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_user->set_search_user($user_rules);
      $get_buyer = $this->M_user->get_user();
      $data['buyer'] = $get_buyer->result();
      $this->M_product_category->set_search_product_category();
      $get_product_category = $this->M_product_category->get_product_category();
      $this->M_product_sub_category->set_search_product_sub_category();
      $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
      $data_nav['product_category'] = $get_product_category->result();
      $data_nav['product_sub_category'] = $get_product_sub_category->result();
      // if ($this->session->userdata('user_id')) {
      //         $buyer_id = $this->session->userdata('user_id');
      //         $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$buyer_id);
      //         $data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
      //         $data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
      //     }
      $head_data['page_title'] = "Dinilaku";
      $this->load->view('template/front/head_front',$head_data);
      $this->load->view('template/front/navigation', $data_nav);
      $this->load->view('private/buyer_account/buyer_account',$data);
      $this->load->view('template/front/foot_front');
    }

    function edit_buyer_account(){
      $buyer_id = $this->session->userdata('user_id');
      $config['upload_path']   = './assets/supplier_upload/';
      $config['allowed_types'] = 'gif|jpg|png|pdf';
      $config['overwrite'] = TRUE;
      $config['max_size']      = 1000;
      //$config['max_width']     = 1024;
      //$config['max_height']    = 1000;
      $this->load->library('upload', $config);
      $this->upload->do_upload('profile_image');
      $profile_image_lama = $this->input->post('profile_image_lama');
      $profile_image_file = $this->upload->data();
      if (!empty($profile_image_file['file_name'])){
        $profile_image = $profile_image_file['file_name'];
        $this->session->set_userdata('profile_image',$profile_image);
      }else{
        $profile_image = $profile_image_lama;
      }
      $data = array(
        'FirstName' => $this->input->post('first_name'),
        'LastName' => $this->input->post('last_name'),
        'CompanyName' => $this->input->post('company_name'),
        'Address' => $this->input->post('address'),
        'City' => $this->input->post('city'),
        'Province' => $this->input->post('province'),
        'ZipCode' => $this->input->post('zip_code'),
        'State' => $this->input->post('state'),
        'ProfileImage' => trim($profile_image) ,
        'Phone' => $this->input->post('phone')
      );
      $this->M_user->update_user($data,$buyer_id);
      redirect('User/buyer_account_view');
    }

    function registration(){

      $this->form_validation->set_rules('email', "Email", 'callback_check_email');
      $this->form_validation->set_rules('captcha', "Captcha", 'required');
      $word = $this->session->userdata('captcha_word');
      $captcha = $this->input->post('captcha');
      $email = $this->input->post('email');
      // Jika validasi form salah atau validasi form benar tetapi captcha yang dimasukan
      // salah maka lakukan
      if (
        ($this->form_validation->run() == FALSE)||
        ($this->form_validation->run() == TRUE && $captcha != $word)
      ){
        $cap = $this->M_captcha->generate_captcha();
        $this->session->set_userdata('captcha_word', $cap['word']);

        $this->M_product_category->set_search_product_category();
        $get_product_category = $this->M_product_category->get_product_category();
        $this->M_product_sub_category->set_search_product_sub_category();
        $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
        $data_nav['product_category'] = $get_product_category->result();
        $data_nav['product_sub_category'] = $get_product_sub_category->result();

        $head_data['page_title'] = "Quotation Detail";
        $this->load->view('template/front/head_front',$head_data);
        $this->load->view('template/front/navigation',$data_nav);
        $this->load->view('public/register/register',$cap);
        $this->load->view('template/front/foot_front');
      }
      elseif($this->form_validation->run() == TRUE && $captcha == $word){
        $this->session->unset_userdata('captcha_word');
        // mencari tau apakah email yg sudah terkonfirmasi sebelumnya
        $user_rules['filter_value'] =  array('email'=>$email, "IsConfirmated"=>1);
        $this->M_user->set_search_user($user_rules);
        $get_user_confirmated = $this->M_user->get_user();
        /*
        jika email yg dimasukan tidak ada, maka insert ke database
        tetapi jika email ternya sdh terdaftar tapi tidak terkonfirmasi
        sistem hanya menghirimkan ulang email pendaftaran
        */
        if ($get_user_confirmated->num_rows() == 0) {
          $data = array("Email" => $email);
          $this->M_user->add_user($data);
        }

        $this->M_user->set_search_user();//bersih bersih
        $user_rules['filter_value'] =  array('email'=>$email);
        $this->M_user->set_search_user($user_rules);
        $get_user = $this->M_user->get_user();
        $row = $get_user->row();

        $this->email->from('marketplacesilver@gmail.com', 'marketplacesilver');
        $this->email->to($email);
        $this->email->subject('Email Konfirmasi Akun');

        $this->email->message(" <p><img  src='http://dinilaku.com/assets/front_end_assets/img/2Dinilaku_Logo.png' width='175' alt=''></p>
        <a href='".base_url().
        "User/member_confirmation_view/".$row->Id.
        "'><i class='glyphicon glyphicon-time'></i>VERIFY YOUR ACCOUNTS</a>"
        );
        $this->email->set_newline("\r\n");
        $this->email->send();

        $this->M_product_category->set_search_product_category();
        $get_product_category = $this->M_product_category->get_product_category();
        $this->M_product_sub_category->set_search_product_sub_category();
        $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
        $data_nav['product_category'] = $get_product_category->result();
        $data_nav['product_sub_category'] = $get_product_sub_category->result();

        $head_data['page_title'] = "Quotation Detail";
        $data['email'] = $email;
        $this->load->view('template/front/head_front',$head_data);
        $this->load->view('template/front/navigation',$data_nav);
        $this->load->view('public/register/reg_confirm',$data);
        $this->load->view('template/front/foot_front');
      }
    }

    function member_confirmation_view($user_id){
      $user_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_user->set_search_user($user_rules);
      $get_user = $this->M_user->get_user();
      $data['user'] = $get_user->result();

      $this->M_product_category->set_search_product_category();
      $get_product_category = $this->M_product_category->get_product_category();
      $this->M_product_sub_category->set_search_product_sub_category();
      $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
      $data_nav['product_category'] = $get_product_category->result();
      $data_nav['product_sub_category'] = $get_product_sub_category->result();

      $head_data['page_title'] = "Member Confirmation";
      $this->load->view('template/front/head_front',$head_data);
      $this->load->view('template/front/navigation',$data_nav);
      $this->load->view('public/register/member_confirmation',$data);
      $this->load->view('template/front/foot_front');
    }

    function check_email($str){
      $query = $this->db->get_where("user_tb",array("Email"=>$str, "IsConfirmated"=>1));
      if ($query->num_rows() >= 1) {
        $this->form_validation->set_message('check_email', 'Email you have entered is registered');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    function member_confirmation(){
      if ($this->input->post('password')===$this->input->post('c_password')) {
        $data = array('Password' => sha1($this->input->post('password')),
        'UserLevel' => $this->input->post('user_level'),
        'FirstName' => $this->input->post('first_name'),
        'LastName' => $this->input->post('last_name'),
        'CompanyName' => $this->input->post('company_name'),
        'IsConfirmated' => 1,
        'Phone' => $this->input->post('phone')
        );
      $user_id = $this->input->post('user_id');
      $user_level = $this->input->post('user_level');
      $this->M_user->update_user($data,$user_id);
        if ($user_level==1 OR $user_level==3) {
          $this->session->set_userdata('user_id',$user_id);
          $this->session->set_userdata('user_level',$user_level);
          $this->session->set_userdata('company_name',$this->input->post('company_name'));
          //$this->session->set_userdata('profile_image',$row->ProfilImage);
          $this->session->set_userdata('last_name',$this->input->post('last_name'));
          //echo "both";exit();
          redirect('User/supplier_dashboard_view');
        } elseif ($user_level==2) {
          $this->session->set_userdata('user_id',$user_id);
          $this->session->set_userdata('user_level',$user_level);
          $this->session->set_userdata('company_name',$this->input->post('company_name'));
          //$this->session->set_userdata('profile_image',$row->ProfilImage);
          $this->session->set_userdata('last_name',$this->input->post('last_name'));
          redirect('Home/home_view');
        } else {
          // $this->session->set_userdata('user_id',$user_id);
          // $this->session->set_userdata('user_level',$user_level);
          // $this->session->set_userdata('company_name',$this->input->post('company_name'));
          // //$this->session->set_userdata('profile_image',$row->ProfilImage);
          // $this->session->set_userdata('last_name',$this->input->post('last_name'));
          // redirect('User/supplier_dashboard_view');
        }

      } else {
        redirect('Home/home_view');
      }
    }
    function supplier_verification_view($user_id)
    {
      $admin_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($admin_id) || $user_level != 0) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $user_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_user->set_search_user($user_rules);
      $get_supplier = $this->M_user->get_user();
      $data['user'] = $get_supplier->result();

      $supplier_gallery_pic_rules['filter_value'] =  array('user_id'=>$user_id);
      $this->M_supplier_gallery_pic->set_search_supplier_gallery_pic($supplier_gallery_pic_rules);
      $get_supplier_gallery_pic = $this->M_supplier_gallery_pic->get_supplier_gallery_pic();
      $data['supplier_gallery_pic'] = $get_supplier_gallery_pic->result();

      $this->load->view('template/back_admin/admin_head');
      $this->load->view('template/back_admin/admin_navigation');
      $this->load->view('template/back_admin/admin_sidebar');
      $this->load->view('private/member/supplier_verification',$data);
      $this->load->view('template/back_admin/admin_foot');
    }
    function verify_supplier()
    {
      $user_id = $this->input->post('user_id');
      $data = array(
        'IsVerifiedSupplier' => $this->input->post('is_verified_supplier')
      );
      $this->M_user->update_user($data,$user_id);
      redirect('User/supplier_verification_view/'.$user_id);
    }

    function buyer_reset_password_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || ($user_level != 2)) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }

      $this->M_product_category->set_search_product_category();
      $get_product_category = $this->M_product_category->get_product_category();
      $this->M_product_sub_category->set_search_product_sub_category();
      $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
      $data_nav['product_category'] = $get_product_category->result();
      $data_nav['product_sub_category'] = $get_product_sub_category->result();

      $head_data['page_title'] = "Dinilaku";
      $this->load->view('template/front/head_front',$head_data);
      $this->load->view('template/front/navigation', $data_nav);
      $this->load->view('private/buyer_account/buyer_reset_password');
      $this->load->view('template/front/foot_front');
    }


    function supplier_reset_password_view(){
      $user_id = $this->session->userdata('user_id');
      $user_level = $this->session->userdata('user_level');
      if (empty($user_id) || ($user_level != 1 && $user_level != 3)) {
        $this->session->sess_destroy();
        redirect('Home/home_view');
      }
      $this->load->view('template/back/head_back');
      $this->load->view('template/back/sidebar_back');
      $this->load->view('private/supplier_account/supplier_reset_password');
      $this->load->view('template/back/foot_back');
    }
    function update_password() {
      $user_level = $this->session->userdata('user_level');
      $user_id = $this->session->userdata('user_id');
      $old_password = $this->input->post('old_password');
      $new_password = $this->input->post('new_password');
      $c_new_password = $this->input->post('c_new_password');
      $data = array('Password' => sha1($new_password));


      $this->M_user->update_user($data,$user_id);
      if ($user_level == 1 || $user_level == 3) {
        $this->session->set_flashdata('msg', 'Your password has changed ...');
        redirect('User/supplier_reset_password_view');
      } elseif ($user_level == 2) {
        $this->session->set_flashdata('msg', 'Your password has changed ...');
        redirect('User/buyer_reset_password_view');
      }

      // echo "<pre>";
      // print_r($this->input->post());
      // echo "</pre>";exit();
    }
    function get_user_password()  {
      $member_id = $this->session->userdata('user_id');
      $old_password = $this->input->post('old_password');
      //echo $old_password." ".$member_id;exit();
      $user_rules['filter_value'] =  array('user_id'=>$member_id, 'password'=>sha1($old_password));
      $this->M_user->set_search_user($user_rules);
      $get_user = $this->M_user->get_user();
      $num_rows = $get_user->num_rows();
      if ($num_rows == 1) {
        echo "1";
      } else {
        echo "0";
      }

    }


  }
?>
