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

			$query			= $this->db->query("
				SELECT customer.*, 
				COALESCE(currentInvoiceTable.value, 0) AS value, 
				COALESCE(previousInvoiceTable.value, 0) AS previousValue
				FROM customer
				LEFT JOIN (
					SELECT SUM(price_list.price_list * delivery_order.quantity * ( 100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
					FROM sales_order
					JOIN delivery_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					WHERE MONTH(code_delivery_order.date) = '$month'
					AND YEAR(code_delivery_order.date) = '$year'
					AND item.brand = '$brand'
					AND code_delivery_order.is_sent = 1
					AND invoice.is_confirm = 1
					GROUP BY code_sales_order.customer_id
				) AS currentInvoiceTable
				ON customer.id = currentInvoiceTable.customer_id
				LEFT JOIN (
					SELECT SUM(price_list.price_list * delivery_order.quantity * ( 100 - sales_order.discount) / 100) AS value, code_sales_order.customer_id
					FROM sales_order
					JOIN delivery_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order_id
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					WHERE MONTH(code_delivery_order.date) = '$previousMonth'
					AND YEAR(code_delivery_order.date) = '$previousYear'
					AND item.brand = '$brand'
					AND code_delivery_order.is_sent = 1
					AND invoice.is_confirm = 1
					GROUP BY code_sales_order.customer_id
				) AS previousInvoiceTable
				ON customer.id = previousInvoiceTable.customer_id
				LEFT JOIN (
					SELECT *
					FROM
					(
						SELECT DISTINCT customer_id 
						FROM customer_target
					) P1
					CROSS APPLY
					(
						SELECT TOP (2) customer_target.id
						FROM customer_target P2
						WHERE P1.customer_id = P2.customer_id
						ORDER BY P2.dateCreated DESC
					) foo
				) AS targetTable
				ON targetTable.customer_id = customer.id
			");

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
