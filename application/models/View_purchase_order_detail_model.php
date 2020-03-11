<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_purchase_order_detail_model extends CI_Model {
	private $table_purchase_order = 'view_purchase_order_detail';
		public $reference;
		public $name;
		public $price_list;
		public $net_price;
		public $quantity;
		public $status;
		public $code_purchase_order_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new View_purchase_order_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->name                     = $db_item->name;                   
			$stub->reference                = $db_item->reference;                    
			$stub->price_list               = $db_item->price_list;              
			$stub->net_price	            = $db_item->net_price;            
			$stub->quantity	                = $db_item->quantity;               
			$stub->received	                = $db_item->received;               
			$stub->status		            = $db_item->status;              
			$stub->code_purchase_order_id   = $db_item->code_purchase_order_id;            
			
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
		
		public function show_by_code_purchase_order_id($id)
		{
			$this->db->where('code_purchase_order_id', $id);
			$this->db->where('status', 0);
			$query 		= $this->db->get($this->table_purchase_order);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
}
?>