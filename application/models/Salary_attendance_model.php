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

			if(count($batch) > 0){
				$this->db->insert_batch($this->table_salary, $batch);
			}
		}

		public function getByCodeId($salarySlipId)
		{
			$query		= $this->db->query("
				SELECT attendanceTable.count, attendance_status.name, attendance_status.description, COALESCE(salary_attendance.value, 0) AS value
				FROM salary_slip
				JOIN (
					SELECT COUNT(attendance_list.id) AS count, attendance_list.status, attendance_list.user_id, MONTH(attendance_list.date) AS month, YEAR(attendance_list.date) AS year
					FROM attendance_list
					GROUP BY user_id, status, 
					MONTH(attendance_list.date), 
					YEAR(attendance_list.date)
				) attendanceTable
				ON salary_slip.month = attendanceTable.month 
				AND salary_slip.year = attendanceTable.year
				AND salary_slip.user_id = attendanceTable.user_id
				LEFT JOIN attendance_status ON attendanceTable.status = attendance_status.id
				LEFT JOIN salary_attendance 
				ON salary_attendance.status_id = attendanceTable.status
				AND salary_attendance.salary_slip_id = salary_slip.id
				WHERE salary_slip.id = '$salarySlipId'
			");

			$result			= $query->result();
			return $result;
		}

		public function deleteByCodeId($salarySlipId)
		{
			$this->db->where('salary_attendance.salary_slip_id', $salarySlipId);
			$this->db->delete($this->table_salary);
			$result		= $this->db->affected_rows();
			return $result;
		}
}
