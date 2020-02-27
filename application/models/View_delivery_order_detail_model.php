<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_delivery_order_detail_model extends CI_Model {
		private $table_delivery_order = 'view_delivery_order_detail';
		
		public $id;
		public $code_delivery_order_id;
		public $quantity;
		public $reference;
		public $name;
		public $price_list;
		public $discount;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new View_delivery_order_detail_model();
			
			$stub->id							= $db_item->id;
			$stub->code_delivery_order_id		= $db_item->code_delivery_order_id;
			$stub->quantity						= $db_item->quantity;
			$stub->reference					= $db_item->reference;
			$stub->name							= $db_item->name;
			$stub->price_list					= $db_item->price_list;
			$stub->discount						= $db_item->discount;

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
		
		public function show_by_code_delivery_order_id($id)
		{
			$this->db->where('code_delivery_order_id =', $id);
			$query = $this->db->get($this->table_delivery_order);
			$items	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
}
?>