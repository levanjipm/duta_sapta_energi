<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt_detail_model extends CI_Model {
	private $table_good_receipt = 'good_receipt';
		
		public $id;
		public $purchase_order_id;
		public $quantity;
		public $code_good_receipt_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->purchase_order_id		= $db_item->purchase_order_id;
			$this->quantity					= $db_item->quantity;
			$this->code_good_receipt_id		= $db_item->code_good_receipt_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->purchase_order_id			= $this->purchase_order_id;
			$db_item->quantity					= $this->quantity;
			$db_item->code_good_receipt_id		= $this->code_good_receipt_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Purchase_order_detail_model();
			
			$this->id						= $db_item->id;
			$this->purchase_order_id		= $db_item->purchase_order_id;
			$this->quantity					= $db_item->quantity;
			$this->code_good_receipt_id		= $db_item->code_good_receipt_id;
			
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
			$quantity_array		= $this->input->post('quantity');
			
			foreach($quantity_array as $quantity){
				$purchase_id		= key($quantity_array);
				$batch[] = array(
					'id' => '',
					'purchase_order_id' => $purchase_id,
					'quantity' => $quantity,
					'code_good_receipt_id' => $id
				);
				
				next($quantity_array);
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$this->load->model('Good_receipt_detail_model');
			$batch 		= $this->Good_receipt_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_good_receipt, $batch);
		}
}