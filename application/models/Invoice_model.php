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
					'customer_id' => null,
					'type' => $this->input->post('type')
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
					'customer_id' => $opponentId,
					'type' => $this->input->post('type')
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
						SELECT (SUM(invoice.value + invoice.delivery - invoice.discount) - COALESCE(a.value, 0)) as value, COALESCE(customer.name, other_opponent.name) as name, COALESCE(customer.city, '') as city, deliveryOrderTable.customer_id as customerId, deliveryOrderTable.opponent_id as opponentId, COALESCE(MIN(deliveryOrderTable.difference),0) as difference, deliveryOrderTable.payment
						FROM invoice
						LEFT JOIN (
							SELECT SUM(receivable.value) as value, invoice_id
							FROM receivable
							JOIN invoice ON receivable.invoice_id = invoice.id
							WHERE invoice.is_confirm = 1
							GROUP BY invoice_id
							) a
						ON invoice.id = a.invoice_id
						JOIN(
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id, NULL as opponent_id, DATEDIFF(NOW(), invoice.date) AS difference, code_sales_order.payment
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN invoice ON code_delivery_order.invoice_id = invoice.id
							UNION (
								SELECT invoice.id, invoice.customer_id, invoice.opponent_id, DATEDIFF(NOW(), invoice.date) AS difference, COALESCE(customer.term_of_payment, 0) AS payment
								FROM invoice
								LEFT JOIN customer ON invoice.customer_id = customer.id
								WHERE customer_id IS NOT NULL OR opponent_id IS NOT NULL AND invoice.is_done = 0
							)
						) as deliveryOrderTable
						ON deliveryOrderTable.id = invoice.id
						LEFT JOIN customer ON deliveryOrderTable.customer_id = customer.id
						LEFT JOIN other_opponent ON deliveryOrderTable.opponent_id = other_opponent.id
						WHERE difference > IF(deliveryOrderTable.customer_id IS NOT NULL, payment, 0)
						AND invoice.is_confirm = 1
						AND invoice.is_done = 0
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
						AND invoice.is_confirm = 1
						AND invoice.is_done = 0
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
						AND invoice.is_confirm = 1
						AND invoice.is_done = 0
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
						AND invoice.is_confirm = 1
						AND invoice.is_done = 0
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
						AND invoice.is_confirm = 1
						AND invoice.is_done = 0
						GROUP BY deliveryOrderTable.customer_id, deliveryOrderTable.opponent_id
					");
					break;
			}

			$chart_array = array();
			$result	= $query->result();
			if($result != null){
				foreach($result as $receivable){
					$customer_id		= $receivable->customerId;
					$opponent_id		= $receivable->opponentId;
					$customer_name		= $receivable->name;
					$customer_city		= $receivable->city;
					$invoice_value		= (float) $receivable->value;
					if($invoice_value > 0){
						$chart_array[] = array(
							'customer_id' => $customer_id,
							'opponent_id' => $opponent_id,
							'name' => $customer_name,
							'city' => $customer_city,
							'value' => $invoice_value
						);
					}
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
					SELECT invoice.* 
					FROM invoice 
					JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
					LEFT JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
					WHERE invoice.is_done = '0' 
					AND code_sales_order.customer_id = '$customer_id'
					GROUP BY invoice.id
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
				SELECT b.customer_id, (invoice.value - invoice.discount + invoice.delivery) as value, COALESCE(a.value, 0) AS paid, invoice.date, COALESCE(b.payment, customer.term_of_payment) AS term_of_payment
				FROM invoice
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id FROM receivable GROUP BY invoice_id
				) AS a
				ON invoice.id = a.invoice_id
				INNER JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as invoice_id, code_sales_order.payment, code_sales_order.customer_id
					FROM code_delivery_order
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					LEFT JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
					AND invoice.is_done = '0'
				) AS b
				ON invoice.id = b.invoice_id
				LEFT JOIN customer ON customer.id = invoice.customer_id
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

		public function viewIncompleteReceivableByCustomerId($customerId, $offset = 0)
		{
			$query = $this->db->query("
				SELECT invoice.*, COALESCE(a.value,0) as paid, ADDDATE(invoice.date, INTERVAL invoiceTable.payment DAY) AS due
				FROM invoice 
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, COALESCE(code_sales_order.payment, customer.term_of_payment) AS payment
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = $customerId
					WHERE code_sales_order.customer_id = '$customerId'
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id 
					FROM receivable 
					GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = 1
				AND invoice.is_done = 0
				ORDER BY invoice.date ASC, invoice.name DESC, invoice.id DESC
				LIMIT 10 OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countIncompleteReceivableByCustomerId($customerId){
			$query = $this->db->query("
				SELECT invoice.id
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
						WHERE is_confirm = 1
						AND customer_id = '$customerId'
					)
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id 
					FROM receivable 
					GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = 1
				AND invoice.is_done = 0
			");

			$result = $query->num_rows();
			return $result;
		}

		public function viewCompleteReceivableByCustomerId($customerId, $offset = 0)
		{
			$query = $this->db->query("
				SELECT invoice.*, COALESCE(a.value,0) as paid, ADDDATE(invoice.date, INTERVAL invoiceTable.payment DAY) AS due
				FROM invoice 
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, COALESCE(code_sales_order.payment, customer.term_of_payment) AS payment
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = $customerId
					WHERE code_sales_order.customer_id = '$customerId'
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id 
					FROM receivable 
					GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = 1
				ORDER BY invoice.date ASC, invoice.name DESC, invoice.id DESC
				LIMIT 10 OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countCompleteReceivableByCustomerId($customerId){
			$query = $this->db->query("
				SELECT invoice.id
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
						WHERE is_confirm = 1
						AND customer_id = '$customerId'
					)
				) AS invoiceTable
				ON invoice.id = invoiceTable.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id 
					FROM receivable 
					GROUP BY invoice_id
				) AS a
				ON a.invoice_id = invoice.id
				WHERE invoice.is_confirm = 1
			");

			$result = $query->num_rows();
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

		public function getAllItemsByMonthYear($month, $year)
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
					AND invoice.is_confirm = 1
					AND code_delivery_order.is_sent = 1
				) a
				ON a.id = invoice.id
				UNION (
					SELECT invoice.*, invoice.customer_id, invoice.opponent_id, IF((invoice.taxInvoice = '' OR invoice.taxInvoice IS NULL), 1, 0) AS taxing
					FROM invoice
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
					AND (invoice.customer_id IS NOT NULL || invoice.opponent_id IS NOT NULL)
					AND invoice.is_confirm = 1
				)
			");

			$result = $query->result();
			$this->load->model("Customer_model");
			$this->load->model("Opponent_model");
			$response		= array();
			foreach($result as $item){
				$responseItem = (array) $item;
				if($item->customer_id != null){
					$responseItem['opponent']	= $this->Customer_model->getById($item->customer_id)->name;
				} else {
					$responseItem['opponent']	= $this->Opponent_model->getById($item->opponent_id)->name;
				}

				array_push($response, $responseItem);
			}
			
			return $response;
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
					WHERE MONTH(invoice.date) = '$month' 
					AND YEAR(invoice.date) = '$year'
					AND invoice.is_confirm = 1
				) a
				ON a.id = invoice.id
				UNION (
					SELECT invoice.*, invoice.customer_id, invoice.opponent_id, IF((invoice.taxInvoice = '' OR invoice.taxInvoice IS NULL), 1, 0) AS taxing
					FROM invoice
					WHERE MONTH(invoice.date) = '$month' AND YEAR(invoice.date) = '$year'
					AND (invoice.customer_id IS NOT NULL || invoice.opponent_id IS NOT NULL)
					AND invoice.is_confirm = 1
				)
				ORDER by date ASC, name ASC
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
			$this->db->where('invoice.is_confirm', 1);
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

		public function getBySalesmanMonthYear($month, $year, $salesId = NULL)
		{
			if($salesId != NULL){
				$query		= $this->db->query("
					SELECT sales_order.id, (price_list.price_list * (100 - sales_order.discount) / 100) AS unitPrice, delivery_order.quantity, item.reference, item.type, item.name, item.brand, brand.name AS brand_name, item_class.name AS item_class_name, code_sales_order.customer_id, customer.name AS customer_name, customer_area.name AS customer_area_name
					FROM delivery_order
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN item_class ON item.type = item_class.id
					JOIN brand ON item.brand = brand.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE MONTH(code_delivery_order.date) = '$month' 
					AND YEAR(code_delivery_order.date) = '$year'
					AND code_delivery_order.is_sent = 1
					AND code_delivery_order.is_confirm = 1
					AND code_sales_order.seller = '$salesId'
				");
			} else {
				$query		= $this->db->query("
					SELECT sales_order.id, (price_list.price_list * (100 - sales_order.discount) / 100) AS unitPrice, delivery_order.quantity, item.reference, item.type, item.name, item.brand, brand.name AS brand_name, item_class.name AS item_class_name, code_sales_order.customer_id, customer.name AS customer_name, customer_area.name AS customer_area_name
					FROM delivery_order
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN item_class ON item.type = item_class.id
					JOIN brand ON item.brand = brand.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE MONTH(code_delivery_order.date) = '$month' 
					AND YEAR(code_delivery_order.date) = '$year'
					AND code_delivery_order.is_sent = 1
					AND code_delivery_order.is_confirm = 1
					AND code_sales_order.seller IS NULL
				");
			}
			

			$result		= $query->result();
			$response	= array();
			$response['brands']		= array();
			$response['types']		= array();
			$response['customers']	= array();
			$response['value']		= 0;

			foreach($result as $item){
				$brandId		= $item->brand;
				$brandName		= $item->brand_name;

				$typeId			= $item->type;
				$typeName		= $item->item_class_name;

				$customerId		= $item->customer_id;
				$customerName	= $item->customer_name;
				$customerArea	= $item->customer_area_name;

				$unitPrice		= $item->unitPrice;
				$quantity		= $item->quantity;
				$totalPrice		= $unitPrice * $quantity;
				
				if(!array_key_exists($customerId, $response['customers'])){
					$response['customers'][$customerId] = array(
						"name" => $customerName,
						"area" => $customerArea,
						"value" => $totalPrice
					);
				} else {
					$response['customers'][$customerId]['value'] += $totalPrice;
				}

				if(!array_key_exists($brandId, $response['brands'])){
					$response['brands'][$brandId] = array(
						"name" => $brandName,
						"value" => $totalPrice
					);
				} else {
					$response['brands'][$brandId]['value'] += $totalPrice;
				}

				if(!array_key_exists($typeId, $response['types'])){
					$response['types'][$typeId] = array(
						"name" => $typeName,
						"value" => $totalPrice
					);
				} else {
					$response['types'][$typeId]['value'] += $totalPrice;
				}

				$response['value'] += $totalPrice;
			}

			array_multisort(array_map(function($element) {
				return $element['name'];
			}, $response['brands']), SORT_ASC, $response['brands']);

			array_multisort(array_map(function($element) {
				return $element['name'];
			}, $response['types']), SORT_ASC, $response['types']);

			array_multisort(array_map(function($element) {
				return $element['value'];
			}, $response['customers']), SORT_DESC, $response['customers']);

			return $response;
		}

		public function sortByOrder($a, $b) {
			return $a['name'] - $b['name'];
		}

		public function getByMonthYear($month, $year, $limit = 0)
		{
			if($limit == 0){
				$query = $this->db->query("
					SELECT (a.value - COALESCE(returnTable.value, 0)) AS value, a.name, a.id
					FROM (
						SELECT customer.name, customer.id, invoice.value
						FROM invoice
						JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE MONTH(invoice.date) = '$month'
						AND YEAR(invoice.date) = '$year'
						AND invoice.is_confirm = '1'
						GROUP BY customer.id
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
					ORDER BY a.value DESC
				");
			} else {
				$query = $this->db->query("
					SELECT (a.value - COALESCE(returnTable.value, 0)) AS value, a.name, a.id
					FROM (
						SELECT customer.name, customer.id, invoice.value
						FROM invoice
						JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE MONTH(invoice.date) = '$month'
						AND YEAR(invoice.date) = '$year'
						AND invoice.is_confirm = '1'
						GROUP BY customer.id
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
					ORDER BY a.value DESC
					LIMIT $limit
				");
			}
			
			$result = $query->result();
			return $result;

		}

		public function getBillingData($offset, $term, $day = NULL, $area = NULL, $limit = 10)
		{
			if(!is_numeric($day)){
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
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			} else if($day !== NULL && $area != NULL) {
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
					JOIN (
						SELECT DISTINCT(customer_id) AS customer_id
						FROM customer_schedule
						WHERE day = '$day'
					) scheduleTable
					ON scheduleTable.customer_id = customer.id
					WHERE customer.area_id = '$area'
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			

			$result = $query->result();
			return $result;
		}

		public function countBillingData($term, $day = NULL, $area = NULL)
		{
			if($day === NULL){
				$query = $this->db->query("
					SELECT DISTINCT(customer.id)
					FROM (
						SELECT code_sales_order.customer_id
						FROM invoice
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
				");
			} else if($day !== NULL && $area != NULL) {
				$query = $this->db->query("
					SELECT DISTINCT(customer.id)
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
					JOIN (
						SELECT DISTINCT(customer_id) AS customer_id
						FROM customer_schedule
						WHERE day = '$day'
					) scheduleTable
					ON scheduleTable.customer_id = customer.id
					WHERE customer.area_id = '$area'
				");
			}
			

			$result = $query->num_rows();
			return $result;
		}

		public function getRecommendationList($date, $offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value) as paid, ADDDATE(invoice.date, INTERVAL COALESCE(customerTable.payment, customer.term_of_payment) DAY) AS due
				FROM invoice
				JOIN (
					SELECT a.customer_id, code_delivery_order.invoice_id, code_delivery_order.name, a.payment
					FROM code_delivery_order
					JOIN (
						SELECT delivery_order.code_delivery_order_id, code_sales_order.customer_id, code_sales_order.payment
						FROM delivery_order
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						GROUP BY delivery_order.code_delivery_order_id
					) AS a
					ON code_delivery_order.id = a.code_delivery_order_id
				) AS customerTable
				ON invoice.id = customerTable.invoice_id
				JOIN customer ON customer.id = customerTable.customer_id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id FROM receivable GROUP BY receivable.invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoiceTable.id) as id FROM
					(
						SELECT DISTINCT(invoice.id) as id, (IF(WEEKDAY(ADDDATE(code_sales_order.payment, invoice.date)) = 6, ADDDATE(ADDDATE(invoice.date, code_sales_order.payment), -1), ADDDATE(invoice.date, code_sales_order.payment))) as billingDate 
						FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE invoice.is_done = '0' AND invoice.is_confirm = 1
						UNION
						(
							SELECT DISTINCT(invoice.id) as id, invoice.nextBillingDate as billingDate
							FROM invoice
							WHERE nextBillingDate = '$date' AND is_done = '0' AND is_confirm = '1'
						)
					) AS invoiceTable
					WHERE  invoiceTable.billingDate = '$date' 
					OR invoiceTable.billingDate IS NULL
				) AND (
					invoice.name LIKE '%$term%' 
					OR customer.name LIKE '%$term%' 
					OR customerTable.name LIKE '%$term%'
				)
				AND invoice.id NOT IN (
					SELECT invoice.id
					FROM billing
					JOIN code_billing ON billing.code_billing_id = code_billing.id
					WHERE code_billing.date = '$date'
					AND code_billing.is_delete = 0
				)
				LIMIT $limit OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countRecommendationList($date, $term)
		{
			$query		= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value) as paid
				FROM invoice
				JOIN (
					SELECT a.customer_id, code_delivery_order.invoice_id, code_delivery_order.name
					FROM code_delivery_order
					JOIN (
						SELECT delivery_order.code_delivery_order_id, code_sales_order.customer_id
						FROM delivery_order
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						GROUP BY delivery_order.code_delivery_order_id
					) AS a
					ON code_delivery_order.id = a.code_delivery_order_id
				) AS customerTable
				ON invoice.id = customerTable.invoice_id
				JOIN customer ON customer.id = customerTable.customer_id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id 
					FROM receivable
					GROUP BY receivable.invoice_id
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
					WHERE invoiceTable.billingDate = '$date' 
					OR invoiceTable.billingDate IS NULL
				) AND (
					invoice.name LIKE '%$term%' 
					OR customer.name LIKE '%$term%' 
					OR customerTable.name LIKE '%$term%'
				)
				AND invoice.id NOT IN (
					SELECT invoice.id
					FROM billing
					JOIN code_billing ON billing.code_billing_id = code_billing.id
					WHERE code_billing.date = '$date'
					AND code_billing.is_delete = 0
				)
			");

			$result = $query->num_rows();
			return $result;
		}

		function getUrgentList($date, $offset = 0, $term = "", $limit = 10)
		{
			$dayofweek = date('w', strtotime($date));
			$day = "";
			switch($dayofweek){
				case 0:
					$day .= 6;
					break;
				case 1:
					$day .= 0;
					break;
				case 2:
					$day .= 1;
					break;
				case 3:
					$day .= 2;
					break;
				case 4:
					$day .= 3;
					break;
				case 5:
					$day .= 4;
					break;
				case 6:
					$day .= 5;
					break;
			}

			$query		= $this->db->query("
				SELECT invoice.*, customer.name as customerName, customer.address, customer.city, customer.rt, customer.rw, customer.block, customer.number, customer.postal_code, customer.block, COALESCE(receivableTable.value) as paid, ADDDATE(invoice.date, INTERVAL COALESCE(customerTable.payment, customer.term_of_payment) DAY) AS due
				FROM invoice
				JOIN (
					SELECT a.customer_id, code_delivery_order.invoice_id, code_delivery_order.name, a.payment
					FROM code_delivery_order
					JOIN (
						SELECT delivery_order.code_delivery_order_id, code_sales_order.customer_id, code_sales_order.payment
						FROM delivery_order
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						GROUP BY delivery_order.code_delivery_order_id
					) AS a
					ON code_delivery_order.id = a.code_delivery_order_id
				) AS customerTable
				ON invoice.id = customerTable.invoice_id
				JOIN customer ON customer.id = customerTable.customer_id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id FROM receivable GROUP BY receivable.invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoiceTable.id) as id 
					FROM
					(
						SELECT DISTINCT(invoice.id) as id, (IF(WEEKDAY(ADDDATE(code_sales_order.payment, invoice.date)) = 6, ADDDATE(ADDDATE(invoice.date, code_sales_order.payment), -1), ADDDATE(invoice.date, code_sales_order.payment))) as billingDate, customer.name AS customerName, invoice.name 
						FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE invoice.is_done = 0
						AND invoice.is_confirm = 1
						AND invoice.id NOT IN (
							SELECT billing.invoice_id
							FROM billing
							JOIN code_billing ON billing.code_billing_id = code_billing.id
							WHERE code_billing.date = '$date'
							AND code_billing.is_delete = 0
						)
					) AS invoiceTable
					WHERE invoiceTable.name LIKE '%$term%' OR invoiceTable.customerName LIKE '%$term%'
				)
				AND customer.id IN (
					SELECT DISTINCT(customer_id)
					FROM customer_schedule
					WHERE day = '$day'
				)
				AND ADDDATE(invoice.date, INTERVAL COALESCE(customerTable.payment, customer.term_of_payment) DAY) <= '$date'
				AND invoice.is_done = 0
				ORDER BY invoice.date ASC, invoice.name ASC
				LIMIT $limit OFFSET $offset
			");

			$result			= $query->result();
			return $result;
		}

		function countUrgentList($date, $term)
		{
			$dayofweek = date('w', strtotime($date));
			$day = "";
			switch($dayofweek){
				case 0:
					$day .= 6;
					break;
				case 1:
					$day .= 0;
					break;
				case 2:
					$day .= 1;
					break;
				case 3:
					$day .= 2;
					break;
				case 4:
					$day .= 3;
					break;
				case 5:
					$day .= 4;
					break;
				case 6:
					$day .= 5;
					break;
			}

			$query		= $this->db->query("
				SELECT invoice.id
				FROM invoice
				JOIN (
					SELECT a.customer_id, code_delivery_order.invoice_id, code_delivery_order.name, a.payment
					FROM code_delivery_order
					JOIN (
						SELECT delivery_order.code_delivery_order_id, code_sales_order.customer_id, code_sales_order.payment
						FROM delivery_order
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						GROUP BY delivery_order.code_delivery_order_id
					) AS a
					ON code_delivery_order.id = a.code_delivery_order_id
				) AS customerTable
				ON invoice.id = customerTable.invoice_id
				JOIN customer ON customer.id = customerTable.customer_id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id FROM receivable GROUP BY receivable.invoice_id
				) as receivableTable
				ON receivableTable.invoice_id = invoice.id
				WHERE invoice.id IN(
					SELECT DISTINCT(invoiceTable.id) as id 
					FROM
					(
						SELECT DISTINCT(invoice.id) as id, (IF(WEEKDAY(ADDDATE(code_sales_order.payment, invoice.date)) = 6, ADDDATE(ADDDATE(invoice.date, code_sales_order.payment), -1), ADDDATE(invoice.date, code_sales_order.payment))) as billingDate, customer.name AS customerName, invoice.name 
						FROM invoice 
						JOIN code_delivery_order ON code_delivery_order.invoice_id = invoice.id 
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id 
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id 
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id 
						JOIN customer ON code_sales_order.customer_id = customer.id
						WHERE invoice.is_done = 0
						AND invoice.is_confirm = 1
						AND invoice.id NOT IN (
							SELECT billing.invoice_id
							FROM billing
							JOIN code_billing ON billing.code_billing_id = code_billing.id
							WHERE code_billing.date = '$date'
							AND code_billing.is_delete = 0
						)
					) AS invoiceTable
					WHERE invoiceTable.name LIKE '%$term%' OR invoiceTable.customerName LIKE '%$term%'
				)
				AND customer.id IN (
					SELECT DISTINCT(customer_id)
					FROM customer_schedule
					WHERE day = '$day'
				)
				AND ADDDATE(invoice.date, INTERVAL COALESCE(customerTable.payment, customer.term_of_payment) DAY) <= '$date'
				AND invoice.is_done = 0
			");
			$result = $query->num_rows();
			return $result;
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
			
			return $query;
		}

		public function calculateAspect($aspect, $month, $year)
		{
			if($aspect == 1)
			{
				$query			= $this->db->query("
					SELECT users.id, COALESCE(a.value,0) as value, users.image_url, users.name, COALESCE(returnTable.value, 0) as returned
					FROM
					 (
						SELECT SUM(invoice.value + invoice.delivery - invoice.discount) as value, COALESCE(salesTable.seller,0) AS seller
						FROM invoice
						LEFT JOIN (
							SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.seller
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON code_sales_order.id = sales_order.code_sales_order_id
						) AS salesTable
						ON salesTable.id = invoice.id
						WHERE invoice.id IN (
							SELECT code_delivery_order.invoice_id AS id
							FROM code_delivery_order
							WHERE MONTH(code_delivery_order.date) = $month
							AND YEAR(code_delivery_order.date) = $year
						)
						AND invoice.is_confirm = 1
						GROUP BY seller
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
						WHERE code_sales_return_received.is_confirm = '1' 
						AND MONTH(code_sales_return_received.date) = '$month' 
						AND YEAR(code_sales_return_received.date) = '$year'
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
						WHERE invoice.is_confirm = 1
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
						WHERE MONTH(invoice.date) = '$month' 
						AND YEAR(invoice.date) = '$year'
						AND invoice.is_confirm = 1
						AND code_delivery_order.is_sent = 1
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
			} else if($aspect == 5){
				$query		= $this->db->query("
					SELECT brand.name, name(invoiceTable.value,0) as value, COALESCE(returnTable.value,0) as returned
					FROM brand
					LEFT JOIN (
						SELECT item.brand, SUM(price_list.price_list * (100 - sales_order.discount) * delivery_order.quantity / 100) as value
						FROM delivery_order
						JOIN sales_order ON sales_order.id = delivery_order.sales_order_id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN invoice ON code_delivery_order.invoice_id = invoice.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						WHERE MONTH(invoice.date) = '$month' 
						AND YEAR(invoice.date) = '$year'
						AND invoice.is_confirm = 1
						AND code_delivery_order.is_sent = 1
						GROUP BY item.brand
					) invoiceTable
					ON invoiceTable.type = brand.id
					LEFT JOIN (
						SELECT item.brand, COALESCE(SUM(sales_return.price * sales_return_received.quantity),0) as value
						FROM sales_return_received
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						WHERE code_sales_return_received.is_confirm = '1'
						AND MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						GROUP BY item.brand
					) returnTable
					ON returnTable.type = brand.id
					ORDER BY brand.name ASC
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

		public function calculatePaymentsByAreaId($type, $areaId)
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
						AND invoice.id IN (
							SELECT DISTINCT(code_delivery_order.invoice_id)
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN customer ON code_sales_order.customer_id = customer.id
							WHERE customer.area_id = '$areaId'
						)
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
						AND invoice.id IN (
							SELECT DISTINCT(code_delivery_order.invoice_id)
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN customer ON code_sales_order.customer_id = customer.id
							WHERE customer.area_id = '$areaId'
						)
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
						AND invoice.id IN (
							SELECT DISTINCT(code_delivery_order.invoice_id)
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN customer ON code_sales_order.customer_id = customer.id
							WHERE customer.area_id = '$areaId'
						)
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
						AND invoice.id IN (
							SELECT DISTINCT(code_delivery_order.invoice_id)
							FROM code_delivery_order
							JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
							JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
							JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
							JOIN customer ON code_sales_order.customer_id = customer.id
							WHERE customer.area_id = '$areaId'
						)
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

		public function getIncompletedTransactionByCustomerUID($customerUID, $offset = 0, $limit = 10)
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
				LIMIT $limit OFFSET $offset
			");

			$result	= $query->result();
			return $result;
		}

		public function getCompleteTransactionByCustomerUID($customerUID, $offset = 0, $limit = 10)
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
				AND invoice.is_confirm = '1'
				ORDER BY invoice.date ASC
				LIMIT $limit OFFSET $offset
			");

			$result	= $query->result();
			return $result;
		}

		public function countIncompletedTransactionByCustomerUID($customerUID){
			$query		= $this->db->query("
				SELECT invoice.id
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
			");

			$result	= $query->num_rows();
			return $result;
		}

		public function countCompleteTransactionByCustomerUID($customerUID){
			$query		= $this->db->query("
				SELECT invoice.id
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
			");

			$result	= $query->num_rows();
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
				UNION (
					SELECT SUM(-1 * sales_return_received.quantity * sales_return.price) AS value
					FROM sales_return_received
					JOIN sales_return ON sales_return_received.sales_return_id
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
					AND code_sales_return_received.date <= '$dateEnd'
					AND code_sales_return_received.date >= '$dateStart'
					AND code_sales_return_received.is_confirm = '1'
				)
			");

			$result		= $query->result();
			$value		= 0;
			foreach($result as $item){
				$value += (float)$item->value;
			}

			return $value;
		}

		public function getCustomerValueByDateRangerItemType($customerId, $dateStart, $dateEnd)
		{
			$query		= $this->db->query("
				SELECT item_class.name, COALESCE(a.value,0) AS value, COALESCE(b.value, 0) AS returned, COALESCE(a.quantity, 0) AS quantity, COALESCE(b.quantity, 0) AS returnedQuantity
				FROM item_class
				LEFT JOIN (
					SELECT SUM(delivery_order.quantity * (100 - sales_order.discount) * price_list.price_list) AS value, item.type, SUM(delivery_order.quantity) AS quantity
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE invoice.is_confirm = '1'
					AND invoice.date >= '$dateStart'
					AND invoice.date <= '$dateEnd'
					AND code_sales_order.customer_id = '$customerId'
					GROUP BY item.type
				) a
				ON a.type = item_class.id
				LEFT JOIN (
					SELECT SUM(sales_return_received.quantity * sales_return.price) AS value, item.type, SUM(sales_return_received.quantity) AS quantity
					FROM sales_return_received
					JOIN sales_return ON sales_return_received.sales_return_id
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE code_sales_order.customer_id = '$customerId'
					AND code_sales_return_received.date <= '$dateEnd'
					AND code_sales_return_received.date >= '$dateStart'
					AND code_sales_return_received.is_confirm = '1'
					GROUP BY item.type
				) b
				ON b.type = item_class.id
				ORDER BY item_class.name ASC
			");

			$result			= $query->result();
			return $result;
		}

		public function getAchivementByAreaId($areaId, $brand, $offset, $limit)
		{
			$offsetMonth		= date("m", strtotime("-" . $offset . "month"));
			$offsetYear			= date("Y", strtotime("-" . $offset . "month"));
			$offsetDate			= date("Y-m-d", mktime(0,0,0,$offsetMonth, 1, $offsetYear));

			$limitMonth		= date("m", strtotime("-" . $limit . "month"));
			$limitYear		= date("Y", strtotime("-" . $limit . "month"));
			$limitDate		= date("Y-m-d", mktime(0,0,0,$limitMonth, 1, $limitYear));
			$query			 = $this->db->query("
				SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, YEAR(code_delivery_order.date) AS year, MONTH(code_delivery_order.date) AS month
				FROM delivery_order
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				JOIN item ON price_list.item_id = item.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				WHERE delivery_order.code_delivery_order_id IN (
					SELECT DISTINCT code_delivery_order.id
					FROM code_delivery_order
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE customer.area_id = '$areaId'
				)
				AND item.brand = '$brand'
				AND code_delivery_order.date <= '$offsetDate' AND code_delivery_order.date >= '$limitDate'
				UNION (
					SELECT SUM((-1) * sales_return.price * sales_return_received.quantity) AS value, YEAR(code_sales_return_received.date) AS year, MONTH(code_sales_return_received.date) AS month
					FROM sales_return_received
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
					JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE code_sales_return_received.date <= '$offsetDate' AND code_sales_return_received.date >= '$limitDate'
					AND code_sales_return_received.is_confirm = '1'
					AND customer.area_id = '$areaId'
					AND item.brand = '$brand'
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

		public function getOtherByMonthYear($month, $year)
		{
			$this->db->select_sum('invoice.value');
			$this->db->select('debt_type.*');
			$this->db->from('invoice');
			$this->db->join('debt_type', 'invoice.type = debt_type.id');
			$this->db->where('YEAR(invoice.date)', $year);
			if($month != 0){
				$this->db->where('MONTH(invoice.date)', $month);
			}
			$this->db->where('invoice.opponent_id IS NOT NULL OR invoice.customer_id IS NOT NULL');
			$this->db->where('invoice.is_confirm', 1);
			$this->db->group_by('invoice.type');
			$this->db->order_by('debt_type.name');
			
			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function getByMonthYearDaily($month, $year)
		{
			$query			= $this->db->query("
				SELECT SUM(invoice.value) AS value, invoice.date
				FROM invoice
				WHERE MONTH(invoice.date) = '$month'
				AND YEAR(invoice.date) = '$year'
				AND invoice.is_confirm = '1'
				GROUP BY invoice.date
			");

			$result			= $query->result();
			$response		= array();
			$endOfMonth		=  date("t", mktime(0,0,0,$month, 1, $year));
			for($i = 1; $i <= $endOfMonth; $i++){
				$response[$i] = 0;
			}

			foreach($result as $data){
				$date			= $data->date;
				$day			= (int)date("d", strtotime($date));
				$response[$day]	= (float)$data->value;
			}

			return $response;
		}

		public function getRecap($month, $year, $brand = 0)
		{
			if($brand == 0){
				$query			= $this->db->query("
					SELECT invoice.date, invoice.value, deliveryOrderTable.customer_id
					FROM invoice
					JOIN (
						SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
						FROM code_delivery_order
						LEFT JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					) deliveryOrderTable
					ON invoice.id = deliveryOrderTable.id
					WHERE invoice.id IN (
						SELECT DISTINCT(code_delivery_order.invoice_id) AS id
						FROM code_delivery_order
						JOIN delivery_order ON delivery_order.code_delivery_order_id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					)
					AND MONTH(invoice.date) = '$month'
					AND YEAR(invoice.date) = '$year'
					AND invoice.is_confirm = '1'
				");
			} else {
				$query			= $this->db->query("
					SELECT invoice.date, invoice.value, deliveryOrderTable.customer_id
					FROM invoice
					JOIN (
						SELECT DISTINCT(code_delivery_order.invoice_id) AS id, code_sales_order.customer_id
						FROM code_delivery_order
						LEFT JOIN delivery_order ON code_delivery_order.id = delivery_order.code_delivery_order_id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					) deliveryOrderTable
					ON invoice.id = deliveryOrderTable.id
					WHERE invoice.id IN (
						SELECT DISTINCT(code_delivery_order.invoice_id) AS id
						FROM code_delivery_order
						JOIN delivery_order ON delivery_order.code_delivery_order_id
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN item ON price_list.item_id = item.id
						WHERE item.brand = '$brand'
					)
					AND MONTH(invoice.date) = '$month'
					AND YEAR(invoice.date) = '$year'
					AND invoice.is_confirm = '1'
				");
			}

			$result			= $query->result();
			$response		= array();
			foreach($result as $item){
				$customerId		= $item->customer_id;
				$value			= (float) $item->value;
				$date			= date("j", strtotime($item->date));
				if(!array_key_exists($customerId, $response)){
					$response[$customerId]		= array();
				}

				if(!array_key_exists($date, $response[$customerId])){
					$response[$customerId][$date]		= array(
						"value" => 0,
						"count" => 0
					);
				}

				$response[$customerId][$date]['value']	+= $value;
				$response[$customerId][$date]['count']++;
			}

			return $response;
		}

		public function getByCustomerIdDate($customerId, $date)
		{
			$query			= $this->db->query("
				SELECT invoice.*
				FROM invoice
				WHERE invoice.id IN (
					SELECT code_delivery_order.invoice_id
					FROM code_delivery_order
					LEFT JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					WHERE code_sales_order.customer_id = '$customerId'
				)
				AND invoice.date = '$date'
				AND invoice.is_confirm = '1'
			");

			$result			= $query->result();
			return $result;
		}

		public function getByBrandId($brandId, $startDate, $endDate){
			$query			= $this->db->query("
				SELECT SUM(delivery_order.quantity * price_list.price_list * (100 - sales_order.discount) / 100) AS value, MONTH(code_delivery_order.date) AS month, YEAR(code_delivery_order.date) AS year, customer_area.name, customer_area.id
				FROM delivery_order
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				JOIN item ON price_list.item_id = item.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				JOIN customer_area ON customer.area_id = customer_area.id
				WHERE code_delivery_order.date BETWEEN '$endDate' AND '$startDate'
				AND item.brand = '$brandId'
				AND code_delivery_order.is_delete = '0'
				GROUP BY MONTH(code_delivery_order.date), YEAR(code_delivery_order.date), customer.area_id
			");

			$result			= $query->result();
			return $result;
		}

		public function getAreaList($day = NULL){
			if($day === NULL){
				$query			= $this->db->query("
					SELECT COUNT(customer.id) AS count, customer_area.id, customer_area.name
					FROM customer
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE customer.id IN 
					(
						SELECT DISTINCT(code_sales_order.customer_id) as customer_id
						FROM code_sales_order
						LEFT JOIN sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN delivery_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN invoice ON code_delivery_order.invoice_id = invoice.id
						WHERE invoice.is_done = 0
						AND invoice.is_confirm = 1
					)
					GROUP BY customer.area_id
				");
			} else {
				$query			= $this->db->query("
					SELECT COUNT(customer.id) AS count, customer_area.id, customer_area.name
					FROM customer
					JOIN customer_area ON customer.area_id = customer_area.id
					WHERE customer.id IN 
					(
						SELECT DISTINCT(code_sales_order.customer_id) as customer_id
						FROM code_sales_order
						LEFT JOIN sales_order ON sales_order.code_sales_order_id = code_sales_order.id
						JOIN delivery_order ON delivery_order.sales_order_id = sales_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN invoice ON code_delivery_order.invoice_id = invoice.id
						JOIN (
							SELECT DISTINCT(customer_id) AS customer_id
							FROM customer_schedule
							WHERE day = '$day'
						) scheduleTable
						ON scheduleTable.customer_id = code_sales_order.customer_id
						WHERE invoice.is_done = 0
						AND invoice.is_confirm = 1
					)
					GROUP BY customer.area_id
				");
			}
			
			$result		= $query->result();
			return $result;
		}

		public function getStatusByCustomerUID($customerUID){
			$today			= date("Y-m-d");
			$query			= $this->db->query("
				SELECT SUM(invoice.value + invoice.delivery - invoice.discount - COALESCE(receivableTable.value, 0)) AS value, IF(ADDDATE(invoice.date, INTERVAL a.payment DAY) < '$today', '1', '0') AS due
				FROM invoice
				JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
				JOIN (
					SELECT DISTINCT(delivery_order.code_delivery_order_id) AS id, code_sales_order.payment
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					WHERE customer.uid = '$customerUID'
				) AS a
				ON code_delivery_order.id = a.id
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, invoice_id
					FROM receivable
					GROUP BY receivable.invoice_id
				) AS receivableTable
				ON invoice.id = receivableTable.invoice_id
				WHERE invoice.is_confirm = 1
				AND invoice.is_done = 0
				GROUP BY due
				ORDER BY due ASC
			");

			$result			= $query->result();
			return $result;
		}

		public function getInvoiceByName($name, $customerUID)
		{
			$query		= $this->db->query("
				SELECT invoice.*, a.payment,COALESCE(a.name, 'none') AS seller
				FROM invoice
				JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
				JOIN (
					SELECT DISTINCT(delivery_order.code_delivery_order_id) AS id, code_sales_order.payment, sellerTable.name
					FROM delivery_order
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN customer ON code_sales_order.customer_id = customer.id
					LEFT JOIN (
						SELECT users.name, users.id
						FROM users
					) as sellerTable
					ON code_sales_order.seller = sellerTable.id
					WHERE customer.uid = '$customerUID'
				) AS a
				ON code_delivery_order.id = a.id
				WHERE invoice.name = '$name'
			");

			$result			= $query->row();
			return $result;
		}
	}
?>
