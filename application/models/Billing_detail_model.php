<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_detail_model extends CI_Model {
	private $table_billing = 'billing';
		
		public $id;
		public $invoice_id;
		public $result;
		public $note;
		public $code_billing_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->invoice_id			= $db_item->invoice_id;
			$this->result				= $db_item->result;
			$this->note					= $db_item->note;
			$this->code_billing_id		= $db_item->code_billing_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->invoice_id			= $this->invoice_id;
			$db_item->result				= $this->result;
			$db_item->note					= $this->note;
			$db_item->code_billing_id		= $this->code_billing_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->invoice_id			= $db_item->invoice_id;
			$stub->result				= $db_item->result;
			$stub->note					= $db_item->note;
			$stub->code_billing_id		= $db_item->code_billing_id;
			
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

		public function insertItem($codeBillingId)
		{
			$invoiceArray = $_REQUEST["invoices"];
			$batch = array();
			foreach($invoiceArray as $key => $invoiceId)
			{
				$batch[] = array(
					"id" => "",
					"invoice_id" => $invoiceId,
					"result" => 0,
					"note" => "",
					"code_billing_id" => $codeBillingId
				);
				next($invoiceArray);
			}

			$this->db->insert_batch($this->table_billing, $batch);
		}

		public function getByCodeId($codeBillingId)
		{
			$query			= $this->db->query("
				SELECT invoice.nextBillingDate, billing.note, billing.result, customer.id as customerId, (invoice.value + invoice.delivery - invoice.discount) AS value, invoice.name, invoice.date, customer.name as customerName, customer.address, customer.city, customer.number, customer.block, customer.rt, customer.rw, customer.postal_code, billing.id, billing.result, billing.note, billing.invoice_id, COALESCE(receivableTable.value,0) as paid
				FROM billing
				JOIN invoice ON invoice.id = billing.invoice_id
				JOIN (
					SELECT DISTINCT(code_delivery_order.invoice_id) as id, code_sales_order.customer_id
					FROM code_delivery_order
					JOIN invoice ON code_delivery_order.invoice_id = invoice.id
					JOIN delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				) as a
				ON invoice.id = a.id
				LEFT JOIN (
					SELECT SUM(value) as value, invoice_id
					FROM receivable
					GROUP BY invoice_id
				) AS receivableTable
				ON receivableTable.invoice_id = invoice.id
				JOIN customer ON customer.id = a.customer_id
				WHERE billing.code_billing_id = '$codeBillingId'
				ORDER BY customer.name ASC, customer.id ASC, invoice.name ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function updateReport($billingDate, $resultArray, $noteArray, $nextBillingDateArray)
		{
			$this->load->model("Invoice_model");
			$updateBatch = array();
			foreach($resultArray as $billingId => $result)
			{
				$note = $noteArray[$billingId];
				if($result == 1 || $result == 2){
					if($nextBillingDateArray[$billingId] > $billingDate){
						$this->Invoice_model->updateBillingDate($billingId, $billingDate, $nextBillingDateArray[$billingId]);
					} else {
						$this->Invoice_model->updateBillingDate($billingId, $billingDate);
					}
				}

				$updateBatch[] = array(
					"id" => $billingId,
					"result" => $result,
					"note" => $note,
				);

				next($resultArray);
			}

			$this->db->update_batch($this->table_billing, $updateBatch, "id");
		}
}
