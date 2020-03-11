<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {
	private $table_supplier = 'supplier';
		
		public $id;
		public $name;
		public $address;
		public $number;
		public $block;
		public $rt;
		public $rw;
		public $city;
		public $postal_code;
		public $npwp;
		public $phone_number;
		public $pic_name;
		public $date_created;
		public $created_by;
		public $is_black_list;
		
		public $complete_address;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->address				= $db_item->address;
			$this->number				= $db_item->number;
			$this->block				= $db_item->block;
			$this->rt					= $db_item->rt;
			$this->rw					= $db_item->rw;
			$this->city					= $db_item->city;
			$this->postal_code			= $db_item->postal_code;
			$this->npwp					= $db_item->npwp;
			$this->phone_number			= $db_item->phone_number;
			$this->pic_name				= $db_item->pic_name;
			$this->date_created			= $db_item->date_created;
			$this->created_by			= $db_item->created_by;
			$this->is_black_list		= $db_item->is_black_list;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->address				= $this->address;
			$db_item->number				= $this->number;
			$db_item->block					= $this->block;
			$db_item->rt					= $this->rt;
			$db_item->rw					= $this->rw;
			$db_item->city					= $this->city;
			$db_item->postal_code			= $this->postal_code;
			$db_item->npwp					= $this->npwp;
			$db_item->phone_number			= $this->phone_number;
			$db_item->pic_name				= $this->pic_name;
			$db_item->date_created			= $this->date_created;
			$db_item->created_by			= $this->created_by;
			$db_item->is_black_list			= $this->is_black_list;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Supplier_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->address				= $db_item->address;
			$stub->number				= $db_item->number;
			$stub->block				= $db_item->block;
			$stub->rt					= $db_item->rt;
			$stub->rw					= $db_item->rw;
			$stub->city					= $db_item->city;
			$stub->postal_code			= $db_item->postal_code;
			$stub->npwp					= $db_item->npwp;
			$stub->phone_number			= $db_item->phone_number;
			$stub->pic_name				= $db_item->pic_name;
			$stub->date_created			= $db_item->date_created;
			$stub->created_by			= $db_item->created_by;
			$stub->is_black_list		= $db_item->is_black_list;
			
			return $stub;
		}
		
		public function map_list($suppliers)
		{
			$result = array();
			foreach ($suppliers as $supplier)
			{
				$result[] = $this->get_new_stub_from_db($supplier);
			}
			return $result;
		}
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_supplier);
			$suppliers 	= $query->result();
			
			$items = $this->map_list($suppliers);
			
			return $items;
			
		}
		
		public function show_limited($limit, $offset)
		{
			$query 		= $this->db->get($this->table_supplier, $limit, $offset);
			$suppliers 	= $query->result();
			
			$items = $this->map_list($suppliers);
			
			return $items;
		}
		
		public function count_page()
		{
			$this->load->model('Supplier_model');
			$limit	= 25;
			
			$this->db->select('id');
			$this->db->from($this->table_supplier);	
			
			$items = $this->db->get();
			$count = $items->num_rows();
			
			return $count;
			
		}
		
		public function insert_from_post()
		{
			$this->load->model('Supplier_model');
			$this->db->select('*');
			$this->db->from($this->table_supplier);
			$this->db->where('name =', $this->input->post('supplier_name'));
			$items = $this->db->get();
			$count = $items->num_rows();
			
			if($count == 0){
				$this->id					= '';
				$this->name					= $this->input->post('supplier_name');
				$this->address				= $this->input->post('supplier_address');
				$this->number				= $this->input->post('address_number');
				$this->block				= $this->input->post('address_block');
				$this->rt					= $this->input->post('address_rt');
				$this->rw					= $this->input->post('address_rw');
				$this->city					= $this->input->post('supplier_city');
				$this->postal_code			= $this->input->post('address_postal');
				$this->npwp					= $this->input->post('supplier_npwp');
				$this->phone_number			= $this->input->post('supplier_phone');
				$this->pic_name				= $this->input->post('supplier_pic');
				$this->date_created			= date('Y-m-d');
				$this->created_by			= $this->session->userdata('user_id');
				$this->is_black_list		= '';
				
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_supplier, $db_item);
			}
		}
		
		public function delete_by_id()
		{
			$this->db->where('id', $this->input->post('supplier_id'));
			$this->db->delete($this->table_supplier);
		}
		
		public function show_by_id($supplier_id)
		{
			$this->db->where('id', $supplier_id);
			$query = $this->db->get($this->table_supplier, 1);
			
			$item = $query->row();

			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}
		
		public function update_from_post()
		{
			$where['id']				= $this->input->post('supplier_id');
			
			$this->name					= $this->input->post('supplier_name');
			$this->address				= $this->input->post('supplier_address');
			$this->number				= $this->input->post('address_number');
			$this->block				= $this->input->post('address_block');
			$this->rt					= $this->input->post('address_rt');
			$this->rw					= $this->input->post('address_rw');
			$this->city					= $this->input->post('supplier_city');
			$this->postal_code			= $this->input->post('address_postal');
			$this->area_id				= $this->input->post('area_id');
			$this->npwp					= $this->input->post('supplier_pic');
			$this->phone_number			= $this->input->post('supplier_phone');
			$this->pic_name				= $this->input->post('supplier_pic');
			
			$db_item = $this->get_db_from_stub();
			
			$this->db->where($where);
			
			$this->db->set('name', $this->name);			
			$this->db->set('address', $this->address);
			$this->db->set('number', $this->number);		
			$this->db->set('block', $this->block);
			$this->db->set('rt', $this->rt);	
			$this->db->set('rw', $this->rw);
			$this->db->set('city', $this->city);	
			$this->db->set('postal_code', $this->postal_code);
			$this->db->set('area_id', $this->area_id);	
			$this->db->set('npwp', $this->npwp);
			$this->db->set('phone_number', $this->phone_number);	
			$this->db->set('pic_name', $this->pic_name);
			
			$this->db->update($this->table_supplier);
		}
		
		public function select_by_id($supplier_id)
		{
			$this->db->where('id', $supplier_id);
			$query		= $this->db->get($this->table_supplier);
			$item		= $query->row();
			
			return $item;
		}
}