<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivable_model extends CI_Model {
	private $table_receivable = 'receivable';
		
		public $id;
		public $bank_id;
		public $value;
		public $date;
		public $invoice_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->bank_id				= $db_item->bank_id;
			$this->value				= $db_item->value;
			$this->invoice_id			= $db_item->invoice_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->bank_id				= $this->bank_id;
			$db_item->value					= $this->value;
			$db_item->invoice_id			= $this->invoice_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id					= $db_item->id;
			$stub->bank_id				= $db_item->bank_id;
			$stub->value				= $db_item->value;
			$stub->invoice_id			= $db_item->invoice_id;
			
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
		
		public function viewReceivableByOpponentId($opponentId)
		{
			$this->db->select('receivable.*');
			$this->db->from('receivable');
			$this->db->join('bank_transaction', 'receivable.bank_id = bank_transaction.id', 'left');
			$this->db->where('bank_transaction.other_id', $opponentId);
			$this->db->order_by('receivable.date');
			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function viewReceivableByInvoiceIds($invoiceIds){
			$this->db->where_in('invoice_id', $invoiceIds);
			$this->db->order_by('date', 'asc');
			$query		= $this->db->get($this->table_receivable);
			$result		= $query->result();
			return $result;
		}

		public function viewReceivableByCustomerId($customerId)
		{
			$query			= $this->db->query("
				SELECT receivable.*
				FROM receivable
				WHERE invoice_id IN
				(
					SELECT invoice.id
					FROM invoice
					WHERE invoice.customer_id = '$customerId'
					UNION (
						SELECT DISTINCT(invoice.id) AS id
						FROM invoice
						JOIN code_delivery_order ON invoice.id = code_delivery_order.invoice_id
						JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						JOIN sales_order ON delivery_order.sales_order_id
						JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order_id
						WHERE code_sales_order.customer_id = '$customerId'
					)
				)
				ORDER BY receivable.date ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function getByInvoiceId($invoiceId)
		{
			$this->db->select('receivable.*, internal_bank_account.name, internal_bank_account.number');
			$this->db->from('receivable');
			$this->db->join('bank_transaction', 'bank_transaction.id = receivable.bank_id', 'left');
			$this->db->join('internal_bank_account', 'internal_bank_account.id = bank_transaction.account_id', 'left');
			$this->db->where('invoice_id', $invoiceId);

			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function getCustomerOpponentItems($offset = 0, $term = "", $limit = 10)
		{
			$query			= $this->db->query("
				SELECT customer.id, customer.name, customer.address, customer.number, customer.rt, customer.rw, customer.city, customer.postal_code, customer.block, 1 AS opponentType
				FROM customer
				WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%'
				UNION (
					SELECT other_opponent.id, other_opponent.name, other_opponent.description as address, NULL AS number, NULL AS rt, NULL AS rw, other_opponent_type.name AS city, NULL AS postal_code, NULL AS block, 2 AS opponentType
					FROM other_opponent
					JOIN other_opponent_type ON other_opponent.type = other_opponent_type.id
					WHERE other_opponent.name LIKE '%$term%' OR other_opponent.description LIKE '%$term%' OR other_opponent_type.name LIKE '%$term%'
				)
				ORDER BY name ASC
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countCustomerOpponentItems($term = "")
		{
			$query			= $this->db->query("
				SELECT customer.id
				FROM customer
				WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%'
				UNION (
					SELECT other_opponent.id
					FROM other_opponent
					JOIN other_opponent_type ON other_opponent.type = other_opponent_type.id
					WHERE other_opponent.name LIKE '%$term%' OR other_opponent.description LIKE '%$term%' OR other_opponent_type.name LIKE '%$term%'
				)
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function setInvoiceAsDone($invoiceId, $date)
		{
			$query			= $this->db->query("
				SELECT invoice.value, invoice.discount, invoice.delivery, a.value as paid
				FROM invoice
				JOIN (
					SELECT SUM(value) AS value, receivable.invoice_id
					FROM receivable
					GROUP BY receivable.invoice_id
				) a
				ON a.invoice_id = invoice.id
				WHERE invoice.id = '$invoiceId'
			");
			$result		= $query->row();
			$value		= $result->value;
			$discount	= $result->discount;
			$delivery	= $result->delivery;
			$paid		= $result->paid;

			$totalValue	= $value + $delivery - $discount;

			if($totalValue > $paid)
			{
				$db_item = array(
					"id" => "",
					"bank_id" => NULL,
					"value" => $totalValue - $paid,
					"date" => $date,
					"invoice_id" => $invoiceId
				);

				$this->db->insert($this->table_receivable, $db_item);
				return $this->db->affected_rows();
			}
			
			return 0;
		}

		public function deleteBlankById($receivableId)
		{
			$this->db->where('id', $receivableId);
			$query		= $this->db->get($this->table_receivable);
			$result		= $query->row();
			$invoiceId	= $result->invoice_id;

			$this->db->where("id", $receivableId);
			$this->db->where('bank_id', NULL);
			$this->db->delete($this->table_receivable);
			$result		= $this->db->affected_rows();

			if($result > 0){
				return $invoiceId;
			} else {
				return NULL;
			}
		}

		public function getByBankId($bankId)
		{
			$this->db->select('invoice.*, receivable.date as paidDate, receivable.value as paidValue');
			$this->db->from('receivable');
			$this->db->join('invoice', 'receivable.invoice_id = invoice.id', 'left');
			$this->db->where("bank_id", $bankId);

			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function deleteByBankId($bankId)
		{
			$this->db->where('bank_id', $bankId);
			$query		= $this->db->get($this->table_receivable);
			$result		= $query->result();

			$invoiceIdArray		= array();
			foreach($result as $item){
				array_push($invoiceIdArray, $item->invoice_id);
				next($result);
			}

			$this->db->db_debug = false;
			$this->db->where('bank_id', $bankId);
			$this->db->delete($this->table_receivable);

			$result			= $this->db->affected_rows();
			if($result > 0){
				$this->load->model("Invoice_model");
				$this->Invoice_model->updateDoneStatusByIdArray($invoiceIdArray, 0);
			}
		}

		public function getSetDoneCost($month, $year)
		{
			
			$this->db->select_sum('value');
			if($month != 0){
				$this->db->where('MONTH(date)', $month);
			}
			$this->db->where('YEAR(date)', $year);
			$this->db->where('bank_id', NULL);
			$query		= $this->db->get($this->table_receivable);
			$result		= $query->row();
			return (float)$result->value;
		}

		public function getByDate($date)
		{
			$query			= $this->db->query("
				SELECT SUM(invoice.value + invoice.delivery - invoice.discount) AS value, COALESCE(receivableTable.value, 0) AS paid
				FROM invoice
				LEFT JOIN (
					SELECT SUM(receivable.value) AS value, receivable.invoice_id
					FROM receivable
					WHERE receivable.date <= '$date'
					GROUP BY receivable.invoice_id
				) receivableTable
				ON invoice.id = receivableTable.invoice_id
				WHERE invoice.is_confirm = '1'
				AND invoice.date <= '$date'
			");

			$result			= $query->row();
			return $result->value - $result->paid;
		}
	}
?>
