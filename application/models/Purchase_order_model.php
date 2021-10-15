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
		public $note;
		public $payment;
		
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
			$this->status              		= NULL;
			$this->note						= "";
			$this->payment					= 60;
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
			$this->is_delete	            = $db_item->is_delete;              
			$this->status	              	= $db_item->status; 
			$this->note						= $db_item->note;             
			$this->payment					= $db_item->payment;             
			
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
			$db_item->status	               = $this->status;
			$db_item->note						= $this->note;
			$db_item->payment					= $this->payment;
			
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
			$stub->status		              = $db_item->status;
			$stub->note						= $db_item->note;
			$stub->payment					= $db_item->payment;
			
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
			$stub->status		       		 = $db_item->status;
			$stub->note						= $db_item->note;    
			$stub->payment					= $db_item->payment;    
			
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
		
		public function generateName($date)
		{
			$name = 'PO.DSE-' . date('Ym', strtotime($date)) . '-' . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$this->db->where('name =',$name);
			$query = $this->db->get($this->table_purchase_order);
			$item = $query->num_rows();
			
			if($item > 0){
				$this->Purchase_order_model->generateName($date);
			};
			
			return $name;
		}
		
		public function insertItem()
		{
			$guid			= $this->input->post('guid');
			$date			= $this->input->post('date');
			$status			= $this->input->post('status');
			if($status		== 1){
				$date_send	= $this->input->post('date_send_request');
				$stat		= null;
			} else if($status == 2){
				$stat		= 'TOP URGENT';
				$date_send 	= null;
			} else {
				$date_send	= null;
				$stat		= null;
			}

			$this->load->model('Purchase_order_model');
			$check_guid = $this->Purchase_order_model->check_guid($guid);
			if($check_guid){
				$this->name					= $this->Purchase_order_model->generateName($date);
				$this->guid					= $guid;
				$this->is_delete			= '0';
				$this->date					= $this->input->post('date');
				$this->supplier_id			= $this->input->post('supplier');
				$this->taxing				= $this->input->post('taxing');
				$this->created_by			= $this->session->userdata('user_id');
				$this->date_send_request	= $date_send;
				$this->status				= $stat;
				$this->note					= $this->input->post('note');
				$this->payment				= $this->input->post('payment');
				
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
		
		public function getIncompletePurchaseOrder($offset = 0, $supplier_id = null, $limit = 10)
		{
			if($supplier_id == null){
				$query = $this->db->query("
					SELECT code_purchase_order.* FROM code_purchase_order
					JOIN (
						SELECT DISTINCT(purchase_order.code_purchase_order_id) as id FROM purchase_order
						WHERE purchase_order.status= '0'
					) as a
					ON a.id = code_purchase_order.id
					WHERE code_purchase_order.is_confirm = '1'
					LIMIT $limit OFFSET $offset
				");
			} else {
				$query = $this->db->query("
					SELECT code_purchase_order.* FROM code_purchase_order
					JOIN (
						SELECT DISTINCT(purchase_order.code_purchase_order_id) as id FROM purchase_order
						WHERE purchase_order.status= '0'
					) as a
					ON a.id = code_purchase_order.id
					WHERE code_purchase_order.supplier_id = '$supplier_id' AND code_purchase_order.is_confirm = '1'
					LIMIT $limit OFFSET $offset
				");
			}

			$result	 	= $query->result();		
			return $result;
		}

		public function countIncompletePurchaseOrder($supplier_id = null)
		{
			if($supplier_id != null){
				$query = $this->db->query("
					SELECT code_purchase_order.* FROM code_purchase_order
					JOIN (
						SELECT DISTINCT(purchase_order.code_purchase_order_id) as id FROM purchase_order
						WHERE purchase_order.status= '0'
					) as a
					ON a.id = code_purchase_order.id
					WHERE code_purchase_order.supplier_id = '$supplier_id'
					AND code_purchase_order.is_confirm = '1'
				");
			} else {
				$query = $this->db->query("
					SELECT code_purchase_order.* FROM code_purchase_order
					JOIN (
						SELECT DISTINCT(purchase_order.code_purchase_order_id) as id FROM purchase_order
						WHERE purchase_order.status= '0'
					) as a
					ON a.id = code_purchase_order.id
					WHERE code_purchase_order.is_confirm = '1'
				");
			}
			
			$result	 	= $query->num_rows();
			
			return $result;
		}

		public function getAllIncompletePurchaseOrder($supplier_id)
		{
			$query = $this->db->query("
				SELECT code_purchase_order.* FROM code_purchase_order
				INNER JOIN (
					SELECT DISTINCT(purchase_order.code_purchase_order_id) as id FROM purchase_order
					WHERE purchase_order.status= '0'
				) as a
				ON a.id = code_purchase_order.id
				WHERE code_purchase_order.supplier_id = '$supplier_id' 
				AND code_purchase_order.is_confirm = '1'
			");
			$result	 	= $query->result();
			
			return $result;
		}

		public function getAllIncompletePurchaseOrderSupplier()
		{
			$query = $this->db->query("
				SELECT supplier.*, c.count FROM (
					SELECT DISTINCT(code_purchase_order.supplier_id) as id, COUNT(a.id) AS count
					FROM code_purchase_order
					JOIN (
						SELECT DISTINCT(purchase_order.code_purchase_order_id) as id  FROM purchase_order
						WHERE purchase_order.status = '0'
					) as a
					ON code_purchase_order.id = a.id
					WHERE code_purchase_order.is_confirm = '1'
					GROUP BY code_purchase_order.supplier_id
				) as c
				JOIN supplier ON c.id = supplier.id
			");
			$result	 	= $query->result();
			
			return $result;
		}
		
		public function showById($id)
		{
			$this->db->select('code_purchase_order.*, x.name as created_by, y.name as confirmed_by');
			$this->db->from('code_purchase_order');
			$this->db->join('supplier', 'code_purchase_order.supplier_id = supplier.id');
			$this->db->join('users as x', 'code_purchase_order.created_by = x.id');
			$this->db->join('users as y', 'code_purchase_order.confirmed_by = y.id', 'left');
			$this->db->where('code_purchase_order.id =', $id);
			
			$query 		= $this->db->get();
			$items	 	= $query->row();
			
			return $items;
		}
		
		public function confirmById($id)
		{
			$this->db->db_debug = false;
			$this->db->set('is_confirm', 1);
			$this->db->set('confirmed_by', $this->session->userdata('user_id'));
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_order);

			return $this->db->affected_rows();
		}
		
		public function deleteById($id)
		{
			$this->db->db_debug = false;
			$this->db->set('is_delete', 1);
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_order);

			return $this->db->affected_rows();
		}

		public function getItems($year, $month, $offset = 0, $term = '', $limit = 25)
		{
			$query			= $this->db->query("
				SELECT code_purchase_order.*, supplier.name as supplier_name, supplier.address as address, supplier.city as city, supplier.number, supplier.rt, supplier.rw, supplier.postal_code, supplier.npwp, supplier.phone_number, supplier.block
				FROM code_purchase_order
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				WHERE MONTH(code_purchase_order.date) = '$month'
				AND YEAR(code_purchase_order.date) = '$year'
				AND
				(
					supplier.name LIKE '%$term%'
					OR supplier.address LIKE '%$term%'
					OR supplier.city LIKE '%$term%'
					OR code_purchase_order.name LIKE '%$term%'
					OR code_purchase_order.dropship_address LIKE '%$term%'
					OR code_purchase_order.dropship_city LIKE '%$term%'
					OR code_purchase_order.dropship_contact_person LIKE '%$term%'
					OR code_purchase_order.dropship_contact LIKE '%$term%'
					OR code_purchase_order.id IN (
						SELECT purchase_order.code_purchase_order_id
						FROM purchase_order
						JOIN item ON purchase_order.item_id = item.id
						WHERE item.reference LIKE '%$term%'
						OR item.name LIKE '%$term%'
					)
				)

				LIMIT 10 OFFSET $offset
			");

			$result		= $query->result();
			
			return $result;
		}
		
		public function count_items($year, $month, $term = '')
		{
			$query	= $this->db->query("
				SELECT code_purchase_order.id
				FROM code_purchase_order
				JOIN supplier ON code_purchase_order.supplier_id = supplier.id
				WHERE MONTH(code_purchase_order.date) = '$month'
				AND YEAR(code_purchase_order.date) = '$year'
				AND
				(
					supplier.name LIKE '%$term%'
					OR supplier.address LIKE '%$term%'
					OR supplier.city LIKE '%$term%'
					OR code_purchase_order.name LIKE '%$term%'
					OR code_purchase_order.dropship_address LIKE '%$term%'
					OR code_purchase_order.dropship_city LIKE '%$term%'
					OR code_purchase_order.dropship_contact_person LIKE '%$term%'
					OR code_purchase_order.dropship_contact LIKE '%$term%'
					OR code_purchase_order.id IN (
						SELECT purchase_order.code_purchase_order_id
						FROM purchase_order
						JOIN item ON purchase_order.item_id = item.id
						WHERE item.reference LIKE '%$term%'
						OR item.name LIKE '%$term%'
					)
				)
			");


			$result		= $query->num_rows();
			return $result;

		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}

		public function getUnconfirmedPurchaseOrders()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query = $this->db->get($this->table_purchase_order);
			$result = $query->result();
			return $result;
		}

		public function closeById($id)
		{
			$this->db->set('is_closed', 1);
			$this->db->where('is_confirm', 1);
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_order);
			$result = $this->db->affected_rows();
			return $result;
		}

		public function updateById($id, $inputStatus, $sendDate, $promoCode, $dropshipAddress, $dropshipCity, $dropshipContactPerson, $dropshipContact, $note)
		{
			$this->db->set('status', $inputStatus);
			$this->db->set('date_send_request', $sendDate);
			$this->db->set('dropship_address', $dropshipAddress);
			$this->db->set('dropship_city', $dropshipCity);
			$this->db->set('dropship_contact_person', $dropshipContactPerson);
			$this->db->set('dropship_contact', $dropshipContact);
			$this->db->set('note', $note);
			$this->db->set('promo_code', $promoCode);
			$this->db->where('id', $id);
			$this->db->update($this->table_purchase_order);
		}

		public function getPendingPurchaseOrderBySupplierId($supplierId)
		{
			$query		= $this->db->query("
				SELECT code_purchase_order.*
				FROM code_purchase_order
				WHERE code_purchase_order.supplier_id = '$supplierId'
				AND code_purchase_order.is_confirm = '1'
				AND code_purchase_order.is_closed = '0'
				AND code_purchase_order.id IN (
					SELECT DISTINCT(purchase_order.code_purchase_order_id) AS id
					FROM purchase_order
					WHERE status = '0'
				)
				ORDER BY date DESC
			");

			$result			= $query->result();
			return $result;
		}
	}
?>
