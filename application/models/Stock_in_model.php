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
		
		public function map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($item);
			}
			return $result;
		}
		
		public function input_from_code_good_receipt($good_receipt_array)
		{
			$this->db->insert_batch($this->table_stock_in, $good_receipt_array);
		}
		
		public function search_by_item_id($item_id)
		{
			$this->db->where('residue >', 0);
			$this->db->where('item_id', $item_id);
			$this->db->order_by('id', 'ASC');
			$query 	= $this->db->get($this->table_stock_in);
			$result	= $query->row();
			
			return $result;
			
		}
		
		public function update_price($price_array)
		{
			foreach($price_array as $price){
				$id		= key($price_array);
				$batch[] = array(
					'good_receipt_id' => $id,
					'price' => $price
				);
				
				next($price_array);
			}
			
			$this->db->update_batch($this->table_stock_in,$batch, 'good_receipt_id'); 
		}
		
		public function update_stock_in($id, $final_quantity)
		{
			$this->db->set('residue', $final_quantity);
			$this->db->where('id', $id);
			$this->db->update($this->table_stock_in);
		}
		
		public function check_stock($stock_array)
		{
			$final_stock_array		= array();
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
			
			$sufficient_stock	= TRUE;
			
			foreach($final_stock_array as $final_stock){
				$this->db->select_sum('residue');
				$this->db->where('item_id', $item_id);
				$query		= $this->db->get($this->table_stock_in);
				$result		= $query->row();
				
				$stock		= $result->residue;
				if($stock < $final_stock){
					$sufficient_stock = FALSE;
				}
			}
			
			return $sufficient_stock;
		}
		
		public function search_stock_table($offset = 0, $term = '', $limit = 25)
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
		
		public function count_stock_table($offset = 0, $term = '', $limit = 25)
		{
			$this->db->select_sum('stock_in.residue', 'stock');
			$this->db->group_by('stock_in.item_id');
			
			$query		= $this->db->get($this->table_stock_in);
			$result		= $query->num_rows();
			
			return $result;
		}
}