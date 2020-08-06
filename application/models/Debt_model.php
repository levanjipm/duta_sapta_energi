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
			$this->db->select('DISTINCT(purchase_invoice.id) as id, purchase_invoice.date, purchase_invoice.tax_document, purchase_invoice.invoice_document, supplier.name, supplier.address, supplier.city');
			$this->db->from('purchase_invoice');
			$this->db->join('code_good_receipt', 'code_good_receipt.invoice_id = purchase_invoice.id', 'left');
			$this->db->join('good_receipt', 'code_good_receipt.id = good_receipt.code_good_receipt_id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id', 'inner');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('purchase_invoice.is_confirm', 0);
			$this->db->where('purchase_invoice.is_delete', 0);
			$this->db->limit($limit, $offset);
			
			$query = $this->db->get();
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
			$this->db->select('purchase_invoice.*, supplier.name, supplier.address, supplier.city');
			$this->db->from('purchase_invoice');
			$this->db->join('code_good_receipt', 'code_good_receipt.invoice_id = purchase_invoice.id');
			$this->db->join('good_receipt', 'code_good_receipt.id = good_receipt.code_good_receipt_id');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');

			$this->db->where('purchase_invoice.is_confirm', 0);
			$this->db->where('purchase_invoice.is_delete', 0);
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
		
		public function viewPayableChart()
		{
			$query		= $this->db->query("SELECT SUM(good_receipt.billed_price * good_receipt.quantity) as value, supplier.name, supplier.city, COALESCE(a.value,0) as paid, code_purchase_order.supplier_id
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
			");
			$result		= $query->result();
			
			return $result;
		}

		public function getPayableBySupplierId($supplierId)
		{
			$query = $this->db->query("
				SELECT purchase_invoice.*, SUM(good_receipt.billed_price * good_receipt.quantity) as value, COALESCE(a.value,0) AS paid FROM good_receipt
				INNER JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id 
				JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
				INNER JOIN code_purchase_order ON purchase_order.code_purchase_order_id = code_purchase_order.id
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				JOIN purchase_invoice ON code_good_receipt.invoice_id = purchase_invoice.id
				LEFT JOIN
					(SELECT SUM(value) as value, purchase_id FROM payable GROUP BY purchase_id) a
				ON a.purchase_id = purchase_invoice.id
				GROUP BY code_good_receipt_id.purchase_invoice_id
				WHERE purchase_invoice.is_done = '0'
				AND code_purchase_order.supplier_id = '$supplierId'
			");

			$result = $query->result();
			return $result;
		}
		
		public function getIncompletedTransaction($supplier_id)
		{
			$this->db->select('sum(good_receipt.quantity * good_receipt.billed_price) as value, purchase_invoice.id, purchase_invoice.date, purchase_invoice.invoice_document as name, purchase_invoice.tax_document, sum(payable.value) as paid');
			$this->db->from('purchase_invoice');
			$this->db->join('code_good_receipt', 'code_good_receipt.invoice_id = purchase_invoice.id');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'left');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'code_purchase_order.id = purchase_order.code_purchase_order_id');
			$this->db->join('payable', 'purchase_invoice.id = payable.purchase_id', 'left');
			$this->db->group_by('payable.purchase_id');
			$this->db->where('code_purchase_order.supplier_id', $supplier_id);
			$this->db->where('purchase_invoice.is_done', 0);
			$this->db->order_by('purchase_invoice.date');
			
			$query	= $this->db->get();
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
				SELECT purchase_invoice.*, code_purchase_order.supplier_id
				FROM purchase_invoice
				JOIN (
					SELECT DISTINCT(code_good_receipt.invoice_id) AS id 
					FROM code_good_receipt
					GROUP BY code_good_receipt.invoice_id
				) AS a
				ON a.id = purchase_invoice.id
			")
			$this->db->select('purchase_invoice.*, code_purchase_order.supplier_id');
			$this->db->from('purchase_invoice');
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

		public function getYears()
		{
			$this->db->select("DISTINCT(YEAR(date)) as years");
			$this->db->order_by('date');
			$query = $this->db->get($this->table_purchase_invoice);
			$result = $query->result();

			return $result;
		}
}