<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order_model extends CI_Model {
	private $table_delivery_order = 'code_delivery_order';
		
		public $id;
		public $name;
		public $date;
		public $is_delete;
		public $is_sent;
		public $is_confirm;
		public $invoice_id;
		public $guid;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id					='';
			$this->name					='';
			$this->date					='';
			$this->is_confirm			='';
			$this->is_delete			='';
			$this->is_sent				='';
			$this->guid					='';
			$this->invoice_id			='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->date					= $db_item->date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->is_sent				= $db_item->is_sent;
			$this->guid					= $db_item->guid;
			$this->invoice_id			= $db_item->invoice_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->date				= $this->date;
			$db_item->is_confirm		= $this->is_confirm;
			$db_item->is_delete			= $this->is_delete;
			$db_item->is_sent			= $this->is_sent;
			$db_item->guid				= $this->guid;
			$db_item->invoice_id		= $this->invoice_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->is_sent				= $db_item->is_sent;
			$stub->guid					= $db_item->guid;
			$stub->invoice_id			= $db_item->invoice_id;
			
			return $stub;
		}
		
		public function get_new_stub_from_db_with_customer($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->date					= $db_item->date;
			$stub->guid					= $db_item->guid;
			$stub->customer_name		= $db_item->customer_name;
			$stub->address				= $db_item->address;
			$stub->city					= $db_item->city;
			
			return $stub;
		}
		
		public function map_list($delivery_orders)
		{
			$result = array();
			foreach ($delivery_orders as $delivery_order)
			{
				$result[] = $this->get_new_stub_from_db($delivery_order);
			}
			return $result;
		}
		
		public function showUnconfirmedDeliveryOrder($offset = 0, $limit = 10)
		{
			$query = $this->db->query("
				SELECT code_delivery_order.*, a.customer_id FROM code_delivery_order
				JOIN (
					SELECT DISTINCT(code_delivery_order.id) AS id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
				) AS a
				ON a.id = code_delivery_order.id
				WHERE code_delivery_order.is_confirm = '0'
				AND code_delivery_order.is_delete = '0'
				AND code_delivery_order.is_sent = '0'

			");

			$result	 	= $query->result();
			
			return $result;
		}

		public function countUnconfirmedDeliveryOrder()
		{
			$query = $this->db->query("
				SELECT code_delivery_order.id FROM code_delivery_order
				JOIN (
					SELECT DISTINCT(code_delivery_order.id) AS id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
				) AS a
				ON a.id = code_delivery_order.id
				WHERE code_delivery_order.is_confirm = '0'
				AND code_delivery_order.is_delete = '0'
				AND code_delivery_order.is_sent = '0'

			");

			$result	 	= $query->num_rows();
			
			return $result;
		}
		
		public function showUnsentDeliveryOrder($offset = 0, $limit = 10)
		{
			$query = $this->db->query("
				SELECT code_delivery_order.*, a.customer_id FROM code_delivery_order
				JOIN (
					SELECT DISTINCT(code_delivery_order.id) AS id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
				) AS a
				ON a.id = code_delivery_order.id
				WHERE code_delivery_order.is_confirm = '1'
				AND code_delivery_order.is_delete = '0'
				AND code_delivery_order.is_sent = '0'

			");

			$result	 	= $query->result();
			
			return $result;
		}

		public function countUnsentDeliveryOrder()
		{
			$query = $this->db->query("
				SELECT code_delivery_order.* FROM code_delivery_order
				JOIN (
					SELECT DISTINCT(code_delivery_order.id) AS id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
				) AS a
				ON a.id = code_delivery_order.id
				WHERE code_delivery_order.is_confirm = '1'
				AND code_delivery_order.is_delete = '0'
				AND code_delivery_order.is_sent = '0'

			");

			$result	 	= $query->num_rows();
			return $result;
		}
		
		public function generateName($date, $taxing)
		{
			$this->db->where('month(date)',date('m',strtotime($date)));
			$this->db->where('year(date)',date('Y',strtotime($date)));
			$query	= $this->db->get($this->table_delivery_order);
			$item 	= $query-> num_rows();
			
			$item++;
			
			$name = 'DO-DSE-' . date('Ym', strtotime($date)) . '-' . str_pad($item, 4, '0', STR_PAD_LEFT) . $taxing;
			return $name;
		}
		
		public function getById($id)
		{
			$this->db->select('code_delivery_order.*, code_sales_order.name as sales_order_name, code_sales_order.taxing, users.name as seller, code_sales_order.invoicing_method, code_sales_order.date as sales_order_date, code_sales_order.customer_id');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id', 'inner');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('users', 'code_sales_order.seller = users.id', 'left');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_delivery_order.id',$id);
			$query 		= $this->db->get();
			$result 		= $query->row();
			
			return $result;
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_delivery_order);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function insertItem()
		{
			$this->load->model('Delivery_order_model');
			$delivery_order_name = $this->Delivery_order_model->generateName($this->input->post('date'), $this->input->post('taxing'));
			
			$this->date				= $this->input->post('date');
			$this->name				= $delivery_order_name;
			$this->guid				= $this->input->post('guid');
			$this->invoice_id		= null;
			
			$db_item 				= $this->get_db_from_stub($this);
			$db_result 				= $this->db->insert($this->table_delivery_order, $db_item);
			
			$insert_id				= $this->db->insert_id();
			
			return $insert_id;
		}
		
		public function getUninvoicedDeliveryOrders($type, $offset = 0, $filter = '', $accountant = null, $limit = 10)
		{
			$this->db->select('DISTINCT(code_delivery_order.id) as id, , code_delivery_order.date, code_delivery_order.name, code_delivery_order.is_confirm, code_delivery_order.is_sent, code_delivery_order.guid, code_delivery_order.invoice_id, customer.name as customer_name, customer.address as customer_address, customer.city as customer_city');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'left');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id', 'inner');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->join('customer_accountant', 'customer.id = customer_accountant.customer_id');

			$this->db->where('code_sales_order.invoicing_method', $type);
			$this->db->where('code_delivery_order.is_confirm', 1);
			$this->db->where('code_delivery_order.is_delete', 0);
			$this->db->where('code_delivery_order.invoice_id', null);

			if($accountant != null){
				$this->db->where('customer_accountant.accountant_id', $accountant);
			}
			$this->db->like('code_delivery_order.name', null);
			$this->db->limit($limit, $offset);
			$query		= $this->db->get();
			
			$result		= $query->result();
			
			return $result;
		}
		
		public function countUninvoicedDeliveryOrders($type = 0, $term = '', $accountant =  null)
		{
			$this->db->select('DISTINCT(code_delivery_order.id)');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('customer_accountant', 'code_sales_order.customer_id = customer_accountant.customer_id');

			if($type != 0){
				$this->db->where('code_sales_order.invoicing_method', $type);
			}

			$this->db->where('code_delivery_order.is_confirm', 1);
			$this->db->where('code_delivery_order.is_delete', 0);

			$this->db->where('code_delivery_order.invoice_id', null);
			if($accountant != null){
				$this->db->where('customer_accountant.accountant_id', $accountant);
			}
			$query	= $this->db->get();

			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function sendById($deliveryOrderId)
		{
			$this->db->set('is_sent', 1);
			$this->db->where('id', $deliveryOrderId);
			$this->db->where('is_sent', 0);
			$this->db->update($this->table_delivery_order);
			
			if($this->db->affected_rows() == 1){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function updateInvoiceId($delivery_order_id, $invoice_id)
		{
			$this->db->set('invoice_id', $invoice_id);
			$this->db->where('invoice_id', NULL);
			$this->db->where('id', $delivery_order_id);
			$this->db->update($this->table_delivery_order);
		}
		
		public function getArchive($year, $month, $offset = 0, $term = "", $limit = 10)
		{
			$query = $this->db->query("
				SELECT code_delivery_order.*, customer.name as customer_name, customer.address, customer.city, a.salesOrderName, customer.number, customer.rt, customer.rw, customer.block, customer.pic_name
				FROM code_delivery_order
				JOIN (
					SELECT DISTINCT(delivery_order.code_delivery_order_id) as id, code_sales_order.customer_id, code_sales_order.name as salesOrderName
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				) AS a
				ON a.id = code_delivery_order.id
				JOIN customer ON a.customer_id = customer.id
				WHERE MONTH(code_delivery_order.date) = '$month'
				AND YEAR(code_delivery_order.date) = '$year'
				AND code_delivery_order.is_delete = '0'
				AND (code_delivery_order.name LIKE '%$term%'
				OR customer.name LIKE '%$term%'
				OR customer.address LIKE '%$term%'
				OR customer.city LIKE '%$term%'
				OR a.salesOrderName	LIKE '%$term%')
				LIMIT $limit OFFSET $offset	
			");
			
			$result		= $query->result();
			
			return $result;
		}
		
		public function countArchive($year, $month, $term)
		{
			$this->db->select('code_delivery_order.id');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id', 'inner');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('MONTH(code_delivery_order.date)',$month);
			$this->db->where('YEAR(code_delivery_order.date)',$year);
			$this->db->where('code_delivery_order.is_delete', 0);
			if($term != ''){
				$this->db->like('code_delivery_order.name', $term, 'both');
				$this->db->or_like('customer.name', $term, 'both');
				$this->db->or_like('customer.address', $term, 'both');
				$this->db->or_like('customer.city', $term, 'both');
				$this->db->or_like('code_sales_order.name', $term, 'both');
			}
			
			$query		= $this->db->get();
			$result		= $query->num_rows();
			
			return $result;
		}

		public function updateById($status, $id)
		{
			if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->set('invoice_id', NULL);
				$this->db->where('is_delete', 0);
			} else if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->where('is_confirm', 0);
			} else if($status == -1){
				$this->db->set('is_confirm', 0);
				$this->db->where('is_delete', 0);
			} else if($status == 2){
				$this->db->set('is_sent', 1);
				$this->db->where('is_sent', 0);
			}

			$this->db->where('id', $id);
			$this->db->update($this->table_delivery_order);

			return ($this->db->affected_rows() == 1) ? true: false;
		}

		public function unassignInvoiceById($invoiceId)
		{
			$this->db->set('invoice_id', null);
			$this->db->where('invoice_id', $invoiceId);
			$this->db->update($this->table_delivery_order);

			return $this->db->affected_rows();
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
		
		public function getByName($name)
		{
			$this->db->where("name", $name);
			$this->db->where('is_confirm', 1);
			$this->db->where('is_sent', 1);
			$this->db->where('invoice_id IS NOT NULL', null, false);
			$query = $this->db->get($this->table_delivery_order);
			
			$result = $query->row();
			return ($result == null)? null: $result;
		}

		public function getItemBySalesOrderId($salesOrderId)
		{
			$query		= $this->db->query("
				SELECT code_delivery_order.* 
				FROM code_delivery_order
				JOIN
				(
					SELECT DISTINCT(delivery_order.code_delivery_order_id) AS id
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.id = '$salesOrderId'
				) as deliveryOrderTable
				ON deliveryOrderTable.id = code_delivery_order.id
				WHERE code_delivery_order.is_delete = '0';
			");

			$result = $query->result();

			return $result;
		}

		public function getByInvoiceId($invoiceId)
		{
			$query = $this->db->query("
				SELECT code_sales_order.name AS sales_order_name, code_sales_order.id as code_sales_order_id, code_sales_order.customer_id, code_delivery_order.* FROM code_delivery_order
				JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
				WHERE code_delivery_order.invoice_id = '$invoiceId';
			");

			$result = $query->row();

			return $result;
		}

		public function getUninvoicedItems($offset = 0, $limit = 10)
		{
			$this->db->select('code_delivery_order.*, code_sales_order.customer_id');
			$this->db->from('code_delivery_order');
			$this->db->join('delivery_order', 'code_delivery_order.id = delivery_order.code_delivery_order_id');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');

			$this->db->where('code_delivery_order.invoice_id', NULL);
			$this->db->where('code_delivery_order.is_confirm', 1);
			$this->db->where('code_delivery_order.is_sent', 1);
			$this->db->limit($limit, $offset);

			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countUninvoicedItems()
		{
			$this->db->where('code_delivery_order.invoice_id', NULL);
			$this->db->where('code_delivery_order.is_confirm', 1);
			$this->db->where('code_delivery_order.is_sent', 1);

			$query			= $this->db->get($this->table_delivery_order);
			$result			= $query->num_rows();
			return $result;
		}

		public function deleteById($deliveryOrderId)
		{
			$this->db->set('is_confirm', 0);
			$this->db->set('is_sent', 0);
			$this->db->set('is_delete', 1);
			$this->db->where('id', $deliveryOrderId);
			$query			= $this->db->update($this->table_delivery_order);
			$result			= $this->db->affected_rows();
			return $result;
		}

		public function getPendingSentItems($offset = 0, $limit = 10)
		{
			$this->db->select('code_delivery_order.*, customer.name as customerName, customer.address, customer.city, customer.number, customer.block, customer.rt, customer.rw, customer.postal_code, customer.latitude, customer.longitude');
			$this->db->from('code_delivery_order', 'left');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'inner');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_delivery_order.is_confirm', 1);
			$this->db->where('code_delivery_order.is_sent', 0);
			$this->db->limit($limit, $offset);

			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countPendingSentItems($offset = 0, $limit = 10)
		{
			$this->db->where('is_confirm', 1);
			$this->db->where('is_sent', 0);

			$query			= $this->db->get($this->table_delivery_order);
			$result			= $query->num_rows();
			return $result;
		}

		public function getDailyShipments($limit, $offset)
		{
			$query			= $this->db->query("
				SELECT COUNT(code_delivery_order.id) AS count, code_delivery_order.is_sent, code_delivery_order.date
				FROM code_delivery_order
				WHERE code_delivery_order.is_delete = '0'
				AND code_delivery_order.is_confirm = '1'
				AND code_delivery_order.date >= DATE_ADD(CURDATE(), INTERVAL -$limit DAY)
				AND code_delivery_order.date <= DATE_ADD(CURDATE(), INTERVAL -$offset DAY)
				GROUP BY code_delivery_order.date, code_delivery_order.is_sent
			");

			$result			= $query->result();

			$paramDate		= mktime(0,0,0,date("m", strtotime("-" . $offset . "day")), date("d", strtotime("-" . $offset . "day")), date("Y", strtotime("-" . $offset . "day")));
			$response		= array();

			for($i = $offset; $i <= $limit; $i++){
				$response[$i]['label']	= date("d M Y", strtotime("-" . $i . "day"));
				$response[$i]['sent']	= 0;
				$response[$i]['confirmed'] = 0;
			}

			foreach($result as $data)
			{
				$date			= strtotime($data->date);
				$difference		= ($paramDate - $date) / (60 * 60 * 24);
				if($data->is_sent == 1){
					$response[$difference]['sent']	= $data->count;
				} else {
					$response[$difference]['confirmed']	= $data->count;
				}
				
			}

			$response = array_reverse($response);

			return $response;
		}

		public function viewOnProgressByItemId($itemId)
		{
			$query			= $this->db->query("
				SELECT customer.name AS customerName, customer.city, code_delivery_order.name, code_delivery_order.date, delivery_order.quantity
				FROM delivery_order
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				WHERE price_list.item_id = '$itemId'
				AND code_delivery_order.is_confirm = '1' AND code_delivery_order.is_sent = '0'
				ORDER BY code_delivery_order.date ASC
			");

			$result			= $query->result();
			return $result;
		}
}
