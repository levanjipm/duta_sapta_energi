<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payable_model extends CI_Model {
	private $table_payable = 'payable';
		
		public $id;
		public $bank_id;
		public $value;
		public $date;
		public $purchase_id;
		public $other_purchase_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->bank_id				= $db_item->bank_id;
			$this->value				= $db_item->value;
			$this->purchase_id			= $db_item->purchase_id;
			$this->other_purchase_id	= $db_item->other_purchase_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->bank_id				= $this->bank_id;
			$db_item->value					= $this->value;
			$db_item->purchase_id			= $this->purchase_id;
			$db_item->other_purchase_id		= $this->other_purchase_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id					= $db_item->id;
			$stub->bank_id				= $db_item->bank_id;
			$stub->value				= $db_item->value;
			$stub->purchase_id			= $db_item->purchase_id;
			$stub->other_purchase_id	= $db_item->other_purchase_id;
			
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
		
		public function getByPurchaseInvoiceId($purchaseInvoiceId)
		{
			$this->db->where('purchase_id', $purchaseInvoiceId);
			$query			= $this->db->get($this->table_payable);
			$result			= $query->result();
			return $result;
		}

		public function getByOtherPurchaseInvoiceId($otherPurchaseId)
		{
			$this->db->where('other_purchase_id', $otherPurchaseId);
			$query			= $this->db->get($this->table_payable);
			$result			= $query->result();
			return $result;
		}

		public function getSupplierOpponentItems($offset = 0, $term = "", $limit = 10){
			$query			= $this->db->query("
				SELECT supplier.id, supplier.name, supplier.address, supplier.number, supplier.rt, supplier.rw, supplier.city, supplier.postal_code, supplier.block, 1 AS opponentType
				FROM supplier
				WHERE supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR supplier.city LIKE '%$term%'
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

		public function countSupplierOpponentItems($term)
		{
			$query			= $this->db->query("
				SELECT supplier.id
				FROM supplier
				WHERE supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR supplier.city LIKE '%$term%'
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

		public function getBySupplierId($supplierId)
		{
			$query			= $this->db->query("
				SELECT payable.*
				FROM
				payable
				WHERE purchase_id IN
				(
					SELECT DISTINCT(purchase_invoice.id) AS id
					FROM purchase_invoice
					JOIN code_good_receipt ON purchase_invoice.id = code_good_receipt.invoice_id
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					WHERE code_purchase_order.supplier_id = '$supplierId'
					AND purchase_invoice.is_done = '0'
				)
				OR other_purchase_id IN
				(
					SELECT DISTINCT(id) FROM purchase_invoice_other
					WHERE supplier_id = '$supplierId'
					AND is_done = '0'
				)
			");

			$result			= $query->result();
			return $result;
		}

		public function getCompleteBySupplierId($supplierId)
		{
			$query			= $this->db->query("
				SELECT payable.*
				FROM
				payable
				WHERE purchase_id IN
				(
					SELECT DISTINCT(purchase_invoice.id) AS id
					FROM purchase_invoice
					JOIN code_good_receipt ON purchase_invoice.id = code_good_receipt.invoice_id
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id
					WHERE code_purchase_order.supplier_id = '$supplierId'
				)
				OR other_purchase_id IN
				(
					SELECT DISTINCT(id) FROM purchase_invoice_other
					WHERE supplier_id = '$supplierId'
				)
			");

			$result			= $query->result();
			return $result;
		}

		public function getPayableByOtherId($opponentId)
		{
			$query			= $this->db->query("
				SELECT payable.*
				FROM
				payable
				WHERE other_purchase_id IN
				(
					SELECT DISTINCT(id) FROM purchase_invoice_other
					WHERE other_opponent_id = '$opponentId'
					AND is_done = '0'
				)
			");

			$result			= $query->result();
			return $result;
		}

		public function getByBankId($bankId)
		{
			$query			= $this->db->query("
				SELECT payable.date AS paidDate, payable.value AS paidValue, COALESCE(purchaseInvoiceTable.id, purchaseInvoiceOtherTable.id) AS id, COALESCE(purchaseInvoiceTable.tax_document, purchaseInvoiceOtherTable.tax_document) AS tax_document, COALESCE(purchaseInvoiceTable.invoice_document, purchaseInvoiceOtherTable.invoice_document) AS invoice_document, COALESCE(purchaseInvoiceTable.date, purchaseInvoiceOtherTable.date) AS date, COALESCE(purchaseInvoiceTable.is_confirm, purchaseInvoiceOtherTable.is_confirm) AS is_confirm, COALESCE(purchaseInvoiceTable.supplier_id, purchaseInvoiceOtherTable.supplier_id) AS supplier_id, COALESCE(purchaseInvoiceTable.opponent_id, purchaseInvoiceOtherTable.opponent_id) AS opponent_id, COALESCE(purchaseInvoiceTable.information, purchaseInvoiceOtherTable.information) AS information
				FROM payable
				LEFT JOIN (
					SELECT purchase_invoice.id, purchase_invoice.tax_document, purchase_invoice.invoice_document, purchase_invoice.date, purchase_invoice.is_confirm, goodReceiptTable.supplier_id, NULL as opponent_id, '' AS information
					FROM purchase_invoice
					JOIN (
						SELECT DISTINCT(code_good_receipt.invoice_id) AS id, code_purchase_order.supplier_id
						FROM code_good_receipt
						JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					) goodReceiptTable
					ON purchase_invoice.id = goodReceiptTable.id
				) purchaseInvoiceTable
				ON purchaseInvoiceTable.id = payable.purchase_id
				LEFT JOIN (
					SELECT purchase_invoice_other.id, purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, purchase_invoice_other.date, purchase_invoice_other.is_confirm, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id AS opponent_id, purchase_invoice_other.information
					FROM purchase_invoice_other
				) purchaseInvoiceOtherTable
				ON purchaseInvoiceOtherTable.id = payable.other_purchase_id
				WHERE payable.bank_id = '$bankId'
			");

			$result			= $query->result();
			return $result;
		}

		public function deleteByBankId($bankId)
		{
			$this->db->where('bank_id', $bankId);
			$query		= $this->db->get($this->table_payable);
			$result		= $query->result();

			$invoiceIdArray		= array();
			$blankInvoiceIdArray	= array();
			foreach($result as $item){
				if($item->purchase_id == NULL){
					array_push($blankInvoiceIdArray, $item->other_purchase_id);
				} else {
					array_push($invoiceIdArray, $item->purchase_id);
				}
				next($result);
			}

			$this->db->db_debug = false;
			$this->db->where('bank_id', $bankId);
			$this->db->delete($this->table_payable);

			$result			= $this->db->affected_rows();
			if($result > 0){
				if(count($invoiceIdArray) > 0){
					$this->load->model("Debt_model");
					$this->Debt_model->updateDoneStatusByIdArray($invoiceIdArray, 0);
				}
				
				if(count($blankInvoiceIdArray) > 0){
					$this->load->model("Debt_other_model");
					$this->Debt_other_model->updateDoneStatusByIdArray($blankInvoiceIdArray, 0);
				}
			}
		}

		public function deleteBlankById($id)
		{
			$this->db->where('id', $id);
			$query			= $this->db->get($this->table_payable);
			$result			= $query->row();

			$purchaseInvoiceId		= $result->purchase_id;
			$blankPurchaseInvoiceId	= $result->other_purchase_id;

			$this->db->where('id', $id);
			$this->db->delete($this->table_payable);
			$result			= $this->db->affected_rows();

			if($purchaseInvoiceId != NULL){
				$this->load->model("Debt_model");
				$data	= $this->Debt_model->setInvoiceAsUndone($purchaseInvoiceId);
			} else if($blankPurchaseInvoiceId != NULL){
				$this->load->model("Debt_other_model");
				$data	= $this->Debt_other_model->setInvoiceAsUndone($blankPurchaseInvoiceId);
			}

			return $data;
		}

		public function getSuppliersWithIncompletedInvoices()
		{
			$query		= $this->db->query("
				SELECT supplier.*
				FROM supplier
				WHERE supplier.id IN (
					SELECT purchase_invoice_other.supplier_id
					FROM purchase_invoice_other
					WHERE purchase_invoice_other.is_done = '0'
					AND purchase_invoice_other.is_confirm = '1'
					UNION (
						SELECT code_purchase_order.supplier_id
						FROM good_receipt
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
						WHERE purchase_invoice.is_done = '0'
						AND purchase_invoice.is_confirm = '1'
					)
				)
			");

			$result		= $query->result();
			return $result;
		}

		public function getIncompletedInvoiceBySupplierId($supplierId)
		{
			$query			= $this->db->query("
				SELECT * FROM (
					SELECT purchase_invoice.id, purchase_invoice.date, purchase_invoice.invoice_document, purchase_invoice.tax_document, goodReceiptTable.value, COALESCE(payableTable.value,0) AS paid, '1' AS type, goodReceiptTable.payment
					FROM purchase_invoice
					JOIN (
						SELECT SUM(a.value) AS value, code_good_receipt.invoice_id, a.payment
						FROM code_good_receipt
						JOIN (
							SELECT SUM(good_receipt.billed_price * good_receipt.quantity) AS value, good_receipt.code_good_receipt_id, code_purchase_order.payment
							FROM good_receipt
							JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
							JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
							WHERE code_purchase_order.supplier_id = '$supplierId'
							GROUP BY good_receipt.code_good_receipt_id
						) AS a
						ON a.code_good_receipt_id = code_good_receipt.id
						GROUP BY code_good_receipt.invoice_id
					) AS goodReceiptTable
					ON goodReceiptTable.invoice_id = purchase_invoice.id
					LEFT JOIN (
						SELECT SUM(payable.value) AS value, purchase_id
						FROM payable
						GROUP BY purchase_id
					) AS payableTable
					ON purchase_invoice.id = payableTable.purchase_id
					WHERE purchase_invoice.is_confirm = '1'
					AND purchase_invoice.is_done = '0'
					UNION (
						SELECT purchase_invoice_other.id, purchase_invoice_other.date, purchase_invoice_other.invoice_document, purchase_invoice_other.tax_document, purchase_invoice_other.value, COALESCE(payableTable.value,0) AS paid, '2' AS type, '30' AS payment
						FROM purchase_invoice_other
						LEFT JOIN (
							SELECT SUM(payable.value) AS value, other_purchase_id
							FROM payable
							GROUP BY other_purchase_id
						) payableTable
						ON payableTable.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.supplier_id = '$supplierId'
						AND purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
					)
				) purchaseInvoiceTable
				ORDER BY purchaseInvoiceTable.date ASC, purchaseInvoiceTable.invoice_document ASC, purchaseInvoiceTable.tax_document ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function getSetDoneGain($month, $year)
		{
			$this->db->select_sum('value');
			if($month != 0){
				$this->db->where('MONTH(date)', $month);
			}
			$this->db->where('YEAR(date)', $year);
			$this->db->where('bank_id', NULL);
			$query		= $this->db->get($this->table_payable);
			$result		= $query->row();
			return (float)$result->value;
		}
	}
?>
