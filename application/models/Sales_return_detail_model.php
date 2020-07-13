<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_detail_model extends CI_Model {
	private $table_sales_return = 'sales_return';
		
		public $id;
		public $delivery_order_id;
		public $quantity;
		public $received;
		public $is_done;
		public $code_sales_return_id;
		
		public $complete_address;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->delivery_order_id	= $db_item->delivery_order_id;
			$this->quantity				= $db_item->quantity;
			$this->received				= $db_item->received;
			$this->is_done				= $db_item->is_done;
			$this->code_sales_return_id	= $db_item->code_sales_return_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;		
			$db_item->delivery_order_id		= $this->delivery_order_id;
			$db_item->quantity				= $this->quantity;		
			$db_item->received				= $this->received;				
			$db_item->is_done				= $this->is_done;				
			$db_item->code_sales_return_id	= $this->code_sales_return_id;	
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Supplier_model();
			
			$stub->id					= $db_item->id;		
			$stub->delivery_order_id	= $db_item->delivery_order_id;
			$stub->quantity				= $db_item->quantity;		
			$stub->received				= $db_item->received;				
			$stub->is_done				= $db_item->is_done;				
			$stub->code_sales_return_id	= $db_item->code_sales_return_id;
			
			return $stub;
		}
		
		public function map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($item);
			}
			return $result;
		}
		
		public function get_sum_quantity_by_delivery_order_id($delivery_order_id)
		{
			$query = $this->db->query("SELECT delivery_order.quantity, COALESCE(SUM(IF(sales_return.is_done = 1, sales_return.received, sales_return.quantity)), 0) AS returned FROM sales_return 
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				WHERE sales_return.delivery_order_id = '$delivery_order_id'");
			$result = $query->row();
			return $result;
		}
		
		public function insert_return_data($delivery_order_id, $quantity, $code_sales_return_id)
		{
			$this->id		= "";
			$this->delivery_order_id	= $delivery_order_id;
			$this->quantity				= $quantity;
			$this->received				= 0;
			$this->is_done				= 0;
			$this->code_sales_return_id	= $code_sales_return_id;
			
			$db_item 		= $this->get_db_from_stub($this);
			$db_result 		= $this->db->insert($this->table_sales_return, $db_item);
		}
}