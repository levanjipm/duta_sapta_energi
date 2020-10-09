<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_benefit_model extends CI_Model {
	private $table_salary = 'salary_benefit';
		
		public $id;
		public $benefit_id;
		public $salary_slip_id;
		public $value;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->benefit_id			= $db_item->benefit_id;
			$this->salary_slip_id		= $db_item->salary_slip_id;
			$this->value				= $db_item->value;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->benefit_id			= $this->benefit_id;
			$db_item->salary_slip_id		= $this->salary_slip_id;
			$db_item->value					= $this->value;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Salary_benefit_model();
			
			$stub->id					= $db_item->id;
			$stub->benefit_id			= $db_item->benefit_id;
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
			$benefitArray = $this->input->post('benefit');
			if(count($benefitArray) > 0){
				$batch		= array();
				foreach($benefitArray as $benefitId => $benefitValue)
				{
					if($benefitValue > 0){
						$batch[] = array(
							'id' => "",
							'benefit_id' => $benefitId,
							'salary_slip_id' => $salarySlipId,
							'value' => $benefitValue
						);
					}
					next($benefitArray);
				}

				$this->db->insert_batch($this->table_salary, $batch);
			}
		}

		public function getByCodeId($salarySlipId)
		{
			$this->db->select('benefit.name, benefit.information, salary_benefit.value');
			$this->db->from('salary_benefit');
			$this->db->join('benefit', 'salary_benefit.benefit_id = benefit.id');
			$this->db->where("salary_benefit.salary_slip_id", $salarySlipId);
			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function deleteByCodeId($salarySlipId)
		{
			$this->db->where('salary_benefit.salary_slip_id', $salarySlipId);
			$this->db->delete($this->table_salary);
			$result		= $this->db->affected_rows();
			return $result;
		}
}
