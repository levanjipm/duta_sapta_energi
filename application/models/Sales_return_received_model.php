<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_received_model extends CI_Model {
	private $table_sales_return = 'code_sales_return_received';
		
		public $id;
		public $created_by;
		public $created_date;
		public $date;
		public $name;
		public $is_confirm;	
		public $confirmed_by;
		public $is_done;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->date					= $db_item->date;
			$this->name					= $db_item->name;
			$this->is_confirm			= $db_item->is_confirm;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_done				= $db_item->is_done;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->date					= $this->date;
			$db_item->name					= $this->name;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_done				= $this->is_done;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id					= $db_item->id;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->date					= $db_item->date;
			$stub->name					= $db_item->name;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_done				= $db_item->is_done;
			
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
		
		public function insertItem($date, $document)
		{
			$this->id					= "";
			$this->created_by			= $this->session->userdata('user_id');
			$this->created_date			= date('Y-m-d');
			$this->date					= $date;
			$this->name					= $document;
			$this->is_confirm			= 0;
			$this->confirmed_by			= null;
			$this->is_done				= 0;

			$db_item 				= $this->get_db_from_stub();
			$db_result 				= $this->db->insert($this->table_sales_return, $db_item);
			
			$insert_id				= $this->db->insert_id();
			return $insert_id;
		}
	}
?>