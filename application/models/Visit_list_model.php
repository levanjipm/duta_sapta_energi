<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit_list_model extends CI_Model {
	private $table_visit = 'code_visit_list';
		
		public $id;
		public $date;
		public $created_by;
		public $created_date;
		public $visited_by;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $is_reported;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->date					= $db_item->date;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_delete			= $db_item->is_delete;
			$this->is_confirm			= $db_item->is_confirm;
			$this->visited_by			= $db_item->visited_by;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->is_reported			= $db_item->is_reported;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_delete				= $this->is_delete;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->visited_by			= $this->visited_by;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->is_reported			= $this->is_reported;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Salary_slip_model();
			
			$stub->id					= $db_item->id;
			$stub->date					= $db_item->date;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_delete			= $db_item->is_delete;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->visited_by			= $db_item->visited_by;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->is_reported			= $db_item->is_reported;
			
			return $stub;
		}
		
		public function map_list($items)
		{
			$result = array();
			foreach($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($item);
			}
			return $result;
		}

		public function getCustomerList($sales, $mode, $includedAreas = array(), $term = "", $offset = 0, $limit = 25)
		{
			if(count($includedAreas) > 0){
				$string		= "AND customer.area_id IN (";
				foreach($includedAreas as $includedArea){
					$string .= $includedArea;
					$string .= ",";
				}

				$string	= substr($string, 0, -1);
				$string	.= ")";
			} else {
				$string = "";
			}
			//Urgent Customer//
			//This mode implements to show customer that has not bought more than 3 months//
			if($mode == 1){
				$query			= $this->db->query("
					SELECT customer.*, a.lastOrder, a.lastVisited 
					FROM customer
					INNER JOIN(
						SELECT DISTINCT(customer.id) AS id, salesOrderTable.date AS lastOrder, visitListTable.date AS lastVisited
						FROM customer
						LEFT JOIN (
							SELECT customer.id, maxSalesOrderDateTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_sales_order.date) as date, code_sales_order.customer_id
								FROM code_sales_order
								GROUP BY code_sales_order.customer_id
							) maxSalesOrderDateTable
							ON maxSalesOrderDateTable.customer_id = customer.id
						) salesOrderTable
						ON salesOrderTable.id = customer.id
						LEFT JOIN (
							SELECT customer.id, maxVisitListTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_visit_list.date) AS date, visit_list.customer_id
								FROM code_visit_list
								JOIN visit_list
								ON visit_list.code_visit_list_id = code_visit_list.id
								WHERE code_visit_list.is_confirm = '1'
								AND visit_list.result = '1'
								GROUP BY visit_list.customer_id
							) maxVisitListTable
							ON customer.id = maxVisitListTable.customer_id
						) visitListTable
						ON visitListTable.id = customer.id
						JOIN customer_sales ON customer.id = customer_sales.customer_id
						WHERE TIMESTAMPDIFF(MONTH, salesOrderTable.date, CURDATE()) > 3
						AND customer.is_black_list = 0
						AND (
							customer.name LIKE '%$term%'
							OR customer.address LIKE '%$term%'
							OR customer.city LIKE '%$term%'
							OR customer.pic_name LIKE '%$term%'
						)
						AND customer.is_remind = 1
						AND customer_sales.sales_id = '$sales'
						$string
					) AS a
					ON a.id = customer.id
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			//Recommended Customer//
			//This mode implements to show customer that last visit is according to visit frequency//
			//Give +- 1 day error range//
			else if($mode == 2){
				$query			= $this->db->query("
					SELECT customer.*, a.lastOrder, a.lastVisited 
					FROM customer
					INNER JOIN(
						SELECT DISTINCT(customer.id) AS id, salesOrderTable.date AS lastOrder, visitListTable.date AS lastVisited
						FROM customer
						LEFT JOIN (
							SELECT customer.id, maxSalesOrderDateTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_sales_order.date) as date, code_sales_order.customer_id
								FROM code_sales_order
								GROUP BY code_sales_order.customer_id
							) maxSalesOrderDateTable
							ON maxSalesOrderDateTable.customer_id = customer.id
						) salesOrderTable
						ON salesOrderTable.id = customer.id
						LEFT JOIN (
							SELECT customer.id, maxVisitListTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_visit_list.date) AS date, visit_list.customer_id
								FROM code_visit_list
								JOIN visit_list
								ON visit_list.code_visit_list_id = code_visit_list.id
								WHERE code_visit_list.is_confirm = '1'
								AND visit_list.result = '1'
								GROUP BY visit_list.customer_id
							) maxVisitListTable
							ON customer.id = maxVisitListTable.customer_id
						) visitListTable
						ON visitListTable.id = customer.id
						JOIN customer_sales ON customer.id = customer_sales.customer_id
						WHERE DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency
						OR DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency + 1
						OR DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency - 1
						AND customer.is_black_list = 0
						AND (
							customer.name LIKE '%$term%'
							OR customer.address LIKE '%$term%'
							OR customer.city LIKE '%$term%'
							OR customer.pic_name LIKE '%$term%'
						)
						AND customer.is_remind = 1
						AND customer_sales.sales_id = '$sales'
						$string
					) AS a
					ON a.id = customer.id
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			//Inactive Customer//
			//This mode implements to show customer that have not bought//
			//Give +- 1 day error range//
			else if($mode == 3){
				$query			= $this->db->query("
					SELECT customer.*, a.lastOrder, a.lastVisited 
					FROM customer
					INNER JOIN(
						SELECT DISTINCT(customer.id) AS id, salesOrderTable.date AS lastOrder, visitListTable.date AS lastVisited
						FROM customer
						LEFT JOIN (
							SELECT customer.id, maxSalesOrderDateTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_sales_order.date) as date, code_sales_order.customer_id
								FROM code_sales_order
								GROUP BY code_sales_order.customer_id
							) maxSalesOrderDateTable
							ON maxSalesOrderDateTable.customer_id = customer.id
						) salesOrderTable
						ON salesOrderTable.id = customer.id
						LEFT JOIN (
							SELECT customer.id, maxVisitListTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_visit_list.date) AS date, visit_list.customer_id
								FROM code_visit_list
								JOIN visit_list
								ON visit_list.code_visit_list_id = code_visit_list.id
								WHERE code_visit_list.is_confirm = '1'
								AND visit_list.result = '1'
								GROUP BY visit_list.customer_id
							) maxVisitListTable
							ON customer.id = maxVisitListTable.customer_id
						) visitListTable
						ON visitListTable.id = customer.id
						JOIN customer_sales ON customer.id = customer_sales.customer_id
						WHERE customer.id NOT IN (
							SELECT DISTINCT(code_sales_order.customer_id) 
							FROM code_sales_order
							WHERE code_sales_order.is_confirm = '1'
						)
						AND customer.is_black_list = 0
						AND (
							customer.name LIKE '%$term%'
							OR customer.address LIKE '%$term%'
							OR customer.city LIKE '%$term%'
							OR customer.pic_name LIKE '%$term%'
						)
						AND customer_sales.sales_id = '$sales'
						$string
					) AS a
					ON a.id = customer.id
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			else if($mode == 4){
				$query		= $this->db->query("
					SELECT customer.*, a.lastOrder, a.lastVisited 
					FROM customer
					INNER JOIN(
						SELECT DISTINCT(customer.id) AS id, salesOrderTable.date AS lastOrder, visitListTable.date AS lastVisited
						FROM customer
						LEFT JOIN (
							SELECT customer.id, maxSalesOrderDateTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_sales_order.date) as date, code_sales_order.customer_id
								FROM code_sales_order
								GROUP BY code_sales_order.customer_id
							) maxSalesOrderDateTable
							ON maxSalesOrderDateTable.customer_id = customer.id
						) salesOrderTable
						ON salesOrderTable.id = customer.id
						LEFT JOIN (
							SELECT customer.id, maxVisitListTable.date
							FROM customer
							JOIN (
								SELECT MAX(code_visit_list.date) AS date, visit_list.customer_id
								FROM code_visit_list
								JOIN visit_list
								ON visit_list.code_visit_list_id = code_visit_list.id
								WHERE code_visit_list.is_confirm = '1'
								AND visit_list.result = '1'
								GROUP BY visit_list.customer_id
							) maxVisitListTable
							ON customer.id = maxVisitListTable.customer_id
						) visitListTable
						ON visitListTable.id = customer.id
						JOIN customer_sales ON customer.id = customer_sales.customer_id

						WHERE customer.is_black_list = 0
						AND (
							customer.name LIKE '%$term%'
							OR customer.address LIKE '%$term%'
							OR customer.city LIKE '%$term%'
							OR customer.pic_name LIKE '%$term%'
						)
						AND customer_sales.sales_id = '$sales'
						$string
					) AS a
					ON a.id = customer.id
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			$result		= $query->result();
			return $result;
		}

		public function countCustomerList($sales, $mode, $includedAreas = array(), $term = "")
		{
			if(count($includedAreas) > 0){
				$string		= "AND customer.area_id IN (";
				foreach($includedAreas as $includedArea){
					$string .= $includedArea;
					$string .= ",";
				}

				$string	= substr($string, 0, -1);
				$string	.= ")";
			} else {
				$string = "";
			}
			//Urgent Customer//
			//This mode implements to show customer that has not bought more than 3 months//
			if($mode == 1){
				$query			= $this->db->query("
					SELECT customer.id
					FROM customer
					JOIN (
						SELECT customer.id, maxSalesOrderDateTable.date
						FROM customer
						JOIN (
							SELECT MAX(code_sales_order.date) as date, code_sales_order.customer_id
							FROM code_sales_order
							GROUP BY code_sales_order.customer_id
						) maxSalesOrderDateTable
						ON maxSalesOrderDateTable.customer_id = customer.id
					) salesOrderTable
					ON salesOrderTable.id = customer.id
					JOIN customer_sales ON customer.id = customer_sales.customer_id
					WHERE TIMESTAMPDIFF(MONTH, salesOrderTable.date, CURDATE()) > 3
					AND customer.is_black_list = 0
					AND (
						customer.name LIKE '%$term%'
						OR customer.address LIKE '%$term%'
						OR customer.city LIKE '%$term%'
						OR customer.pic_name LIKE '%$term%'
					)
					AND customer_sales.sales_id = '$sales'
					$string
				");
			}
			//Recommended Customer//
			//This mode implements to show customer that last visit is according to visit frequency//
			//Give +- 1 day error range//
			else if($mode == 2){
				$query			= $this->db->query("
					SELECT customer.id
					FROM customer
					JOIN (
						SELECT customer.id, maxVisitListTable.date
						FROM customer
						JOIN (
							SELECT MAX(code_visit_list.date) AS date, visit_list.customer_id
							FROM code_visit_list
							JOIN visit_list
							ON visit_list.code_visit_list_id = code_visit_list.id
							WHERE code_visit_list.is_confirm = '1'
							AND visit_list.result = '1'
							GROUP BY visit_list.customer_id
						) maxVisitListTable
						ON customer.id = maxVisitListTable.customer_id
					) visitListTable
					ON visitListTable.id = customer.id
					JOIN customer_sales ON customer.id = customer_sales.customer_id
					WHERE DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency
					OR DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency + 1
					OR DATEDIFF(CURDATE(), visitListTable.date) = customer.visiting_frequency - 1
					AND customer.is_black_list = 0
					AND (
						customer.name LIKE '%$term%'
						OR customer.address LIKE '%$term%'
						OR customer.city LIKE '%$term%'
						OR customer.pic_name LIKE '%$term%'
					)
					AND customer_sales.sales_id = '$sales'
					$string
				");
			}
			//Inactive Customer//
			//This mode implements to show customer that have not bought//
			//Give +- 1 day error range//
			else if($mode == 3){
				$query			= $this->db->query("
					SELECT customer.id
					FROM customer
					JOIN customer_sales ON customer.id = customer_sales.customer_id
					WHERE customer.id NOT IN (
						SELECT DISTINCT(code_sales_order.customer_id) 
						FROM code_sales_order
						WHERE code_sales_order.is_confirm = '1'
					)
					AND customer.is_black_list = 0
					AND (
						customer.name LIKE '%$term%'
						OR customer.address LIKE '%$term%'
						OR customer.city LIKE '%$term%'
						OR customer.pic_name LIKE '%$term%'
					)
					AND customer_sales.sales_id = '$sales'
					$string
				");
			}
			else if($mode == 4){
				$query		= $this->db->query("
					SELECT customer.id
					FROM customer
					JOIN customer_sales ON customer.id = customer_sales.customer_id
					WHERE is_black_list = 0
					AND (
						customer.name LIKE '%$term%'
						OR customer.address LIKE '%$term%'
						OR customer.city LIKE '%$term%'
						OR customer.pic_name LIKE '%$term%'
					)
					AND customer_sales.sales_id = '$sales'
					$string
				");
			}

			$result		= $query->num_rows();
			return $result;
		}

		public function insertItem($date, $sales)
		{
			$db_item			= array(
				"id" => "",
				"date" => $date,
				"is_delete" => 0,
				"is_confirm" => 0,
				"visited_by" => $sales,
				"created_by" => $this->session->userdata('user_id'),
				"created_date" => date("Y-m-d")
			);

			$this->db->insert($this->table_visit, $db_item);
			return $this->db->insert_id();
		}

		public function getUnconfirmedItems($offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_visit_list.*, a.name AS visited_by, b.name AS created_by
				FROM code_visit_list
				JOIN (
					SELECT id, name FROM users
				) a
				ON code_visit_list.visited_by = a.id
				JOIN (
					SELECT id, name FROM users
				) b
				ON code_visit_list.created_by = b.id
				WHERE code_visit_list.is_confirm = '0' AND code_visit_list.is_delete = '0'
				ORDER BY code_visit_list.date ASC
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countUnconfirmedItems()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_visit);
			$result		= $query->num_rows();
			return $result;
		}

		public function getByid($id)
		{
			$query		= $this->db->query("
				SELECT code_visit_list.*, a.name AS visited_by, b.name AS created_by
				FROM code_visit_list
				JOIN (
					SELECT id, name FROM users
				) a
				ON code_visit_list.visited_by = a.id
				JOIN (
					SELECT id, name FROM users
				) b
				ON code_visit_list.created_by = b.id
				AND code_visit_list.id = '$id'
			");

			$result		= $query->row();
			return $result;
		}

		public function updateById($id, $status)
		{
			if($status == 1){
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->set('is_confirm', 1);
				$this->db->where('is_delete', 0);
				$this->db->where('id', $id);
			} else if($status == 0){
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->set('is_delete', 1);
				$this->db->where('is_confirm', 0);
				$this->db->where('id', $id);
			}

			$this->db->update($this->table_visit);
			$result			= $this->db->affected_rows();
			return $result;
		}

		public function getUnreportedItems($offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_visit_list.*, a.name AS visited_by, b.name AS created_by
				FROM code_visit_list
				JOIN (
					SELECT id, name FROM users
				) a
				ON code_visit_list.visited_by = a.id
				JOIN (
					SELECT id, name FROM users
				) b
				ON code_visit_list.created_by = b.id
				WHERE code_visit_list.is_confirm = '1' AND code_visit_list.is_reported = '0'
				LIMIT $limit OFFSET $offset
			");
			$result		= $query->result();
			return $result;
		}

		public function countUnreportedItems()
		{
			$this->db->where('is_reported', 0);
			$this->db->where('is_confirm', 1);

			$query		= $this->db->get($this->table_visit);
			$result		= $query->num_rows();
			return $result;
		}

		public function updateReport($id)
		{
			$this->db->set('is_reported', 1);
			$this->db->where('id', $id);
			$this->db->update($this->table_visit);
			$result		= $this->db->affected_rows();
			return $result;
		}

		public function getItems($offset = 0, $month, $year, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_visit_list.*, a.name as created_by, b.name as visited_by, c.name as confirmed_by
				FROM code_visit_list
				JOIN (
					SELECT id, name FROM users
				) a
				ON code_visit_list.created_by = a.id
				JOIN (
					SELECT id, name FROM users
				) b
				ON code_visit_list.visited_by = b.id
				LEFT JOIN (
					SELECT id, name FROM users
				) c
				ON code_visit_list.confirmed_by = c.id
				WHERE MONTH(code_visit_list.date) = '$month'
				AND YEAR(code_visit_list.date) = '$year'
				AND code_visit_list.is_delete = '0'
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countItems($month, $year, $limit = 10)
		{
			$this->db->where('is_delete', 0);
			$this->db->where('MONTH(date)', $month);
			$this->db->where('YEAR(date)', $year);
			$query		= $this->db->get($this->table_visit);
			$result		= $query->num_rows();
			return $result;
		}
	}
?>
