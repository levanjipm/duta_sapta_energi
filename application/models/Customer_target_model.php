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

			if($limit > 0){
				$query				= $this->db->query("
					SELECT customer.*, invoiceTable.value, COALESCE(targetTable.value, 0) AS target, COALESCE(returnTable.value, 0) AS returned, customer_area.name AS areaName
					FROM customer
					JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * ( 100 - sales_order.discount ) / 100) AS value, invoiceCustomer.customer_id
						FROM delivery_order
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						JOIN (
							SELECT invoice.id, code_sales_order.customer_id
							FROM invoice
							JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
							JOIN (
								SELECT delivery_order.code_delivery_order_id, sales_order.code_sales_order_id
								FROM delivery_order
								JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
								GROUP BY sales_order.code_sales_order_id
							) deliverySales
							ON code_delivery_order.id = deliverySales.code_delivery_order_id
							JOIN code_sales_order ON deliverySales.code_sales_order_id = code_sales_order.id
						) invoiceCustomer
						ON code_delivery_order.invoice_id = invoiceCustomer.id
						JOIN customer ON customer.id = invoiceCustomer.customer_id
						WHERE MONTH(code_delivery_order.date) = $month
						AND YEAR(code_delivery_order.date) = $year
						AND code_delivery_order.is_confirm = 1
						AND item.brand = $brand
						GROUP BY invoiceCustomer.customer_id
						ORDER BY `delivery_order`.`id` ASC
					) invoiceTable
					ON customer.id = invoiceTable.customer_id
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.dateCreated <= '$currentDate'
						AND customer_target.brand = '$brand'
						GROUP BY customer_target.customer_id
					) targetTable
					ON targetTable.customer_id = customer.id
					LEFT JOIN (
						SELECT SUM(sales_return_received.quantity * sales_return.price) AS value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return.id = sales_return_received.sales_return_id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON price_list.id = sales_order.price_list_id
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE MONTH(code_sales_return_received.date) = '$month'
						AND YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = 1
						AND code_sales_return_received.is_delete = 0
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) returnTable
					ON returnTable.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE (customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%' OR customer.pic_name LIKE '%$term%')
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			} else {
				$query				= $this->db->query("
					SELECT customer.*, invoiceTable.value, COALESCE(targetTable.value, 0) AS target, COALESCE(returnTable.value, 0) AS returned, customer_area.name AS areaName
					FROM customer
					JOIN (
						SELECT SUM(delivery_order.quantity * price_list.price_list * ( 100 - sales_order.discount ) / 100) AS value, invoiceCustomer.customer_id
						FROM delivery_order
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						JOIN (
							SELECT invoice.id, code_sales_order.customer_id
							FROM invoice
							JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
							JOIN (
								SELECT delivery_order.code_delivery_order_id, sales_order.code_sales_order_id
								FROM delivery_order
								JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
								GROUP BY sales_order.code_sales_order_id
							) deliverySales
							ON code_delivery_order.id = deliverySales.code_delivery_order_id
							JOIN code_sales_order ON deliverySales.code_sales_order_id = code_sales_order.id
							WHERE invoice.is_confirm = 1
						) invoiceCustomer
						ON code_delivery_order.invoice_id = invoiceCustomer.id
						JOIN customer ON customer.id = invoiceCustomer.customer_id
						WHERE MONTH(code_delivery_order.date) = $month
						AND YEAR(code_delivery_order.date) = $year
						AND code_delivery_order.is_confirm = 1
						AND item.brand = $brand
						GROUP BY invoiceCustomer.customer_id
						ORDER BY `delivery_order`.`id` ASC
					) invoiceTable
					ON customer.id = invoiceTable.customer_id
					LEFT JOIN (
						SELECT customer_target.value, customer_target.customer_id
						FROM customer_target
						WHERE customer_target.dateCreated <= '$currentDate'
						AND customer_target.brand = '$brand'
						GROUP BY customer_target.customer_id
					) targetTable
					ON targetTable.customer_id = customer.id
					LEFT JOIN (
						SELECT SUM(sales_return_received.quantity * sales_return.price) AS value, code_sales_order.customer_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return.id = sales_return_received.sales_return_id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON price_list.id = sales_order.price_list_id
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE MONTH(code_sales_return_received.date) = '$month'
						AND YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = 1
						AND code_sales_return_received.is_delete = 0
						AND item.brand = '$brand'
						GROUP BY code_sales_order.customer_id
					) returnTable
					ON returnTable.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE (customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%' OR customer.pic_name LIKE '%$term%')
					ORDER BY customer.name ASC
				");
			}

			
			$result		= $query->result();
			return $result;
		}

		public function countItems($month, $year, $brand, $term){
			$currentDate		= date("Y-m-t", mktime(0,0,0,$month, 1, $year));
			$query	= $this->db->query("
				SELECT customer.*, invoiceTable.value, COALESCE(targetTable.value, 0) AS target, COALESCE(returnTable.value, 0) AS returned, customer_area.name AS areaName
				FROM customer
				JOIN (
					SELECT SUM(delivery_order.quantity * price_list.price_list * ( 100 - sales_order.discount ) / 100) AS value, invoiceCustomer.customer_id
					FROM delivery_order
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN (
						SELECT invoice.id, code_sales_order.customer_id
						FROM invoice
						JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
						JOIN (
							SELECT delivery_order.code_delivery_order_id, sales_order.code_sales_order_id
							FROM delivery_order
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							GROUP BY sales_order.code_sales_order_id
						) deliverySales
						ON code_delivery_order.id = deliverySales.code_delivery_order_id
						JOIN code_sales_order ON deliverySales.code_sales_order_id = code_sales_order.id
						WHERE invoice.is_confirm = 1
					) invoiceCustomer
					ON code_delivery_order.invoice_id = invoiceCustomer.id
					JOIN customer ON customer.id = invoiceCustomer.customer_id
					WHERE MONTH(code_delivery_order.date) = $month
					AND YEAR(code_delivery_order.date) = $year
					AND code_delivery_order.is_confirm = $brand
					GROUP BY invoiceCustomer.customer_id
					ORDER BY `delivery_order`.`id` ASC
				) invoiceTable
				ON customer.id = invoiceTable.customer_id
				LEFT JOIN (
					SELECT customer_target.value, customer_target.customer_id
					FROM customer_target
					WHERE customer_target.dateCreated <= '$currentDate'
					AND customer_target.brand = '$brand'
					GROUP BY customer_target.customer_id
				) targetTable
				ON targetTable.customer_id = customer.id
				LEFT JOIN (
					SELECT SUM(sales_return_received.quantity * sales_return.price) AS value, code_sales_order.customer_id
					FROM sales_return_received
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN sales_return ON sales_return.id = sales_return_received.sales_return_id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON price_list.id = sales_order.price_list_id
					JOIN item ON price_list.item_id = item.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE MONTH(code_sales_return_received.date) = '$month'
					AND YEAR(code_sales_return_received.date) = '$year'
					AND code_sales_return_received.is_confirm = 1
					AND code_sales_return_received.is_delete = 0
					AND item.brand = '$brand'
					GROUP BY code_sales_order.customer_id
				) returnTable
				ON returnTable.customer_id = customer.id
				JOIN customer_area ON customer.area_id = customer_area.id
				WHERE (customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%' OR customer.pic_name LIKE '%$term%')
				ORDER BY customer.name ASC
			");

			$result			= $query->num_rows();
			return $result;
		}

		public function getCurrentTarget($customerId)
		{
			$query			= $this->db->query("
				SELECT customer_target.value, customer_target.dateCreated, brand.name
				FROM customer_target
				LEFT JOIN (
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
