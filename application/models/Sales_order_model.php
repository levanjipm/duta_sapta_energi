<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_model extends CI_Model {
		private $table_sales_order = 'code_sales_order';
		
		public $id;
		public $customer_id;
		public $name;
		public $date;
		public $seller;
		public $taxing;
		public $is_confirm;
		public $guid;
		public $invoicing_method;
		public $created_by;
		public $note;
		
		public $customer_name;
		public $customer_address;
		public $customer_city;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->name					= $db_item->name;
			$this->date					= $db_item->date;
			$this->seller				= $db_item->seller;
			$this->taxing				= $db_item->taxing;
			$this->is_confirm			= $db_item->is_confirm;
			$this->guid					= $db_item->guid;
			$this->invoicing_method		= $db_item->invoicing_method;
			$this->created_by			= $db_item->created_by;
			$this->note					= $db_item->note;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->name					= $this->name;
			$db_item->date					= $this->date;
			$db_item->seller				= $this->seller;
			$db_item->taxing				= $this->taxing;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->guid					= $this->guid;
			$db_item->invoicing_method		= $this->invoicing_method;
			$db_item->created_by			= $this->created_by;
			$db_item->note					= $this->note;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db_with_customer($db_item)
		{
			$stub = new Sales_order_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->seller				= $db_item->seller;
			$stub->taxing				= $db_item->taxing;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->guid					= $db_item->guid;
			$stub->invoicing_method		= $db_item->invoicing_method;
			$stub->created_by			= $db_item->created_by;
			$stub->note					= $db_item->note;
			
			$stub->customer_name 		= $db_item->customer_name;
			$stub->customer_address 	= $db_item->customer_address;
			$stub->customer_city 		= $db_item->customer_city;
			return $stub;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_order_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->seller				= $db_item->seller;
			$stub->taxing				= $db_item->taxing;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->guid					= $db_item->guid;
			$stub->invoicing_method		= $db_item->invoicing_method;
			$stub->note					= $db_item->note;
			return $stub;
		}
		
		public function map_list_with_customer($sales_orders)
		{
			$result = array();
			foreach ($sales_orders as $sales_order)
			{
				$result[] = $this->get_new_stub_from_db_with_customer($sales_order);
			}
			return $result;
		}
		
		public function map_list($sales_orders)
		{
			$result = array();
			foreach ($sales_orders as $sales_order)
			{
				$result[] = $this->get_new_stub_from_db($sales_order);
			}
			return $result;
		}
		
		public function generateName($date)
		{
			$name = date('Ym', strtotime($date)) . '.' . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$this->db->where('name =',$name);
			$query = $this->db->get($this->table_sales_order);
			$item = $query->num_rows();
			
			if($item != 0){
				$this->Sales_order_model->generateName($date);
			};
			
			return $name;
		}

		public function getIncompleteSalesOrder($offset, $term = "")
		{
			$query = $this->db->query("
				SELECT code_sales_order.*, sellerTable.name as seller, salesOrderTable.quantity, salesOrderTable.sent
				FROM code_sales_order
				LEFT JOIN (
					SELECT id, name FROM users
				) as sellerTable
				ON code_sales_order.seller = sellerTable.id
				JOIN (
					SELECT sales_order.code_sales_order_id, SUM(sales_order.quantity) AS quantity, SUM(IF(sales_order.status = 1, sales_order.quantity, sales_order.sent)) as sent
					FROM sales_order
					GROUP BY sales_order.code_sales_order_id
				) as salesOrderTable
				ON salesOrderTable.code_sales_order_id = code_sales_order.id
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order WHERE status = '0'
				) as incompletedSalesOrderTable
				ON code_sales_order.id = incompletedSalesOrderTable.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.is_confirm = '1'
				AND code_sales_order.id NOT IN (
					SELECT code_sales_order_close_request.id
					FROM code_sales_order_close_request
					WHERE is_confirm = '1'	
				)
				AND (code_sales_order.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR customer.city LIKE '%$term%')
				LIMIT 10 OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countIncompleteSalesOrder($term)
		{
			$query = $this->db->query("
				SELECT code_sales_order.* FROM code_sales_order 
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order
					WHERE status = '0' 	
				) as a
				ON a.id = code_sales_order.id
				WHERE code_sales_order.is_confirm = '1'
				AND code_sales_order.name LIKE '%$term%'
			");

			$result = $query->num_rows();
			return $result;
		}

		public function getIncompleteSalesOrderDelivery($offset, $term = "", $areaArray)
		{
			$inString = "(";
			foreach($areaArray as $area){
				$inString .= "'" . $area . "',";
			}

			$inString = substr($inString, 0, -1);
			$inString .= ")";
			
			$query = $this->db->query("
				SELECT code_sales_order.*, sellerTable.name as seller, salesOrderTable.quantity, salesOrderTable.sent
				FROM code_sales_order
				LEFT JOIN (
					SELECT id, name FROM users
				) as sellerTable
				ON code_sales_order.seller = sellerTable.id
				JOIN (
					SELECT sales_order.code_sales_order_id, SUM(sales_order.quantity) AS quantity, SUM(IF(sales_order.status = 1, sales_order.quantity, sales_order.sent)) as sent
					FROM sales_order
					GROUP BY sales_order.code_sales_order_id
				) as salesOrderTable
				ON salesOrderTable.code_sales_order_id = code_sales_order.id
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order WHERE status = '0'
				) as incompletedSalesOrderTable
				ON code_sales_order.id = incompletedSalesOrderTable.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.is_confirm = '1'
				AND code_sales_order.id NOT IN (
					SELECT code_sales_order_close_request.id
					FROM code_sales_order_close_request
					WHERE is_confirm = '1'	
				)
				AND (code_sales_order.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR customer.city LIKE '%$term%')
				AND customer.area_id IN $inString
				LIMIT 10 OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countIncompleteSalesOrderDelivery($term = "", $areaArray)
		{
			$inString = "(";
			foreach($areaArray as $area){
				$inString .= "'" . $area . "',";
			}

			$inString = substr($inString, 0, -1);
			$inString .= ")";

			$query = $this->db->query("
				SELECT code_sales_order.* 
				FROM code_sales_order 
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order
					WHERE status = '0' 	
				) as a
				ON a.id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.is_confirm = '1'
				AND code_sales_order.name LIKE '%$term%'
				AND (code_sales_order.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR customer.city LIKE '%$term%')
				AND code_sales_order.id NOT IN (
					SELECT code_sales_order_close_request.id
					FROM code_sales_order_close_request
					WHERE is_confirm = '1'	
				)
				AND customer.area_id IN $inString
			");

			$result = $query->num_rows();
			return $result;
		}

		public function getUnclosedSalesOrders($offset, $term = "")
		{
			$query = $this->db->query("
				SELECT code_sales_order.*, sellerTable.name as seller, salesOrderTable.quantity, salesOrderTable.sent
				FROM code_sales_order
				LEFT JOIN (
					SELECT id, name 
					FROM users
				) AS sellerTable
				ON code_sales_order.seller = sellerTable.id
				JOIN (
					SELECT sales_order.code_sales_order_id, SUM(sales_order.quantity) AS quantity, IF(sales_order.status = 1, SUM(sales_order.quantity), SUM(sales_order.sent)) as sent
					FROM sales_order
					GROUP BY sales_order.code_sales_order_id
				) as salesOrderTable
				ON salesOrderTable.code_sales_order_id = code_sales_order.id
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order WHERE status = '0'
				) as incompletedSalesOrderTable
				ON code_sales_order.id = incompletedSalesOrderTable.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.is_confirm = '1'
				AND (code_sales_order.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR customer.city LIKE '%$term%')
				AND code_sales_order.id NOT IN (
					SELECT DISTINCT(code_sales_order_close_request.code_sales_order_id) AS id
					FROM code_sales_order_close_request
					WHERE is_approved IS NULL
				);
			");

			$result = $query->result();
			return $result;
		}

		public function countUnclosedSalesOrders($term = "")
		{
			$query = $this->db->query("
				SELECT code_sales_order.id
				FROM code_sales_order
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order WHERE status = '0'
				) as incompletedSalesOrderTable
				ON code_sales_order.id = incompletedSalesOrderTable.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.is_confirm = '1'
				AND (code_sales_order.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR customer.city LIKE '%$term%')
				AND code_sales_order.id NOT IN (
					SELECT DISTINCT(code_sales_order_close_request.code_sales_order_id) AS id
					FROM code_sales_order_close_request
					WHERE is_approved IS NULL
				);
			");

			$result = $query->num_rows();
			return $result;
		}
		
		public function getById($id)
		{
			$query			= $this->db->query("
				SELECT code_sales_order.*, sellerTable.name as seller, creatorTable.name as creator, confirmTable.name as confirmed_by
				FROM code_sales_order
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) AS id FROM sales_order
				) AS salesOrderTable
				ON code_sales_order.id = salesOrderTable.id
				LEFT JOIN (
					SELECT users.id, users.name FROM users
				) AS sellerTable
				ON code_sales_order.seller = sellerTable.id
				JOIN (
					SELECT users.id, users.name FROM users
				) AS creatorTable
				LEFT JOIN (
					SELECT users.id, users.name FROM users
				) AS confirmTable
				ON code_sales_order.confirmed_by = confirmTable.id
				WHERE code_sales_order.id = '$id';
			");

			$items 		= $query->row();
			return $items;
		}
		
		public function getByDeliveryOrderId($deliveryOrderId)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*');
			$this->db->from('code_sales_order');
			$this->db->join('sales_order', 'code_sales_order.id = sales_order.code_sales_order_id','inner');
			$this->db->join('delivery_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->where('code_delivery_order.id =',$deliveryOrderId);
			
			$query 		= $this->db->get();
			
			$result 		= $query->result();
			return $result;
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_sales_order);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function insertItem()
		{
			$this->load->model('Sales_order_model');
			
			$guid					= $this->input->post('guid');
			$check_guid				= $this->Sales_order_model->check_guid($guid);
			
			if($check_guid){
				$sales_order_date		= $this->input->post('sales_order_date');
				$sales_order_name 		= $this->Sales_order_model->generateName($sales_order_date);
				if($this->input->post('sales_order_seller') == ''){
					$sales_order_seller	= NULL;
				} else {
					$sales_order_seller	= $this->input->post('sales_order_seller');
				}
				
				$this->id				= '';
				$this->customer_id		= $this->input->post('customer_id');
				$this->name				= $sales_order_name;
				$this->date				= $sales_order_date;
				$this->seller			= $sales_order_seller;
				$this->taxing			= $this->input->post('taxing');
				$this->is_confirm		= '';
				$this->guid				= $guid;
				$this->invoicing_method	= $this->input->post('method');
				$this->created_by		= $this->session->userdata('user_id');
				$this->note				= $this->input->post('note');
				
				$db_item 				= $this->get_db_from_stub();
				$db_result 				= $this->db->insert($this->table_sales_order, $db_item);
				
				$insert_id				= $this->db->insert_id();
				
				return $insert_id;
			} else {
				return NULL;
			}
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
		
		public function show_uncompleted_sales_order($offset = 0, $filter = '', $limit = 25)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*, customer.name as customer_name, customer.name as customer_name, customer.address as customer_address, customer.city as customer_city, customer.rt as customer_rt, customer.rw as customer_rw, customer.block as customer_block, customer.postal_code as customer_postal_code, users.name as seller');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->join('users', 'code_sales_order.seller = users.id', 'left');
			
			if($filter != ''){
				$this->db->like('customer.name', $filter,'both');
				$this->db->or_like('customer.address', $filter,'both');
				$this->db->or_like('code_sales_order.name', $filter, 'both');
			}
			
			$this->db->where('sales_order.status', 0);
			$this->db->where('code_sales_order.is_confirm', 1);
			$this->db->where('code_sales_order.is_delete', 0);
			$this->db->order_by('code_sales_order.date');
			$this->db->limit($limit, $offset);
			$query 		= $this->db->get();
			
			$items 		= $query->result();
			return $items;
		}
		
		public function countIncompletedSalesOrder($filter = '')
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			
			if($filter != ''){
				$this->db->like('customer.name', $filter,'both');
				$this->db->or_like('customer.address', $filter,'both');
				$this->db->or_like('code_sales_order.name', $filter, 'both');
			}
			
			$this->db->where('sales_order.status', 0);
			$this->db->where('code_sales_order.is_confirm', 1);
			$this->db->where('code_sales_order.is_delete', 0);
			
			$query 		= $this->db->get();
			
			$items 		= $query->num_rows();
			return $items;
		}
		
		public function getUnconfirmedSalesOrder($offset = 0, $filter = '', $limit = 25)
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id, code_sales_order.*, customer.name as customer_name, customer.address as customer_address, customer.city as customer_city, customer.city as customer_city, customer.rt as customer_rt, customer.rw as customer_rw, customer.block as customer_block, customer.postal_code as customer_postal_code, customer.number as customer_number, users.name as seller');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('users', 'code_sales_order.seller = users.id', 'left');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			
			$this->db->where('sales_order.status', 0);
			$this->db->where('code_sales_order.is_confirm', 0);
			$this->db->where('code_sales_order.is_delete', 0);
			if($filter != ''){
				$this->db->like('customer.name', $filter,'both');
				$this->db->or_like('customer.address', $filter,'both');
				$this->db->or_like('code_sales_order.name', $filter, 'both');
			}
			$this->db->order_by('code_sales_order.date');
			$this->db->limit($limit, $offset);
			$query 		= $this->db->get();
			
			$items 		= $query->result();
			return $items;
		}
		
		public function countUnconfirmedSalesOrder($filter = '')
		{
			$this->db->select('DISTINCT(sales_order.code_sales_order_id) as id');
			$this->db->from('sales_order');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			
			if($filter != ''){
				$this->db->like('customer.name', $filter,'both');
				$this->db->or_like('customer.address', $filter,'both');
				$this->db->or_like('code_sales_order.name', $filter, 'both');
			}
			
			$this->db->where('sales_order.status', 0);
			$this->db->where('code_sales_order.is_confirm', 0);
			$this->db->where('code_sales_order.is_delete', 0);
			$this->db->order_by('code_sales_order.date');
			$query 		= $this->db->get();
			
			$items 		= $query->num_rows();
			return $items;
		}
		
		public function updateById($status, $sales_order_id)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
				$this->db->where('is_delete', 0);
			} else if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->where('is_confirm', 0);
			}
			
			$this->db->where('id', $sales_order_id);
			$this->db->update($this->table_sales_order);

			return $this->db->affected_rows();
		}
		
		public function showItems($year, $month, $offset = 0, $term = '', $limit = 25)
		{
			$this->db->select('code_sales_order.*, customer.name as customer_name, customer.address, customer.city, customer.number, customer.rt, customer.rw, customer.block, customer.pic_name, users.name as seller, COALESCE(code_sales_order_close_request.is_approved, 0) as is_approved, code_sales_order_close_request.approved_date');
			$this->db->from('code_sales_order');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->join('users', 'code_sales_order.seller = users.id', 'left');
			$this->db->join('code_sales_order_close_request', 'code_sales_order_close_request.code_sales_order_id = code_sales_order.id', 'left');
			$this->db->where('MONTH(code_sales_order.date)',$month);
			$this->db->where('YEAR(code_sales_order.date)',$year);
			$this->db->where('code_sales_order.is_delete', 0);
			if($term != ''){
				$this->db->like('code_sales_order.name', $term, 'both');
				$this->db->or_like('customer.name', $term, 'both');
				$this->db->or_like('customer.address', $term, 'both');
				$this->db->or_like('customer.city', $term, 'both');
			}
			
			$this->db->order_by('code_sales_order.date', 'asc');
			$this->db->order_by('code_sales_order.id', 'asc');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function countItems($year, $month, $term = '')
		{
			$this->db->select('code_sales_order.id');
			$this->db->from('code_sales_order');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('MONTH(code_sales_order.date)',$month);
			$this->db->where('YEAR(code_sales_order.date)',$year);
			$this->db->where('code_sales_order.is_delete', 0);
			if($term != ''){
				$this->db->like('code_sales_order.name', $term, 'both');
				$this->db->or_like('customer.name', $term, 'both');
				$this->db->or_like('customer.address', $term, 'both');
				$this->db->or_like('customer.city', $term, 'both');
			}
			
			$query		= $this->db->get();
			$result		= $query->num_rows();
			
			return $result;
		}

		public function getPendingSalesOrdersByCustomerId($customerId)
		{
			$query		= $this->db->query("
				SELECT code_sales_order.date, code_sales_order.id, code_sales_order.name, sellerTable.name as seller, salesOrderTable.quantity, salesOrderTable.sent
				FROM code_sales_order
				LEFT JOIN (
					SELECT id, name FROM users
				) as sellerTable
				ON code_sales_order.seller = sellerTable.id
				JOIN (
					SELECT sales_order.code_sales_order_id, SUM(sales_order.quantity) AS quantity, IF(sales_order.status = 1, SUM(sales_order.quantity), SUM(sales_order.sent)) as sent
					FROM sales_order
					GROUP BY sales_order.code_sales_order_id
				) as salesOrderTable
				ON salesOrderTable.code_sales_order_id = code_sales_order.id
				JOIN (
					SELECT DISTINCT(sales_order.code_sales_order_id) as id FROM sales_order WHERE status = '0'
				) as incompletedSalesOrderTable
				ON code_sales_order.id = incompletedSalesOrderTable.id
				WHERE code_sales_order.customer_id = '$customerId';
			");
			$result = $query->result();
			return $result;
		}

		public function getCountByCustomerId($customerId)
		{
			$query			= $this->db->query("
				SELECT COUNT(code_sales_order.id) AS count, MONTH(code_sales_order.date) AS month, YEAR(code_sales_order.date) AS year, a.value
				FROM code_sales_order
				JOIN (
					SELECT SUM(price_list.price_list * (100 - sales_order.discount) * IF(sales_order.status = '1', sales_order.sent, sales_order.quantity) / 100) AS value, sales_order.code_sales_order_id
					FROM sales_order
					JOIN price_list ON price_list.id = sales_order.price_list_id
					GROUP BY sales_order.code_sales_order_id
				) a
				ON a.code_sales_order_id = code_sales_order.id
				WHERE code_sales_order.customer_id = '$customerId' AND code_sales_order.date >= DATE_ADD(CURDATE(), INTERVAL -12 MONTH)
				AND code_sales_order.is_confirm = '1'
				GROUP BY MONTH(code_sales_order.date), YEAR(code_sales_order.date)
				ORDER BY code_sales_order.date DESC
			");

			$result = $query->result();
			return $result;
		}

		public function getByCustomerIdWeekly($customerId)
		{
			$query			= $this->db->query("
				SELECT COUNT(code_sales_order.id) AS count, CONCAT(YEAR(date), '/', WEEK(date)) AS week_name, YEAR(code_sales_order.date)  AS year, WEEK(code_sales_order.date) AS week
				FROM code_sales_order
				WHERE code_sales_order.customer_id = '$customerId' AND code_sales_order.is_confirm = '1' AND code_sales_order.date >= NOW() - INTERVAL 12 WEEK
				GROUP BY week_name
				ORDER BY YEAR(code_sales_order.date) ASC, WEEK(code_sales_order.date) ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function getConfirmedByCustomerId($customerId, $offset = 0, $limit = 10)
		{
			$this->db->where('customer_id', $customerId);
			$this->db->where('is_confirm', 1);
			$this->db->limit($limit, $offset);

			$query		= $this->db->get($this->table_sales_order);
			$result		= $query->result();
			return $result;
		}

		public function countConfirmedByCustomerId($customerId)
		{
			$this->db->where('customer_id', $customerId);
			$this->db->where('is_confirm', 1);

			$query		= $this->db->get($this->table_sales_order);
			$result		= $query->num_rows();
			return $result;
		}

		public function getIncompletedSalesOrdersByCustomer($offset = 0, $limit = 10)
		{
			$query			= $this->db->query("
				SELECT customer.*, a.count FROM (
					SELECT COUNT(code_sales_order.id) AS count, code_sales_order.customer_id
					FROM code_sales_order
					WHERE code_sales_order.id NOT IN (
						SELECT DISTINCT(code_sales_order_close_request.code_sales_order_id) AS id
						FROM code_sales_order_close_request
						WHERE is_approved IS NULL OR is_approved = 1
					)
					AND code_sales_order.id IN (
						SELECT DISTINCT(sales_order.code_sales_order_id) AS id
						FROM sales_order
						WHERE status = '0'
					)
					GROUP BY code_sales_order.customer_id
				) AS a
				JOIN customer ON a.customer_id = customer.id				
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countIncompletedSalesOrdersByCustomer()
		{
			$query			= $this->db->query("
				SELECT customer.id, a.count FROM (
					SELECT COUNT(code_sales_order.id) AS count, code_sales_order.customer_id
					FROM code_sales_order
					WHERE code_sales_order.id NOT IN (
						SELECT DISTINCT(code_sales_order_close_request.code_sales_order_id) AS id
						FROM code_sales_order_close_request
						WHERE is_approved IS NULL OR is_approved = 1
					)
					GROUP BY code_sales_order.customer_id
				) AS a
				JOIN customer ON a.customer_id = customer.id
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function countPendingByCustomerUID($customerUID)
		{
			$query		= $this->db->query("
				SELECT code_sales_order.id AS id
				FROM code_sales_order
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_order.id NOT IN (
					SELECT DISTINCT(code_sales_order_close_request.code_sales_order_id) AS id
					FROM code_sales_order_close_request
					WHERE is_approved IS NULL OR is_approved = 1
				)
				AND code_sales_order.id NOT IN (
					SELECT DISTINCT(sales_order.code_sales_order_id) AS id
					FROM sales_order
					WHERE sales_order.status = '0'
				)
				AND code_sales_order.is_confirm = '1'
				AND customer.uid = '$customerUID'
			");

			$result			= $query->num_rows();
			return $result;
		}
}
