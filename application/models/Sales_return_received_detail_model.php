<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_received_detail_model extends CI_Model {
	private $table_sales_return = 'sales_return_received';
		
		public $id;
		public $code_sales_return_received_id;
		public $sales_return_id;
		public $quantity;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id								= $db_item->id;
			$this->code_sales_return_received_id	= $db_item->code_sales_return_received_id;
			$this->sales_return_id					= $db_item->sales_return_id;
			$this->quantity							= $db_item->quantity;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id								= $this->id;
			$db_item->code_sales_return_received_id		= $this->code_sales_return_received_id;
			$db_item->sales_return_id					= $this->sales_return_id;
			$db_item->quantity							= $this->quantity;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id								= $db_item->id;
			$stub->code_sales_return_received_id	= $db_item->code_sales_return_received_id;
			$stub->sales_return_id					= $db_item->sales_return_id;
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
		
		public function insertItem($itemArray, $codeSalesReturnReceivedId)
		{
			$resultArray = array();
			foreach($itemArray as $key => $item)
			{
				$result		= array(
					'id' => '',
					'code_sales_return_received_id' => $codeSalesReturnReceivedId,
					'sales_return_id' => $key,
					'quantity' => $item
				);

				array_push($resultArray, $result);
			}

			$this->db->insert_batch($this->table_sales_return, $resultArray);
		}
	}
?>