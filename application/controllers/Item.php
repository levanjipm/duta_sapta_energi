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
		
		$this->load->model('Item_class_model');
		$items = $this->Item_class_model->show_all();
		$data['classes'] = $items;
		
		$this->load->view('sales/item_manage_dashboard',$data);
	}
	
	public function insert_new_item()
	{
		$price_list		= $this->input->post('price_list');
		$this->load->model('Item_model');
		$result = $this->Item_model->insert_from_post();
		if($result != null){
			$this->load->model('Price_list_model');
			$this->Price_list_model->insert_from_post($result, $price_list);
		}
		
		redirect(site_url('Item'));
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
		
		header('Content-Type: application/json');
		echo json_encode($items);
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
		
		header('Content-Type: application/json');
		echo json_encode($data);
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
	
	public function item_edit_form()
	{
		$item_id		= $this->input->get('id');
		$this->load->model('Item_model');
		$data	= $this->Item_model->select_by_id($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_item_to_cart()
	{
		$item_id	= $this->input->post('price_list_id');
		$this->load->model('Item_model');
		$item = $this->Item_model->select_by_price_list_id($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
}