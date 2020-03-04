<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {
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
		
		$this->load->model('Item_model');
		$items = $this->Item_model->show_items();
		$data['items'] = $items;
		
		$pages = $this->Item_model->count_items();
		$data['pages'] = ceil($pages / 25);
		
		$this->load->model('Item_class_model');
		$items = $this->Item_class_model->show_all();
		$data['classes'] = $items;
		
		$this->load->view('sales/item_manage_dashboard',$data);
	}
	
	public function insert_new_item()
	{
		$this->load->model('Item_model');
		$result = $this->Item_model->insert_from_post();
		if($result != NULL){
			$this->load->model('Price_list_model');
			$this->Price_list_model->insert_from_post($result);
		}
		
		redirect(site_url('Item'));
	}
	
	public function update_view_page()
	{
		$current_page	= $this->input->get('page') - 1;
		$filter			= $this->input->get('term');
		$this->load->model('Item_model');
		$items			= $this->Item_model->show_items($current_page * 25, $filter);
		$data['items']	= $items;
		
		$data['pages']	= ceil($this->Item_model->count_items($filter) / 25);
		
		$data['paging'] = $this->input->get('page');
		
		$this->load->view('sales/item_manage_view',$data);
	}
	
	public function update_item()
	{
		$this->load->model('Item_model');
		$this->Item_model->update_from_post();
		
		redirect(site_url('Item'));
	}
	
	public function shopping_cart_view()
	{
		$this->load->model('Item_model');
		$items = $this->Item_model->show_items(25,0);
		
		$data['items'] = $items;
		
		$this->load->view('sales/shopping_cart_item',$data);
	}
	
	public function shopping_cart_view_purchase()
	{		
		$this->load->view('purchasing/shopping_cart_item');
	}
	
	public function search_item_cart()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Item_model');
		$items = $this->Item_model->show_items($offset, $term);
		$data['items'] = $items;
		
		$items = $this->Item_model->count_items($term);
		$data['pages'] = max(1, ceil($items / 25));
		$data['page'] = $page;
		
		$this->load->view('purchasing/shopping_cart_item_view',$data);
	}
	
	public function view_item_table()
	{
		$this->load->model('Item_model');
		$items = $this->Item_model->show_search_result(25,0);
		
		$data['items'] = $items;
		$this->load->view('sales/customer_table_view', $data);
		
		$this->load->model('Item_model');
		$data['pages'] = ceil($this->Item_model->count_page() / 25);
	}
}