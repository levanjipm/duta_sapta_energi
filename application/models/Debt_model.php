<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_model extends CI_Model {
	private $table_purchase_invoice = 'purchase_invoice';
		
		public $id;
		public $date;
		public $created_by;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $is_done;
		public $tax_document;
		public $invoice_document;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->created_by			= $db_item->created_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_done				= $db_item->is_done;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->created_by			= $this->created_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_done				= $this->is_done;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->created_by			= $this->created_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_done				= $this->is_done;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->created_by			= $db_item->created_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_done				= $db_item->is_done;
			
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
		
		public function insertItem()
		{
			$tax_document		= $this->input->post('taxInvoiceName');
			if(strlen($tax_document) < 19){
				$tax_document	= NULL;
			}
			
			$data		= array(
				'id' => '',
				'created_by' => $this->session->userdata('user_id'),
				'date' => $this->input->post('date'),
				'tax_document' => $tax_document,
				'invoice_document' => $this->input->post('invoiceName'),
				'is_confirm' => '0',
				'is_delete' => '0',
				'confirmed_by' => null,
				'is_done' => '0'
			);
				
			$this->db->insert($this->table_purchase_invoice, $data);
			$insert_id 	= $this->db->insert_id();
			
			if ($this->db->affected_rows() > 0){
				return $insert_id;
			} else {
				return NULL;
			}
		}
		
		public function showUnconfirmedDocuments($offset = 0, $term = '', $limit = 25)
		{
			$query		= $this->db->query("
				SELECT DISTINCT(purchase_invoice.id) as id, purchase_invoice.date, purchase_invoice.tax_document, purchase_invoice.invoice_document, supplier.name, supplier.address, supplier.city, '' as information, '' as type, '' as class 
				FROM purchase_invoice
				LEFT JOIN code_good_receipt ON code_good_receipt.invoice_id = purchase_invoice.id
				JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
				JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
				JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				WHERE purchase_invoice.is_confirm = '0' AND purchase_invoice.is_delete = '0'
				UNION (
					SELECT purchase_invoice_other.id, purchase_invoice_other.date, purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(supplier.address, other_opponent.description) as address, COALESCE(supplier.city, other_opponent_type.name) as city, purchase_invoice_other.information, debt_type.name as type, 'Blank' as class
					FROM purchase_invoice_other
					JOIN debt_type ON purchase_invoice_other.type = debt_type.id
					LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
					LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
					LEFT JOIN other_opponent_type ON other_opponent.type = other_opponent_type.id
					WHERE purchase_invoice_other.is_confirm = '0' AND purchase_invoice_other.is_delete = '0'
				)
				LIMIT $limit OFFSET $offset
			");

			$result	= $query->result();
			return $result;
		}
		
		public function countUnconfirmedDocuments($term = '')
		{
			$this->db->like('purchase_invoice.tax_document', $term, 'both');
			$this->db->or_like('purchase_invoice.invoice_document', $term, 'both');
			$this->db->where('purchase_invoice.is_confirm', 0);
			$this->db->where('purchase_invoice.is_delete', 0);
			
			$query = $this->db->get($this->table_purchase_invoice);
			$result	= $query->num_rows();
			
			return $result;
		}
		
		public function getById($invoice_id)
		{
			$this->db->select('purchase_invoice.*, code_purchase_order.supplier_id');
			$this->db->from('purchase_invoice');
			$this->db->join('code_good_receipt', 'code_good_receipt.invoice_id = purchase_invoice.id');
			$this->db->join('good_receipt', 'code_good_receipt.id = good_receipt.code_good_receipt_id');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');

			$this->db->where('purchase_invoice.id', $invoice_id);
			
			$query = $this->db->get();
			$result	= $query->row();
			
			return $result;
		}
		
		public function updateById($status, $invoice_id)
		{
			$confirmed_by = $this->session->userdata('user_id');
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $confirmed_by);
				$this->db->where('is_delete', 0);
				$this->db->where('id', $invoice_id);
			} else if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->where('is_confirm', 0);
				$this->db->where('id', $invoice_id);
			}

			$this->db->update($this->table_purchase_invoice);

			return $this->db->affected_rows();
		}
		
		public function viewPayableChart($category)
		{
			if($category == 1){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id, NULL as other_opponent_id
					FROM good_receipt 
					INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN supplier ON code_purchase_order.supplier_id = supplier.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					LEFT JOIN
						(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
					ON a.purchase_id = purchase_invoice.id
					WHERE purchase_invoice.is_done = '0'
					GROUP BY code_purchase_order.supplier_id
					UNION
					(
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						GROUP BY IF(purchase_invoice_other.supplier_id = null, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			} else if($category == 2){
				$query		= $this->db->query("
					SELECT a.value, supplier.name, COALESCE(b.value,0) AS paid, a.supplier_id, NULL as other_opponent_id
					FROM (
						SELECT SUM(good_receipt.billed_price * good_receipt.quantity) AS value, code_purchase_order.payment, purchase_invoice.date, code_good_receipt.invoice_id, code_purchase_order.supplier_id
						FROM good_receipt
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
						JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
						GROUP BY code_good_receipt.invoice_id
					) AS a
					LEFT JOIN (
						SELECT SUM(payable.value) AS value, payable.purchase_id
						FROM payable
						GROUP BY payable.purchase_id	
					) AS b
					ON a.invoice_id = b.purchase_id
					JOIN supplier ON supplier.id = a.supplier_id
					WHERE DATE_ADD(a.date, INTERVAL a.payment DAY) < CURDATE()
					OR DATE_ADD(a.date, INTERVAL a.payment DAY) = CURDATE()
					UNION (
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						AND (DATE_ADD(purchase_invoice_other.date, INTERVAL purchase_invoice_other.payment DAY) < CURDATE() OR DATE_ADD(purchase_invoice_other.date, INTERVAL purchase_invoice_other.payment DAY) = CURDATE())
						GROUP BY IF(purchase_invoice_other.supplier_id IS NULL, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			} else if($category == 3){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id, NULL as other_opponent_id
					FROM good_receipt 
					INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN supplier ON code_purchase_order.supplier_id = supplier.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					LEFT JOIN
						(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
					ON a.purchase_id = purchase_invoice.id
					WHERE purchase_invoice.is_done = '0'
					AND purchase_invoice.date >= DATE_ADD(CURDATE(), INTERVAL - 30 DAY)
					GROUP BY code_purchase_order.supplier_id
					UNION
					(
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						AND purchase_invoice_other.date >= DATE_ADD(CURDATE(), INTERVAL - 30 DAY)
						GROUP BY IF(purchase_invoice_other.supplier_id = null, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			} else if($category == 4){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id, NULL as other_opponent_id
					FROM good_receipt 
					INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN supplier ON code_purchase_order.supplier_id = supplier.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					LEFT JOIN
						(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
					ON a.purchase_id = purchase_invoice.id
					WHERE purchase_invoice.is_done = '0'
					AND purchase_invoice.date < DATE_ADD(CURDATE(), INTERVAL - 45 DAY) AND purchase_invoice.date >= DATE_ADD(CURDATE(), INTERVAL - 30 DAY)
					GROUP BY code_purchase_order.supplier_id
					UNION
					(
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						AND purchase_invoice_other.date < DATE_ADD(CURDATE(), INTERVAL - 45 DAY) AND purchase_invoice_other.date >= DATE_ADD(CURDATE(), INTERVAL - 30 DAY)
						GROUP BY IF(purchase_invoice_other.supplier_id = null, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			} else if($category == 5){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id, NULL as other_opponent_id
					FROM good_receipt 
					INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN supplier ON code_purchase_order.supplier_id = supplier.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					LEFT JOIN
						(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
					ON a.purchase_id = purchase_invoice.id
					WHERE purchase_invoice.is_done = '0'
					AND purchase_invoice.date < DATE_ADD(CURDATE(), INTERVAL - 60 DAY) AND purchase_invoice.date >= DATE_ADD(CURDATE(), INTERVAL - 45 DAY)
					GROUP BY code_purchase_order.supplier_id
					UNION
					(
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						AND purchase_invoice_other.date < DATE_ADD(CURDATE(), INTERVAL - 60 DAY) AND purchase_invoice_other.date >= DATE_ADD(CURDATE(), INTERVAL - 45 DAY)
						GROUP BY IF(purchase_invoice_other.supplier_id = null, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			} else if($category == 6){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id, NULL as other_opponent_id
					FROM good_receipt 
					INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN supplier ON code_purchase_order.supplier_id = supplier.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					LEFT JOIN
						(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
					ON a.purchase_id = purchase_invoice.id
					WHERE purchase_invoice.is_done = '0'
					AND purchase_invoice.date < DATE_ADD(CURDATE(), INTERVAL - 60 DAY)
					GROUP BY code_purchase_order.supplier_id
					UNION
					(
						SELECT SUM(purchase_invoice_other.value) as value, COALESCE(supplier.name, other_opponent.name) as name, COALESCE(b.value,0) as paid, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id
						FROM purchase_invoice_other
						LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
						LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
						LEFT JOIN (
							SELECT SUM(value) AS value, other_purchase_id FROM payable GROUP BY other_purchase_id
						) b
						ON b.other_purchase_id = purchase_invoice_other.id
						WHERE purchase_invoice_other.is_done = '0'
						AND purchase_invoice_other.is_confirm = '1'
						AND purchase_invoice_other.date < DATE_ADD(CURDATE(), INTERVAL - 60 DAY)
						GROUP BY IF(purchase_invoice_other.supplier_id = null, purchase_invoice_other.other_opponent_id, purchase_invoice_other.supplier_id)
					)
				");
			}
			$result		= $query->result();
			
			return $result;
		}

		public function getPayableBySupplierId($supplierId)
		{
			$query = $this->db->query("
				SELECT purchase_invoice.*, SUM(good_receipt.billed_price * good_receipt.quantity) as value, COALESCE(a.value,0) AS paid, 1 AS type 
				FROM good_receipt
				INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
				JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
				INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
				LEFT JOIN
					(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
				ON a.purchase_id = purchase_invoice.id
				WHERE purchase_invoice.is_done = '0'
				AND code_purchase_order.supplier_id = '$supplierId'
				GROUP BY code_good_receipt.invoice_id
				UNION (
					SELECT purchase_invoice_other.id, purchase_invoice_other.date,  purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, purchase_invoice_other.created_by, purchase_invoice_other.is_confirm, purchase_invoice_other.is_delete, purchase_invoice_other.confirmed_by, purchase_invoice_other.is_done, purchase_invoice_other.value, COALESCE(b.value,0) as paid, 2 AS type
					FROM purchase_invoice_other
					LEFT JOIN (
						SELECT SUM(value) as value, payable.other_purchase_id FROM payable GROUP BY payable.other_purchase_id
					) AS b
					ON b.other_purchase_id = purchase_invoice_other.id
					WHERE purchase_invoice_other.supplier_id = '$supplierId'
					AND purchase_invoice_other.is_done = '0'
				)
			");

			$result = $query->result();
			return $result;
		}

		public function getCompletePayableBySupplierId($supplierId)
		{
			$query = $this->db->query("
				SELECT purchase_invoice.*, SUM(good_receipt.billed_price * good_receipt.quantity) as value, COALESCE(a.value,0) AS paid, 1 AS type 
				FROM good_receipt
				INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
				JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
				INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
				LEFT JOIN
					(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
				ON a.purchase_id = purchase_invoice.id
				AND code_purchase_order.supplier_id = '$supplierId'
				GROUP BY code_good_receipt.invoice_id
				UNION (
					SELECT purchase_invoice_other.id, purchase_invoice_other.date,  purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, purchase_invoice_other.created_by, purchase_invoice_other.is_confirm, purchase_invoice_other.is_delete, purchase_invoice_other.confirmed_by, purchase_invoice_other.is_done, purchase_invoice_other.value, COALESCE(b.value,0) as paid, 2 AS type
					FROM purchase_invoice_other
					LEFT JOIN (
						SELECT SUM(value) as value, payable.other_purchase_id FROM payable GROUP BY payable.other_purchase_id
					) AS b
					ON b.other_purchase_id = purchase_invoice_other.id
					WHERE purchase_invoice_other.supplier_id = '$supplierId'
				)
			");

			$result		= $query->result();
			return $result;
		}

		public function getPayableByOtherId($otherId)
		{
			$query		= $this->db->query("
				SELECT purchase_invoice_other.id, purchase_invoice_other.date,  purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, purchase_invoice_other.created_by, purchase_invoice_other.is_confirm, purchase_invoice_other.is_delete, purchase_invoice_other.confirmed_by, purchase_invoice_other.is_done, purchase_invoice_other.value, COALESCE(b.value,0) as paid
				FROM purchase_invoice_other
				LEFT JOIN (
					SELECT SUM(value) as value, payable.other_purchase_id FROM payable GROUP BY payable.other_purchase_id
				) AS b
				ON b.other_purchase_id = purchase_invoice_other.id
				WHERE purchase_invoice_other.other_opponent_id = '$otherId'
			");
			$result = $query->result();
			return $result;
		}
		
		public function getIncompletedTransaction($supplier_id)
		{
			$query		= $this->db->query("
				SELECT a.value as value, COALESCE(b.paid,0) as paid, purchase_invoice.id, purchase_invoice.date, purchase_invoice.invoice_document as name, purchase_invoice.tax_document, 1 AS type
				FROM (
					SELECT SUM(good_receipt.quantity * good_receipt.billed_price) as value, code_good_receipt.invoice_id
					FROM good_receipt
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					WHERE code_purchase_order.supplier_id = '$supplier_id'
					GROUP BY code_good_receipt.invoice_id
				) AS a
				JOIN purchase_invoice ON a.invoice_id = purchase_invoice.id
				LEFT JOIN (
					SELECT SUM(value) as paid, purchase_id FROM payable
					GROUP BY purchase_id
					) b
				ON a.invoice_id = b.purchase_id
				WHERE purchase_invoice.is_done = '0'
				UNION (
					SELECT purchase_invoice_other.value, COALESCE(payableTable.paid, 0) AS paid, purchase_invoice_other.id, purchase_invoice_other.date, purchase_invoice_other.invoice_document as name, purchase_invoice_other.tax_document, 2 AS type
					FROM purchase_invoice_other
					LEFT JOIN (
						SELECT SUM(payable.value) AS paid, payable.other_purchase_id
						FROM payable
						GROUP BY payable.other_purchase_id
					) payableTable
					ON payableTable.other_purchase_id = purchase_invoice_other.id
					WHERE purchase_invoice_other.is_done = '0'
				)
			");
			
			$result	= $query->result();
			
			return $result;
		}
		
		public function deleteById($purchaseInvoiceId)
		{
			$this->db->where('id', $purchaseInvoiceId);
			$this->db->delete($this->table_purchase_invoice);
		}

		public function getItems($offset, $month, $year)
		{
			$query = $this->db->query("
				SELECT purchase_invoice.id, purchase_invoice.date, purchase_invoice.tax_document, purchase_invoice.invoice_document, a.supplier_id, NULL as other_opponent_id, NULL as type, 'regular' as class
				FROM purchase_invoice
				JOIN (
					SELECT DISTINCT(code_good_receipt.invoice_id) AS id, code_purchase_order.supplier_id 
					FROM code_good_receipt
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					GROUP BY code_good_receipt.invoice_id
				) AS a
				ON a.id = purchase_invoice.id
				WHERE MONTH(purchase_invoice.date) = '$month' AND YEAR(purchase_invoice.date) = '$year'
				AND purchase_invoice.is_delete = '0'
				UNION (
					SELECT purchase_invoice_other.id, purchase_invoice_other.date, purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, purchase_invoice_other.supplier_id, purchase_invoice_other.other_opponent_id, debt_type.name as type, 'blank' as class
					FROM purchase_invoice_other
					JOIN debt_type ON purchase_invoice_other.type = debt_type.id
					WHERE MONTH(purchase_invoice_other.date) = '$month' AND YEAR(purchase_invoice_other.date) = '$year'
					AND purchase_invoice_other.is_delete = '0'
				)
				ORDER BY date ASC
				LIMIT 10 OFFSET $offset
			");
			
			$result = $query->result();

			return $result;
		}

		public function countItems($month, $year)
		{
			$this->db->where('MONTH(date)', $month);
			$this->db->where('YEAR(date)', $year);
			$this->db->where('is_delete', 0);
			$query = $this->db->get($this->table_purchase_invoice);

			$result = $query->num_rows();
			return $result;
		}

		public function getYears()
		{
			$query	= $this->db->query("
				SELECT DISTINCT(a.years) as years FROM
				(
					SELECT DISTINCT(YEAR(purchase_invoice.date)) as years FROM purchase_invoice
					UNION (SELECT DISTINCT(YEAR(purchase_invoice_other.date)) AS years FROM purchase_invoice_other)
				) a
				ORDER BY a.years ASC
			");

			$result = $query->result();

			return $result;
		}

		public function getConfirmedItems($month, $year, $offset = 0, $limit = 10)
		{
			$query			= $this->db->query("
				SELECT purchase_invoice.id, purchase_invoice.date, purchase_invoice.tax_document, purchase_invoice.invoice_document, supplier.name as supplierName, supplier.city as type, 'regular' as class
				FROM purchase_invoice
				JOIN (
					SELECT DISTINCT(code_good_receipt.invoice_id) AS id, code_purchase_order.supplier_id 
					FROM code_good_receipt
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					GROUP BY code_good_receipt.invoice_id
				) AS a
				ON a.id = purchase_invoice.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE MONTH(purchase_invoice.date) = '$month' AND YEAR(purchase_invoice.date) = '$year'
				AND purchase_invoice.is_delete = '0'
				AND purchase_invoice.is_confirm = '1'
				UNION (
					SELECT purchase_invoice_other.id, purchase_invoice_other.date, purchase_invoice_other.tax_document, purchase_invoice_other.invoice_document, COALESCE(supplier.name, other_opponent.name) AS supplierName, COALESCE(supplier.city, other_opponent.description) AS type, 'blank' as class
					FROM purchase_invoice_other
					JOIN debt_type ON purchase_invoice_other.type = debt_type.id
					LEFT JOIN supplier ON purchase_invoice_other.supplier_id = supplier.id
					LEFT JOIN other_opponent ON purchase_invoice_other.other_opponent_id = other_opponent.id
					WHERE MONTH(purchase_invoice_other.date) = '$month' AND YEAR(purchase_invoice_other.date) = '$year'
					AND purchase_invoice_other.is_delete = '0'
					AND purchase_invoice_other.is_confirm = '1'
				)
				ORDER BY date ASC
				LIMIT $limit OFFSET $offset
			");

			$result			= $query->result();
			return $result;
		}
		
		public function countConfirmedItems($month, $year)
		{
			$query			= $this->db->query("
				SELECT purchase_invoice.id
				FROM purchase_invoice
				WHERE MONTH(purchase_invoice.date) = '$month' AND YEAR(purchase_invoice.date) = '$year'
				AND purchase_invoice.is_delete = '0'
				AND purchase_invoice.is_confirm = '1'
				UNION (
					SELECT purchase_invoice_other.id
					FROM purchase_invoice_other
					WHERE MONTH(purchase_invoice_other.date) = '$month' AND YEAR(purchase_invoice_other.date) = '$year'
					AND purchase_invoice_other.is_delete = '0'
					AND purchase_invoice_other.is_confirm = '1'
				)
			");

			$result			= $query->num_rows();
			return $result;
		}

		public function deleteDebtById($id)
		{
			$this->db->select('payable.*');
			$this->db->from('payable');
			$this->db->where('payable.purchase_id', $id);
			$query			= $this->db->get();
			$result			= $query->num_rows();

			if($result == 0){
				$this->db->set('is_delete', 1);
				$this->db->set('is_confirm ', 0);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 1);
				$this->db->where('id', $id);

				$query		= $this->db->update($this->table_purchase_invoice);
				$result		= $this->db->affected_rows();
				return $result;
			} else {
				return 0;
			}
		}

		public function setInvoiceAsDone($invoiceId, $date)
		{
			$query			= $this->db->query("
				SELECT goodReceiptTable.value, COALESCE(payableTable.value, 0) AS paid
				FROM (
					SELECT SUM(good_receipt.quantity * good_receipt.billed_price) AS value, code_good_receipt.invoice_id
					FROM good_receipt
					JOIN code_good_receipt
					ON good_receipt.code_good_receipt_id = code_good_receipt_id
					GROUP BY code_good_receipt.invoice_id
				) goodReceiptTable
				LEFT JOIN (
					SELECT SUM(value) AS value, purchase_id FROM payable
					GROUP BY purchase_id
				) payableTable
				ON payableTable.purchase_id = goodReceiptTable.invoice_id
			");

			$result			= $query->row();
			$value			= $result->value;
			$paid			= $result->paid;
			if($value > $paid){
				$db_item		= array(
					"id" => "",
					"value" => ($value - $paid),
					"bank_id" => NULL,
					"date" => $date,
					"purchase_id" => $invoiceId,
					"other_purchase_id" => NULL
				);

				$this->db->insert("payable", $db_item);
				if($this->db->affected_rows() == 1){
					$this->db->set('is_done', 1);
					$this->db->where('id', $invoiceId);
					$this->db->update($this->table_purchase_invoice);
				}

				return 1;
			} else {
				return 0;
			}
		}

		public function updateDoneStatusByIdArray($idArray)
		{
			$this->db->set('is_done', 0);
			$this->db->where_in("id", $idArray);
			$this->db->update($this->table_purchase_invoice);
		}

		public function getBySupplierIdPeriod($supplierId, $month, $year)
		{
			if($month == NULL){
				$query		= $this->db->query("
					SELECT COALESCE(SUM(a.value), 0) AS value, a.name, a.description 
					FROM (
						SELECT SUM(good_receipt.billed_price * good_receipt.quantity) AS value, item_class.name, item_class.id, item_class.description
						FROM good_receipt
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN item ON purchase_order.item_id = item.id
						JOIN item_class ON item.type = item_class.id
						JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
						WHERE code_good_receipt.is_confirm = '1'
						AND purchase_invoice.is_confirm = '1'
						AND YEAR(purchase_invoice.date) = '$year'
						GROUP BY item_class.id
						UNION (
							SELECT SUM((-1) * purchase_return_sent.quantity * purchase_return.price) AS value, item_class.name, item_class.id, item_class.description
							FROM purchase_return_sent
							JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
							JOIN code_purchase_return_sent ON purchase_return_sent.code_purchase_return_sent_id = code_purchase_return_sent.id
							JOIN item ON purchase_return.item_id = item.id
							JOIN item_class ON item.type = item_class.id
							WHERE YEAR(code_purchase_return_sent.date) = '$year'
							AND code_purchase_return_sent.is_confirm = '1'
							GROUP BY item_class.id
						)
					) AS a
					GROUP BY a.id
				");
			} else {
				$query		= $this->db->query("
					SELECT COALESCE(SUM(a.value), 0) AS value, a.name, a.id, a.description FROM (
						SELECT (good_receipt.billed_price * good_receipt.quantity) AS value, item_class.name, item_class.id, item_class.description
						FROM good_receipt
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN item ON purchase_order.item_id = item.id
						JOIN item_class ON item.type = item_class.id
						JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
						WHERE code_good_receipt.is_confirm = '1'
						AND purchase_invoice.is_confirm = '1'
						AND YEAR(purchase_invoice.date) = '$year'
						AND MONTH(purchase_invoice.date) = '$month'
						GROUP BY item_class.id
						UNION (
							SELECT SUM((-1) * purchase_return_sent.quantity * purchase_return.price) AS value, item_class.name, item_class.id, item_class.description
							FROM purchase_return_sent
							JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
							JOIN code_purchase_return_sent ON purchase_return_sent.code_purchase_return_sent_id = code_purchase_return_sent.id
							JOIN item ON purchase_return.item_id = item.id
							JOIN item_class ON item.type = item_class.id
							WHERE YEAR(code_purchase_return_sent.date) = '$year'
							AND MONTH(code_purchase_return_sent.date) = '$month'
							AND code_purchase_return_sent.is_confirm = '1'
							GROUP BY item_class.id
						)
					) AS a
					GROUP BY a.id
					ORDER BY a.name ASC
				");
			}

			$result			= $query->result();
			return $result;
		}

		public function viewPurchaseByMonth()
		{
			$query			= $this->db->query("
				SELECT SUM(purchaseInvoiceTable.value) AS value, purchaseInvoiceTable.month, purchaseInvoiceTable.year FROM (
					SELECT a.value, MONTH(purchase_invoice.date) AS month, YEAR(purchase_invoice.date) AS year
					FROM (
						SELECT SUM(good_receipt.quantity * good_receipt.billed_price) AS value, code_good_receipt.invoice_id
						FROM good_receipt
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						GROUP BY code_good_receipt.invoice_id
					) AS a
					JOIN purchase_invoice ON a.invoice_id = purchase_invoice.id
					WHERE purchase_invoice.is_confirm = '1'
					AND DATEDIFF(CURDATE(), purchase_invoice.date) <= 180 AND DATEDIFF(CURDATE(), purchase_invoice.date) > 0
					GROUP BY MONTH(purchase_invoice.date), YEAR(purchase_invoice.date)
					UNION (
						SELECT purchase_invoice_other.value, MONTH(purchase_invoice_other.date) AS month, YEAR(purchase_invoice_other.date) AS year
						FROM purchase_invoice_other
						WHERE DATEDIFF(CURDATE(), purchase_invoice_other.date) <= 180 AND DATEDIFF(CURDATE(), purchase_invoice_other.date) > 0
						GROUP BY MONTH(purchase_invoice_other.date), YEAR(purchase_invoice_other.date)
					)
				) purchaseInvoiceTable
				GROUP BY purchaseInvoiceTable.month, purchaseInvoiceTable.year
				ORDER BY purchaseInvoiceTable.year DESC, purchaseInvoiceTable.month	DESC
			");

			$result			= $query->result();
			return $result;
		}

		public function setInvoiceAsUndone($id)
		{
			$this->db->set('is_done', 0);
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_invoice);
			$result			= $this->db->affected_rows();
			return $result;
		}

		public function getIncompletedTransactionByDate($date)
		{
			$query			= $this->db->query("
				SELECT COALESCE(SUM(debtTable.value - debtTable.paid), 0) AS value
				FROM (
					SELECT purchase_invoice_other.id, COALESCE(purchase_invoice_other.value,0) AS value, COALESCE(payableTable.value,0) AS paid
					FROM purchase_invoice_other
					LEFT JOIN (
						SELECT SUM(payable.value) AS value, payable.other_purchase_id
						FROM payable
						JOIN bank_transaction ON payable.bank_id = bank_transaction.id
						GROUP BY payable.other_purchase_id
					) payableTable
					ON purchase_invoice_other.id = payableTable.other_purchase_id
					WHERE purchase_invoice_other.is_confirm = '1'
					UNION (
						SELECT purchaseInvoiceTable.id, COALESCE(purchaseInvoiceTable.value, 0) AS value, COALESCE(payableTable.value) AS paid
						FROM (
							SELECT SUM(good_receipt.quantity * good_receipt.billed_price) AS value, purchase_invoice.id
							FROM good_receipt
							JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
							JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
							WHERE purchase_invoice.date <= '$date'
							AND purchase_invoice.is_confirm = '1'
							GROUP BY purchase_invoice.id
						) purchaseInvoiceTable
						LEFT JOIN (
							SELECT SUM(payable.value) AS value, payable.purchase_id
							FROM payable
							JOIN bank_transaction ON bank_transaction.id = payable.bank_id
							WHERE bank_transaction.date <= '$date'
							GROUP BY payable.purchase_id
						) payableTable
						ON purchaseInvoiceTable.id = payableTable.purchase_id
					)
					UNION (
						SELECT NULL as id, SUM(purchase_order.net_price * good_receipt.quantity) AS value, 0 AS paid
						FROM good_receipt
						JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
						JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						WHERE code_good_receipt.is_confirm = '1'
						AND code_good_receipt.invoice_id IS NULL
					)
				) debtTable
			");

			$result		= $query->row();
			return $result->value;
		}

		public function getValueByMonthYear($month, $year)
		{
			if($month == 0){
				$query		= $this->db->query("
					SELECT SUM(good_receipt.quantity * good_receipt.billed_price) AS value
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					WHERE YEAR(purchase_invoice.date) = '$year'
					AND purchase_invoice.is_confirm = '1'
				");
			} else {
				$query		= $this->db->query("
					SELECT SUM(good_receipt.quantity * good_receipt.billed_price) AS value
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
					WHERE MONTH(purchase_invoice.date) = '$month' AND YEAR(purchase_invoice.date) = '$year'
					AND purchase_invoice.is_confirm = '1'
				");
			}

			$result		= $query->row();
			return $result->value;
		}

		public function getValueByPeriod($supplierId, $maxDate, $minDate)
		{
			$query			= $this->db->query("
				SELECT SUM(goodReceiptTable.value) AS value, MONTH(purchase_invoice.date) AS month, YEAR(purchase_invoice.date) AS year
				FROM
				purchase_invoice	
				JOIN (
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) AS value, code_good_receipt.invoice_id
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					WHERE code_purchase_order.supplier_id = '$supplierId'
					AND code_good_receipt.is_confirm = '1'
					GROUP BY code_good_receipt.invoice_id
				) goodReceiptTable
				ON purchase_invoice.id = goodReceiptTable.invoice_id
				WHERE purchase_invoice.date <= '$maxDate'
				AND purchase_invoice.date >= '$minDate'
				AND purchase_invoice.is_confirm = '1'
				UNION (
					SELECT SUM(purchase_invoice_other.value) AS value, MONTH(purchase_invoice_other.date) AS month, YEAR(purchase_invoice_other.date) AS year
					FROM purchase_invoice_other
					WHERE purchase_invoice_other.date <= '$maxDate'
					AND purchase_invoice_other.date >= '$minDate'
					AND purchase_invoice_other.is_confirm = '1'
					AND purchase_invoice_other.supplier_id = '$supplierId'
					GROUP BY MONTH(purchase_invoice_other.date), YEAR(purchase_invoice_other.date)
				)
				UNION (
					SELECT SUM((-1) * purchase_return_sent.quantity * purchase_return.price) AS value, MONTH(code_purchase_return_sent.date) AS month, YEAR(code_purchase_return_sent.date) AS year
					FROM purchase_return_sent
					JOIN code_purchase_return_sent ON purchase_return_sent.code_purchase_return_sent_id = code_purchase_return_sent.id
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
					WHERE code_purchase_return_sent.date <= '$maxDate'
					AND code_purchase_return_sent.date >= '$minDate'
					AND code_purchase_return_sent.is_confirm = '1'
					AND code_purchase_return.supplier_id = '$supplierId'
					GROUP BY MONTH(code_purchase_return_sent.date), YEAR(code_purchase_return_sent.date)
				)
			");

			$result		= $query->result();
			$response	= array();
			foreach($result as $item){
				$month		= $item->month;
				$year		= $item->year;

				$index		= (date("m", strtotime($maxDate)) - $month) + 12 * (date("Y", strtotime($maxDate)) - $year);
				if(!array_key_exists($index, $response)){
					$response[$index]	= array(
						"value" => $item->value,
						"label" => date("M Y", mktime(0,0,0,$month, 1, $year))
					);
				} else {
					$response[$index]['value']	+= $item->value;
				}

				next($result);
			}
			
			$difference		= (date("m", strtotime($maxDate)) - date("m", strtotime($minDate))) + 12 * (date("Y", strtotime($maxDate)) - date("Y", strtotime($minDate)));
			for($i = 0 ;$i <= $difference; $i++){
				if(!array_key_exists($i, $response)){
					$date		= mktime(0, 0, 0, date('m', strtotime("-" . $i . "month", strtotime($maxDate))), 1, date('Y', strtotime("-" . $i . "month", strtotime($maxDate))));
					$response[$i] = array(
						"value" => 0,
						"label" => date("M Y", $date)
					);
				}
			}

			return $response;
		}

		public function getValueByDateRange($supplierId, $dateStart, $dateEnd)
		{
			$query			= $this->db->query("
				SELECT COALESCE(SUM(goodReceiptTable.value), 0) AS value FROM purchase_invoice
				JOIN (
					SELECT SUM(good_receipt.billed_price * good_receipt.quantity) AS value, code_good_receipt.invoice_id
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
					WHERE code_purchase_order.supplier_id = '$supplierId'
					AND code_good_receipt.is_confirm = '1'
					GROUP BY code_good_receipt.invoice_id
				) goodReceiptTable
				ON goodReceiptTable.invoice_id = purchase_invoice.id
				WHERE purchase_invoice.date <= '$dateEnd'
				AND purchase_invoice.date >= '$dateStart'
				AND purchase_invoice.is_confirm = '1'
				UNION (
					SELECT SUM(purchase_invoice_other.value) AS value
					FROM purchase_invoice_other
					WHERE purchase_invoice_other.is_confirm = '1'
					AND purchase_invoice_other.supplier_id = '$supplierId'
					AND purchase_invoice_other.date <= '$dateEnd'
					AND purchase_invoice_other.date >= '$dateStart'
					AND purchase_invoice_other.is_confirm = '1'
				)
				UNION (
					SELECT ((-1) * purchase_return_sent.quantity * purchase_return.price) AS value
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return_sent ON purchase_return_sent.code_purchase_return_sent_id = code_purchase_return_sent.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return_id
					WHERE code_purchase_return.supplier_id = '$supplierId'
					AND code_purchase_return_sent.date <= '$dateEnd'
					AND code_purchase_return_sent.date >= '$dateStart'
					AND code_purchase_return_sent.is_confirm = '1'
					GROUP BY purchase_return_sent.id
				)
			");

			$result			= $query->result();
			$totalValue		= 0;
			foreach($result as $item){
				$totalValue	+= (float) $item->value;
			}
			return $totalValue;
		}
	}
?>
