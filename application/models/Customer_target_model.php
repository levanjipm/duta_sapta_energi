<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_target_model extends CI_Model {
	private $table_target = 'customer_target';
		
		public $id;
		public $customer_id;
		public $dateCreated;
		public $created_by;
		public $value;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->dateCreated			= $db_item->dateCreated;
			$this->created_by			= $db_item->created_by;
			$this->value				= $db_item->value;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->dateCreated			= $this->dateCreated;
			$db_item->created_by			= $this->created_by;
			$db_item->value					= $this->value;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->dateCreated			= $db_item->dateCreated;
			$stub->created_by			= $db_item->created_by;
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

		public function getItems($offset = 0, $term = "", $month, $year, $limit = 25)
		{
			$currentDate		= mktime(0,0,0,$month, 1, $year);

			$previousMonth		= date("m", strtotime("-1 month", $currentDate));
			$previousYear		= date("Y", strtotime("-1 month", $currentDate));
			if($limit != 0){
				$query		= $this->db->query("
					SELECT customer.*, COALESCE(a.value,0) AS value, COALESCE(b.value, 0) AS target, COALESCE(returnTable.value, 0) as returned, COALESCE(c.value, 0) AS previousValue, COALESCE(previousReturnTable.value, 0) AS previousReturned, COALESCE(d.value, 0) AS previousTarget, customer_area.name AS areaName
					FROM customer
					LEFT JOIN (
						SELECT SUM(invoice.value) AS value, deliveryOrderTable.customer_id 
						FROM invoice
						JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) AS deliveryOrderTable
						ON invoice.id = deliveryOrderTable.id
						WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
						GROUP BY deliveryOrderTable.customer_id
					) AS a
					ON customer.id = a.customer_id
					LEFT JOIN (
						SELECT SUM(invoice.value) AS value, deliveryOrderTable.customer_id 
						FROM invoice
						JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) AS deliveryOrderTable
						ON invoice.id = deliveryOrderTable.id
						WHERE MONTH(invoice.date) = '$previousMonth' AND YEAR(invoice.date) = '$previousYear'
						GROUP BY deliveryOrderTable.customer_id
					) AS c
					ON customer.id = a.customer_id
					LEFT JOIN
					(
						SELECT COALESCE(targetTable.value, 3000000) AS value, targetTable.customer_id
						FROM customer
						LEFT JOIN (
							SELECT customer_target.value, customer_target.customer_id FROM
							customer_target WHERE customer_target.id IN (
								SELECT MAX(id) AS id
								FROM customer_target
								WHERE MONTH(dateCreated) <= '$month' AND YEAR(dateCreated) <= '$year'
								GROUP BY customer_id
							)
						) AS targetTable
						ON targetTable.customer_id = customer.id
					) AS b
					ON b.customer_id = customer.id
					LEFT JOIN
					(
						SELECT COALESCE(targetTable.value, 3000000) AS value, targetTable.customer_id
						FROM customer
						LEFT JOIN (
							SELECT customer_target.value, customer_target.customer_id FROM
							customer_target WHERE customer_target.id IN (
								SELECT MAX(id) AS id
								FROM customer_target
								WHERE MONTH(dateCreated) <= '$previousMonth' AND YEAR(dateCreated) <= '$previousYear'
								GROUP BY customer_id
							)
						) AS targetTable
						ON targetTable.customer_id = customer.id
					) AS d
					ON d.customer_id = customer.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY code_sales_order.customer_id
					) AS returnTable
					ON returnTable.customer_id = customer.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$previousMonth' AND YEAR(code_sales_return_received.date) = '$previousYear'
						GROUP BY code_sales_order.customer_id
					) AS previousReturnTable
					ON previousReturnTable.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%'
					ORDER BY customer.name
					LIMIT $limit OFFSET $offset
				");
			} else {
				$query		= $this->db->query("
					SELECT customer.*, COALESCE(a.value,0) AS value, COALESCE(b.value, 0) AS target, COALESCE(returnTable.value, 0) as returned, COALESCE(c.value, 0) AS previousValue, COALESCE(previousReturnTable.value, 0) AS previousReturned, COALESCE(d.value, 0) AS previousTarget, customer_area.name AS areaName
					FROM customer
					LEFT JOIN (
						SELECT SUM(invoice.value) AS value, deliveryOrderTable.customer_id 
						FROM invoice
						JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) AS deliveryOrderTable
						ON invoice.id = deliveryOrderTable.id
						WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
						GROUP BY deliveryOrderTable.customer_id
					) AS a
					ON customer.id = a.customer_id
					LEFT JOIN (
						SELECT SUM(invoice.value) AS value, deliveryOrderTable.customer_id 
						FROM invoice
						JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) AS deliveryOrderTable
						ON invoice.id = deliveryOrderTable.id
						WHERE MONTH(invoice.date) = '$previousMonth' AND YEAR(invoice.date) = '$previousYear'
						GROUP BY deliveryOrderTable.customer_id
					) AS c
					ON customer.id = a.customer_id
					LEFT JOIN
					(
						SELECT COALESCE(targetTable.value, 3000000) AS value, targetTable.customer_id
						FROM customer
						LEFT JOIN (
							SELECT customer_target.value, customer_target.customer_id FROM
							customer_target WHERE customer_target.id IN (
								SELECT MAX(id) AS id
								FROM customer_target
								WHERE MONTH(dateCreated) <= '$month' AND YEAR(dateCreated) <= '$year'
								GROUP BY customer_id
							)
						) AS targetTable
						ON targetTable.customer_id = customer.id
					) AS b
					ON b.customer_id = customer.id
					LEFT JOIN
					(
						SELECT COALESCE(targetTable.value, 3000000) AS value, targetTable.customer_id
						FROM customer
						LEFT JOIN (
							SELECT customer_target.value, customer_target.customer_id FROM
							customer_target WHERE customer_target.id IN (
								SELECT MAX(id) AS id
								FROM customer_target
								WHERE MONTH(dateCreated) <= '$previousMonth' AND YEAR(dateCreated) <= '$previousYear'
								GROUP BY customer_id
							)
						) AS targetTable
						ON targetTable.customer_id = customer.id
					) AS d
					ON d.customer_id = customer.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY code_sales_order.customer_id
					) AS returnTable
					ON returnTable.customer_id = customer.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$previousMonth' AND YEAR(code_sales_return_received.date) = '$previousYear'
						GROUP BY code_sales_order.customer_id
					) AS previousReturnTable
					ON previousReturnTable.customer_id = customer.id
					JOIN customer_area ON customer_area.id = customer.area_id
					ORDER BY customer.name
				");
			}

			$result		= $query->result();
			return $result;
		}

		public function getCurrentTarget($customerId)
		{
			$query			= $this->db->query("
				SELECT customer_target.value, customer_target.dateCreated
				FROM customer_target
				JOIN (
					SELECT MAX(id) as id FROM customer_target
					WHERE customer_id = '$customerId'
				) AS targetTable
				ON customer_target.id = targetTable.id
				ORDER BY dateCreated DESC
			");
			$result = $query->row();
			return $result;
		}

		public function insertItem($customerId, $value, $date)
		{
			$this->db->where('customer_id', $customerId);
			$this->db->where('dateCreated >=', $date);
			$query			= $this->db->get($this->table_target);
			$result			= $query->num_rows();
			if($result == 0){
				$targetObject = $this->Customer_target_model->getCurrentTarget($customerId);
				if($targetObject != NULL){
					$currentTarget = $targetObject->value;
					if($value > 0 && $value != $currentTarget){
						$db_item = array(
							"id" => "",
							"customer_id" => $customerId,
							"created_by" => $this->session->userdata('user_id'),
							"dateCreated" => $date,
							"value" => $value
						);
						$this->db->insert($this->table_target, $db_item);
						return $this->db->affected_rows();
					}
				} else {
					$db_item = array(
						"id" => "",
						"customer_id" => $customerId,
						"created_by" => $this->session->userdata('user_id'),
						"dateCreated" => $date,
						"value" => $value
					);
					$this->db->insert($this->table_target, $db_item);
					return $this->db->affected_rows();
				}
			} else {
				return 0;
			}
		}

		public function getByCustomerId($customerId)
		{
			$this->db->where('customer_id', $customerId);
			$this->db->order_by('dateCreated', 'DESC');
			$query			= $this->db->get($this->table_target);
			$result			= $query->result();
			return $result;
		}

		public function deleteById($id)
		{
			$this->db->where('id', $id);
			$this->db->where('dateCreated >', date("Y-m-d"));
			$this->db->delete($this->table_target);

			return $this->db->affected_rows();
		}

		public function getByAreaId($areaId)
		{
			$query			= $this->db->query("
				SELECT customer_target.value, customer_target.customer_id, customer_target.dateCreated
				FROM customer_target
				JOIN customer ON customer_target.customer_id = customer.id
				WHERE customer.area_id = '$areaId'
				ORDER BY customer_target.customer_id ASC, customer_target.dateCreated ASC
			");

			$result		= $query->result();
			return $result;
		}
	}
?>
