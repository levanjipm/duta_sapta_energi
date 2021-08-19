<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_target_model extends CI_Model {
	private $table_target = 'customer_target';
		
		public $id;
		public $customer_id;
		public $dateCreated;
		public $created_by;
		public $value;
		public $brand;

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
			$this->brand				= $db_item->brand;
			
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
			$db_item->brand					= $this->brand;
			
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
			$stub->brand				= $db_item->brand;
			
			return $stub;
		}
		
		public function map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($item);
				continue;
			}
			return $result;
		}

		public function getItems($month, $year, $brand, $offset = 0, $term = "", $limit = 25)
		{
			$currentDate		= date("Y-m-t", mktime(0,0,0,$month, 1, $year));
			$currentTimestamp	= mktime(0, 0, 0, $month, 1, $year);
			$previousDate		= date("Y-m-t", strtotime("-1 month", $currentTimestamp));
			$previousMonth		= date("m", strtotime("-1 month", $currentTimestamp));
			$previousYear		= date("Y", strtotime("-1 month", $currentTimestamp));

			if($limit != 0){
				$query		= $this->db->query("
					SELECT customer.*, customer_area.name AS areaName, 
					COALESCE(a.value,0) AS value, 
					COALESCE(b.value, 0) AS target, 
					COALESCE(returnTable.value, 0) as returned, 
					COALESCE(c.value, 0) AS previousValue, 
					COALESCE(previousReturnTable.value, 0) AS previousReturned, 
					COALESCE(d.value, 0) AS previousTarget
					FROM customer
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.brand = '$brand'
						AND dateCreated <= '$currentDate'
						GROUP BY customer_target.customer_id
						ORDER BY YEAR(customer_target.dateCreated) DESC, MONTH(customer_target.dateCreated) DESC
					) AS b
					ON customer.id = b.customer_id
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.brand = '$brand'
						AND dateCreated <= '$previousDate' 
						GROUP BY customer_target.customer_id
						ORDER BY YEAR(customer_target.dateCreated) DESC, MONTH(customer_target.dateCreated) DESC
					) AS d
					ON customer.id = d.customer_id
					LEFT JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
						FROM code_sales_order
						JOIN sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						JOIN delivery_order ON sales_order.id = delivery_order.sales_order_id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(code_delivery_order.date) = '$month'
						AND YEAR(code_delivery_order.date) = '$year'
						AND code_delivery_order.is_sent = 1
						AND code_delivery_order.is_confirm = 1
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) AS a
					ON a.customer_id = customer.id
					LEFT JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
						FROM code_sales_order
						JOIN sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						JOIN delivery_order ON sales_order.id = delivery_order.sales_order_id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(code_delivery_order.date) = '$previousMonth'
						AND YEAR(code_delivery_order.date) = '$previousYear'
						AND code_delivery_order.is_sent = 1
						AND code_delivery_order.is_confirm = 1
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) AS c
					ON customer.id = a.customer_id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' 
						AND MONTH(code_sales_return_received.date) = '$month' 
						AND YEAR(code_sales_return_received.date) = '$year'
						AND item.brand = '$brand'
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
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' 
						AND MONTH(code_sales_return_received.date) = '$previousMonth'
						AND YEAR(code_sales_return_received.date) = '$previousYear'
						AND item.brand = '$brand'
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
					SELECT customer.*, customer_area.name AS areaName, 
					COALESCE(a.value,0) AS value, 
					COALESCE(b.value, 0) AS target, 
					COALESCE(returnTable.value, 0) as returned, 
					COALESCE(c.value, 0) AS previousValue, 
					COALESCE(previousReturnTable.value, 0) AS previousReturned, 
					COALESCE(d.value, 0) AS previousTarget
					FROM customer
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.brand = '$brand'
						AND dateCreated <= '$currentDate'
						GROUP BY customer_target.customer_id
						ORDER BY YEAR(customer_target.dateCreated) DESC, MONTH(customer_target.dateCreated) DESC
					) AS b
					ON customer.id = b.customer_id
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.brand = '$brand'
						AND dateCreated <= '$previousDate' 
						GROUP BY customer_target.customer_id
						ORDER BY YEAR(customer_target.dateCreated) DESC, MONTH(customer_target.dateCreated) DESC
					) AS d
					ON customer.id = d.customer_id
					LEFT JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
						FROM code_sales_order
						JOIN sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						JOIN delivery_order ON sales_order.id = delivery_order.sales_order_id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(code_delivery_order.date) = '$month'
						AND YEAR(code_delivery_order.date) = '$year'
						AND code_delivery_order.is_sent = '1'
						AND code_delivery_order.is_confirm = '1'
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) AS a
					ON a.customer_id = customer.id
					LEFT JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
						FROM code_sales_order
						JOIN sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						JOIN delivery_order ON sales_order.id = delivery_order.sales_order_id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(code_delivery_order.date) = '$previousMonth'
						AND YEAR(code_delivery_order.date) = '$previousYear'
						AND code_delivery_order.is_sent = '1'
						AND code_delivery_order.is_confirm = '1'
						AND item.brand = '$brand'
					) AS c
					ON customer.id = a.customer_id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - sales_order.discount / 100)),0) as value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' 
						AND MONTH(code_sales_return_received.date) = '$month' 
						AND YEAR(code_sales_return_received.date) = '$year'
						AND item.brand = '$brand'
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
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' 
						AND MONTH(code_sales_return_received.date) = '$previousMonth'
						AND YEAR(code_sales_return_received.date) = '$previousYear'
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) AS previousReturnTable
					ON previousReturnTable.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%'
					ORDER BY customer.name
				");
			}

			$result		= $query->result();
			return $result;
		}

		public function getCurrentTarget($customerId)
		{
			$query			= $this->db->query("
				SELECT customer_target.value, customer_target.dateCreated, brand.name
				FROM customer_target
				JOIN (
					SELECT MAX(id) as id FROM customer_target
					WHERE customer_id = '$customerId'
				) AS targetTable
				ON customer_target.id = targetTable.id
				JOIN brand ON customer_target.brand = brand.id
				ORDER BY dateCreated DESC
			");
			$result = $query->row();
			return $result;
		}

		public function insertItem($customerId, $value, $brand, $date)
		{
			$this->db->where('customer_id', $customerId);
			$this->db->where('brand', $brand);
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
							"value" => $value,
							"brand" => $brand
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
						"value" => $value,
						"brand" => $brand
					);
					$this->db->insert($this->table_target, $db_item);
					return $this->db->affected_rows();
				}
			} else {
				return 0;
			}
		}

		public function getByCustomerId($customerId, $brandId = NULL)
		{
			$this->db->select('customer_target.*, brand.name');
			$this->db->from('customer_target');
			$this->db->where('customer_target.customer_id', $customerId);

			$this->db->join('brand', 'customer_target.brand = brand.id');

			if($brandId != NULL) {
				$this->db->where('customer_id', $customerId);
				$this->db->where('brand', $brandId);
			}

			$this->db->order_by('customer_target.dateCreated', 'DESC');

			$query			= $this->db->get();
			$result			= $query->result();			
			return $result;
		}

		public function deleteById($id)
		{
			$this->db->where('id', $id);
			$this->db->delete($this->table_target);

			return $this->db->affected_rows();
		}

		public function getByAreaId($areaId, $brand = NULL)
		{
			if($brand != NULL){
				$query			= $this->db->query("
					SELECT customer_target.value, customer_target.customer_id, customer_target.dateCreated
					FROM customer_target
					JOIN customer ON customer_target.customer_id = customer.id
					WHERE customer.area_id = '$areaId'
					AND customer_target.brand = '$brand'
					ORDER BY customer_target.customer_id ASC, customer_target.dateCreated ASC
				");
			} else {
				$query			= $this->db->query("
					SELECT customer_target.value, customer_target.customer_id, customer_target.dateCreated
					FROM customer_target
					JOIN customer ON customer_target.customer_id = customer.id
					WHERE customer.area_id = '$areaId'
					ORDER BY customer_target.customer_id ASC, customer_target.dateCreated ASC
				");
			}
			

			$result		= $query->result();
			return $result;
		}

		public function getByBrandId($brandId)
		{
			$query			= $this->db->query("
				SELECT customer_target.value, customer_target.customer_id, customer_target.dateCreated, customer.area_id, customer_area.name
				FROM customer_target
				JOIN customer ON customer_target.customer_id = customer.id
				JOIN customer_area ON customer.area_id = customer_area.id
				WHERE customer_target.brand = '$brandId'
				ORDER BY customer_target.customer_id ASC, 
				customer_target.dateCreated ASC
			");

			$result		= $query->result();
			return $result;
		}
	}
?>
