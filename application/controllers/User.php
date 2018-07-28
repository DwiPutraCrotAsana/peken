<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class User extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->library(array('form_validation','pagination'));
        $this->load->helper(array('form', 'url'));
        $this->load->model(array('M_user','M_product','M_pagination', 'M_product_category', 'M_product_sub_category', 'M_quotation', 'M_quotation_detail','M_supplier_gallery_pic'));
    }
    function dashboard_supplier_view(){
        $id_supplier = $this->session->userdata('id_supplier');
        if (empty($id_supplier)) {
        redirect('Home/home_view');
        }
        $get_quotation = $this->M_quotation->get_quotation("",$id_supplier,"",0);
        $data_notification['unread_quotation'] = $get_quotation->result();
        $data_notification['unread_quotation_num_rows'] = $get_quotation->num_rows();
        $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail($id_supplier);
        $data_notification['unread_quotation_detail'] = $get_unread_qutation_detail->result();
        $data_notification['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
        $this->load->view('template/back/head_back',$data_notification);
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

            $user_rules['filter_value'] =  array( 'search_value'=>$search_value, 'user_level'=>1);
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
            $user_rules['filter_value'] =  array('user_level'=>1);
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
        // $get_product_category = $this->M_product_category->get_product_category();
        // $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category_all();
        // $data_nav['product_category'] = $get_product_category->result();
        // $data_nav['product_sub_category'] = $get_product_sub_category->result();
        // if ($this->session->userdata('id_buyer')) {
        // $id_buyer = $this->session->userdata('id_buyer');
        // $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$id_buyer);
        // $data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
        // $data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
        // }
        $head_data['page_title'] = "Dinilaku";
        $this->load->view('template/front/head_front',$head_data);
        $this->load->view('template/front/navigation');
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
        $product_rules['filter_value'] =  array('supplier_id'=>$supplier_id);
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
        $supplier_gallery_pic_rules['filter_value'] =  array('supplier_id'=>$supplier_id);
        $this->M_supplier_gallery_pic->set_search_supplier_gallery_pic($supplier_gallery_pic_rules);
        $get_supplier_gallery_pic = $this->M_supplier_gallery_pic->get_supplier_gallery_pic();
        
        $user_rules['filter_value'] =  array('supplier_id'=>$supplier_id, 'user_level'=>1);
        $this->M_user->set_search_user($user_rules);
        $get_supplier = $this->M_user->get_user();

        $data['supplier_gallery_pic'] = $get_supplier_gallery_pic->result();
        $data['product'] = $get_product->result();
        $data['supplier'] = $get_supplier->result();
        $this->pagination->initialize($config);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        // $get_product_category = $this->M_product_category->get_product_category();
        // $get_product_sub_category = $this->M_product_sub_category->get_product_sub_category_all();
        // $data_nav['product_category'] = $get_product_category->result();
        // $data_nav['product_sub_category'] = $get_product_sub_category->result();
        // if ($this->session->userdata('id_buyer')) {
        // $id_buyer = $this->session->userdata('id_buyer');
        // $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$id_buyer);
        // $data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
        // $data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
        // }
        $head_data['page_title'] = "Dinilaku";
        $this->load->view('template/front/head_front',$head_data);
        $this->load->view('template/front/navigation');
        $this->load->view('public/supplier/supplier_mini_site',$data);
        $this->load->view('template/front/foot_front');
    }

    function supplier_account_view(){
        $id_supplier = $this->session->userdata('id_supplier');
        if (empty($id_supplier)) {
        redirect('Home/home_view');
        }
        $get_member = $this->M_user->get_member("",1,$id_supplier,"","","","","","include");
        $data['user'] = $get_member->result();
        // print_r($data['user']);exit();
        $get_quotation = $this->M_quotation->get_quotation("",$id_supplier,"",0);
        $data_notification['unread_quotation'] = $get_quotation->result();
        $data_notification['unread_quotation_num_rows'] = $get_quotation->num_rows();
        $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail($id_supplier);
        $data_notification['unread_quotation_detail'] = $get_unread_qutation_detail->result();
        $data_notification['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
        $this->load->view('template/back/head_back',$data_notification);
        $this->load->view('template/back/sidebar_back');
        $this->load->view('private/supplier_account/supplier_account',$data);
        $this->load->view('template/back/foot_back');
    }
    public function edit_supplier_account(){
        $id_supplier = $this->session->userdata('id_supplier');
        $config['upload_path']   = './assets/supplier_upload/';
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['overwrite'] = TRUE;
        $config['max_size']      = 1000;
        //$config['max_width']     = 1024;
        //$config['max_height']    = 1000;
        $this->load->library('upload', $config);
        $this->upload->do_upload('profil_image');
        $profil_image_lama = $this->input->post('profil_image_lama');
        $profil_image_file = $this->upload->data();
        if (!empty($profil_image_file['file_name'])){
        $profil_image = $profil_image_file['file_name'];
        $this->session->set_userdata('profil_image',$profil_image);
        }else{
        $profil_image = $profil_image_lama;
        }
        $this->upload->do_upload('siup');
        $siup_lama = $this->input->post('siup_lama');
        $siup_file = $this->upload->data();
        if (!empty($siup_file['file_name']) AND $siup_file['file_name'] != $profil_image_file['file_name']){
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
        'FirstName' => $this->input->post('first_name'),
        'LastName' => $this->input->post('last_name'),
        'CompanyName' => $this->input->post('company_name'),
        'CompanyAddress' => $this->input->post('company_address'),
        'City' => $this->input->post('city'),
        'ZipCode' => $this->input->post('zip_code'),
        'Location' => $this->input->post('location'),
        'Npwp' => $this->input->post('npwp'),
        'Phone' => $this->input->post('phone'),
        'CompanyDescription' => $this->input->post('company_description'),
        'ProfilImage' => $profil_image,
        'Siup' => $siup,
        'Tdp' => $tdp
        );
        $supplier_gallery_pic = $this->input->post('file');
        $this->session->set_userdata('first_name',$this->input->post('first_name'));
        $this->session->set_userdata('company_name',$this->input->post('company_name'));
        // print_r($data);exit();
        $this->M_user->edit_member($data,$id_supplier,$supplier_gallery_pic);
        redirect('Supplier/supplier_account_view');
    }
    public function supplier_upload_siup(){
        $id = $this->session->userdata('id_supplier');
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

    public function suplier_upload_tdp(){
        $id = $this->session->userdata('id_supplier');
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
        $id_gallery_pic=$this->input->post('id_gallery_pic');
        $this->db->where('IdGalleryPic', $id_gallery_pic);
        $this->db->delete('tbgallerypic');
    }
    function remove_supplier_gallery_pic(){
        $nama=$this->input->post('nama');
        if(file_exists($file='./assets/supplier_upload/'.str_replace(' ', '_', $nama))){
        unlink($file);
        }
        echo "{}";
        }
    function supplier_view(){
        //$data['product_category'] = $this->M_product_category->get_product_category();
        $id_admin = $this->session->userdata('id_admin');
        if (empty($id_admin)) {
            redirect('Home/home_view');
        }
        $this->load->view('template/back_admin/admin_head');
        $this->load->view('template/back_admin/admin_navigation');
        $this->load->view('template/back_admin/admin_sidebar');
        $this->load->view('private/supplier/supplier');
        $this->load->view('template/back_admin/admin_foot');
    }
    function get_supplier_json(){
        $get_member = $this->M_user->get_member(0,1);
        // print_r($get_product_category->row());exit();
        $baris = $get_member->result();
        $data = array();
        foreach ($baris as $bar) {
            $row = array(
            "CompanyName" => $bar->CompanyName,
            "Email" => $bar->Email,
            "Phone" => $bar->Phone,
            "Location" => $bar->Location,
            "City" => $bar->City,
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

    }

    function login(){
		$email = $this->input->post('email');
		$password = sha1($this->input->post('password'));
		$get_member = $this->M_user->get_user("","","","","","",$email,$password,"");
		$num_rows = $get_member->num_rows();
		$row = $get_member->row();

		if ($num_rows > 0 AND $row->IsSupplier == 1  AND $row->IsUser == 0) {
			//echo "supplier";exit();
			$get_supplier = $this->M_user->get_member(0,1,$row->IdMember);
	  	$data['supplier'] = $get_supplier->result();
			$this->session->set_userdata('id_supplier',$row->IdMember);
			$this->session->set_userdata('company_name',$row->CompanyName);
			$this->session->set_userdata('profil_image',$row->ProfilImage);
			$this->session->set_userdata('first_name',$row->FirstName);
			redirect('Supplier/dashboard_supplier_view');
		}
		elseif ($num_rows > 0 AND $row->IsSupplier == 0 AND $row->IsUser == 0) {
		//	echo "buyer";exit();
			$get_buyer = $this->M_user->get_member(0,0,$row->IdMember);
	  		$data['buyer'] = $get_buyer->result();
			$this->session->set_userdata('id_buyer',$row->IdMember);
			// $this->session->set_userdata('company_name',$row->CompanyName);
			// $this->session->set_userdata('profil_image',$row->ProfilImage);
			$this->session->set_userdata('first_name',$row->FirstName);
			redirect('Home/index');
		}
		elseif ($num_rows > 0 AND $row->IsUser == 1) {
			$get_supplier = $this->M_user->get_member(1,0,$row->IdMember);
	  	$data['supplier'] = $get_supplier->result();
			//echo "admin";exit();
			$this->session->set_userdata('id_admin',$row->IdMember);
			$this->session->set_userdata('company_name',$row->CompanyName);
			$this->session->set_userdata('profil_image',$row->ProfilImage);
			$this->session->set_userdata('first_name',$row->FirstName);
			redirect('Admin/admin_dashboard_view');
		}
		 else {
			// echo "sinf ada";exit();
			redirect('Home/index');
		}


	}

	function logout(){
		$this->session->sess_destroy();
		redirect('Home/index');
	}
?>
