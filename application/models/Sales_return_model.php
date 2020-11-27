<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_model extends CI_Model {
	private $table_sales_return = 'code_sales_return';
		
		public $id;
		public $created_by;
		public $created_date;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $name;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_delete			= $db_item->is_delete;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			
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

		public function generateName($date)
		{
			$name		= "SRS-" . date('Ym', strtotime($date)) . "-" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);

			$this->db->where('name', $name);
			$query = $this->db->get($this->table_sales_return);
			$result = $query->num_rows();
			if($result == 0){
				return $name;
			} else {
				$this->Sales_return_model->generateName($date);
			}
		}
		
		public function insertItem()
		{
				$this->id			= "";
				$this->name			= $this->Sales_return_model->generateName(date('Y-m-d'));
				$this->created_by	= $this->session->userdata('user_id');	
				$this->created_date	= date('Y-m-d');
				$this->is_confirm	= 0;
				$this->is_delete 	= 0;
				$this->confirmed_by = null;

				$db_item 		= $this->get_db_from_stub($this);
				$db_result 		= $this->db->insert($this->table_sales_return, $db_item);
				$insertId		= $this->db->insert_id();

				return $insertId;
		}

		public function getUnconfirmedDocuments($offset, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT customer.*, code_sales_return.name as documentName, code_sales_return.created_date as date, users.name as created_by, code_sales_return.id
				FROM code_sales_return
				JOIN users ON code_sales_return.created_by = users.id
				JOIN (
					SELECT DISTINCT(sales_return.code_sales_return_id) as id, code_sales_order.customer_id FROM sales_return
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				) AS a
				ON code_sales_return.id = a.id
				JOIN customer ON a.customer_id = customer.id
				WHERE code_sales_return.is_confirm = '0' AND code_sales_return.is_delete = '0'
				LIMIT $limit OFFSET $offset
			");

			$result = $query->result();

			return $result;
		}

		public function countUnconfirmedDocuments()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query = $this->db->get($this->table_sales_return);
			$result = $query->num_rows();

			return $result;
		}

		public function getById($id)
		{
			$query		= $this->db->query("
				SELECT code_sales_order.customer_id, delivery_order.code_delivery_order_id as codeDeliveryOrderId, code_sales_return.name as documentName, code_sales_return.created_date as date, users.name as created_by
				FROM code_sales_return
				JOIN users ON code_sales_return.created_by = users.id
				JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_return.id = '$id'
			");

			$result = $query->row();
			return $result;
		}

		public function updateById($status, $salesReturnId)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->where('is_delete', 0);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('id', $salesReturnId);
			} else if($status == 0) {
				$this->db->set('is_delete', 1);
				$this->db->where('is_confirm', 0);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('id', $salesReturnId);
			}

			$this->db->update($this->table_sales_return);
			return $this->db->affected_rows();
		}

		public function getYears()
		{
			$query			= $this->db->query("
				SELECT DISTINCT(YEAR(created_date)) as year FROM
				code_sales_return
				ORDER BY created_date
			");
			$result		= $query->result();
			return $result;
		}

		public function getItems($month, $year, $offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_sales_return.*, customer.name as customerName, customer.city as customerCity FROM
				(
					SELECT DISTINCT(code_sales_return.id) as id, code_sales_order.customer_id
					FROM code_sales_return
					JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				) AS a
				JOIN code_sales_return ON a.id = code_sales_return.id
				JOIN customer ON a.customer_id = customer.id
				WHERE MONTH(code_sales_return.created_date) = '$month' AND YEAR(code_sales_return.created_date) = '$year'
				AND code_sales_return.is_delete = '0'
			");
			$result		= $query->result();
			return $result;
		}

		public function countItems($month, $year)
		{
			$this->db->where('MONTH(created_date)', $month);
			$this->db->where('YEAR(created_date)', $year);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_sales_return);
			$result		= $query->num_rows();
			return $result;
		}

		public function getIncompletedReturn($offset, $month, $year, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_sales_return.*, customer.name as customerName, customer.city as customerCity FROM
				(
					SELECT DISTINCT(code_sales_return.id) as id, code_sales_order.customer_id
					FROM code_sales_return
					JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE sales_return.is_done = '0'
				) AS a
				JOIN code_sales_return ON a.id = code_sales_return.id
				JOIN customer ON a.customer_id = customer.id
				WHERE MONTH(code_sales_return.created_date) = '$month' AND YEAR(code_sales_return.created_date) = '$year'
				AND code_sales_return.is_delete = '0'
				AND code_sales_return.is_confirm = '1'
				LIMIT $limit OFFSET $offset
			");
			$result		= $query->result();
			return $result;
		}

		public function countIncompletedReturn($month, $year)
		{
			$query		= $this->db->query("
				SELECT code_sales_return.id
				FROM
				(
					SELECT DISTINCT(code_sales_return.id) as id, code_sales_order.customer_id
					FROM code_sales_return
					JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE sales_return.is_done = '0'
				) AS a
				JOIN code_sales_return ON a.id = code_sales_return.id
				WHERE MONTH(code_sales_return.created_date) = '$month' AND YEAR(code_sales_return.created_date) = '$year'
				AND code_sales_return.is_delete = '0'
				AND code_sales_return.is_confirm = '1'
			");
			$result		= $query->num_rows();
			return $result;
		}
}
