<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {
	private $table_invoice = 'invoice';
		
		public $id;
		public $name;
		public $value;
		public $date;
		public $information;
		public $is_confirm;
		public $is_done;
		public $taxInvoice;
		public $lastBillingDate;
		public $nextBillingDate;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->value				= $db_item->value;
			$this->date					= $db_item->date;
			$this->information			= $db_item->information;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_done				= $db_item->is_done;
			$this->taxInvoice			= $db_item->taxInvoice;
			$this->nextBillingDate		= $db_item->nextBillingDate;
			$this->lastBillingDate		= $db_item->lastBillingDate;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->value					= $this->value;
			$db_item->date					= $this->date;
			$db_item->information			= $this->information;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_done				= $this->is_done;
			$db_item->taxInvoice			= $this->taxInvoice;
			$db_item->nextBillingDate		= $this->nextBillingDate;
			$db_item->lastBillingDate		= $this->lastBillingDate;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->value				= $db_item->value;
			$stub->date					= $db_item->date;
			$stub->information			= $db_item->information;
			$stub->is_confirm			= $this->is_confirm;
			$stub->is_done				= $this->is_done;
			$stub->taxInvoice			= $this->taxInvoice;
			$stub->nextBillingDate		= $this->nextBillingDate;
			$stub->lastBillingDate		= $this->lastBillingDate;
			
			return $stub;
		}
		
		public function map_list($invoices)
		{
			$result = array();
			foreach ($invoices as $invoice)
			{
				$result[] = $this->get_new_stub_from_db($invoice);
			}
			return $result;
		}
		
		public function show_items($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
				$this->db->or_like('city', $filter, 'both');
			}
			
			$query 		= $this->db->get($this->table_customer, $limit, $offset);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function count_items($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
			}
			
			$query		= $this->db->get($this->table_customer);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function insertFromDeliveryOrder($deliveryOrderObject)
		{
			$deliveryOrderId	= $deliveryOrderObject->id;
			$deliveryOrderName	= $deliveryOrderObject->name;
			$deliveryOrderDate	= $deliveryOrderObject->date;
			$customerId			= $deliveryOrderObject->customer_id;

			$value	= $this->Invoice_model->calculateValueByDeliveryOrder($deliveryOrderId);			
			$invoice_name	= 'INV.DSE' . substr($deliveryOrderName,7);

			$db_item		= array(
				'id' => '',
				'name' => $invoice_name,
				'value' => $value,
				'information' => $deliveryOrderName,
				'date' => $deliveryOrderDate,
				'is_confirm' => 0,
				'is_done' => 0,
				'taxInvoice' => null
			);
			
			$this->db->insert($this->table_invoice, $db_item);
			return $this->db->insert_id();
		}
		
		public function calculateValueByDeliveryOrder($delivery_order_id)
		{
			$this->db->select('sum(`price_list`.`price_list`*`delivery_order`.`quantity`* (100 - `sales_order`.`discount`) / 100) as value', FAlSE);
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('price_list', 'sales_order.price_list_id = price_list.id');
			$this->db->where('delivery_order.code_delivery_order_id', $delivery_order_id);
			
			$query	= $this->db->get();
			$result	= $query->row();
			
			return $result->value;
		}
		
		public function getIncompletedTransaction($customer_id)
		{
			$this->db->select('invoice.*, sum(receivable.value) as paid');
			$this->db->from('invoice');
			$this->db->join('code_delivery_order', 'code_delivery_order.invoice_id = invoice.id');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id', 'left');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id');
			$this->db->join('receivable', 'invoice.id = receivable.invoice_id', 'left');
			$this->db->group_by('receivable.invoice_id');
			$this->db->where('code_sales_order.customer_id', $customer_id);
			$this->db->where('invoice.is_done', 0);
			$this->db->order_by('invoice.date');
			
			$query	= $this->db->get();
			$result	= $query->result();
			
			return $result;
		}
		
		public function viewReceivableChart($category)
		{
			switch($category){
				case 1:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
				case 2:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE difference > customer.term_of_payment
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
				case 3:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE DATEDIFF(NOW(), invoice.date) <= 30
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
				case 4:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE DATEDIFF(NOW(), invoice.date) > 30 AND DATEDIFF(NOW(), invoice.date) <= 45
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
				case 5:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE DATEDIFF(NOW(), invoice.date) > 45 AND DATEDIFF(NOW(), invoice.date) <= 60
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
				case 6:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value) - COALESCE(a.value, 0)) as value, customer.name, customer.city, deliveryOrderTable.customer_id as id, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						JOIN customer ON deliveryOrderTable.customer_id = customer.id
						WHERE DATEDIFF(NOW(), invoice.date) > 60
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id
					");
					break;
			}

			$result	= $query->result();
			if($result != null){
				foreach($result as $receivable){
					$customer_id		= $receivable->id;
					$customer_name		= $receivable->name;
					$customer_city		= $receivable->city;
					$invoice_value		= $receivable->value;
					$chart_array[] = array(
						'id' => $customer_id,
						'name' => $customer_name,
						'city' => $customer_city,
						'value' => $invoice_value
					);
				}
	
				usort($chart_array, function($a, $b) {
					return $a['value'] - $b['value'];
				});
	
				$data = $chart_array;
			} else {
				$data = array();
			}
			
			return $data;
		}
		
		public function getReceivableByCustomerId($customer_id)
		{
			$query = $this->db->query("
				SELECT COALESCE(SUM(a.value),0) as value, COALESCE(SUM(b.value),0) as paid 
				FROM (
					SELECT invoice.* FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						LEFT JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						WHERE  invoice.is_done = '0' AND code_sales_order.customer_id = '$customer_id'
					) as a
				LEFT JOIN (
					SELECT SUM(receivable.value) as value, receivable.invoice_id 
					FROM receivable 
					GROUP BY receivable.invoice_id
				) as b
				ON a.id = b.invoice_id
			");
			$result = $query->row();
			return $result;
		}
		
		public function getCustomerStatusById($customerId)
		{
			$query = $this->db->query("
				SELECT invoice.value AS value, COALESCE(a.value, 0) AS paid, invoice.date FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON invoice.id = a.invoice_id
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as invoice_id FROM code_delivery_order
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					LEFT JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
					AND invoice.is_done = '0'
				) AS b
				ON invoice.id = b.invoice_id
			");
			$result		= $query->result();
			
			return $result;
		}
		
		public function viewReceivableByCustomerId($customerId)
		{
			$query = $this->db->query("
				SELECT invoice.*, COALESCE(a.value,0) as paid
				FROM invoice 
				JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				) AS deliveryOrderTable
				ON deliveryOrderTable.id = invoice.id
				WHERE deliveryOrderTable.customer_id = '$customerId'
				AND invoice.is_done = '0'
				ORDER BY invoice.date ASC, invoice.name ASC, invoice.id ASC
			");
			$result = $query->result();
			
			return $result;
		}

		public function viewCompleteReceivableByCustomerId($customerId)
		{
			$query = $this->db->query("
				SELECT invoice.*, COALESCE(a.value,0) as paid
				FROM invoice 
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				ORDER BY invoice.date ASC, invoice.name ASC, invoice.id ASC
			");
			$result = $query->result();
			
			return $result;
		}

		public function getYears()
		{
			$this->db->select("DISTINCT(YEAR(date)) as years");
			$this->db->order_by('date');
			$query = $this->db->get($this->table_invoice);
			$result = $query->result();

			return $result;
		}

		public function getUnconfirmedInvoice()
		{
			$query = $this->db->query("
				SELECT invoice.*, a.code_delivery_order_id as code_delivery_order_id, a.customer_id
				FROM (
					SELECT DISTINCT(invoice.id) AS id, code_delivery_order.id as code_delivery_order_id, code_sales_order.customer_id FROM invoice
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE invoice.is_confirm = '0'
				) AS a
				JOIN invoice ON a.id = invoice.id
				ORDER BY invoice.date ASC;
			");

			$result = $query->result();

			return $result;
		}

		public function getValueByMonthYear($month, $year)
		{
			$this->db->select_sum('value');
			$this->db->where('MONTH(date)', (int)$month);
			$this->db->where('YEAR(date)', $year);
			$query = $this->db->get($this->table_invoice);
			$result = $query->row();

			return ($result->value == null)? 0 : (float)$result->value;
		}

		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_invoice);
			$result = $query->row();

			return $result;
		}

		public function updateById($invoiceId, $taxInvoice = null)
		{
			$this->db->set('is_confirm', 1);
			$this->db->set('taxInvoice', $taxInvoice);
			$this->db->where('id', $invoiceId);
			$this->db->where('is_confirm', 0);
			$this->db->update($this->table_invoice);
			
			return $this->db->affected_rows();
		}

		public function getItems($offset, $month, $year)
		{
			$this->db->select('invoice.*, code_sales_order.customer_id, code_sales_order.taxing');
			$this->db->from('invoice');
			$this->db->join('code_delivery_order', 'code_delivery_order.invoice_id = invoice.id');
			$this->db->join('delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->where('MONTH(invoice.date)', $month);
			$this->db->where('YEAR(invoice.date)', $year);
			$this->db->order_by('invoice.name');
			$this->db->order_by('invoice.date');
			$this->db->limit(10, $offset);

			$query = $this->db->get();
			$result = $query->result();

			return $result;
		}

		public function countItems($month, $year)
		{
			$this->db->select('invoice.id');
			$this->db->from('invoice');
			$this->db->join('code_delivery_order', 'code_delivery_order.invoice_id = invoice.id');
			$this->db->where('MONTH(invoice.date)', $month);
			$this->db->where('YEAR(invoice.date)', $year);
			$this->db->order_by('invoice.name');

			$query = $this->db->get();
			$result = $query->num_rows();

			return $result;
		}

		public function deleteById($invoiceId)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $invoiceId);
			$result = $this->db->delete($this->table_invoice);
			return $result;
		}

		public function getByMonthYear($month, $year, $offset)
		{
			$query = $this->db->query("
				SELECT a.value, a.name FROM (
					SELECT SUM(invoice.value) as value, customer.name
					FROM invoice
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE MONTH(invoice.date) = '$month'
					AND YEAR(invoice.date) = '$year'
					GROUP BY code_sales_order.customer_id
				) a
				ORDER BY value ASC
			");
			$result = $query->result();
			return $result;

		}

		public function getBillingData($offset, $term, $limit = 10)
		{
			$query = $this->db->query("
				SELECT (a.value - a.paid) as value, customer.* 
				FROM (
					SELECT SUM(invoice.value) as value, COALESCE(receivableTable.value,0) AS paid, code_sales_order.customer_id
					FROM invoice
					LEFT JOIN (
						SELECT SUM(receivable.value) as value, receivable.invoice_id 
						FROM receivable
						GROUP BY receivable.invoice_id
					) receivableTable
					ON receivableTable.invoice_id = invoice.id
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE invoice.is_done = '0'
					AND customer.name LIKE '%$term%'
					GROUP BY code_sales_order.customer_id
					ORDER BY invoice.date ASC
				) a
				JOIN customer ON a.customer_id = customer.id
				ORDER BY value DESC
				LIMIT $limit OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countBillingData($term)
		{
			$query = $this->db->query("
				SELECT customer.id
				FROM (
					SELECT SUM(invoice.value) as value, COALESCE(receivableTable.value,0) AS paid, code_sales_order.customer_id
					FROM invoice
					LEFT JOIN (
						SELECT SUM(receivable.value) as value, receivable.invoice_id 
						FROM receivable
						GROUP BY receivable.invoice_id
					) receivableTable
					ON receivableTable.invoice_id = invoice.id
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE invoice.is_done = '0'
					AND customer.name LIKE '%" . $term . "%'
					GROUP BY code_sales_order.customer_id
					ORDER BY invoice.date ASC
				) a
				JOIN customer ON a.customer_id = customer.id
				ORDER BY value DESC
			");

			$result = $query->num_rows();
			return $result;
		}

		public function getRecommendationList($date, $offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value) as paid
				FROM invoice
				JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
				JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
				JOIN customer ON code_sales_order.customer_id = customer.id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id FROM receivable GROUP BY receivable.invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoiceTable.id) as id FROM
					(
						SELECT DISTINCT(invoice.id) as id, (IF(WEEKDAY(ADDDATE(customer.term_of_payment, invoice.date)) = 6, ADDDATE(ADDDATE(invoice.date, customer.term_of_payment), -1), ADDDATE(invoice.date, customer.term_of_payment))) as billingDate 
						FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
						UNION
						(
							SELECT DISTINCT(invoice.id) as id, invoice.nextBillingDate as billingDate
							FROM invoice
							WHERE nextBillingDate = '$date' AND is_done = '0' AND is_confirm = '1'
						)
					) AS invoiceTable
					WHERE invoiceTable.billingDate = '$date' OR invoiceTable.billingDate IS NULL
				) AND (invoice.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR code_delivery_order.name LIKE '%$term%')
				LIMIT $limit OFFSET $offset
			");
			$result = $query->result();
			return $result;
		}

		public function countRecommendationList($date, $term)
		{
			$query		= $this->db->query("
				SELECT invoice.id FROM invoice
				JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
				JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoiceTable.id) as id FROM
					(
						SELECT DISTINCT(invoice.id) as id, (IF(WEEKDAY(ADDDATE(customer.term_of_payment, invoice.date)) = 6, ADDDATE(ADDDATE(invoice.date, customer.term_of_payment), -1), ADDDATE(invoice.date, customer.term_of_payment))) as billingDate 
						FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE invoice.is_done = '0' AND invoice.is_confirm = '1'
						UNION
						(
							SELECT DISTINCT(invoice.id) as id, invoice.nextBillingDate as billingDate
							FROM invoice
							WHERE nextBillingDate = '$date' AND is_done = '0' AND is_confirm = '1'
						)
					) AS invoiceTable
					WHERE invoiceTable.billingDate = '$date' OR invoiceTable.billingDate IS NULL
				) AND (invoice.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR code_delivery_order.name LIKE '%$term%')
			");
			$result = $query->num_rows();
			return $result;
		}

		function getUrgentList($date, $offset = 0, $term = "", $limit = 10)
		{
			$query	= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value) as paid
				FROM invoice
				JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
				JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
				JOIN customer ON code_sales_order.customer_id = customer.id
				LEFT JOIN (
					SELECT invoice_id, SUM(value) as value FROM receivable
					GROUP BY invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoice.id) as id
					FROM invoice 
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE invoice.is_done = '0' AND invoice.is_confirm = '1' 
					AND (ADDDATE(customer.term_of_payment, invoice.date) > NOW())
				)
				AND (invoice.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR code_delivery_order.name LIKE '%$term%')
				LIMIT $limit OFFSET $offset
			");
			$result = $query->result();
			return $result;
		}

		function countUrgentList($date, $term)
		{
			$query	= $this->db->query("
				SELECT invoice.id
				FROM invoice
				JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
				JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoice.id) as id
					FROM invoice 
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE invoice.is_done = '0' AND invoice.is_confirm = '1' 
					AND (ADDDATE(customer.term_of_payment, invoice.date) > NOW())
				)
				AND (invoice.name LIKE '%$term%' OR customer.name LIKE '%$term%' OR code_delivery_order.name LIKE '%$term%')
			");
		}

		public function getByIdArray($idArray)
		{
			$query	= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value, 0) as paid
				FROM invoice
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
					FROM invoice
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
				) AS a
				ON invoice.id = a.id
				LEFT JOIN (
					SELECT invoice_id, SUM(value) as value FROM receivable
					GROUP BY invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				JOIN customer ON a.customer_id = customer.id
				WHERE invoice.id IN (" . implode(',', array_map('intval', $idArray)) . ")
			");
			$result = $query->result();
			return $result;
		}

		public function updateBillingDate($id, $lastBillingDate, $nextBillingDate = null){
			$this->db->set('lastBillingDate', $lastBillingDate);
			$this->db->set('nextBillingDate', $nextBillingDate);
			$this->db->where('id', $id);
			$this->db->update($this->table_invoice);
		}

		public function calculateAspect($aspect, $month, $year)
		{
			if($aspect == 1)
			{
				$query			= $this->db->query("
					SELECT users.id, SUM(invoice.value) as value, users.image_url, users.name, COALESCE(returnTable.returnValue, 0) as returned
					FROM invoice
					JOIN (
						SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.seller
						FROM code_delivery_order
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
					) AS a
					ON a.id = invoice.id
					LEFT JOIN users ON a.seller = users.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (100 - discount) / 100),0) as returnValue, code_delivery_order.invoice_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						WHERE code_sales_return_received.is_confirm = '1'
					) as returnTable
					ON invoice.id = returnTable.invoice_id
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
					GROUP BY a.seller
				");
			} else if($aspect == 2)
			{
				$query			= $this->db->query("
					SELECT customer_area.name, SUM(invoice.value) as value, COALESCE(SUM(returnTable.returnValue),0) as returned FROM invoice
					JOIN (
						SELECT DISTINCT(code_delivery_order.invoice_id) as id, customer.area_id
						FROM code_delivery_order
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE code_delivery_order.is_sent = '1'
					) AS a
					ON a.id = invoice.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * price_list.price_list * (1 - discount / 100)),0) as returnValue, code_delivery_order.invoice_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						WHERE code_sales_return_received.is_confirm = '1'
						GROUP BY code_delivery_order.invoice_id
					) as returnTable
					ON invoice.id = returnTable.invoice_id
					JOIN customer_area ON a.area_id = customer_area.id
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
					AND invoice.is_confirm = '1'
					GROUP BY customer_area.id
				");
			}

			$result		= $query->result();
			return $result;
		}
	}
?>
