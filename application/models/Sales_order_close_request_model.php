<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_close_request_model extends CI_Model {
	private $table_close_sales_order = 'code_sales_order_close_request';
		
		public $id;
		public $date;
		public $code_sales_order_id;
		public $requested_by;
		public $information;
		public $is_approved;
		public $approved_by;
		public $approved_date;
		public $created_by;

		public function __construct()
		{
			parent::__construct();
			
			$this->id						='';
			$this->date                     ='';
			$this->code_sales_order_id      ='';
			$this->requested_by             ='';
			$this->information	            = NULL;
			$this->is_approved              ='';
			$this->approved_by              = NULL;
			$this->approved_date	        = NULL;
			$this->created_by		        = NULL;
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->date                     = $db_item->date;                   
			$this->code_sales_order_id      = $db_item->code_sales_order_id;                    
			$this->requested_by             = $db_item->requested_by;              
			$this->information	            = $db_item->information;            
			$this->is_approved              = $db_item->is_approved;               
			$this->approved_by              = $db_item->approved_by;              
			$this->approved_date	        = $db_item->approved_date;                    
			$this->created_by	        = $db_item->created_by;                    
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date                  = $this->date;                   
			$db_item->code_sales_order_id   = $this->code_sales_order_id;                    
			$db_item->requested_by          = $this->requested_by;              
			$db_item->information	         = $this->information;            
			$db_item->is_approved           = $this->is_approved;               
			$db_item->approved_by           = $this->approved_by;              
			$db_item->approved_date	        = $this->approved_date;          
			$db_item->created_by	        = $this->created_by;          
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Purchase_order_model();
			
			$stub->id						= $db_item->id;
			$stub->date                     = $db_item->date;                   
			$stub->code_sales_order_id      = $db_item->code_sales_order_id;                    
			$stub->requested_by             = $db_item->requested_by;              
			$stub->information	            = $db_item->information;            
			$stub->is_approved              = $db_item->is_approved;               
			$stub->approved_by              = $db_item->approved_by;              
			$stub->approved_date	        = $db_item->approved_date;     
			$stub->created_by		        = $db_item->created_by;     
			
			return $stub;
		}
		
		public function close()
		{
			$code_sales_order_id = $this->input->post('id');
			$requested_by			= $this->input->post('requested_by');
			$created_by				= $this->session->userdata('user_id');
			$information			= $this->input->post('information');

			$db_item	= array(
				'id' => '',
				'date' => date('Y-m-d'),
				'code_sales_order_id' => $code_sales_order_id,
				'requested_by' => $requested_by,
				'information' => $information,
				'created_by' => $created_by,
			);
			
			$this->db->insert($this->table_close_sales_order, $db_item);
			
			if($this->db->affected_rows() > 0){
				return($this->db->insert_id());;
			} else {
				return NULL;
			};
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id', $id);
			$query	= $this->db->get($this->table_close_sales_order);
			$result	= $query->row();
			
			return $result;
		}
		
		public function get_unconfirmed()
		{
			$query = $this->db->query("SELECT code_sales_order_close_request.*, a.name as created_by, b.name as seller, customer.name, customer.address, customer.city, customer.block, customer.number, customer.rt, customer.rw, code_sales_order.date as sales_order_date, code_sales_order.name as sales_order_name
			FROM code_sales_order_close_request
			JOIN code_sales_order ON code_sales_order_close_request.code_sales_order_id = code_sales_order.id
			JOIN customer ON code_sales_order.customer_id = customer.id
			JOIN (SELECT id, name FROM users) as a ON a.id = code_sales_order_close_request.created_by
			LEFT JOIN (SELECT id, name FROM users) as b ON b.id = code_sales_order.seller
			WHERE code_sales_order_close_request.is_approved IS NULL
			ORDER BY code_sales_order_close_request.date");
			$result = $query->result();
			return $result;
		}
		
		public function update_status($status, $id, $approval)
		{
			$this->db->set('is_approved', $status);
			$this->db->set('approved_by', $approval);
			$this->db->set('approved_date', date('Y-m-d'));
			$this->db->where('id', $id);
			
			$this->db->update($this->table_close_sales_order);
			
			return $this->db->affected_rows();
		}
}