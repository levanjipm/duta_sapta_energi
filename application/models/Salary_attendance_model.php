<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_attendance_model extends CI_Model {
	private $table_salary = 'salary_attendance';
		
		public $id;
		public $status_id;
		public $salary_slip_id;
		public $value;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->status_id			= $db_item->status_id;
			$this->salary_slip_id		= $db_item->salary_slip_id;
			$this->value				= $db_item->value;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->status_id				= $this->status_id;
			$db_item->salary_slip_id		= $this->salary_slip_id;
			$db_item->value					= $this->value;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Salary_benefit_model();
			
			$stub->id					= $db_item->id;
			$stub->status_id			= $db_item->status_id;
			$stub->salary_slip_id		= $db_item->salary_slip_id;
			$stub->value				= $db_item->value;
			
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

		public function insertItem($salarySlipId)
		{
			$attendanceArray = $this->input->post('value');
			$batch		= array();
			foreach($attendanceArray as $statusId => $attendanceValue)
			{
				if($attendanceValue > 0){
					$batch[] = array(
						'id' => "",
						'status_id' => $statusId,
						'salary_slip_id' => $salarySlipId,
						'value' => $attendanceValue
					);
				}
				next($attendanceArray);
			}

			$this->db->insert_batch($this->table_salary, $batch);
		}
}
