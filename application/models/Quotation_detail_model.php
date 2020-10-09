<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_detail_model extends CI_Model {
	private $table_quotation = 'quotation';
		
		public $id;
		public $price_list_id;
		public $discount;
		public $quantity;
		public $code_quotation_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->price_list_id		= $db_item->price_list_id;
			$this->discount				= $db_item->discount;
			$this->quantity				= $db_item->quantity;
			$this->code_quotation_id	= $db_item->code_quotation_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->price_list_id			= $this->price_list_id;
			$db_item->discount				= $this->discount;
			$db_item->quantity				= $this->quantity;
			$db_item->code_quotation_id		= $this->code_quotation_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->price_list_id		= $db_item->price_list_id;
			$stub->discount				= $db_item->discount;
			$stub->quantity				= $db_item->quantity;
			$stub->code_quotation_id	= $db_item->code_quotation_id;
			
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
		
		public function insertItem($codeQuotationId)
		{
			$batch = array();
			$discountArray = $this->input->post('discount');
			$quantityArray = $this->input->post('quantity');
			foreach($quantityArray as $priceListId => $quantity){
				$batch[] = array(
					"id" => "",
					"quantity" => $quantity,
					"code_quotation_id" => $codeQuotationId,
					"price_list_id" => $priceListId,
					"discount" => $discountArray[$priceListId]
				);
			}

			$this->db->insert_batch($this->table_quotation, $batch);
		}

		public function getByCodeId($id)
		{
			$query			= $this->db->query("
				SELECT item.name, item.reference, price_list.price_list, quotation.discount, quotation.quantity
				FROM quotation
				JOIN price_list ON quotation.price_list_id = price_list.id
				JOIN item ON price_list.item_id = item.id
				WHERE quotation.code_quotation_id = '$id'
			");

			$result		= $query->result();
			return $result;
		}
}
