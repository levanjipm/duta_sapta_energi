<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_in_model extends CI_Model {
	private $table_stock_in = 'stock_in';
		
		public $id;
		public $item_id;
		public $quantity;
		public $residue;
		public $supplier_id;
		public $customer_id;
		public $code_good_receipt_id;
		public $code_sales_return_id;
		public $code_event_id;
		public $price;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id						='';
			$this->item_id					='';
			$this->quantity					='';
			$this->residue					='';
			$this->supplier_id				='';
			$this->customer_id				='';
			$this->code_good_receipt_id		='';
			$this->code_sales_return_id		='';
			$this->code_event_id			='';
			$this->price					='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->item_id					= $db_item->item_id;
			$this->quantity					= $db_item->quantity;
			$this->residue					= $db_item->residue;
			$this->supplier_id				= $db_item->supplier_id;
			$this->customer_id				= $db_item->customer_id;
			$this->code_good_receipt_id		= $db_item->code_good_receipt_id;
			$this->code_sales_return_id		= $db_item->code_sales_return_id;
			$this->code_event_id			= $db_item->code_event_id;
			$this->price					= $db_item->price;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->item_id					= $this->item_id;
			$db_item->quantity					= $this->quantity;
			$db_item->residue					= $this->residue;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->customer_id				= $this->customer_id;
			$db_item->code_good_receipt_id		= $this->code_good_receipt_id;
			$db_item->code_sales_return_id		= $this->code_sales_return_id;
			$db_item->code_event_id				= $this->code_event_id;
			$db_item->price						= $this->price;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id						= $db_item->id;
			$stub->item_id					= $db_item->item_id;
			$stub->quantity					= $db_item->quantity;
			$stub->residue					= $db_item->residue;
			$stub->supplier_id				= $db_item->supplier_id;
			$stub->customer_id				= $db_item->customer_id;
			$stub->code_good_receipt_id		= $db_item->code_good_receipt_id;
			$stub->code_sales_return_id		= $db_item->code_sales_return_id;
			$stub->code_event_id			= $db_item->code_event_id;
			$stub->price					= $db_item->price;
			
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
		
		public function input_from_code_good_receipt($good_receipt_array)
		{
			$this->db->insert_batch($this->table_stock_in, $good_receipt_array);
		}
}