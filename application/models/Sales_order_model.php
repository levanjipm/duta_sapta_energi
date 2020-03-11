<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_model extends CI_Model {
		private $table_sales_order = 'code_sales_order';
		
		public $id;
		public $customer_id;
		public $name;
		public $date;
		public $seller;
		public $taxing;
		public $is_confirm;
		public $guid;
		public $invoicing_method;
		
		public $customer_name;
		public $customer_address;
		public $customer_city;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->name					= $db_item->name;
			$this->date					= $db_item->date;
			$this->seller				= $db_item->seller;
			$this->taxing				= $db_item->taxing;
			$this->is_confirm			= $db_item->is_confirm;
			$this->guid					= $db_item->guid;
			$this->invoicing_method		= $db_item->invoicing_method;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->name					= $this->name;
			$db_item->date					= $this->date;
			$db_item->seller				= $this->seller;
			$db_item->taxing				= $this->taxing;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->guid					= $this->guid;
			$db_item->invoicing_method		= $this->invoicing_method;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db_with_customer($db_item)
		{
			$stub = new Sales_order_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->seller				= $db_item->seller;
			$stub->taxing				= $db_item->taxing;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->guid					= $db_item->guid;
			$stub->invoicing_method		= $db_item->invoicing_method;
			
			$stub->customer_name 		= $db_item->customer_name;
			$stub->customer_address 	= $db_item->customer_address;
			$stub->customer_city 		= $db_item->customer_city;
			return $stub;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_order_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->seller				= $db_item->seller;
			$stub->taxing				= $db_item->taxing;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->guid					= $db_item->guid;
			$stub->invoicing_method		= $db_item->invoicing_method;
			return $stub;
		}
		
		public function map_list_with_customer($sales_orders)
		{
			$result = array();
			foreach ($sales_orders as $sales_order)
			{
				$result[] = $this->get_new_stub_from_db_with_customer($sales_order);
			}
			return $result;
		}
		
		public function map_list($sales_orders)
		{
			$result = array();
			foreach ($sales_orders as $sales_order)
			{
				$result[] = $this->get_new_stub_from_db($sales_order);
			}
			return $result;
		}
		
		public function name_generator($date)
		{
			$name = date('Y', strtotime($date)) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$this->db->where('name =',$name);
			$query = $this->db->get($this->table_sales_order);
			$item = $query->num_rows();
			
			if($item != 0){
				$this->Sales_order_model->name_generator($date);
			};
			
			return $name;
		}
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_user);
			$items 		= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function show_by_id($id)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*, customer.name as customer_name, customer.name as customer_name, customer.address as customer_address, customer.city as customer_city');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_sales_order.id',$id);
			
			$query 		= $this->db->get();
			print_r($this->db->last_query());
			
			$items 		= $query->result();
			return $items;
		}
		
		public function show_invoicing_method_by_id($delivery_order_id)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*');
			$this->db->from('code_sales_order');
			$this->db->join('sales_order', 'code_sales_order.id = sales_order.code_sales_order_id','inner');
			$this->db->join('delivery_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->where('code_delivery_order.id =',$delivery_order_id);
			
			$query 		= $this->db->get();
			
			$items 		= $query->result();
			$result		= $this->map_list($items);
			return $result;
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_sales_order);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function insert_from_post()
		{
			$this->load->model('Sales_order_model');
			
			$guid					= $this->input->post('guid');
			$check_guid				= $this->Sales_order_model->check_guid($guid);
			
			if($check_guid){
				$sales_order_date		= $this->input->post('sales_order_date');
				$sales_order_name 		= $this->Sales_order_model->name_generator($sales_order_date);
				if($this->input->post('sales_order_seller') === ''){
					$sales_order_seller	= NULL;
				} else {
					$sales_order_seller	= $this->input->post('sales_order_seller');
				}
				
				$this->id				= '';
				$this->customer_id		= $this->input->post('customer_id');
				$this->name				= $sales_order_name;
				$this->date				= $sales_order_date;
				$this->seller			= $sales_order_seller;
				$this->taxing			= $this->input->post('taxing');
				$this->is_confirm		= '';
				$this->guid				= $guid;
				$this->invoicing_method	= $this->input->post('method');
				
				$db_item 				= $this->get_db_from_stub();
				$db_result 				= $this->db->insert($this->table_sales_order, $db_item);
				
				$insert_id				= $this->db->insert_id();
				
				return $insert_id;
			} else {
				return NULL;
			}
		}
		
		public function add_item_to_cart()
		{
			$item_id = $this->input->post('item_id');
			$cart_products = $this->session->userdata('cart_products');
			
			$cart_products[] = $item_id;
			
			$this->session->set_userdata('cart_products', $cart_products);
		}
		
		public function remove_item_from_cart()
		{
			$cart_products = $this->session->userdata('cart_products');
			$item_id = $this->input->post('item_id');
			$key = array_search($item_id, $cart_products);
			unset($cart_products[$key]);
			
			$this->session->set_userdata('cart_products', $cart_products);
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
		
		public function show_uncompleted_sales_order($offset = 0, $filter = '', $limit = 25)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*, customer.name as customer_name, customer.name as customer_name, customer.address as customer_address, customer.city as customer_city');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			
			if($filter != ''){
				$this->db->like('customer.name', $filter,'both');
				$this->db->or_like('customer.address', $filter,'both');
				$this->db->or_like('code_sales_order.name', $filter, 'both');
			}
			
			$this->db->where('sales_order.status =', 0);
			$this->db->order_by('code_sales_order.date');
			$this->db->limit($limit, $offset);
			$query 		= $this->db->get();
			
			$items 		= $query->result();
			$result		= $this->map_list_with_customer($items);
			
			return $result;
		}
}