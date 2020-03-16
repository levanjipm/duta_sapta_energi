<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_detail_model extends CI_Model {
	private $table_sales_order = 'sales_order';
		
		public $id;
		public $price_list_id;
		public $discount;
		public $quantity;
		public $sent;
		public $status;
		public $code_sales_order_id;
		
		public $name;
		public $reference;
		public $price_list;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->price_list_id			= $db_item->price_list_id;
			$this->discount					= $db_item->discount;
			$this->quantity					= $db_item->quantity;
			$this->sent						= $db_item->sent;
			$this->status					= $db_item->status;
			$this->code_sales_order_id		= $db_item->code_sales_order_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->price_list_id			= $this->price_list_id;
			$db_item->discount				= $this->discount;
			$db_item->quantity				= $this->quantity;
			$db_item->sent					= $this->sent;
			$db_item->status				= $this->status;
			$db_item->code_sales_order_id	= $this->code_sales_order_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_order_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->price_list_id			= $db_item->price_list_id;
			$stub->discount					= $db_item->discount;
			$stub->quantity					= $db_item->quantity;
			$stub->sent						= $db_item->sent;
			$stub->status					= $db_item->status;
			$stub->code_sales_order_id		= $db_item->code_sales_order_id;
			
			return $stub;
		}
		
		public function get_new_stub_from_item_price_list_db($db_item)
		{
			$stub = new Sales_order_detail_model();

			$stub->name						= $db_item->name;
			$stub->reference				= $db_item->reference;
			$stub->price_list				= $db_item->price_list;
			$stub->discount					= $db_item->discount;
			$stub->quantity					= $db_item->quantity;
			$stub->id						= $db_item->id;
			$stub->sent						= $db_item->sent;
			
			return $stub;
		}
		
		public function map_list_item_price_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_item_price_list_db($item);
			}
			return $result;
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
		
		public function create_batch($id)
		{
			$discount_array		= $this->input->post('discount');
			$quantity_array		= $this->input->post('quantity');
			foreach($discount_array as $discount){
				$batch[] = array(
					'id' => '',
					'price_list_id' => key($discount_array),
					'discount' => $discount,
					'quantity' => $quantity_array[key($discount_array)],
					'code_sales_order_id' => $id
				);
				
				next($discount_array);
			}
			
			if(!empty($this->input->post('bonus_quantity'))){
				$bonus_array		= $this->input->post('bonus_quantity');
				foreach($bonus_array as $bonus){
					$batch[] = array(
						'id' => '',
						'price_list_id' => key($bonus_array),
						'discount' => '100',
						'quantity' => $bonus,
						'code_sales_order_id' => $id
					);
					
					next($bonus_array);
				}
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$code_sales_order_id	= $id;			
			$batch 					= $this->Sales_order_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_sales_order, $batch);
		}
		
		public function show_by_code_sales_order_id($id)
		{
			$this->db->select('sales_order.id, item.name, item.reference, price_list.price_list, sales_order.quantity, sales_order.discount, sales_order.sent');
			$this->db->from('sales_order');
			$this->db->join('price_list', 'price_list.id = sales_order.price_list_id');
			$this->db->join('item', 'price_list.item_id = item.id');
			$this->db->where('sales_order.code_sales_order_id',$id);
			$query 	= $this->db->get();
			
			$items	= $query->result();
			
			return $items;
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_sales_order);
			
			$item = $query->row();
			
			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}
		
		public function check_sales_order($sales_order_array, $quantity)
		{
			$code_sales_order_array 		= array();
			$validation						= TRUE;
			$this->load->model('Sales_order_detail_model');
			foreach($sales_order_array as $sales_order){
				$key						= key($sales_order_array);
				$quantity_delivery_order 	= $quantity[$key];
				$items 						= $this->Sales_order_detail_model->show_by_id($sales_order);
				$sales_order_id 			= $items->code_sales_order_id;
				$quantity_ordered			= $items->quantity;
				$quantity_sent				= $items->sent;
				
				if($quantity_delivery_order + $quantity_sent > $quantity_ordered){
					$validation				= FALSE;
				}
				
				if(!in_array($sales_order_id, $code_sales_order_array)){
					array_push($code_sales_order_array, $sales_order_id);
				}
				
				next($sales_order_array);
			}
			
			$count	= count($code_sales_order_array);
			if($count == 1 && $validation == TRUE){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function update_sales_order_sent($sales_order_array, $quantity)
		{
			$batch = array();
			foreach($sales_order_array as $sales_order){
				$items 						= $this->Sales_order_detail_model->show_by_id($sales_order);
				$key						= key($sales_order_array);
				$quantity_delivery_order	= $quantity[$key];
				$quantity_ordered			= $items->quantity;
				$quantity_sent				= $items->sent;
				$final_quantity				= $quantity_delivery_order + $quantity_sent;
				if($final_quantity			== $quantity_ordered){
					$status					= 1;
				} else {
					$status 				= 0;
				}
				
				$batch[] = array(
					'id' => $sales_order,
					'sent' => $final_quantity,
					'status' => $status
				);
				
				next($sales_order_array);
					
			}
			
			$this->db->update_batch($this->table_sales_order,$batch, 'id'); 
		}
}