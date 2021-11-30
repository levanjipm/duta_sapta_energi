<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_case_detail_model extends CI_Model {
	private $table_event = 'event';
		
		public $id;
		public $item_id;
		public $quantity;
		public $price;
		public $transaction;
		public $code_event_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->item_id				= $db_item->item_id;
			$this->quantity				= $db_item->quantity;
			$this->price				= $db_item->price;
			$this->transaction			= $db_item->transaction;
			$this->code_event_id		= $db_item->code_event_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->item_id				= $this->item_id;
			$db_item->quantity				= $this->quantity;
			$db_item->price					= $this->price;
			$db_item->transaction			= $this->transaction;
			$db_item->code_event_id			= $this->code_event_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Inventory_case_model();
			
			$stub->id					= $db_item->id;
			$stub->item_id				= $db_item->item_id;
			$stub->quantity				= $db_item->quantity;
			$stub->price				= $db_item->price;
			$stub->transaction			= $db_item->transaction;
			$stub->code_event_id		= $db_item->code_event_id;
			
			return $stub;
		}
		
		public function map_list($areas)
		{
			$result = array();
			foreach ($areas as $area)
			{
				$result[] = $this->get_new_stub_from_db($area);
			}
			return $result;
		}
				
		public function insertBatchItem($code_event_id, $quantity_array, $type, $price_array = array())
		{
			$this->db->db_debug = false;
			if($type == 1){
				//Lost goods//
				foreach($quantity_array as $quantity){
					$item_id		= key($quantity_array);
					$batch[] = array(
						'id' => '',
						'item_id' => $item_id,
						'quantity' => $quantity,
						'transaction' => 'OUT',
						'code_event_id' => $code_event_id,
						'price' => 0
					);
					next($quantity_array);
				}
			} else if($type == 2){
				//Found goods//
				foreach($quantity_array as $quantity){
					$item_id		= key($quantity_array);
					$price			= $price_array[$item_id];
					$batch[] = array(
						'id' => '',
						'item_id' => $item_id,
						'quantity' => $quantity,
						'transaction' => 'IN',
						'code_event_id' => $code_event_id,
						'price' => $price
					);
					next($quantity_array);
				}
			} else if($type == 3){
				//Dematerialized goods//
				$priceDem		= $this->input->post('priceDem');
				$this->load->model('Item_model');

				$quantityPriceArray = $quantity_array;
				$totalPriceList = 0;
				$totalQuantity	= 0;
				foreach($quantityPriceArray as $quantityPrice){
					$itemId			= key($quantityPriceArray);
					$pricelist		= $this->Item_model->showById($itemId)->price_list;
					$totalPriceList	+= $quantityPrice * $pricelist;
					next($quantityPriceArray);
				}

				foreach($quantity_array as $quantity){
					$itemId			= key($quantity_array);
					$pricelist		= $this->Item_model->showById($itemId)->price_list;
					$price			= ($pricelist * $priceDem) / $totalPriceList;
					$batch[] = array(
						'id' => '',
						'item_id' => $itemId,
						'quantity' => $quantity,
						'transaction' => 'IN',
						'code_event_id' => $code_event_id,
						'price' => $price
					);
					next($quantity_array);
				}
			} else if($type == 4){
				//Materialized goods//
				foreach($quantity_array as $quantity){
					$item_id		= key($quantity_array);
					$batch[] = array(
						'id' => '',
						'item_id' => $item_id,
						'quantity' => $quantity,
						'transaction' => 'OUT',
						'code_event_id' => $code_event_id,
						'price' => 0
					);
					next($quantity_array);
				}
			}
			
			$this->db->insert_batch($this->table_event, $batch);
			return $this->db->affected_rows();
		}

		public function insertItem($codeEventId, $itemId, $itemQuantity, $transaction, $itemPrice = 0)
		{
			$this->db->db_debug = false;
			$db_item = array(
				'id' => '',
				'item_id' => $itemId,
				'quantity' => $itemQuantity,
				'transaction' => $transaction,
				'code_event_id' => $codeEventId,
				'price' => $itemPrice
			);

			$this->db->insert($this->table_event, $db_item);
			return $this->db->affected_rows();
		}
		
		public function showByCodeId($id)
		{
			$this->db->select('event.*, item.name, item.reference');
			$this->db->from('event');
			$this->db->join('item', 'item.id = event.item_id');
			$this->db->where('event.code_event_id', $id);
			$query	= $this->db->get();
			$result	= $query->result();
			
			return $result;
		}

		public function deleteByCodeId($id)
		{
			$this->db->db_debug = false;
			$this->db->where('code_event_id', $id);
			$result = $this->db->delete($this->table_event);
			return $result;
		}
		
		public function getOutBatchByCodeEventId($id)
		{
			$this->db->select('event.*, item.name, item.reference');
			$this->db->from('event');
			$this->db->join('item', 'item.id = event.item_id');
			$this->db->where('event.code_event_id', $id);
			$this->db->where('event.transaction', 'OUT');
			$query	= $this->db->get();
			$result	= $query->result();
			
			$batch = $this->Inventory_case_detail_model->convertToStockBatch($result);
			return (count($batch) != 0) ? $batch : array();
		}
		
		public function convertToStockBatch($results)
		{
			$batch		= array();
			foreach($results as $result){
				$quantity					= $result->quantity;
				$item_id					= $result->item_id;
				$transaction				= $result->transaction;
				$id							= $result->id;
				if($transaction == 'OUT'){
					$batch[] = array(
						'quantity' => $quantity,
						'item_id' => $item_id,
						'id' => $id
					);
				}
			}
			
			return $batch;
		}
		
		public function get_stock_in_batch_by_code_event_id($id)
		{
			$this->db->select('event.*, item.name, item.reference');
			$this->db->from('event');
			$this->db->join('item', 'item.id = event.item_id');
			$this->db->where('event.code_event_id', $id);
			$this->db->where('event.transaction', 'IN');
			$query	= $this->db->get();
			$result	= $query->result();
			
			$batch = $this->Inventory_case_detail_model->create_complete_stock_batch($result);
			return (count($batch) != 0) ? $batch : null;
		}
		
		public function create_complete_stock_batch($results)
		{
			$batch		= array();
			foreach($results as $result){
				$id							= $result->id;
				$quantity					= $result->quantity;
				$item_id					= $result->item_id;
				$transaction				= $result->transaction;
				$price						= $result->price;
				$batch[] = array(
					'id' => '',
					'quantity' => $quantity,
					'item_id' => $item_id,
					'residue' => $quantity,
					'customer_id' => null,
					'supplier_id' => null,
					'good_receipt_id' => null,
					'sales_return_received_id' => null,
					'event_id' => $id,
					'price' => $price,
				);
			}
			
			return $batch;
		}
}