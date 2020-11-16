<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_sent_detail_model extends CI_Model {
	private $table_purchase_return = 'purchase_return_sent';
		
		public $id;
		public $code_purchase_return_sent_id;
		public $purchase_return_id;
		public $quantity;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id								= $db_item->id;
			$this->code_purchase_return_sent_id		= $db_item->code_purchase_return_sent_id;
			$this->purchase_return_id				= $db_item->purchase_return_id;
			$this->quantity							= $db_item->quantity;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id								= $this->id;
			$db_item->code_purchase_return_sent_id		= $this->code_purchase_return_sent_id;
			$db_item->purchase_return_id				= $this->purchase_return_id;
			$db_item->quantity							= $this->quantity;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id								= $db_item->id;
			$stub->code_purchase_return_sent_id		= $db_item->code_purchase_return_sent_id;
			$stub->purchase_return_id				= $db_item->purchase_return_id;
			$stub->quantity							= $db_item->quantity;
			
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
		
		public function insertItem($itemArray, $codePurchaseReturnSentId)
		{
			$resultArray = array();
			foreach($itemArray as $key => $item)
			{
				$result		= array(
					'id' => '',
					'code_purchase_return_sent_id' => $codePurchaseReturnSentId,
					'purchase_return_id' => $key,
					'quantity' => $item
				);

				array_push($resultArray, $result);
			}

			$this->db->insert_batch($this->table_purchase_return, $resultArray);
		}

		public function getByCodeId($codePurchaseReturnSentId)
		{
			$query		= $this->db->query("
				SELECT item.reference, item.name, purchase_return.price, purchase_return_sent.quantity, purchase_return_sent.id, purchase_return.id as purchaseReturnId, item.id AS item_id
				FROM purchase_return_sent
				JOIN purchase_return ON purchase_return.id = purchase_return_sent.purchase_return_id
				JOIN item ON purchase_return.item_id = item.id
				WHERE purchase_return_sent.code_purchase_return_sent_id = '$codePurchaseReturnSentId'
			");

			$result = $query->result();
			return $result;
		}
	}
?>
