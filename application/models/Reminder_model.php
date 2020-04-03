<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reminder_model extends CI_Model {
	private $table_reminder = 'reminder_customer';
		
		public $id;
		public $customer_id;
		public $invoice_id;
		public $type;
		public $date_created;
		public $created_by;
		public $confirmed_by;
		public $is_confirm;
		public $date_effective;
		public $date_end;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->invoice_id			= $db_item->invoice_id;
			$this->type					= $db_item->type;
			$this->date_created			= $db_item->date_created;
			$this->created_by			= $db_item->created_by;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->date_effective		= $db_item->date_effective;
			$this->date_end				= $db_item->date_end;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->invoice_id			= $this->invoice_id;
			$db_item->type					= $this->type;
			$db_item->date_created			= $this->date_created;
			$db_item->created_by			= $this->created_by;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->date_effective		= $this->date_effective;
			$db_item->date_end				= $this->date_end;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->invoice_id			= $db_item->invoice_id;
			$stub->type					= $db_item->type;
			$stub->date_created			= $db_item->date_created;
			$stub->created_by			= $db_item->created_by;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->date_effective		= $db_item->date_effective;
			$stub->date_end				= $db_item->date_end;
			
			return $stub;
		}
		
		public function map_list($reminders)
		{
			$result = array();
			foreach ($reminders as $reminder)
			{
				$result[] = $this->get_new_stub_from_db($reminder);
			}
			return $result;
		}
		
		public function show_status_by_customer_id($customer_id)
		{
			$this->db->select('MAX(type) as type');
			$this->db->where('customer_id', $customer_id);
			$this->db->where('date_end <=', date('Y-m-d'));
			$query		= $this->db->get($this->table_reminder);
			$result		= $query->row();
			
			return $result;
		}
}