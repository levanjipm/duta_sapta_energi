<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_other_model extends CI_Model {
	private $table_purchase_invoice = 'purchase_invoice_other';
		
		public $id;
		public $date;
		public $created_by;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $is_done;
		public $tax_document;
		public $invoice_document;
		public $other_opponent_id;
		public $supplier_id;
		public $taxing;
		public $information;
		public $value;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->date					= $db_item->date;
			$this->created_by			= $db_item->created_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_done				= $db_item->is_done;
			$this->tax_document			= $db_item->tax_document;
			$this->invoice_document		= $db_item->invoice_document;
			$this->supplier_id			= $db_item->supplier_id;
			$this->other_opponent_id	= $db_item->other_opponent_id;
			$this->taxing				= $db_item->taxing;
			$this->information			= $db_item->information;
			$this->value				= $db_item->value;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->created_by			= $this->created_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_done				= $this->is_done;
			$db_item->tax_document			= $this->tax_document;
			$db_item->invoice_document		= $this->invoice_document;
			$db_item->supplier_id			= $this->supplier_id;
			$db_item->other_opponent_id		= $this->other_opponent_id;
			$db_item->taxing				= $this->taxing;
			$db_item->information			= $this->information;
			$db_item->value					= $this->value;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->date					= $db_item->date;
			$stub->created_by			= $db_item->created_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_done				= $db_item->is_done;
			$stub->tax_document			= $db_item->tax_document;
			$stub->invoice_document		= $db_item->invoice_document;
			$stub->supplier_id			= $db_item->supplier_id;
			$stub->other_opponent_id	= $db_item->other_opponent_id;
			$stub->taxing				= $db_item->taxing;
			$stub->information			= $db_item->information;
			$stub->value				= $db_item->value;
			
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
		
		public function getById($invoice_id)
		{
			$this->db->select('purchase_invoice_other.*, debt_type.name as type, users.name as created_by');
			$this->db->from('purchase_invoice_other');
			$this->db->join('debt_type', 'debt_type.id = purchase_invoice_other.type');
			$this->db->join('users', 'purchase_invoice_other.created_by = users.id');
			$this->db->where('purchase_invoice_other.is_confirm', 0);
			$this->db->where('purchase_invoice_other.is_delete', 0);
			$this->db->where('purchase_invoice_other.id', $invoice_id);
			
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

		public function insertItem()
		{
			$this->id					= "";
			$this->date					= $this->input->post('date');
			$this->created_by			= $this->session->userdata('user_id');
			$this->is_confirm			= 0;
			$this->is_delete			= 0;
			$this->confirmed_by			= null;
			$this->is_done				= 0;
			$this->tax_document			= $this->input->post('taxInvoiceName');
			$this->invoice_document		= $this->input->post('invoiceName');

			if($this->input->post('supplierType') == 1){
				$this->supplier_id			= $this->input->post('supplier_id');
				$this->other_opponent_id	= null;
			} else {
				$this->other_opponent_id	= $this->input->post('supplier_id');
				$this->supplier_id			= null;
			}
			
			$this->taxing				= $this->input->post('taxing');
			$this->information			= $this->input->post('information');
			$this->value				= $this->input->post('value');

			$db_item 					= $this->get_db_from_stub($this);
			$this->db->insert($this->table_purchase_invoice, $db_item);

			return $this->db->affected_rows();
		}

		public function getIncompletedTransaction($otherOpponentId)
		{
			$this->db->where('other_opponent_id', $otherOpponentId);
			$this->db->where('is_done', 0);
			$query	= $this->db->get($this->table_purchase_invoice);
			$result	= $query->result();
			return $result;
		}
}