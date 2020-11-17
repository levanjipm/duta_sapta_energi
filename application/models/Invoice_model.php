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
		public $opponent_id;
		public $customer_id;
		public $discount;
		public $delivery;
		
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
			$this->opponent_id			= $db_item->opponent_id;
			$this->discount				= $db_item->discount;
			$this->delivery				= $db_item->delivery;
			
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
			$db_item->opponent_id			= $this->opponent_id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->discount				= $this->discount;
			$db_item->delivery				= $this->delivery;
			
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
			$stub->opponent_id			= $db_item->opponent_id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->discount				= $db_item->discount;
			$stub->delivery				= $db_item->delivery;
			
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
			$discount			= $this->input->post('discount');
			$delivery			= $this->input->post('delivery');

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
				'taxInvoice' => null,
				'opponent_id' => null,
				'customer_id' => null,
				'discount' => $discount,
				'delivery' => $delivery
			);
			
			$this->db->insert($this->table_invoice, $db_item);
			return $this->db->insert_id();
		}

		public function insertBlankItem()
		{
			$opponentId		= $this->input->post('opponentId');
			$opponentType	= $this->input->post('opponentType');
			if($opponentType == 2){
				$db_item		= array(
					'id' => '',
					'name' => $this->input->post('invoiceDocument'),
					'value' => $this->input->post('value'),
					'information' => $this->input->post('note'),
					'date' => $this->input->post('date'),
					'is_confirm' => 0,
					'is_done' => 0,
					'taxInvoice' => null,
					'opponent_id' => $opponentId,
					'customer_id' => null
				);
			} else if($opponentType == 1){
				$db_item		= array(
					'id' => '',
					'name' => $this->input->post('invoiceDocument'),
					'value' => $this->input->post('value'),
					'information' => $this->input->post('note'),
					'date' => $this->input->post('date'),
					'is_confirm' => 0,
					'is_done' => 0,
					'taxInvoice' => null,
					'opponent_id' => null,
					'customer_id' => $opponentId
				);
			}

			$this->db->insert($this->table_invoice, $db_item);
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
		
		public function getIncompletedTransaction($customerId)
		{
			$query		= $this->db->query("
				SELECT invoice.*, receivableTable.value as paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable
					GROUP BY invoice_id
				) AS receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
					UNION (
						SELECT id FROM invoice
						WHERE is_done = '0'
						AND customer_id = '$customerId'
					)
				)
				AND invoice.is_done = '0'
				AND invoice.is_confirm = '1'
				ORDER BY invoice.date ASC
			");

			$result	= $query->result();
			
			return $result;
		}

		public function getIncompletedTransactionByOpponentId($opponentId)
		{
			$query		= $this->db->query("
				SELECT invoice.*, receivableTable.value as paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable
					GROUP BY invoice_id
				) AS receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN (
					SELECT id FROM invoice
					WHERE is_done = '0'
					AND opponent_id = '$opponentId'
				)
				AND invoice.is_done = '0'
				ORDER BY invoice.date ASC
			");

			$result	= $query->result();
			
			return $result;
		}
		
		public function viewReceivableChart($category)
		{
			switch($category){
				case 1:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(SUM(a.value), 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							UNION (
								SELECT id, customer_id, opponent_id
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = '0'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
				case 2:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, COALESCE(MIN(deliveryOrderTable.difference),0) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id, DATEDIFF(NOW(), invoice.date) AS difference
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN invoice ON code_delivery_order.invoice_id = invoice.id
							UNION (
								SELECT id, customer_id, opponent_id, DATEDIFF(NOW(), invoice.date) AS difference
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = '0'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE difference > IF(deliveryOrderTable.customer_id IS NOT NULL, customer.term_of_payment, 0)
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
				case 3:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN invoice ON code_delivery_order.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1' AND invoice.is_done = '0'
							UNION (
								SELECT id, customer_id, opponent_id
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL 
								AND invoice.is_done = '0'
								AND invoice.is_confirm = '1'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE DATEDIFF(NOW(), invoice.date) <= 30
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
				case 4:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							UNION (
								SELECT id, customer_id, opponent_id
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = '0'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE DATEDIFF(NOW(), invoice.date) > 30 AND DATEDIFF(NOW(), invoice.date) <= 45
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
				case 5:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							UNION (
								SELECT id, customer_id, opponent_id
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = '0'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE DATEDIFF(NOW(), invoice.date) > 45 AND DATEDIFF(NOW(), invoice.date) <= 60
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
				case 6:
					$query	= $this->db->query("
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, MIN(a.difference) as difference
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id, invoice.date, DATEDIFF(NOW(), invoice.date) as difference
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = '1'
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							UNION (
								SELECT id, customer_id, opponent_id
								FROM invoice
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = '0'
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE DATEDIFF(NOW(), invoice.date) > 60
						AND invoice.is_confirm = '1'
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
			}

			$result	= $query->result();
			if($result != null){
				foreach($result as $receivable){
					$customer_id		= $receivable->customerId;
					$opponent_id		= $receivable->opponentId;
					$customer_name		= $receivable->name;
					$customer_city		= $receivable->city;
					$invoice_value		= $receivable->value;
					$chart_array[] = array(
						'customer_id' => $customer_id,
						'opponent_id' => $opponent_id,
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
				SELECT invoice.value AS value, (invoice.value - invoice.discount + invoice.delivery) as value, COALESCE(a.value, 0) AS paid, invoice.date 
				FROM invoice
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
				SELECT invoice.*, (invoice.value - invoice.discount + invoice.delivery) as value,COALESCE(a.value,0) as paid
				FROM invoice 
				LEFT JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					UNION (
						SELECT id, customer_id
						FROM invoice
						WHERE is_confirm = '1'
						AND customer_id = '$customerId'
					)
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
					UNION (
						SELECT id
						FROM invoice
						WHERE is_confirm = '1'
						AND customer_id = '$customerId'
					)
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = '1'
				ORDER BY invoice.date ASC, invoice.name ASC, invoice.id ASC
			");

			$result = $query->result();
			return $result;
		}

		public function viewCompleteReceivableByOpponentId($opponentId)
		{
			$query		= $this->db->query("
				SELECT invoice.*, COALESCE(a.value,0) as paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = '1' and invoice.opponent_id = '$opponentId' AND invoice.is_done = '0'
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
				SELECT invoice.*, a.code_delivery_order_id as code_delivery_order_id, COALESCE(a.customer_id, invoice.customer_id) AS customer_id, invoice.opponent_id
				FROM invoice
				LEFT JOIN (
					SELECT DISTINCT(invoice.id) AS id, code_delivery_order.id as code_delivery_order_id, code_sales_order.customer_id
					FROM invoice
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE invoice.is_confirm = '0'
				) a
				ON invoice.id = a.id
				WHERE invoice.id IN (
					SELECT id
					FROM invoice
					WHERE invoice.is_confirm = '0'
				)
				ORDER BY invoice.date ASC;
			");

			$result = $query->result();

			return $result;
		}

		public function getValueByMonthYear($month, $year)
		{
			if($month == 0){
				$query		= $this->db->query("
					SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount), 0) AS value
					FROM invoice
					WHERE YEAR(invoice.date) = '$year'
					AND invoice.is_confirm = '1'
					AND invoice.customer_id IS NULL
					AND invoice.opponent_id IS NULL
					UNION (
						SELECT COALESCE(SUM(-1 * sales_return.price * sales_return_received.quantity), 0) AS value
						FROM sales_return_received
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return_id
						JOIN code_sales_return ON sales_return.code_sales_return_id = code_sales_return.id
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						WHERE YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = '1'
						AND code_sales_return.is_confirm = '1'
					)
				");
			} else {
				$query		= $this->db->query("
					SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount), 0) AS value
					FROM invoice
					WHERE YEAR(invoice.date) = '$year'
					AND MONTH(invoice.date) = '$month'
					AND invoice.is_confirm = '1'
					AND invoice.customer_id IS NULL
					AND invoice.opponent_id IS NULL
					UNION (
						SELECT COALESCE(SUM(-1 * sales_return.price * sales_return_received.quantity), 0) AS value
						FROM sales_return_received
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return_id
						JOIN code_sales_return ON sales_return.code_sales_return_id = code_sales_return.id
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						WHERE MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = '1'
						AND code_sales_return.is_confirm = '1'
					)
				");
			}

			$result = $query->result();
			if(count($result) == 1){
				$value		= $result[0]->value;
			} else {
				$value		=  $result[0]->value + $result[1]->value;
			}
			
			return (float)$value;
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
			$query		= $this->db->query("
				SELECT invoice.*, a.customer_id, NULL as opponent_id, a.taxing
				FROM invoice
				JOIN (
					SELECT DISTINCT(invoice.id) AS id, code_sales_order.customer_id, code_sales_order.taxing
					FROM invoice
					JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
					JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
				) a
				ON a.id = invoice.id
				UNION (
					SELECT invoice.*, invoice.customer_id, invoice.opponent_id, IF((invoice.taxInvoice = '' OR invoice.taxInvoice IS NULL), 1, 0) AS taxing
					FROM invoice
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
					AND (invoice.customer_id IS NOT NULL || invoice.opponent_id IS NOT NULL)
				)
				LIMIT 10 OFFSET $offset
			");

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
				SELECT (a.value - COALESCE(returnTable.value, 0)) AS value, a.name 
				FROM (
					SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount), 0) as value, customer.name, customer.id
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
				LEFT JOIN (
					SELECT COALESCE(SUM(sales_return.price * sales_return_received.quantity), 0) AS value, code_sales_order.customer_id
					FROM sales_return_received
					JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date)  = '$year'
					AND code_sales_return_received.is_confirm = '1'
					GROUP BY code_sales_order.customer_id
				) returnTable
				ON returnTable.customer_id = a.id
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
					SELECT SUM(invoice.value + invoice.delivery - invoice.discount) as value, COALESCE(receivableTable.value,0) AS paid, code_sales_order.customer_id
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
					SELECT SUM(invoice.value + invoice.delivery - invoice.discount) as value, COALESCE(receivableTable.value,0) AS paid, code_sales_order.customer_id
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

		public function updateBillingDate($billingId, $lastBillingDate, $nextBillingDate = null){
			if($nextBillingDate != null){
				$formatedNextBillingDate = date('Y-m-d', strtotime($nextBillingDate));
				$formatedLastBillingDate = date('Y-m-d', strtotime($lastBillingDate));
				$query		= $this->db->query("
					UPDATE invoice SET
					lastBillingDate = '$formatedLastBillingDate', nextBillingDate = '$formatedNextBillingDate'
					WHERE id = (
						SELECT invoice_id as id
						FROM billing
						WHERE id = '$billingId'
					)
				");
			} else {
				$formatedLastBillingDate = date('Y-m-d', strtotime($lastBillingDate));
				$query		= $this->db->query("
					UPDATE invoice SET
					lastBillingDate = '$formatedLastBillingDate', nextBillingDate = NULL
					WHERE id = (
						SELECT invoice_id as id
						FROM billing
						WHERE id = '$billingId'
					)
				");
			}

			$result = $query->result();
		}

		public function calculateAspect($aspect, $month, $year)
		{
			if($aspect == 1)
			{
				$query			= $this->db->query("
					SELECT users.id, COALESCE(a.value,0) as value, users.image_url, users.name, COALESCE(returnTable.value, 0) as returned
					FROM
					 (
						SELECT SUM(invoice.value + invoice.delivery - invoice.discount) as value, COALESCE(deliveryTable.seller,0) AS seller
						FROM invoice
						JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.seller
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						) AS deliveryTable
						ON deliveryTable.id = invoice.id
						WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
						GROUP BY deliveryTable.seller
					) AS a
					LEFT JOIN users ON a.seller = users.id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * sales_return.price),0) as value, COALESCE(code_sales_order.seller,0) AS seller
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY code_sales_order.seller
					) as returnTable
					ON returnTable.seller = a.seller
					ORDER BY users.name ASC
				");
			} else if($aspect == 2)
			{
				$query			= $this->db->query("
					SELECT customer_area.name, COALESCE(invoiceTable.value,0) as value, COALESCE(returnTable.value,0) as returned 
					FROM customer_area
					LEFT JOIN (
						SELECT SUM(invoice.value + invoice.delivery - invoice.discount) as value, a.area_id
						FROM (
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, customer.area_id
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
							JOIN customer ON code_sales_order.customer_id = customer.id
							WHERE code_delivery_order.is_sent = '1' AND MONTH(code_delivery_order.date) = '$month' AND YEAR(code_delivery_order.date) = '$year'
						) AS a
						JOIN invoice ON a.id = invoice.id
						GROUP BY a.area_id
					) AS invoiceTable
					ON customer_area.id = invoiceTable.area_id
					LEFT JOIN (
						SELECT COALESCE(SUM(sales_return_received.quantity * sales_return.price),0) as value, customer.area_id
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE code_sales_return_received.is_confirm = '1' AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY customer.area_id
					) AS returnTable
					ON customer_area.id = returnTable.area_id
					ORDER BY customer_area.name ASC
				");
			} else if($aspect == 3){
				$query			= $this->db->query("
					SELECT item_class.name, COALESCE(invoiceTable.value,0) as value, COALESCE(returnTable.value,0) as returned
					FROM item_class
					LEFT JOIN (
						SELECT item.type, SUM(price_list.price_list * (100 - sales_order.discount) * delivery_order.quantity / 100) as value
						FROM delivery_order
						JOIN sales_order ON sales_order.id = delivery_order.sales_order_id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN invoice ON code_delivery_order.invoice_id = invoice.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
						AND invoice.is_confirm = '1'
						GROUP BY item.type
					) invoiceTable
					ON invoiceTable.type = item_class.id
					LEFT JOIN (
						SELECT item.type, COALESCE(SUM(sales_return.price * sales_return_received.quantity),0) as value
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						WHERE code_sales_return_received.is_confirm = '1'
						AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY item.type
					) returnTable
					ON returnTable.type = item_class.id
					ORDER BY item_class.name ASC
				");
			}

			$result		= $query->result();
			return $result;
		}

		public function getConfirmedItems($month, $year, $offset = 0, $limit = 10)
		{
			$query			= $this->db->query("
				SELECT invoice.*, COALESCE(`customer`.`name`, `other_opponent`.`name`) AS opponentName, COALESCE(`customer`.`city`, `other_opponent`.`description`) AS opponentCity
				FROM invoice
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id, NULL as opponent_id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					UNION (
						SELECT invoice.id, invoice.customer_id, invoice.opponent_id FROM
						invoice WHERE invoice.customer_id IS NOT NULL OR invoice.opponent_id IS NOT NULL
					)
				) AS deliveryOrderTable
				ON deliveryOrderTable.id = invoice.id
				LEFT JOIN customer ON customer.id = deliveryOrderTable.customer_id
				LEFT JOIN other_opponent ON other_opponent.id = deliveryOrderTable.opponent_id
				WHERE invoice.is_confirm = '1' AND MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
				ORDER BY invoice.date ASC, invoice.id ASC
				LIMIT $limit OFFSET $offset
			");

			$result			= $query->result();
			return $result;
		}

		public function countConfirmedItems($month, $year)
		{
			$query			= $this->db->query("
				SELECT invoice.id
				FROM invoice
				WHERE invoice.is_confirm = '1' AND MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
			");

			$result			= $query->num_rows();
			return $result;
		}

		public function permanentDeleteInvoiceById($invoiceId)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $invoiceId);
			$this->db->delete($this->table_invoice);

			return $this->db->affected_rows();
		}

		public function getByItemType($customerId)
		{
			$query			= $this->db->query("
				SELECT item_class.id, item_class.name, COALESCE(invoiceTable.value,0) as value, COALESCE(returnTable.value,0) as returned
					FROM item_class
					LEFT JOIN (
						SELECT item.type, SUM(price_list.price_list * (100 - sales_order.discount) * delivery_order.quantity / 100) as value
						FROM delivery_order
						JOIN sales_order ON sales_order.id = delivery_order.sales_order_id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN invoice ON code_delivery_order.invoice_id = invoice.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						AND code_sales_order.customer_id = '$customerId'
						AND invoice.is_confirm = '1'
					) invoiceTable
					ON invoiceTable.type = item_class.id
					LEFT JOIN (
						SELECT item.type, COALESCE(SUM(sales_return.price * sales_return_received.quantity),0) as value
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						WHERE code_sales_return_received.is_confirm = '1'
						AND code_sales_order.customer_id = '$customerId'
						GROUP BY item.type
					) returnTable
					ON returnTable.type = item_class.id
					ORDER BY item_class.name ASC
			");

			$result			= $query->result();
			return $result;
		}

		public function calculatePayments($type)
		{
			if($type == 1){
				$query			= $this->db->query("
					SELECT COUNT(a.id) as count, SUM(invoice.value + invoice.delivery - invoice.discount) AS value, SUM(DATEDIFF(a.date, invoice.date) * (invoice.value + invoice.delivery - invoice.discount)) / SUM(invoice.value + invoice.delivery - invoice.discount) AS vwa, AVG(DATEDIFF(a.date, invoice.date)) AS pa
					FROM invoice
					JOIN (
						SELECT receivableTable.date, invoice.id
						FROM invoice
						JOIN (
							SELECT MAX(date) AS date, receivable.invoice_id FROM receivable
							GROUP BY receivable.invoice_id
						) receivableTable
						ON receivableTable.invoice_id = invoice.id
						WHERE invoice.is_done = '1'
						AND invoice.date >= DATE_ADD(CURDATE(), INTERVAL -3 MONTH)
						AND invoice.customer_id IS NULL AND invoice.opponent_id IS NULL
					) a
					ON a.id = invoice.id
				");

				$result		= $query->row();
				return $result;
			} else if($type == 2) {
				$query			= $this->db->query("
					SELECT COUNT(a.id) as count, SUM(invoice.value + invoice.delivery - invoice.discount) AS value, SUM(DATEDIFF(a.date, invoice.date) * invoice.value) / SUM(invoice.value + invoice.delivery - invoice.discount) AS vwa, AVG(DATEDIFF(a.date, invoice.date)) AS pa
					FROM invoice
					JOIN (
						SELECT receivableTable.date, invoice.id
						FROM invoice
						JOIN (
							SELECT MAX(date) AS date, receivable.invoice_id FROM receivable
							GROUP BY receivable.invoice_id
						) receivableTable
						ON receivableTable.invoice_id = invoice.id
						WHERE invoice.is_done = '1'
						AND invoice.date >= DATE_ADD(CURDATE(), INTERVAL -6 MONTH)
						AND invoice.customer_id IS NULL AND invoice.opponent_id IS NULL
					) a
					ON a.id = invoice.id
				");

				$result		= $query->row();
				return $result;
			} else if($type == 3){
				$query			= $this->db->query("
					SELECT COUNT(a.id) as count, SUM(invoice.value + invoice.delivery - invoice.discount) AS value, SUM(DATEDIFF(a.date, invoice.date) * (invoice.value + invoice.delivery - invoice.discount)) / SUM(invoice.value + invoice.delivery - invoice.discount) AS vwa, AVG(DATEDIFF(a.date, invoice.date)) AS pa
					FROM invoice
					JOIN (
						SELECT receivableTable.date, invoice.id
						FROM invoice
						JOIN (
							SELECT MAX(date) AS date, receivable.invoice_id 
							FROM receivable
							GROUP BY receivable.invoice_id
						) receivableTable
						ON receivableTable.invoice_id = invoice.id
						WHERE invoice.is_done = '1'
						AND invoice.date >= DATE_ADD(CURDATE(), INTERVAL -12 MONTH)
						AND invoice.customer_id IS NULL AND invoice.opponent_id IS NULL
					) a
					ON a.id = invoice.id
				");

				$result		= $query->row();
				return $result;
			} else if($type == 4){
				$query			= $this->db->query("
					SELECT COUNT(a.id) as count, SUM(invoice.value + invoice.delivery - invoice.discount) AS value, SUM(DATEDIFF(a.date, invoice.date) * (invoice.value + invoice.delivery - invoice.discount)) / SUM(invoice.value + invoice.delivery - invoice.discount) AS vwa, AVG(DATEDIFF(a.date, invoice.date)) AS pa
					FROM invoice
					JOIN (
						SELECT receivableTable.date, invoice.id
						FROM invoice
						JOIN (
							SELECT MAX(date) AS date, receivable.invoice_id 
							FROM receivable
							GROUP BY receivable.invoice_id
						) receivableTable
						ON receivableTable.invoice_id = invoice.id
						WHERE invoice.is_done = '1'
						AND invoice.customer_id IS NULL AND invoice.opponent_id IS NULL
					) a
					ON a.id = invoice.id
				");

				$result		= $query->row();
				return $result;
			}
		}

		public function setInvoiceAsDone($invoiceId)
		{
			$this->db->set('is_done', 1);
			$this->db->where('id', $invoiceId);
			$this->db->update($this->table_invoice);
		}

		public function updateDoneStatusByIdArray($idArray, $status)
		{
			$this->db->set('is_done', 0);
			$this->db->where_in('id', $idArray);
			$this->db->update($this->table_invoice);
		}

		public function getBalanceByCustomerUID($customerUID)
		{
			$query		= $this->db->query("
				SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount),0) AS value, receivableTable.value AS paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, receivable.invoice_id AS id
					FROM receivable
					GROUP BY receivable.invoice_id
				) AS receivableTable
				ON invoice.id = receivableTable.id
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id) AS id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE customer.uid = '$customerUID'
					UNION (
						SELECT invoice.id FROM invoice
						JOIN customer ON invoice.customer_id = customer.id
						WHERE customer.uid = '$customerUID'
					)
				)
				AND invoice.is_confirm = '1'
				AND invoice.is_done = '0'
			");

			$result			= $query->row();
			return $result;
		}

		public function getCustomerSalesHistory($customerUID)
		{
			$query			= $this->db->query("
				SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount),0) AS value, MONTH(invoice.date) AS month, YEAR(invoice.date) AS year
				FROM invoice
				LEFT JOIN customer ON invoice.customer_id = customer.id
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id) AS id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE customer.uid = '$customerUID'
				)
				OR customer.uid = '$customerUID'
				AND invoice.is_confirm = '1'
				GROUP BY MONTH(invoice.date), YEAR(invoice.date)
				ORDER BY invoice.date DESC
			");

			$result			= $query->result();
			return $result;
		}

		public function getIncompletedTransactionByCustomerUID($customerUID)
		{
			$query		= $this->db->query("
				SELECT invoice.*, COALESCE(receivableTable.value,0) as paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable
					GROUP BY invoice_id
				) AS receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON customer.id = code_sales_order.customer_id
					WHERE customer.uid = '$customerUID'
					UNION (
						SELECT invoice.id 
						FROM invoice
						JOIN customer ON invoice.customer_id = customer.id
						WHERE invoice.is_done = '0'
						AND invoice.is_confirm = '1'
						AND customer.uid = '$customerUID'						
					)
				)
				AND invoice.is_done = '0'
				AND invoice.is_confirm = '1'
				ORDER BY invoice.date ASC
			");

			$result	= $query->result();
			return $result;
		}

		public function getCustomerHistory($customerUID)
		{
			$query			= $this->db->query("
				SELECT invoice.*, COALESCE(receivableTable.value,0) as paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable
					GROUP BY invoice_id
				) AS receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON customer.id = code_sales_order.customer_id
					WHERE customer.uid = '$customerUID'
					UNION (
						SELECT invoice.id 
						FROM invoice
						JOIN customer ON invoice.customer_id = customer.id
						WHERE invoice.is_done = '0'
						AND invoice.is_confirm = '1'
						AND customer.uid = '$customerUID'						
					)
				)
				AND invoice.is_confirm = '1'
				ORDER BY invoice.date ASC
			");

			$result			= $query->result();
			return $result;
		}

		public function getCustomerValueByDateRange($customerId, $dateStart, $dateEnd)
		{
			$query			= $this->db->query("
				SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount),0) AS value
				FROM invoice
				WHERE invoice.id IN (
					SELECT DISTINCT(code_delivery_order.invoice_id)
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
					AND code_sales_order.is_confirm = '1'
					UNION (
						SELECT invoice.id
						FROM invoice
						WHERE invoice.customer_id = '$customerId'
					)
				)
				AND invoice.is_confirm = '1'
				AND invoice.date >= '$dateStart'
				AND invoice.date <= '$dateEnd'
			");

			$result		= $query->row();

			return $result->value;
		}

		public function getCustomerValueByDateRangerItemType($id, $start, $end)
		{
			$query		= $this->db->query("
				SELECT item_class.name, COALESCE(a.value,0) AS value
				FROM item_class
				LEFT JOIN (
					SELECT SUM(delivery_order.quantity * (100 - sales_order.discount) * price_list.price_list) AS value, item_class.id
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN item_class ON item.type = item_class.id
					GROUP BY item_class.id
				) a
				ON a.id = item_class.id
			");

			$result			= $query->result();
			return $result;
		}

		public function getAchivementByAreaId($areaId, $offset, $limit)
		{
			$offsetMonth		= date("m", strtotime("-" . $offset . "month"));
			$offsetYear			= date("Y", strtotime("-" . $offset . "month"));
			$offsetDate			= date("Y-m-d", mktime(0,0,0,$offsetMonth, 1, $offsetYear));

			$limitMonth		= date("m", strtotime("-" . $limit . "month"));
			$limitYear		= date("Y", strtotime("-" . $limit . "month"));
			$limitDate		= date("Y-m-d", mktime(0,0,0,$limitMonth, 1, $limitYear));
			$query			 = $this->db->query("
				SELECT invoiceTable.*
				FROM (
					SELECT SUM(invoice.value + invoice.delivery - invoice.discount) AS value, YEAR(invoice.date) AS year, MONTH(invoice.date) AS month
					FROM invoice
					LEFT JOIN customer ON invoice.customer_id = customer.id
					WHERE (invoice.id IN (
						SELECT DISTINCT(code_delivery_order.invoice_id) AS id
						FROM code_delivery_order
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE customer.area_id = '$areaId'
					)
					AND invoice.date <= '$offsetDate' AND invoice.date >= '$limitDate')
					OR customer.area_id = '$areaId'					
					GROUP BY YEAR(invoice.date), MONTH(invoice.date)
				) invoiceTable
				UNION (
					SELECT SUM((-1) * sales_return.price * sales_return_received.quantity) AS value, YEAR(code_sales_return_received.date) AS year, MONTH(code_sales_return_received.date) AS month
					FROM sales_return_received
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					WHERE code_sales_return_received.date <= '$offsetDate' AND code_sales_return_received.date >= '$limitDate'
					GROUP BY YEAR(code_sales_return_received.date), MONTH(code_sales_return_received.date)
				)
			");

			$result			= $query->result();
			return $result;
		}

		public function getIncompletedTransactionByDate($date)
		{
			$query		= $this->db->query("
				SELECT COALESCE(SUM(invoice.value + invoice.delivery - invoice.discount),0) AS value, COALESCE(receivableTable.value,0) AS paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, receivable.invoice_id
					FROM receivable
					GROUP BY receivable.invoice_id
				) receivableTable
				ON invoice.id = receivableTable.invoice_id
				WHERE invoice.is_done = '0'
				AND invoice.date <= '$date'
			");

			$result		= $query->row();
			return $result;
		}
	}
?>
