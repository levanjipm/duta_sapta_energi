<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_class extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function index()
	{
		$this->load->view('head');
		$this->load->view('sales/header');
		
		$this->load->model('Item_class_model');
		$items = $this->Item_class_model->show_limited(25,0);
		$data['classes'] = $items;
		
		$this->load->view('sales/item_class_manage_dashboard',$data);
	}
	
	public function insert_new_class()
	{
		$this->load->model('Item_class_model');
		$this->Item_class_model->insert_from_post();
		
		redirect(site_url('Item_class'));
	}
	
	public function delete_item_class()
	{
		$this->load->model('Item_class_model');
		$this->Item_class_model->delete_by_id();
		
		redirect(site_url('Item_class'));
	}
}