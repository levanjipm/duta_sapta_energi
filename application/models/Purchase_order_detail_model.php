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
		
		public $purchase_order_name;
		public $purchase_order_date;
		public $supplier_name;
		public $supplier_address;
		public $supplier_city;
		
		public $name;
		public $reference;
		
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
			
			$stub->id						= $db_item->id;
			$stub->item_id					= $db_item->item_id;
			$stub->price_list				= $db_item->price_list;
			$stub->net_price				= $db_item->net_price;
			$stub->quantity					= $db_item->quantity;
			$stub->received					= $db_item->received;
			$stub->status					= $db_item->status;
			$stub->code_purchase_order_id	= $db_item->code_purchase_order_id;
			
			return $stub;
		
		}
		
		public function get_complete_new_stub_from_db($db_item)
		{
			$stub = new Purchase_order_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->item_id					= $db_item->item_id;
			$stub->price_list				= $db_item->price_list;
			$stub->net_price				= $db_item->net_price;
			$stub->quantity					= $db_item->quantity;
			$stub->received					= $db_item->received;
			$stub->status					= $db_item->status;
			$stub->code_purchase_order_id	= $db_item->code_purchase_order_id;
			 
			$stub->purchase_order_name		= $db_item->purchase_order_name;
			$stub->purchase_order_date		= $db_item->purchase_order_date;
			$stub->supplier_name			= $db_item->supplier_name;
			$stub->supplier_address			= $db_item->supplier_address;
			$stub->supplier_city			= $db_item->supplier_city;
			
			$stub->name						= $db_item->name;
			$stub->reference				= $db_item->reference;
			
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
		
		public function complete_map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_complete_new_stub_from_db($item);
			}
			return $result;
		}
		
		public function create_batch($id)
		{
			$discount_array		= $this->input->post('discount');
			$quantity_array		= $this->input->post('quantity');
			$price_list_array	= $this->input->post('price_list');
			
			foreach($discount_array as $discount){
				$net_price		= (100 - $discount) * $price_list_array[key($discount_array)] / 100;
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
			
			$bonus_quantity		= $this->input->post('bonus_quantity');
			foreach($bonus_quantity as $quantity){
				$batch[] = array(
					'id' => '',
					'price_list' => '1',
					'item_id' => key($bonus_quantity),
					'quantity' => $quantity,
					'net_price' => '0',
					'code_purchase_order_id' => $id
				);
				
				next($bonus_quantity);
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$this->load->model('Purchase_order_detail_model');
			$batch 		= $this->Purchase_order_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_purchase_order, $batch);
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_purchase_order, 1);
			
			$item = $query->row();
			
			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}

		public function updatePurchaseOrderReceivedArray($quantity_array)
		{
			print_r($quantity_array);
			$batch = array();
			// foreach($quantity_array as $quantity){
			// 	$purchase_order_id			= key($quantity_array);
			// 	$items						= $this->Purchase_order_detail_model->getById($purchase_order_id);
			// 	$received					= $items->received;
			// 	$ordered					= $items->quantity;
			// 	$final_quantity				= $received + $quantity;
			// 	if($final_quantity			== $ordered){
			// 		$status					= 1;
			// 	} else {
			// 		$status 				= 0;
			// 	}
				
			// 	$batch[] = array(
			// 		'id' => $purchase_order_id,
			// 		'received' => $final_quantity,
			// 		'status' => $status
			// 	);
				
			// 	next($quantity_array);
					
			// }
			
			// $this->db->update_batch($this->table_purchase_order,$batch, 'id'); 
		}
		
		public function getByCodeId($id)
		{
			$this->db->select('purchase_order.*, item.name, item.reference');
			$this->db->from('purchase_order');
			$this->db->join('item', 'purchase_order.item_id = item.id');
			$this->db->where('purchase_order.code_purchase_order_id =', $id);
			$query = $this->db->get();
			$item = $query->result();
			
			return $item;
		}
		
		public function delete_from_good_receipt($good_receipt_array)
		{
			foreach($good_receipt_array as $good_receipt){
				$purchase_order_id		= $good_receipt->purchase_order_id;
				$quantity				= $good_receipt->quantity;
				$received				= $good_receipt->received;
				
				$final_quantity			= $received - $quantity;
				$batch[] = array(
					'id' => $purchase_order_id,
					'received' => $final_quantity
				);
			}
			
			$this->db->update_batch($this->table_purchase_order,$batch, 'id'); 
		}
		
		public function show_supplier_for_incomplete_purchase_orders()
		{
			$this->db->select('DISTINCT(supplier.id) as id, supplier.name, supplier.address, supplier.number, supplier.rt, supplier.rw, supplier.city, supplier.postal_code, supplier.phone_number, supplier.pic_name');
			$this->db->from('purchase_order');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id', 'inner');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('purchase_order.status', 0);
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}

		public function getPendingItems()
		{
			$query = $this->db->query(
				"SELECT SUM(quantity - received) as quantity, item_id FROM purchase_order
				WHERE status = '0'
				GROUP BY item_id"
			);

			$result = $query->result();
			return $result;
		}

		public function getPendingItemsById($item_id)
		{
			$query = $this->db->query(
				"SELECT COALESCE(SUM(quantity - received),0) as quantity FROM purchase_order
				WHERE status = '0'
				AND item_id = '$item_id'"
			);

			$result = $query->row();
			return $result;
		}
}