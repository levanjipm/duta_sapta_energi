<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_class_model extends CI_Model {
	private $table_expense = 'expense_class';
		
		public $id;
		public $name;
		public $parent_id;
		public $description;
		public $created_by;
		public $created_date;
		public $type;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->name						= $db_item->name;
			$this->parent_id				= $db_item->parent_id;
			$this->description				= $db_item->description;
			$this->created_by				= $db_item->created_by;
			$this->created_date				= $db_item->created_date;
			$this->type						= $db_item->type;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->type					= $this->type;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->type					= $this->type;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->name						= $db_item->name;
			$stub->parent_id				= $db_item->parent_id;
			$stub->description				= $db_item->description;
			$stub->created_by				= $db_item->created_by;
			$stub->created_date				= $db_item->created_date;
			$stub->type						= $db_item->type;
			
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
		
		public function getItems()
		{			
			$query	= $this->db->get($this->table_expense);
			$result	= $query->result();
			
			return $result;
		}
		
		public function insertItem()
		{
			$name			= $this->input->post('name');
			$information	= $this->input->post('information');
			
			if($this->input->post('null_check') == 'on'){				
				$db_item	= array(
					'id' => '',
					'name' => $name,
					'parent_id' => null,
					'description' => $information,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d'),
					'type' => null
				);
			} else {
				$parent_id	= $this->input->post('parent_id');
				$db_item	= array(
					'id' => '',
					'name' => $name,
					'parent_id' => $parent_id,
					'description' => $information,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d'),
					'type' => $this->input->post('type')
				);
			}
			$this->db->insert($this->table_expense, $db_item);
			return $this->db->affected_rows();
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query	= $this->db->get($this->table_expense);
			$result	= $query->row();
			
			return $result;
		}
		
		public function updateById($name, $information, $type, $id, $parent_id = null)
		{
			$this->db->set('name', $name);
			$this->db->set('description', $information);
			$this->db->set('parent_id', $parent_id);
			$this->db->set('type', $type);
			
			$this->db->where('id', $id);
			$this->db->update($this->table_expense);

			return $this->db->affected_rows();
		}

		public function deleteById($id)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $id);
			$result = $this->db->delete($this->table_expense);
			return $result;
		}

		public function getExpensesByParentClass($month, $year)
		{
			if($month == 0){
				$query		= $this->db->query("
					SELECT (overallTable.bankValue + overallTable.pettyValue) as value, expense_class.name, expense_class.description, expense_class.id
					FROM expense_class
					LEFT JOIN
					(
						SELECT COALESCE(SUM(bankTable.value),0) as bankValue, COALESCE(SUM(pettyTable.value),0) as pettyValue, expense_class.parent_id
						FROM
						expense_class
						LEFT JOIN(
							SELECT SUM(bank_transaction.value) AS value, bank_assignment.expense_id
							FROM bank_assignment
							JOIN bank_transaction ON bank_assignment.bank_id = bank_transaction.id
							JOIN expense_class ON bank_assignment.expense_id = expense_class.id
							WHERE YEAR(bank_transaction.date) = '$year'
							GROUP BY bank_assignment.expense_id
						) AS bankTable
						ON bankTable.expense_id = expense_class.id
						LEFT JOIN (
							SELECT SUM(petty_cash.value) AS value, petty_cash.expense_class
							FROM petty_cash
							WHERE  YEAR(petty_cash.date) = '$year'
							GROUP BY petty_cash.expense_class
						) AS pettyTable
						ON pettyTable.expense_class = expense_class.id
						GROUP BY expense_class.parent_id
					) overallTable
					ON expense_class.id = overallTable.parent_id
					WHERE expense_class.parent_id IS NULL
					ORDER BY expense_class.name
				");
			} else {
				$query		= $this->db->query("
					SELECT (overallTable.bankValue + overallTable.pettyValue) as value, expense_class.name, expense_class.description, expense_class.id
					FROM expense_class
					LEFT JOIN
					(
						SELECT COALESCE(SUM(bankTable.value),0) as bankValue, COALESCE(SUM(pettyTable.value),0) as pettyValue, expense_class.parent_id
						FROM
						expense_class
						LEFT JOIN(
							SELECT SUM(bank_transaction.value) AS value, bank_assignment.expense_id
							FROM bank_assignment
							JOIN bank_transaction ON bank_assignment.bank_id = bank_transaction.id
							JOIN expense_class ON bank_assignment.expense_id = expense_class.id
							WHERE MONTH(bank_transaction.date) = '$month' AND YEAR(bank_transaction.date) = '$year'
							GROUP BY bank_assignment.expense_id
						) AS bankTable
						ON bankTable.expense_id = expense_class.id
						LEFT JOIN (
							SELECT SUM(petty_cash.value) AS value, petty_cash.expense_class
							FROM petty_cash
							WHERE MONTH(petty_cash.date) = '$month' AND YEAR(petty_cash.date) = '$year'
							GROUP BY petty_cash.expense_class
						) AS pettyTable
						ON pettyTable.expense_class = expense_class.id
						GROUP BY expense_class.parent_id
					) overallTable
					ON expense_class.id = overallTable.parent_id
					WHERE expense_class.parent_id IS NULL
					ORDER BY expense_class.name
				");
			}
			
			$result = $query->result();
			return $result;
		}

		public function getExpenseByParentId($parentId, $month, $year)
		{
			if($month == 0){
				$query		= $this->db->query("
					SELECT COALESCE(overallTable.bankValue + overallTable.pettyValue, 0) as value, expense_class.name, expense_class.description, expense_class.id
					FROM expense_class
					LEFT JOIN (
						SELECT COALESCE(SUM(bankTable.value),0) as bankValue, COALESCE(SUM(pettyTable.value),0) as pettyValue, expense_class.id
						FROM
						expense_class
						LEFT JOIN(
							SELECT SUM(bank_transaction.value) AS value, bank_assignment.expense_id
							FROM bank_assignment
							JOIN bank_transaction ON bank_assignment.bank_id = bank_transaction.id
							JOIN expense_class ON bank_assignment.expense_id = expense_class.id
							WHERE YEAR(bank_transaction.date) = '$year'
							GROUP BY bank_assignment.expense_id
						) AS bankTable
						ON bankTable.expense_id = expense_class.id
						LEFT JOIN (
							SELECT SUM(petty_cash.value) AS value, petty_cash.expense_class
							FROM petty_cash
							WHERE YEAR(petty_cash.date) = '$year'
							GROUP BY petty_cash.expense_class
						) AS pettyTable
						ON pettyTable.expense_class = expense_class.id
						GROUP BY expense_class.id
					) overallTable
					ON expense_class.id = overallTable.id
					WHERE expense_class.parent_id = '$parentId'
					ORDER BY expense_class.name
				");
			} else {
				$query		= $this->db->query("
					SELECT COALESCE(overallTable.bankValue + overallTable.pettyValue, 0) as value, expense_class.name, expense_class.description, expense_class.id
					FROM expense_class
					LEFT JOIN (
						SELECT COALESCE(SUM(bankTable.value),0) as bankValue, COALESCE(SUM(pettyTable.value),0) as pettyValue, expense_class.id
						FROM
						expense_class
						LEFT JOIN(
							SELECT SUM(bank_transaction.value) AS value, bank_assignment.expense_id
							FROM bank_assignment
							JOIN bank_transaction ON bank_assignment.bank_id = bank_transaction.id
							JOIN expense_class ON bank_assignment.expense_id = expense_class.id
							WHERE MONTH(bank_transaction.date) = '$month' AND YEAR(bank_transaction.date) = '$year'
							GROUP BY bank_assignment.expense_id
						) AS bankTable
						ON bankTable.expense_id = expense_class.id
						LEFT JOIN (
							SELECT SUM(petty_cash.value) AS value, petty_cash.expense_class
							FROM petty_cash
							WHERE MONTH(petty_cash.date) = '$month' AND YEAR(petty_cash.date) = '$year'
							GROUP BY petty_cash.expense_class
						) AS pettyTable
						ON pettyTable.expense_class = expense_class.id
						GROUP BY expense_class.id
					) overallTable
					ON expense_class.id = overallTable.id
					WHERE expense_class.parent_id = '$parentId'
					ORDER BY expense_class.name
				");
			}
			
			$result = $query->result();
			return $result;
		}
}
