<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_detail_model extends CI_Model {
	private $table_return = 'purchase_return';
		
		public $id;
		public $item_id;
		public $price_list;
		public $discount;
		public $quantity;
		public $received;
		public $status;
		public $code_purchase_return_id;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id		= '';
			$this->status	= 0;
			$this->received	= 0;
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->item_id					= $db_item->item_id;
			$this->price					= $db_item->price;
			$this->quantity					= $db_item->quantity;
			$this->received					= $db_item->received;
			$this->status					= $db_item->status;
			$this->code_purchase_return_id	= $db_item->code_purchase_return_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->item_id					= $this->item_id;
			$db_item->price						= $this->price;
			$db_item->quantity					= $this->quantity;
			$db_item->received					= $this->received;
			$db_item->status					= $this->status;
			$db_item->code_purchase_return_id	= $this->code_purchase_return_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Purchase_return_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->item_id					= $db_item->item_id;
			$stub->price					= $db_item->price;
			$stub->quantity					= $db_item->quantity;
			$stub->received					= $db_item->received;
			$stub->status					= $db_item->status;
			$stub->code_purchase_return_id	= $db_item->code_purchase_return_id;
			
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

		public function insertItem($codePurchaseReturnId)
		{
			$quantityArray = $this->input->post('quantity');
			$priceArray		= $this->input->post('price');
			$batch = array();
			foreach($quantityArray as $itemId => $quantity){
				$batch[] = array(
					"id"		=> "",			
					"item_id"		=> $itemId,					
					"price"		=> $priceArray[$itemId],		
					"quantity"	=> $quantity,					
					"received"	=> 0,
					"status"		=> 0,					
					"code_purchase_return_id"	=> $codePurchaseReturnId
				);

				next($quantityArray);
			};

			$this->db->insert_batch($this->table_return, $batch);
		}

		public function getByCodeId($codePurchaseReturnId)
		{
			$query		= $this->db->query("
				SELECT item.reference, item.name, purchase_return.*
				FROM purchase_return
				JOIN item ON purchase_return.item_id = item.id
				WHERE purchase_return.code_purchase_return_id = '$codePurchaseReturnId'
			");
			$result		= $query->result();
			return $result;
		}
}
