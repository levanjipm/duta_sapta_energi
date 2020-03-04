<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_out_model extends CI_Model {
	private $table_stock_in = 'stock_in';
		
		public $id;
		public $in_id;
		public $quantity;
		public $customer_id;
		public $supplier_id;
		public $code_delivery_order_id;
		public $code_event_id;
		public $code_purchase_return_id;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id						='';
			$this->in_id					='';
			$this->quantity					='';
			$this->supplier_id				='';
			$this->customer_id				='';
			$this->code_delivery_order_id	='';
			$this->code_purchase_return_id	='';
			$this->code_event_id			='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->in_id					= $db_item->in_id;
			$this->quantity					= $db_item->quantity;
			$this->supplier_id				= $db_item->supplier_id;
			$this->customer_id				= $db_item->customer_id;
			$this->code_delivery_order_id	= $db_item->code_delivery_order_id;
			$this->code_purchase_return_id	= $db_item->code_purchase_return_id;
			$this->code_event_id			= $db_item->code_event_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->in_id						= $this->in_id;
			$db_item->quantity					= $this->quantity;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->customer_id				= $this->customer_id;
			$db_item->code_delivery_order_id	= $this->code_delivery_order_id;
			$db_item->code_purchase_return_id	= $this->code_purchase_return_id;
			$db_item->code_event_id				= $this->code_event_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id						= $db_item->id;
			$stub->in_id					= $db_item->in_id;
			$stub->quantity					= $db_item->quantity;
			$stub->supplier_id				= $db_item->supplier_id;
			$stub->customer_id				= $db_item->customer_id;
			$stub->code_delivery_order_id	= $db_item->code_delivery_order_id;
			$stub->code_purchase_return_id	= $db_item->code_purchase_return_id;
			$stub->code_event_id			= $db_item->code_event_id;
			
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
		
		public function send_delivery_order($delivery_order_array)
		{
			$this->load->model('Stock_in_model');
			foreach($delivery_order_array as $delivery_order){
				$item_id				= $delivery_order['item_id'];
				$quantity				= $delivery_order['quantity'];
				$customer_id			= $delivery_order['customer_id'];
				$code_delivery_order_id	= $delivery_order['code_delivery_order_id'];
				
				$this->Stock_in_model->search_by_item_id($item_id, $quantity);
			}
		}
}