<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_detail_model extends CI_Model {
	private $table_purchase_order = 'purchase_order';
		
		public $id;
		public $item_id;
		public $price_list;
		public $net_price;
		public $quantity;
		public $received;
		public $status;
		public $code_purchase_order_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->item_id					= $db_item->item_id;
			$this->price_list				= $db_item->price_list;
			$this->net_price				= $db_item->net_price;
			$this->quantity					= $db_item->quantity;
			$this->received					= $db_item->received;
			$this->status					= $db_item->status;
			$this->code_purchase_order_id	= $db_item->code_purchase_order_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->item_id					= $this->item_id;
			$db_item->price_list				= $this->price_list;
			$db_item->net_price					= $this->net_price;
			$db_item->quantity					= $this->quantity;
			$db_item->received					= $this->received;
			$db_item->status					= $this->status;
			$db_item->code_purchase_order_id	= $this->code_purchase_order_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Purchase_order_detail_model();
			
			$this->id						= $db_item->id;
			$this->item_id					= $db_item->item_id;
			$this->price_list				= $db_item->price_list;
			$this->net_price				= $db_item->net_price;
			$this->quantity					= $db_item->quantity;
			$this->received					= $db_item->received;
			$this->status					= $db_item->status;
			$this->code_purchase_order_id	= $db_item->code_purchase_order_id;
			
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
		
		public function create_batch($id)
		{
			$discount_array		= $this->input->post('discount');
			$quantity_array		= $this->input->post('quantity');
			$price_list_array	= $this->input->post('price_list');
			
			foreach($discount_array as $discount){
				$net_price		= (100 - $discount) * $price_list_array[key($discount_array)];
				$batch[] = array(
					'id' => '',
					'price_list' => $price_list_array[key($discount_array)],
					'item_id' => key($discount_array),
					'quantity' => $quantity_array[key($discount_array)],
					'net_price' => $net_price,
					'code_purchase_order_id' => $id
				);
				
				next($discount_array);
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$this->load->model('Purchase_order_detail_model');
			$batch 		= $this->Purchase_order_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_purchase_order, $batch);
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id =', $id);
			$query = $this->db->get($this->table_purchase_order, 1);
			
			$item = $query->row();
			
			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}

		public function update_purchase_order_received($quantity_array)
		{
			$this->load->model('Purchase_order_detail_model');
			$batch = array();
			foreach($quantity_array as $quantity){
				$purchase_order_id			= key($quantity_array);
				$items		= $this->Purchase_order_detail_model->show_by_id($purchase_order_id);
				$received					= $items->received;
				$ordered					= $items->quantity;
				$final_quantity				= $quantity + $received;
				if($final_quantity			== $ordered){
					$status					= 1;
				} else {
					$status 				= 0;
				}
				
				$batch[] = array(
					'id' => $purchase_order_id,
					'sent' => $final_quantity,
					'status' => $status
				);
				
				next($quantity_array);
					
			}
			
			$this->db->update_batch($this->table_purchase_order,$batch, 'id'); 
		}
}