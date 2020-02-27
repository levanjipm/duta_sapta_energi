<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt_detail_model extends CI_Model {
	private $table_good_receipt = 'good_receipt';
		
		public $id;
		public $purchase_order_id;
		public $quantity;
		public $code_good_receipt_id;
		
		public $item_id;
		public $supplier_id;
		public $date;
		
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
		
		public function get_batch_by_code_good_receipt_id($id)
		{
			$this->db->select('good_receipt.*, purchase_order.item_id, code_purchase_order.supplier_id');
			$this->db->from('good_receipt');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->where('good_receipt.code_good_receipt_id =', $id);
			
			$query	= $this->db->get();
			$result	= $query->result();
			$batch = $this->Good_receipt_detail_model->create_stock_batch($result);
			
			return $batch;
		}
		
		public function create_stock_batch($results)
		{
			foreach($results as $result){
				$quantity					= $result->quantity;
				$item_id					= $result->item_id;
				$supplier_id				= $result->supplier_id;
				$code_good_receipt_id		= $result->code_good_receipt_id;
				$batch[] = array(
					'id' => '',
					'quantity' => $quantity,
					'residue' => $quantity,
					'code_good_receipt_id' => $code_good_receipt_id,
					'supplier_id' => $supplier_id,
					'item_id' => $item_id,
				);
			}
			
			return $batch;
		}
}