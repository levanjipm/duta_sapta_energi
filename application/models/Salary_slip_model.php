<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_slip_model extends CI_Model {
	private $table_salary = 'salary_slip';
		
		public $id;
		public $user_id;
		public $month;
		public $year;
		public $basic;
		public $bonus;
		public $deduction;
		public $created_by;
		public $created_date;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->user_id				= $db_item->user_id;
			$this->month				= $db_item->$month;
			$this->year					= $db_item->v;
			$this->basic				= $db_item->basic;
			$this->bonus				= $db_item->bonus;
			$this->deduction			= $db_item->deduction;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->user_id				= $this->user_id;
			$db_item->month					= $this->month;
			$db_item->year					= $this->year;
			$db_item->basic					= $this->basic;
			$db_item->bonus					= $this->bonus;
			$db_item->deduction				= $this->deduction;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Salary_slip_model();
			
			$stub->id					= $db_item->id;
			$stub->user_id				= $db_item->user_id;
			$stub->month				= $db_item->$month;
			$stub->year					= $db_item->v;
			$stub->basic				= $db_item->basic;
			$stub->bonus				= $db_item->bonus;
			$stub->deduction			= $db_item->deduction;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			
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

		public function checkUser($month, $year, $user_id)
		{
			$this->db->where('month', $month);
			$this->db->where('year', $year);
			$this->db->where('user_id', $user_id);
			$query		= $this->db->get($this->table_salary);
			$result		= $query->num_rows();
			if($result == 0){
				return true;
			} else {
				return false;
			}
		}
}
