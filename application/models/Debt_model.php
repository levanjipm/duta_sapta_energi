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
		
		public function show_items($offset = 0, $filter = '', $limit = 25)
		{			
			$query 		= $this->db->get($this->table_item, $limit, $offset);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function count_items($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('reference', $filter, 'both');
			}
			
			$query		= $this->db->get($this->table_item);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function insert_from_post()
		{
			$created_by			= $this->session->userdata('user_id');
			$date				= $this->input->post('date');
			$tax_document		= $this->input->post('tax_document');
			$invoice_document	= $this->input->post('invoice_document');
			
			$data		= array(
				'id' => '',
				'created_by' => $created_by,
				'date' => $date,
				'tax_document' => $tax_document,
				'invoice_document' => $invoice_document,
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
		
		public function view_unconfirmed_documents($offset = 0, $term = '', $limit = 25)
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
			
			// $this->db->or_like('purchase_invoice.tax_document', $term, 'both');
			// $this->db->or_like('purchase_invoice.invoice_document', $term, 'both');
			// $this->db->or_like('supplier.name', $term, 'both');
			// $this->db->or_like('supplier.address', $term, 'both');
			// $this->db->or_like('supplier.city', $term, 'both');
			
			$this->db->limit($limit, $offset);
			
			$query = $this->db->get();
			
			// print_r($this->db->last_query());
			$result	= $query->result();
			
			return $result;
		}
		
		public function count_unconfirmed_documents($term = '')
		{
			$this->db->like('purchase_invoice.tax_document', $term, 'both');
			$this->db->or_like('purchase_invoice.invoice_document', $term, 'both');
			$this->db->where('purchase_invoice.is_confirm', 0);
			$this->db->where('purchase_invoice.is_delete', 0);
			
			$query = $this->db->get($this->table_purchase_invoice);
			$result	= $query->num_rows();
			
			return $result;
		}
		
		public function select_by_id($invoice_id)
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
		
		public function confirm($invoice_id, $confirmed_by)
		{
			$this->db->set('is_confirm', 1);
			$this->db->set('confirmed_by', $confirmed_by);
			$this->db->where('is_delete', 0);
			$this->db->where('id', $invoice_id);
			$this->db->update($this->table_purchase_invoice);
		}
		
		public function delete($invoice_id)
		{
			$this->db->set('is_delete', 1);
			$this->db->where('is_confirm', 0);
			$this->db->where('id', $invoice_id);
			$this->db->update($this->table_purchase_invoice);
			
			if ($this->db->affected_rows() > 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
}