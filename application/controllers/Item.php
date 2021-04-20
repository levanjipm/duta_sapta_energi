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
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Item_class_model');
		$items = $this->Item_class_model->showAllItems();
		$data['classes'] = $items;

		$this->load->model("Brand_model");
		$brands = $this->Brand_model->getItems();
		$data['brands'] = $brands;
		
		$this->load->view('sales/Item/dashboard',$data);
	}
	
	public function insertItem()
	{
		$priceList		= $this->input->post('priceList');
		$this->load->model('Item_model');

		$itemId = $this->Item_model->insertItem();
		if($itemId != null){
			$this->load->model('Price_list_model');
			$this->Price_list_model->insertItem($itemId, $priceList);
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function updateById()
	{
		$this->load->model('Item_model');
		$this->Item_model->updateById();
	}
	
	public function showItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Item_model');
		$items = $this->Item_model->showItems($offset, $term);
		$data['items'] = $items;
		
		$items = $this->Item_model->countItems($term);
		$data['pages'] = max(1, ceil($items / 25));
		$data['page'] = $page;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function showById()
	{
		$item_id		= $this->input->get('id');
		$this->load->model('Item_model');
		
		$data	= $this->Item_model->showById($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_item_to_cart()
	{
		$item_id	= $this->input->post('price_list_id');
		$this->load->model('Item_model');
		$item = $this->Item_model->getByPriceListId($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
	
	public function deleteById()
	{
		$id = $this->input->post('id');

		$this->load->model('Price_list_model');
		$result = $this->Price_list_model->deleteByItemId($id);
		if($result){
			$this->load->model('Item_model');
			$finalResult = $this->Item_model->deleteById($id);

			echo $finalResult;
		} else {
			echo 0;
		}		
	}

	public function viewDetail($reference)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$data		= array();
		$this->load->model("Item_model");
		$data['item']	= $this->Item_model->getByReference($reference);

		$this->load->model("Stock_out_model");
		$stockArray		= $this->Stock_out_model->calculateMonthlyOutput($reference);
		$currentDate	= mktime(0,0,0,date('m'), 1, date("Y"));
		$stockArray		= array();
		foreach($stockArray as $stock){
			$month		= $stock->month;
			$year		= $stock->year;
			$quantity	= $stock->quantity;

			$date		= mktime(0,0,0,$month, 1, $year);
			$difference	= round(($currentDate - $date) / (60 * 60 * 24 * 30));

			$stockArray[$difference] = $quantity;
		}

		for($i = 0; $i < 12; $i++){
			if(!array_key_exists($i, $stockArray)){
				$stockArray[$i] = 0;
			}
		}

		$data['stock']	= json_encode($stockArray);

		$this->load->model("Price_list_model");	
		$data['price']	= $this->Price_list_model->getByItemReference($reference);

		$this->load->view('sales/Item/detailDashboard', $data);
	}
}