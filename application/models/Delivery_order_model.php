<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order_model extends CI_Model {
	private $table_delivery_order = 'code_delivery_order';
		
		public $id;
		public $name;
		public $date;
		public $is_delete;
		public $is_sent;
		public $is_confirm;
		public $invoice_id;
		public $guid;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id					='';
			$this->name					='';
			$this->date					='';
			$this->is_confirm			='';
			$this->is_delete			='';
			$this->is_sent				='';
			$this->guid					='';
			$this->invoice_id			='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->date					= $db_item->date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->is_sent				= $db_item->is_sent;
			$this->guid					= $db_item->guid;
			$this->invoice_id			= $db_item->invoice_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->date				= $this->date;
			$db_item->is_confirm		= $this->is_confirm;
			$db_item->is_delete			= $this->is_delete;
			$db_item->is_sent			= $this->is_sent;
			$db_item->guid				= $this->guid;
			$db_item->invoice_id		= $this->invoice_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->is_sent				= $db_item->is_sent;
			$stub->guid					= $db_item->guid;
			$stub->invoice_id			= $db_item->invoice_id;
			
			return $stub;
		}
		
		public function get_new_stub_from_db_with_customer($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->guid					= $db_item->guid;
			$stub->customer_name		= $db_item->customer_name;
			$stub->address				= $db_item->address;
			$stub->city					= $db_item->city;
			
			return $stub;
		}
		
		public function map_list($delivery_orders)
		{
			$result = array();
			foreach ($delivery_orders as $delivery_order)
			{
				$result[] = $this->get_new_stub_from_db($delivery_order);
			}
			return $result;
		}
		
		public function map_list_with_customer($delivery_orders)
		{
			$result = array();
			foreach ($delivery_orders as $delivery_order)
			{
				$result[] = $this->get_new_stub_from_db_with_customer($delivery_order);
			}
			return $result;
		}
		
		public function show_unconfirmed_delivery_order($offset = 0, $filter = '', $limit = 25)
		{
			$this->db->select('code_delivery_order.*, customer.name as customer_name, customer.address, customer.city');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id', 'inner');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_delivery_order.is_confirm =',0);
			$this->db->where('code_delivery_order.is_delete =',0);
			$this->db->where('code_delivery_order.is_sent =',0);
			$this->db->limit($limit, $offset);
			
			$query 		= $this->db->get();
			$items	 	= $query->result();
			
			$result 	= $this->map_list_with_customer($items);
			
			return $result;
		}
		
		public function show_unsent_delivery_order($offset = 0, $filter = '', $limit = 25)
		{
			$this->db->select('code_delivery_order.*, customer.name as customer_name, customer.address, customer.city');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id', 'inner');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_delivery_order.is_confirm =',1);
			$this->db->where('code_delivery_order.is_delete =',0);
			$this->db->where('code_delivery_order.is_sent =',0);
			$this->db->order_by('id', 'ASC');
			$this->db->limit($limit, $offset);
			
			$query 		= $this->db->get();
			$items	 	= $query->result();
			
			$result 	= $this->map_list_with_customer($items);
			
			return $result;
		}
		
		public function name_generator($date, $taxing)
		{
			$this->db->where('month(date)',date('m',strtotime($date)));
			$this->db->where('year(date)',date('Y',strtotime($date)));
			$this->db->where('is_delete',0);
			$query	= $this->db->get($this->table_delivery_order);
			$item 	= $query-> num_rows();
			
			$item++;
			
			$name = 'DO-DSE-' . date('Y', strtotime($date)) . str_pad($item, 3, '0', STR_PAD_LEFT) . $taxing;
			return $name;
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id =',$id);
			$this->db->from($this->table_sales_order);
			$query 		= $this->db->get();
			
			$items 		= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_delivery_order);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function check_confirm($id)
		{
			$this->db->where('id =',$id);
			$this->db->where('is_confirm =', 0);
			$query = $this->db->get($this->table_delivery_order);
			$item = $query-> num_rows();
			
			if($item == 1){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function insert_from_post()
		{
			$this->load->model('Delivery_order_model');
			$delivery_order_name = $this->Delivery_order_model->name_generator($this->input->post('date'), $this->input->post('taxing'));
			
			$this->date				= $this->input->post('date');
			$this->name				= $delivery_order_name;
			$this->guid				= $this->input->post('guid');
			
			$db_item 				= $this->get_db_from_stub($this);
			$db_result 				= $this->db->insert($this->table_delivery_order, $db_item);
			
			$insert_id				= $this->db->insert_id();
			
			return $insert_id;
		}
		
		public function confirm($id)
		{
			$this->load->model('Delivery_order_model');
			$check	= $this->Delivery_order_model->check_confirm($id);
			
			if($check){
				$this->db->set('is_confirm', '1');
				$this->db->where('id',$id);
				$this->db->update($this->table_delivery_order);
				return TRUE;
			} else {
				return FALSE;
				
			}
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
}