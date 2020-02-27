<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_model extends CI_Model {
	private $table_purchase_order = 'code_purchase_order';
		
		public $id;
		public $date;
		public $name;
		public $created_by;
		public $confirmed_by;
		public $is_closed;
		public $promo_code;
		public $dropship_address;
		public $dropship_city;
		public $dropship_contact_person;
		public $dropship_contact;
		public $taxing;
		public $date_send_request;
		public $guid;
		public $supplier_id;
		public $is_delete;
		
		public $supplier_name;
		public $supplier_address;
		public $supplier_city;

		public function __construct()
		{
			parent::__construct();
			
			$this->id						='';
			$this->date                     ='';
			$this->name                     ='';
			$this->created_by               ='';
			$this->confirmed_by             = NULL;
			$this->is_closed                ='';
			$this->promo_code               = NULL;
			$this->dropship_address         = NULL;
			$this->dropship_city            = NULL;
			$this->dropship_contact_person  = NULL;
			$this->dropship_contact         = NULL;
			$this->taxing                   ='';
			$this->date_send_request        = NULL;
			$this->guid                     ='';
			$this->supplier_id              ='';
			$this->is_delete              	='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->date                     = $db_item->date;                   
			$this->name                     = $db_item->name;                    
			$this->created_by               = $db_item->created_by;              
			$this->confirmed_by             = $db_item->confirmed_by;            
			$this->is_closed                = $db_item->is_closed;               
			$this->promo_code               = $db_item->promo_code;              
			$this->dropship_address         = $db_item->dropship_address;        
			$this->dropship_city            = $db_item->dropship_city;           
			$this->dropship_contact_person  = $db_item->dropship_contact_person; 
			$this->dropship_contact         = $db_item->dropship_contact;        
			$this->taxing                   = $db_item->taxing;                  
			$this->date_send_request        = $db_item->date_send_request;       
			$this->guid                     = $db_item->guid;                    
			$this->supplier_id              = $db_item->supplier_id;              
			$this->is_delete	              = $db_item->is_delete;              
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->date                      = $this->date;                   
			$db_item->name                      = $this->name;                    
			$db_item->created_by                = $this->created_by;              
			$db_item->confirmed_by              = $this->confirmed_by;            
			$db_item->is_closed                 = $this->is_closed;               
			$db_item->promo_code                = $this->promo_code;              
			$db_item->dropship_address          = $this->dropship_address;        
			$db_item->dropship_city             = $this->dropship_city;           
			$db_item->dropship_contact_person   = $this->dropship_contact_person; 
			$db_item->dropship_contact          = $this->dropship_contact;        
			$db_item->taxing                    = $this->taxing;                  
			$db_item->date_send_request         = $this->date_send_request;       
			$db_item->guid                      = $this->guid;                    
			$db_item->supplier_id               = $this->supplier_id;
			$db_item->is_delete	               = $this->is_delete;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Purchase_order_model();
			
			$stub->id						= $db_item->id;
			$stub->date                     = $db_item->date;                   
			$stub->name                     = $db_item->name;                    
			$stub->created_by               = $db_item->created_by;              
			$stub->confirmed_by             = $db_item->confirmed_by;            
			$stub->is_closed                = $db_item->is_closed;               
			$stub->promo_code               = $db_item->promo_code;              
			$stub->dropship_address         = $db_item->dropship_address;        
			$stub->dropship_city            = $db_item->dropship_city;           
			$stub->dropship_contact_person  = $db_item->dropship_contact_person; 
			$stub->dropship_contact         = $db_item->dropship_contact;        
			$stub->taxing                   = $db_item->taxing;                  
			$stub->date_send_request        = $db_item->date_send_request;       
			$stub->guid                     = $db_item->guid;                    
			$stub->supplier_id              = $db_item->supplier_id;     
			$stub->is_delete	              = $db_item->is_delete;     
			
			return $stub;
		}
		
		public function get_new_stub_from_db_with_supplier($db_item)
		{
			$stub = new Purchase_order_model();
			
			$stub->id						= $db_item->id;
			$stub->date                     = $db_item->date;                   
			$stub->name                     = $db_item->name;                    
			$stub->created_by               = $db_item->created_by;              
			$stub->confirmed_by             = $db_item->confirmed_by;            
			$stub->is_closed                = $db_item->is_closed;               
			$stub->promo_code               = $db_item->promo_code;              
			$stub->dropship_address         = $db_item->dropship_address;        
			$stub->dropship_city            = $db_item->dropship_city;           
			$stub->dropship_contact_person  = $db_item->dropship_contact_person; 
			$stub->dropship_contact         = $db_item->dropship_contact;        
			$stub->taxing                   = $db_item->taxing;                  
			$stub->date_send_request        = $db_item->date_send_request;       
			$stub->guid                     = $db_item->guid;                    
			$stub->supplier_id              = $db_item->supplier_id;    
			$stub->supplier_name            = $db_item->supplier_name;    
			$stub->supplier_address         = $db_item->supplier_address;    
			$stub->supplier_city	        = $db_item->supplier_city;    
			$stub->is_delete	       		 = $db_item->is_delete;    
			
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
		
		public function map_list_with_supplier($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db_with_supplier($item);
			}
			return $result;
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_purchase_order);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function show_unconfirmed_purchase_order()
		{
			$this->db->select('code_purchase_order.*, supplier.name as supplier_name, supplier.address as supplier_address, supplier.city as supplier_city');
			$this->db->from($this->table_purchase_order);
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('code_purchase_order.confirmed_by =', null);
			$query = $this->db->get();
			$items	 	= $query->result();
			
			$result 	= $this->map_list_with_supplier($items);
			
			return $result;
			
		}
		
		public function add_item_to_cart()
		{
			$item_id = $this->input->post('item_id');
			$cart_products = $this->session->userdata('purchase_cart_products');
			
			$cart_products[] = $item_id;
			
			$this->session->set_userdata('purchase_cart_products', $cart_products);
		}
		
		public function remove_item_from_cart()
		{
			$cart_products = $this->session->userdata('purchase_cart_products');
			$item_id = $this->input->post('item_id');
			$key = array_search($item_id, $cart_products);
			unset($cart_products[$key]);
			
			$this->session->set_userdata('purchase_cart_products', $cart_products);
		}
		
		public function name_generator($date)
		{
			$name = 'PO.DSE-' . date('Ym', strtotime($date)) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$this->db->where('name =',$name);
			$query = $this->db->get($this->table_purchase_order);
			$item = $query->num_rows();
			
			if($item != 0){
				$this->Purchase_order_model->name_generator($date);
			};
			
			return $name;
		}
		
		public function input_from_post()
		{
			$guid			= $this->input->post('guid');
			$date			= $this->input->post('date');
			$this->load->model('Purchase_order_model');
			$check_guid = $this->Purchase_order_model->check_guid($guid);
			if($check_guid){
				$this->name				= $this->Purchase_order_model->name_generator($date);
				$this->guid				= $guid;
				$this->date				= $this->input->post('date');
				$this->supplier_id		= $this->input->post('supplier');
				$this->taxing			= $this->input->post('taxing');
				$this->created_by		= $this->session->userdata('user_id');
				
				if($this->input->post('dropship_address') != ''){
					$this->dropship_address			= $this->input->post('dropship_address');
					$this->dropship_city			= $this->input->post('dropship_city');
					$this->dropship_contact_person	= $this->input->post('dropship_contact_person');
					$this->dropship_contact			= $this->input->post('dropship_contact');
				}
				
				$db_item 				= $this->get_db_from_stub($this);
				
				$db_result 				= $this->db->insert($this->table_purchase_order, $db_item);
				
				$insert_id				= $this->db->insert_id();
				
				return $insert_id;
			}
		}
		
		public function get_incompleted_purchase_order($supplier_id)
		{
			$this->db->select('purchase_order.code_purchase_order_id as id, code_purchase_order.*');
			$this->db->from('purchase_order');
			$this->db->join('code_purchase_order', 'purchase_order.code_purchase_order_id = code_purchase_order.id', 'inner');
			$this->db->where('purchase_order.status =', 0);
			$this->db->where('code_purchase_order.supplier_id =', $supplier_id);
			
			$query 		= $this->db->get();
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
			
		}
		
		public function show_by_id($id)
		{
			$this->db->select('code_purchase_order.*, supplier.name as supplier_name, supplier.address as supplier_address, supplier.city as supplier_city');
			$this->db->from('code_purchase_order');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->where('code_purchase_order.id =', $id);
			
			$query 		= $this->db->get();
			$items	 	= $query->result();
			
			$result 	= $this->map_list_with_supplier($items);
			
			return $result;
		}
		
		public function confirm_purchase_order($id)
		{
			$this->db->set('is_confirm', 1);
			$this->db->set('confirmed_by', $this->session->userdata('user_id'));
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_order);
		}
		
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
}