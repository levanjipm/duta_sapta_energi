<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_out_model extends CI_Model {
	private $table_stock_out = 'stock_out';
		
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
				$code_delivery_order_id	= $delivery_order['code_delivery_order_id'];
				$customer_id			= $delivery_order['customer_id'];
				$stock_in		= $this->Stock_in_model->search_by_item_id($item_id);
				$residue		= $stock_in->residue;
				$in_id			= $stock_in->id;
				while($quantity > 0){
					if($residue		> $quantity){
						$current_residue	= $residue - $quantity;
						$this->Stock_in_model->update_stock_in($in_id, $current_residue);
						$this->Stock_out_model->insert_stock_out_delivery_order($in_id, $quantity, $code_delivery_order_id, $customer_id); 
						break;
					} else {
						$current_residue		= $quantity - $residue;
						$this->Stock_in_model->update_stock_in($in_id, 0);
						$this->Stock_out_model->insert_stock_out_delivery_order($in_id, $current_residue, $code_delivery_order_id, $customer_id);
						
						$quantity = $quantity - $residue;
					}
				}
			}
		}
		
		public function insert_stock_out_delivery_order($in_id, $quantity, $code_delivery_order_id, $customer_id)
		{
			$db_item		= array(
				'in_id' => $in_id,
				'quantity' => $quantity,
				'code_delivery_order_id' => $code_delivery_order_id,
				'customer_id' => $customer_id,
				'supplier_id' => null,
				'code_event_id' => null,
				'code_purchase_return_id' => null
			);
				
			$this->db->insert($this->table_stock_out, $db_item);
		}
}