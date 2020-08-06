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
		public $good_receipt_id;
		public $sales_return_id;
		public $event_id;
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
			$this->good_receipt_id			='';
			$this->sales_return_id			='';
			$this->event_id					='';
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
			$this->good_receipt_id			= $db_item->good_receipt_id;
			$this->sales_return_id			= $db_item->sales_return_id;
			$this->event_id					= $db_item->event_id;
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
			$db_item->good_receipt_id			= $this->good_receipt_id;
			$db_item->sales_return_id			= $this->sales_return_id;
			$db_item->event_id					= $this->event_id;
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
			$stub->good_receipt_id			= $db_item->good_receipt_id;
			$stub->sales_return_id			= $db_item->sales_return_id;
			$stub->event_id					= $db_item->event_id;
			$stub->price					= $db_item->price;
			
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
		
		public function insertItemFromGoodReceipt($good_receipt_array)
		{
			$this->db->insert_batch($this->table_stock_in, $good_receipt_array);
			return $this->db->affected_rows();
		}
		
		public function input_from_code_event($event_array)
		{
			$this->db->insert_batch($this->table_stock_in, $event_array);
		}
		
		public function getResidueByItemId($item_id)
		{
			$this->db->where('residue >', 0);
			$this->db->where('item_id', $item_id);
			$this->db->order_by('id', 'ASC');
			$query 	= $this->db->get($this->table_stock_in);
			$result	= $query->row();
			
			return $result;
			
		}
		
		public function updatePrice($priceArray)
		{
			foreach($priceArray as $price){
				$id		= key($priceArray);
				$batch[] = array(
					'good_receipt_id' => $id,
					'price' => $price
				);
				
				next($priceArray);
			}
			
			$this->db->update_batch($this->table_stock_in,$batch, 'good_receipt_id'); 
		}
		
		public function updateById($id, $final_quantity)
		{
			$this->db->set('residue', $final_quantity);
			$this->db->where('id', $id);
			$this->db->update($this->table_stock_in);
		}
		
		public function checkStock($stock_array)
		{
			$final_stock_array		= array();
			if(!empty($stock_array)){
				foreach($stock_array as $stock){
					$item_id				= $stock['item_id'];
					$quantity				= $stock['quantity'];
					if(array_key_exists($item_id, $final_stock_array)){
						$final_quantity	= $final_stock_array[$item_id] + $quantity;
						$final_stock_array[$item_id] = $final_quantity;
					} else {
						$final_stock_array[$item_id] = $quantity;
					}
				}
			}
			
			$sufficient_stock	= TRUE;
			
			if(!empty($final_stock_array)){
				foreach($final_stock_array as $final_stock){
					$item_id	= key($final_stock_array);
					
					$this->db->select_sum('residue');
					$this->db->where('item_id', $item_id);
					$query		= $this->db->get($this->table_stock_in);
					$result		= $query->row();
					
					$stock		= max(0, $result->residue);
					if($stock < $final_stock){
						$sufficient_stock = FALSE;
					}
				}
			}
			
			return $sufficient_stock;
		}
		
		public function showItems($offset = 0, $term = '', $limit = 25)
		{
			$this->db->select('item.reference, item.name, item.id');
			$this->db->select_sum('stock_in.residue', 'stock');
			$this->db->from('stock_in');
			$this->db->join('item', 'stock_in.item_id = item.id');
			
			if($term != ''){
				$this->db->like('reference', $term, 'both');
				$this->db->or_like('name', $term, 'both');
			};
			
			$this->db->group_by('stock_in.item_id');
			$this->db->order_by('item.reference');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function countItems($offset = 0, $term = '', $limit = 25)
		{
			if($term != ''){
				$this->db->like('reference', $term, 'both');
				$this->db->or_like('name', $term, 'both');
			};
			$this->db->select_sum('stock_in.residue', 'stock');
			$this->db->group_by('stock_in.item_id');
			
			$query		= $this->db->get($this->table_stock_in);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function ViewCard($item_id)
		{
			$query = $this->db->query("
				SELECT stock_in.* FROM stock_in
				LEFT JOIN (
					SELECT good_receipt.id, code_good_receipt.name FROM code_good_receipt
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
				) goodReceiptTable
				ON stock_in.good_receipt_id = goodReceiptTable.id
				WHERE stock_in.item_id = '$item_id'
			");
			
			$result		= $query->result();
			
			return $result;
		}

		public function getStockByItemId($itemId)
		{
			$this->db->select("COALESCE(SUM(residue), 0) as quantity, id");
			$this->db->where('item_id', $itemId);
			
			$query = $this->db->get($this->table_stock_in);

			$result = $query->row();
			return $result;
		}

		public function deleteItemFromGoodReceipt($codeGoodReceiptId)
		{
			$query = $this->db->query("
				DELETE stock_in, good_receipt, FROM stock_in FROM stock_in
					JOIN good_receipt ON stock_in.good_receipt_id = good_receipt.id
					WHERE good_receipt.code_good_receipt_id = '$codeGoodReceiptId'
			");
			$result = $query->affected_rows();
			return $result;
		}
}