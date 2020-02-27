<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_complete_good_receipt extends CI_Model {
	private $table_good_receipt = 'view_complete_good_receipt';
		
		public $id;
		public $code_good_receipt_id;
		public $good_receipt_name;
		public $date;
		public $received_date;
		public $purchase_order_name;
		public $supplier_name;
		public $address;
		public $city;
		public $reference;
		public $name;
		public $quantity;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->code_good_receipt_id	= $db_item->code_good_receipt_id;
			$this->good_receipt_name	= $db_item->good_receipt_name;
			$this->date					= $db_item->date;
			$this->received_date		= $db_item->received_date;
			$this->purchase_order_name	= $db_item->purchase_order_name;
			$this->supplier_name		= $db_item->supplier_name;
			$this->address				= $db_item->address;
			$this->city					= $db_item->city;
			$this->reference			= $db_item->reference;
			$this->name					= $db_item->name;
			$this->quantity				= $db_item->quantity;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->code_good_receipt_id	= $this->code_good_receipt_id;
			$db_item->good_receipt_name		= $this->good_receipt_name;
			$db_item->date					= $this->date;
			$db_item->received_date			= $this->received_date;
			$db_item->purchase_order_name	= $this->purchase_order_name;
			$db_item->supplier_name			= $this->supplier_name;
			$db_item->address				= $this->address;
			$db_item->city					= $this->city;
			$db_item->reference				= $this->reference;
			$db_item->name					= $this->name;
			$db_item->quantity				= $this->quantity;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new View_complete_good_receipt();
			
			$stub->id					= $db_item->id;
			$stub->code_good_receipt_id	= $db_item->code_good_receipt_id;
			$stub->good_receipt_name	= $db_item->good_receipt_name;
			$stub->date					= $db_item->date;
			$stub->received_date		= $db_item->received_date;
			$stub->purchase_order_name	= $db_item->purchase_order_name;
			$stub->supplier_name		= $db_item->supplier_name;
			$stub->address				= $db_item->address;
			$stub->city					= $db_item->city;
			$stub->reference			= $db_item->reference;
			$stub->name					= $db_item->name;
			$stub->quantity				= $db_item->quantity;
			
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
		
		
		public function show_by_id($id)
		{
			$this->db->where('code_good_receipt_id =', $id);
			$query = $this->db->get($this->table_good_receipt);
			
			$item = $query->result();
			return ($item !== null) ? $this->map_list($item) : null;
		}
}