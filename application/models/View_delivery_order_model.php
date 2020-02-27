<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_delivery_order_model extends CI_Model {
		private $table_delivery_order = 'view_code_delivery_order';
		
		public $id;
		public $date;
		public $name;
		public $is_confirm;
		public $is_delete;
		public $is_sent;
		public $guid;
		public $invoice_id;
		public $sales_order_name;
		public $customer_name;
		public $address;
		public $city;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new View_delivery_order_model();
			
			$stub->id				= $db_item->id;
			$stub->date				= $db_item->date;
			$stub->name				= $db_item->name;
			$stub->is_confirm		= $db_item->is_confirm;
			$stub->is_delete		= $db_item->is_delete;
			$stub->is_sent			= $db_item->is_sent;
			$stub->guid				= $db_item->guid;
			$stub->invoice_id		= $db_item->invoice_id;
			$stub->sales_order_name	= $db_item->sales_order_name;
			$stub->customer_name	= $db_item->customer_name;
			$stub->address			= $db_item->address;
			$stub->city				= $db_item->city;
			
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
		
		public function show_by_id($id)
		{
			$this->db->where('id =', $id);
			$query = $this->db->get($this->table_delivery_order);
			$items	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result[0];
		}
}
?>