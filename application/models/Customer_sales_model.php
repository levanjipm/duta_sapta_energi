<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_sales_model extends CI_Model {
	private $table_target = 'customer_sales';
		
		public $id;
		public $customer_id;
		public $sales_id;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->sales_id				= $db_item->sales_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->sales_id				= $this->sales_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->sales_id				= $db_item->sales_id;
			
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
	}
?>
