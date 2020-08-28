<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {
	private $table_billing = 'code_billing';
		
		public $id;
		public $date;
		public $name;
		public $created_by;
		public $is_confirm;
		public $is_delete;
		public $is_reported;
		public $confirmed_by;
		public $billed_by;
		public $reported_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->date					= $db_item->date;
			$this->name					= $db_item->name;
			$this->created_by			= $db_item->created_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->billed_by			= $db_item->billed_by;
			$this->is_reported			= $db_item->is_reported;
			$this->reported_by			= $db_item->reported_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->name					= $this->name;
			$db_item->created_by			= $this->created_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->billed_by				= $this->billed_by;
			$db_item->is_reported			= $this->is_reported;
			$db_item->reported_by			= $this->reported_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->date					= $db_item->date;
			$stub->name					= $db_item->name;
			$stub->created_by			= $db_item->created_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->billed_by			= $db_item->billed_by;
			$stub->is_reported			= $db_item->is_reported;
			$stub->reported_by			= $db_item->reported_by;
			
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

		private function generateName($date)
		{
			$name = "CB-" . date('Y', strtotime($date)) . "-" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$validation = false;
			while($validation = false){
				$this->db->where('name', $name);
				$query		= $this->db->get($this->table_billing);
				$result		= $query->num_rows();
				if($result == 0){
					$validation = true;
					break;
				} else {
					generateName($date);
				}
			}
			return $name;
		}
		
		public function insertItem($date, $collector)
		{
			$name = $this->Billing_model->generateName($date);
			$db_item = array(
				'id' => '',
				'date' => $date,
				'name' => $name,
				'created_by' => $this->session->userdata('user_id'),
				'is_confirm' => 0,
				'is_delete' => 0,
				"is_reported" => 0,
				'confirmed_by' => null,
				'billed_by' => $collector,
				'reported_by' => null
			);

			$this->db->insert($this->table_billing, $db_item);
			return $this->db->insert_id();
		}

		public function getUnconfirmedItems($offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT users.name as collector, users.image_url, code_billing.id, code_billing.date, code_billing.name
				FROM code_billing
				JOIN users ON code_billing.billed_by = users.id
				WHERE code_billing.is_confirm = '0' AND code_billing.is_delete = '0'
				LIMIT $limit OFFSET $offset
			");
			$result		= $query->result();
			return $result;
		}

		public function countUnconfirmedItems()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_billing);
			$result		= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$query		= $this->db->query("
				SELECT code_billing.*, a.name as billed_by, a.image_url, b.name as created_by, c.name as confirmed_by
				FROM code_billing
				JOIN(
					SELECT id, name, image_url FROM users
				) AS a
				ON code_billing.billed_by = a.id
				JOIN(
					SELECT id, name FROM users
				) AS b
				ON code_billing.created_by = b.id
				LEFT JOIN(
					SELECT id, name FROM users
				) AS c
				ON code_billing.confirmed_by = c.id
				WHERE code_billing.id = '$id'
			");

			$result			= $query->row();
			return $result;
		}

		public function updateById($status, $id)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
				$this->db->where('is_delete', 0);
			} else if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
				$this->db->where('is_delete', 0);
			}

			$this->db->where('id', $id);
			$this->db->update($this->table_billing);
			$result = $this->db->affected_rows();
			return $result;
		}

		public function getIncompletedItems()
		{
			$this->db->select('code_billing.*, users.name as billed_by');
			$this->db->from('code_billing');
			$this->db->join('users', 'code_billing.billed_by = users.id');

			$this->db->where('code_billing.is_reported', 0);
			$this->db->where("code_billing.is_confirm", 1);
			$this->db->order_by('code_billing.date', 'ASC');
			$this->db->order_by('code_billing.id', 'ASC');

			$query = $this->db->get();
			$result = $query->result();
			return $result;
		}

		public function updateReport($id)
		{
			$this->db->set('is_reported', 1);
			$this->db->set('reported_by', $this->session->userdata("user_id"));

			$this->db->where('id', $id);
			$this->db->where('is_confirm', 1);
			$this->db->where('is_reported', 0);
			$this->db->update($this->table_billing);
			return $this->db->affected_rows();
			
		}

		public function getYears()
		{
			$this->db->select("DISTINCT(YEAR(code_billing.date)) as year");
			$this->db->from('code_billing');
			$this->db->order_by('code_billing.date', 'asc');

			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function getArchive($offset = 0, $month, $year, $limit = 10)
		{
			$this->db->select('code_billing.*, users.name as billed_by');
			$this->db->from('code_billing');
			$this->db->join('users', 'code_billing.billed_by = users.id');

			$this->db->where("code_billing.is_delete", 0);
			$this->db->where("YEAR(date)", $year);
			$this->db->where('MONTH(date)', $month);

			$this->db->order_by('code_billing.date', 'ASC');
			$this->db->order_by('code_billing.id', 'ASC');

			$this->db->limit($limit, $offset);

			$query = $this->db->get();
			$result = $query->result();
			return $result;
		}

		public function countArchive($month, $year)
		{
			$this->db->where("YEAR(date)", $year);
			$this->db->where('MONTH(date)', $month);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_billing);
			$result		= $query->num_rows();
			return $result;
		}
}
