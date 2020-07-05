<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_model extends CI_Model {
	private $table_return = 'code_purchase_return';
		
		public $id;
		public $supplier_id;
		public $created_by;
		public $created_date;
		public $invoice_reference;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->supplier_id			= $db_item->supplier_id;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->invoice_reference	= $db_item->invoice_reference;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->supplier_id			= $this->supplier_id;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->invoice_reference		= $this->invoice_reference;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->supplier_id			= $db_item->supplier_id;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->invoice_reference	= $db_item->invoice_reference;
			
			return $stub;
		}
		
		public function map_list($areas)
		{
			$result = array();
			foreach ($areas as $area)
			{
				$result[] = $this->get_new_stub_from_db($area);
			}
			return $result;
		}
		
		public function insert_from_post()
		{
			$this->id		= '';
			$this->supplier_id		= $this->input->post('supplier');
			$this->invoice_reference	= $this->input->post('invoice_reference');
			$this->created_by			= $this->session->userdata('user_id');
			$this->created_date			= date('Y-m-d');
			
			$db_item 				= $this->get_db_from_stub($this);			
			$this->db->insert($this->table_return, $db_item);
			
			$insert_id				= $this->db->insert_id();
			
			return $insert_id;
		}
		
		public function view_items($offset = 0, $limit = 25)
		{
			$this->db->select('users.name as created_by, code_purchase_return.created_date, code_purchase_return.invoice_reference, code_purchase_return.id, supplier.name, supplier.address, supplier.number, supplier.block, supplier.rt, supplier.rw, supplier.city, supplier.postal_code');
			$this->db->from('code_purchase_return');
			$this->db->join('supplier', 'code_purchase_return.supplier_id = supplier.id');
			$this->db->join('users', 'code_purchase_return.created_by = users.id');
			$this->db->join('purchase_return', 'purchase_return.code_purchase_return_id = code_purchase_return.id', 'left');
			$this->db->where('purchase_return.status', 0);
			$this->db->limit($limit, $offset);
			$query		= $this->db->get();
			
			$result		= $query->result();
			return $result;
		}
		
		public function count_items()
		{
			$this->db->select('code_purchase_return.id');
			$this->db->from('code_purchase_return');
			$this->db->join('purchase_return', 'purchase_return.code_purchase_return_id = code_purchase_return.id', 'left');
			$this->db->where('purchase_return.status', 0);
			
			$query		= $this->db->get();
			$result		= $query->num_rows();
			
			return $result;
		}
}