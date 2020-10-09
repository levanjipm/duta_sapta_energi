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
			$this->month				= $db_item->month;
			$this->year					= $db_item->year;
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
			$stub->month				= $db_item->month;
			$stub->year					= $db_item->year;
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

		public function insertItem()
		{
			$year		= $this->input->post('year');
			$month		= $this->input->post('month');
			$user		= $this->input->post('user');
			$checkResult	= $this->Salary_slip_model->checkUser($month, $year, $user);
			if($checkResult){
				$db_item = array(
					"id"				=> "",
					"user_id"			=> $user,
					"month"				=> $month,
					"year"				=> $year,
					"basic"				=> $this->input->post('basic'),
					"bonus"				=> $this->input->post('bonus'),
					"deduction"			=> $this->input->post('deduction'),
					"created_by"		=> $this->session->userdata('user_id'),
					"created_date"		=> date('Y-m-d')
				);

				$this->db->insert($this->table_salary, $db_item);
				return ($this->db->insert_id() != null)? $this->db->insert_id() : null;
			} else {
				return null;
			}
		}

		public function getItems($month, $year, $offset = 0, $limit = 10)
		{
			$this->db->select('users.name, users.image_url, salary_slip.*');
			$this->db->from('salary_slip');
			$this->db->join('users', 'salary_slip.user_id = users.id');
			$this->db->where("salary_slip.month", $month);
			$this->db->where('salary_slip.year', $year);
			$this->db->limit($limit, $offset);
			$this->db->order_by('users.name');
			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countItems($month, $year)
		{
			$this->db->where("salary_slip.month", $month);
			$this->db->where('salary_slip.year', $year);
			$query			= $this->db->get($this->table_salary);
			$result			= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$this->db->where("id", $id);
			$query		= $this->db->get($this->table_salary);
			$result		= $query->row();
			return $result;
		}

		public function deleteById($id)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $id);
			$this->db->delete($this->table_salary);
			$result		= $this->db->affected_rows();
			return $result;
		}
}
