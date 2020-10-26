<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt_model extends CI_Model {
	private $table_good_receipt = 'code_good_receipt';
		
		public $id;
		public $name;
		public $date;
		public $is_delete;
		public $is_confirm;
		public $invoice_id;
		public $received_date;
		public $created_by;
		public $confirmed_by;
		public $guid;
		
		public $supplier_id;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id				= '';
			$this->name				= '';
			$this->date				= '';
			$this->is_delete		= '0';
			$this->is_confirm		= '0';
			$this->invoice_id		= null;
			$this->received_date	= '';
			$this->created_by		= '';
			$this->confirmed_by		= null;
			$this->guid				= '';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id				= $db_item->id;
			$this->name				= $db_item->name;
			$this->date				= $db_item->date;
			$this->is_delete		= $db_item->is_delete;
			$this->is_confirm		= $db_item->is_confirm;
			$this->invoice_id		= $db_item->invoice_id;	
			$this->received_date	= $db_item->received_date;
			$this->created_by		= $db_item->created_by;	
			$this->confirmed_by		= $db_item->confirmed_by;	
			$this->guid				= $db_item->guid;			
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->date				= $this->date;
			$db_item->is_delete			= $this->is_delete;
			$db_item->is_confirm		= $this->is_confirm;
			$db_item->invoice_id		= $this->invoice_id;	
			$db_item->received_date		= $this->received_date;
			$db_item->created_by		= $this->created_by;	
			$db_item->confirmed_by		= $this->confirmed_by;	
			$db_item->guid				= $this->guid;			
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Good_receipt_model();
			
			$stub->id				= $db_item->id;
			$stub->name				= $db_item->name;
			$stub->date				= $db_item->date;
			$stub->is_delete		= $db_item->is_delete;
			$stub->is_confirm		= $db_item->is_confirm;
			$stub->invoice_id		= $db_item->invoice_id;	
			$stub->received_date	= $db_item->received_date;
			$stub->created_by		= $db_item->created_by;	
			$stub->confirmed_by		= $db_item->confirmed_by;	
			$stub->guid				= $db_item->guid;		
			
			return $stub;
		}
		
		public function map_list($good_receipts)
		{
			$result = array();
			foreach ($good_receipts as $good_receipt)
			{
				$result[] = $this->get_new_stub_from_db($good_receipt);
			}
			return $result;
		}
		
		public function show_unconfirmed_good_receipt()
		{
			$this->db->where('is_confirm =', 0);
			$this->db->where('is_delete =', 0);
			$query 		= $this->db->get($this->table_good_receipt);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function insertItem()
		{
			$guid			= $this->input->post('guid');
			
			$this->load->model('Good_receipt_model');
			$result = $this->Good_receipt_model->check_guid($guid);
			
			if($result == TRUE){
				$this->name				= $this->input->post('document');
				$this->date				= $this->input->post('submit_date');
				$this->guid				= $guid;
				$this->created_by		= $this->session->userdata('user_id');
				$this->received_date	= date('Y-m-d');
				$db_item 				= $this->get_db_from_stub($this);
				$db_result 				= $this->db->insert($this->table_good_receipt, $db_item);
				$insert_id				= $this->db->insert_id();
				
				return $insert_id;
			}
			
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_good_receipt);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function updateStatusById($status, $id)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('id', $id);
				$this->db->where('is_confirm', 0);
				$this->db->update($this->table_good_receipt);
			} else if($status == 0) {
				$this->db->set('is_delete', 1);
				$this->db->where('id', $id);
				$this->db->where('is_delete', 0);
				$this->db->where('is_confirm', 0);
				$this->db->update($this->table_good_receipt);
			} else if($status == -1){
				$this->db->where('id', $id);
				$this->db->delete($this->table_good_receipt);
			}

			return $this->db->affected_rows();
		}
		
		public function view_uninvoiced_documents($offset = 0, $limit = 25)
		{
			$this->db->where('invoice_id', null);
			$this->db->where('is_delete', 0);
			$this->db->where('is_confirm', 1);
			$query = $this->db->get($this->table_good_receipt, $limit, $offset);
			
			$items	 	= $query->result();
			return $items;
		}
		
		public function count_uninvoiced_documents()
		{
			$this->db->where('invoice_id', null);
			$this->db->where('is_delete', 0);
			$this->db->where('is_confirm', 1);
			$query = $this->db->get($this->table_good_receipt);
			
			$item = $query-> num_rows();
			
			return $item;
		}
		
		public function getUninvoicedSupplierIds()
		{
			$this->db->select('DISTINCT(code_purchase_order.supplier_id) as id, supplier.name');
			$this->db->from('code_good_receipt');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id', 'inner');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('code_good_receipt.is_delete', 0);
			$this->db->where('code_good_receipt.invoice_id IS NULL');
			
			$query		= $this->db->get();
			$item		= $query->result();
			return $item;
		}
		
		public function countUninvoicedDocumentsBySupplierId($supplier_id, $term)
		{
			$this->db->select('code_good_receipt.id');
			$this->db->from($this->table_good_receipt);
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id', 'inner');
			$this->db->where('code_good_receipt.invoice_id', NULL);
			$this->db->where('code_good_receipt.is_confirm', 1);
			$this->db->where('code_good_receipt.is_delete', 0);
			$this->db->where('code_purchase_order.supplier_id', $supplier_id);
			$this->db->like('code_good_receipt.name', $term, 'both');
			
			$query		= $this->db->get();
			$item		= $query->result();
			$item = $query-> num_rows();
			
			return $item;
		}
		
		public function getUninvoicedDocumentsBySupplierId($supplier_id, $offset = 0, $term = '', $limit = 25)
		{
			$this->db->select('DISTINCT(code_good_receipt.id) as id, code_good_receipt.name, code_good_receipt.date, code_good_receipt.received_date , supplier.name as supplier_name, supplier.address, supplier.city, code_purchase_order.name as purchase_order_name, code_purchase_order.date as purchase_order_date');
			$this->db->from($this->table_good_receipt);
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id', 'inner');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('code_good_receipt.invoice_id', NULL);
			$this->db->where('code_good_receipt.is_confirm', 1);
			$this->db->where('code_good_receipt.is_delete', 0);
			$this->db->where('code_purchase_order.supplier_id', $supplier_id);
			$this->db->like('code_good_receipt.name', $term, 'both');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$item		= $query->result();

			return $item;
		}
		
		public function getByIdArray($idArray)
		{
			$this->db->select('DISTINCT(code_good_receipt.id) AS id, code_good_receipt.name, code_good_receipt.date, code_good_receipt.received_date, code_purchase_order.name as purchase_order_name, code_purchase_order.date as purchase_order_date, code_purchase_order.supplier_id');
			$this->db->from('code_good_receipt');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->where_in('code_good_receipt.id', $idArray);
			
			$query		= $this->db->get();
			$item		= $query->result();
			
			return $item;
		}
		
		public function showById($code_good_receipt_id)
		{
			$this->db->select('code_good_receipt.*, supplier.name as supplier_name, supplier.address, supplier.city, supplier.number, supplier.rt, supplier.rw, supplier.postal_code, supplier.block, code_purchase_order.name as purchase_order_name, code_purchase_order.date as purchase_order_date');
			$this->db->from('code_good_receipt');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('code_good_receipt.id', $code_good_receipt_id);
			
			$query		= $this->db->get();
			$item		= $query->row();
			
			return $item;
		}
		
		public function updateInvoiceStatusById($status, $invoiceId, $goodReceiptArray = array())
		{
			if($status == 0){
				$this->db->set('invoice_id', null);
				$this->db->where('invoice_id', $invoiceId);
				$this->db->update($this->table_good_receipt);

				return $this->db->affected_rows();
			} else if($status == 1) {
				foreach($goodReceiptArray as $goodReceipt)
				{
					$batch[] = array(
						'id' => $goodReceipt,
						'invoice_id' => $invoiceId,
					);
					
					next($goodReceiptArray);
				}

				$this->db->where('invoice_id', null);

				$result = $this->db->update_batch($this->table_good_receipt, $batch, 'id'); 

				return $result;
			}			
		}
		
		public function getByInvoiceId($invoice_id)
		{
			$this->db->select('code_good_receipt.*, users.name as created_by');
			$this->db->from('code_good_receipt');
			$this->db->join('users', 'code_good_receipt.created_by = users.id');
			$this->db->where('code_good_receipt.invoice_id', $invoice_id);
			$this->db->where('code_good_receipt.is_confirm', 1);
			$this->db->where('code_good_receipt.is_delete', 0);

			$query	= $this->db->get();
			$item	= $query->result();
			
			return $item;
		}
		
		public function show_years()
		{
			$this->db->select('DISTINCT(YEAR(date)) as year');
			$this->db->order_by('date', 'asc');
			$this->db->order_by('id', 'asc');
			
			$query		= $this->db->get($this->table_good_receipt);
			$result		= $query->result();
			
			return $result;
		}
		
		public function show_items($year, $month, $offset = 0, $term = '', $limit = 25)
		{
			$this->db->select('DISTINCT(code_good_receipt.id) as id, code_good_receipt.name, code_good_receipt.date, code_good_receipt.is_confirm, code_good_receipt.is_delete, code_good_receipt.invoice_id, code_good_receipt.received_date, code_good_receipt.created_by, code_good_receipt.confirmed_by, supplier.name as supplier_name, supplier.address, supplier.city, code_purchase_order.name as purchase_order_name, supplier.number, supplier.rt, supplier.rw, supplier.block');
			$this->db->from('code_good_receipt');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id', 'inner');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('MONTH(code_good_receipt.date)',$month);
			$this->db->where('YEAR(code_good_receipt.date)',$year);
			$this->db->where('code_good_receipt.is_delete', 0);
			if($term != ''){
				$this->db->like('code_good_receipt.name', $term, 'both');
				$this->db->or_like('supplier.name', $term, 'both');
				$this->db->or_like('supplier.address', $term, 'both');
				$this->db->or_like('supplier.city', $term, 'both');
				$this->db->or_like('code_purchase_order.name', $term, 'both');
			}
			
			$this->db->order_by('code_good_receipt.date', 'asc');
			$this->db->order_by('code_good_receipt.id', 'asc');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_items($year, $month, $term)
		{
			$this->db->select('DISTINCT(code_good_receipt.id)');
			$this->db->from('code_good_receipt');
			$this->db->join('good_receipt', 'good_receipt.code_good_receipt_id = code_good_receipt.id', 'inner');
			$this->db->join('purchase_order', 'good_receipt.purchase_order_id = purchase_order.id', 'inner');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('MONTH(code_good_receipt.date)',$month);
			$this->db->where('YEAR(code_good_receipt.date)',$year);
			$this->db->where('code_good_receipt.is_delete', 0);
			if($term != ''){
				$this->db->like('code_good_receipt.name', $term, 'both');
				$this->db->or_like('supplier.name', $term, 'both');
				$this->db->or_like('supplier.address', $term, 'both');
				$this->db->or_like('supplier.city', $term, 'both');
				$this->db->or_like('code_purchase_order.name', $term, 'both');
			}
			
			$query		= $this->db->get();
			$result		= $query->num_rows();
			
			return $result;
		}

		public function showUnconfirmedGoodReceipts($offset = 0, $limit = 10)
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$this->db->order_by('date', 'asc');
			$this->db->limit($limit, $offset);
			$query = $this->db->get($this->table_good_receipt);
			$result = $query->result();

			return $result;
		}

		public function countUnconfirmedGoodReceipts()
		{
			$query = $this->db->get($this->table_good_receipt);
			$result = $query->num_rows();

			return $result;
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}

		public function getUninvoicedItems($offset = 0, $limit = 10)
		{
			$query			= $this->db->query("
				SELECT code_good_receipt.* FROM code_good_receipt
				JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
				WHERE code_good_receipt.is_confirm ='1'
				AND code_good_receipt.invoice_id IS NULL
				LIMIT $limit OFFSET $offset
			");
			$result			= $query->result();
			return $result;
		}

		public function countUninvoicedItems()
		{
			$this->db->where('is_confirm', 1);
			$this->db->where('invoice_id', NULL);
			$query			= $this->db->get($this->table_good_receipt);
			$result			= $query->num_rows();
			return $result;
		}

		public function unsetInvoiceByInvoiceId($id)
		{
			$this->db->set('invoice_id', NULL);
			$this->db->where('invoice_id', $id);
			$this->db->update($this->table_good_receipt);
		}

		public function deleteById($id)
		{
			$this->db->set('is_delete', 1);
			$this->db->set('is_confirm', 0);
			$this->db->where('id', $id);
			$this->db->update($this->table_good_receipt);
		}

		public function getReceivedByPurchaseOrderId($purchaseOrderId)
		{
			$query		= $this->db->query("
				SELECT code_good_receipt.*, users.name AS created_by
				FROM code_good_receipt
				JOIN users ON code_good_receipt.created_by = users.id
				WHERE code_good_receipt.id IN (
					SELECT code_good_receipt.id
					FROM code_good_receipt
					JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					JOIN code_purchase_order ON code_purchase_order.id = purchase_order.code_purchase_order_id
					WHERE code_purchase_order.id = '$purchaseOrderId'
					AND code_good_receipt.is_delete = '0'
				)
				ORDER BY code_good_receipt.date ASC				
			");
			$result			= $query->result();
			return $result;
		}
}
