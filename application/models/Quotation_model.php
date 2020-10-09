<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends CI_Model {
	private $table_quotation = 'code_quotation';
		
		public $id;
		public $name;
		public $date;
		public $created_by;
		public $confirmed_by;
		public $is_confirm;
		public $is_delete;
		public $customer_id;
		public $taxing;
		public $note;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->date					= $db_item->date;
			$this->created_by			= $db_item->created_by;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->customer_id			= $db_item->customer_id;
			$this->taxing				= $db_item->taxing;
			$this->note					= $db_item->note;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->date					= $this->date;
			$db_item->created_by			= $this->created_by;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->customer_id			= $this->customer_id;
			$db_item->taxing				= $this->taxing;
			$db_item->note					= $this->note;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->created_by			= $db_item->created_by;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->customer_id			= $db_item->customer_id;
			$stub->taxing				= $db_item->taxing;
			$stub->note					= $db_item->note;
			
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
			$validateion = false;
			while($validation == false){
				$name = "Q.DSE-" . date('Ym', strtotime($date)) . "-" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
				$this->db->where("name", $name);
				$query	= $this->db->get($this->table_quotation);
				$result		= $query->num_rows();
				if($result == 0){
					$validation = true;
					break;
				}
			}

			return $name;

		}
		
		public function insertItem()
		{
			$db_item = array(
				"id" => "",
				"name" => $this->Quotation_model->generateName($this->input->post('date')),
				"date" => $this->input->post('date'),
				"created_by" => $this->session->userdata('user_id'),
				"confirmed_by" => null,
				"is_confirm" => 0,
				"is_delete" => 0,
				"customer_id" => $this->input->post('customerId'),
				"taxing" => $this->input->post('taxing'),
				"note" => $this->input->post('note')
			);
			$this->db->insert($this->table_quotation, $db_item);
			return $this->db->insert_id();
		}

		public function getUnconfirmedItems($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_quotation.*, customer.name as customerName, customer.address, customer.number, customer.city, customer.rw, customer.rt, customer.block, customer.postal_code
				FROM code_quotation
				JOIN customer ON code_quotation.customer_id = customer.id
				WHERE code_quotation.id IN (
					SELECT DISTINCT(quotation.code_quotation_id) as id
					FROM quotation
					JOIN price_list ON quotation.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE item.name LIKE '%" . $this->db->escape_str($term) . "%' OR item.reference LIKE '%" . $this->db->escape_str($term) . "%'
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						JOIN customer ON code_quotation.customer_id = customer.id
						WHERE customer.name LIKE '%" . $this->db->escape_str($term) . "%' OR customer.address LIKE '%" . $this->db->escape_str($term) . "%' OR customer.city LIKE '%" . $this->db->escape_str($term) . "%'
					)
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						WHERE note LIKE '%" . $this->db->escape_str($term) . "%' OR name LIKE '%" . $this->db->escape_str($term) . "%'
					)
				)
				AND code_quotation.is_confirm = '0' AND code_quotation.is_delete = '0'
				LIMIT $limit OFFSET $offset
			");
			$result			= $query->result();
			return $result;
		}

		public function countUnconfirmedItems($term = "")
		{
			$query		= $this->db->query("
				SELECT code_quotation.id
				FROM code_quotation
				WHERE code_quotation.id IN (
					SELECT DISTINCT(quotation.code_quotation_id) as id
					FROM quotation
					JOIN price_list ON quotation.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE item.name LIKE '%" . $this->db->escape_str($term) . "%' OR item.reference LIKE '%" . $this->db->escape_str($term) . "%'
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						JOIN customer ON code_quotation.customer_id = customer.id
						WHERE customer.name LIKE '%" . $this->db->escape_str($term) . "%' OR customer.address LIKE '%" . $this->db->escape_str($term) . "%' OR customer.city LIKE '%" . $this->db->escape_str($term) . "%'
					)
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						WHERE note LIKE '%" . $this->db->escape_str($term) . "%' OR name LIKE '%" . $this->db->escape_str($term) . "%'
					)
				)
				AND code_quotation.is_confirm = '0' AND code_quotation.is_delete = '0'
			");
			$result			= $query->num_rows();
			return $result;
		}

		public function getItems($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_quotation.*, customer.name as customerName, customer.address, customer.number, customer.city, customer.rw, customer.rt, customer.block, customer.postal_code
				FROM code_quotation
				JOIN customer ON code_quotation.customer_id = customer.id
				WHERE code_quotation.id IN (
					SELECT DISTINCT(quotation.code_quotation_id) as id
					FROM quotation
					JOIN price_list ON quotation.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE item.name LIKE '%" . $this->db->escape_str($term) . "%' OR item.reference LIKE '%" . $this->db->escape_str($term) . "%'
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						JOIN customer ON code_quotation.customer_id = customer.id
						WHERE customer.name LIKE '%" . $this->db->escape_str($term) . "%' OR customer.address LIKE '%" . $this->db->escape_str($term) . "%' OR customer.city LIKE '%" . $this->db->escape_str($term) . "%'
					)
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						WHERE note LIKE '%" . $this->db->escape_str($term) . "%' OR name LIKE '%" . $this->db->escape_str($term) . "%'
					)
				)
				LIMIT $limit OFFSET $offset
			");
			$result			= $query->result();
			return $result;
		}

		public function countItems($term = "")
		{
			$query		= $this->db->query("
				SELECT code_quotation.id
				FROM code_quotation
				WHERE code_quotation.id IN (
					SELECT DISTINCT(quotation.code_quotation_id) as id
					FROM quotation
					JOIN price_list ON quotation.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE item.name LIKE '%" . $this->db->escape_str($term) . "%' OR item.reference LIKE '%" . $this->db->escape_str($term) . "%'
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						JOIN customer ON code_quotation.customer_id = customer.id
						WHERE customer.name LIKE '%" . $this->db->escape_str($term) . "%' OR customer.address LIKE '%" . $this->db->escape_str($term) . "%' OR customer.city LIKE '%" . $this->db->escape_str($term) . "%'
					)
					UNION (
						SELECT DISTINCT(code_quotation.id) as id
						FROM code_quotation
						WHERE note LIKE '%" . $this->db->escape_str($term) . "%' OR name LIKE '%" . $this->db->escape_str($term) . "%'
					)
				);
			");
			$result			= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$query			= $this->db->query("
				SELECT code_quotation.*, a.name as created_by, b.name as confirmed_by
				FROM code_quotation
				JOIN (
					SELECT name, id
					FROM users
				) AS a
				ON code_quotation.created_by = a.id
				LEFT JOIN (
					SELECT name, id
					FROM users
				) AS b
				ON code_quotation.confirmed_by = b.id
				WHERE code_quotation.id = '$id'
			");

			$result = $query->row();
			return $result;
		}

		public function updateById($status, $id)
		{
			if($status == 1){
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->set('is_confirm', 1);
				$this->db->where('is_delete', 0);
			} else if($status == 0){
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->set('is_delete', 1);
				$this->db->where('is_confirm', 0);
			}

			$this->db->where('id', $id);
			$this->db->update($this->table_quotation);
			return $this->db->affected_rows();
		}

		public function getArchiveItems($year, $month, $offset = 0, $limit = 10)
		{
			$this->db->select('code_quotation.*, customer.name as customerName, customer.number, customer.address, customer.rt, customer.rw, customer.block, customer.city, customer.postal_code, users.name as created_by');
			$this->db->from('code_quotation');
			$this->db->join('customer', 'code_quotation.customer_id = customer.id');
			$this->db->join('users', 'code_quotation.created_by = users.id');
			$this->db->where('code_quotation.is_delete', 0);
			$this->db->where("MONTH(code_quotation.date)", $month);
			$this->db->where("YEAR(code_quotation.date)", $year);
			$this->db->limit($limit, $offset);

			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countArchiveItems($year, $month)
		{
			$this->db->where("MONTH(date)", $month);
			$this->db->where("YEAR(date)", $year);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_quotation);
			$result		= $query->num_rows();
			return $result;
		}
	}
?>
