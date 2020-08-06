<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petty_cash_model extends CI_Model {
	private $table_petty_cash = 'petty_cash';
		
		public $id;
		public $date;
		public $transaction;
		public $information;
		public $expense_class;
		public $bank_id;
		public $created_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->date						= $db_item->date;
			$this->transaction				= $db_item->transaction;
			$this->information				= $db_item->information;
			$this->expense_class			= $db_item->expense_class;
			$this->bank_id					= $db_item->bank_id;
			$this->created_by				= $db_item->created_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->transaction			= $this->transaction;
			$db_item->information			= $this->information;
			$db_item->expense_class			= $this->expense_class;
			$db_item->bank_id				= $this->bank_id;
			$db_item->created_by			= $this->created_by;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->transaction			= $this->transaction;
			$db_item->information			= $this->information;
			$db_item->expense_class			= $this->expense_class;
			$db_item->bank_id				= $this->bank_id;
			$db_item->created_by			= $this->created_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->date						= $db_item->date;
			$stub->transaction				= $db_item->transaction;
			$stub->information				= $db_item->information;
			$stub->expense_class			= $db_item->expense_class;
			$stub->bank_id					= $db_item->bank_id;
			$stub->created_by				= $db_item->created_by;
			
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
		
		public function insert_from_post($transaction)
		{
			$date		= $this->input->post('date');
			$class			= $this->input->post('class');
			$information	= $this->input->post('information');
			$created_by		= $this->session->userdata('user_id');
			$value			= $this->input->post('value');
			
			if($transaction == 1){
				$db_item		= array(
					'id' => '',
					'date' => $date,
					'transaction' => $transaction,
					'information' => $information,
					'expense_class' => $class,
					'bank_id' => null,
					'value' => $value,
					'created_by' => $created_by
				);
			};
			
			$this->db->insert($this->table_petty_cash, $db_item);
		}
		
		public function insert_income($bank_id, $value, $date)
		{
			$created_by		= $this->session->userdata('user_id');
			$db_item		= array(
				'id' => '',
				'date' => $date,
				'transaction' => '2',
				'information' => '',
				'expense_class' => null,
				'bank_id' => $bank_id,
				'value' => $value,
				'created_by' => $created_by
			);
			
			$this->db->insert($this->table_petty_cash, $db_item);
		}
		
		public function view_mutation($month, $year, $offset = 0, $limit = 25)
		{
			$this->db->where('MONTH(petty_cash.date)', $month);
			$this->db->where('YEAR(petty_cash.date)', $year);
			$this->db->order_by('petty_cash.date', 'asc');
			$this->db->order_by('petty_cash.id', 'asc');
			
			$query		= $this->db->get($this->table_petty_cash, $limit, $offset);
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_mutation($month, $year)
		{
			$this->db->where('MONTH(petty_cash.date)', $month);
			$this->db->where('YEAR(petty_cash.date)', $year);
			
			$query		= $this->db->get($this->table_petty_cash);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function calculateBalance($month, $year, $offset = 0)
		{
			$date		= date('Y-m-d', mktime(1,1,1,$month, 1, $year));
			$this->db->select_sum('petty_cash.value');
			$this->db->where('transaction', 1);
			$this->db->where('date <', $date);
			
			$query		= $this->db->get($this->table_petty_cash);
			$result		= $query->row();
			
			if($result->value == null){
				$value_1	= 0;
			} else {
				$value_1	= $result->value;
			}
			
			$this->db->select_sum('petty_cash.value');
			$this->db->where('transaction', 2);
			$this->db->where('date <', $date);
			
			$query		= $this->db->get($this->table_petty_cash);
			$result		= $query->row();
			
			if($result->value == null){
				$value_2	= 0;
			} else {
				$value_2	= $result->value;
			}
			
			$query		= $this->db->query("
				SELECT  SUM(value) as value FROM (
					SELECT value FROM petty_cash 
					WHERE MONTH(date) = '$month' AND YEAR(date) = '$year'
					AND transaction = '1'
					ORDER BY id
					LIMIT $offset) q
				");
			$result		= $query->row();
			$value_3	= $result->value;
			
			$query		= $this->db->query("SELECT  SUM(value) as value FROM (
				SELECT value FROM petty_cash
				WHERE MONTH(date) = '$month' AND YEAR(date) = '$year'
				AND transaction = '2'
				ORDER BY id
				LIMIT $offset) q");
			$result		= $query->row();
			$value_4	= $result->value;
			
			return $value_2 + $value_4 - $value_3 - $value_1;
		}
		
		public function show_years()
		{
			$this->db->select('DISTINCT(YEAR(date)) as year');
			$this->db->order_by('date', 'asc');
			$query		= $this->db->get($this->table_petty_cash);
			$result		= $query->result();
			
			return $result;
		}

		public function getCurrentBalance()
		{
			$query = $this->db->query("
				SELECT COALESCE(SUM(value), 0) AS value FROM petty_cash WHERE transaction = '1'
			");
			$result = $query->row();

			$expense = $result->value;

			$query = $this->db->query("
				SELECT COALESCE(SUM(value), 0) AS value FROM petty_cash WHERE transaction = '2'
			");
			$result = $query->row();
			$income = $result->value;

			return $income - $expense;
		}
}