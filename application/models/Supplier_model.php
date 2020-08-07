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
		
		public function showItems($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
				$this->db->or_like('city', $filter, 'both');
			}
			
			$query 		= $this->db->get($this->table_supplier, $limit, $offset);
			$items	 	= $query->result();
			
			return $items;
		}

		public function countItems($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
				$this->db->or_like('city', $filter, 'both');
			}
			
			$items = $this->db->get($this->table_supplier);
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
		
		public function deleteById()
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $this->input->post('id'));
			$this->db->delete($this->table_supplier);
			
			return $this->db->affected_rows();
		}
		
		public function getById($supplier_id)
		{
			$this->db->where('id', $supplier_id);
			$query = $this->db->get($this->table_supplier);
			
			$item = $query->row();

			return ($item !== null) ? $item : null;
		}
		
		public function updateById()
		{
			$where['id']				= $this->input->post('id');
			
			$name					= $this->input->post('supplier_name');
			$address				= $this->input->post('supplier_address');
			$number				= $this->input->post('address_number');
			$block				= $this->input->post('address_block');
			$rt					= $this->input->post('address_rt');
			$rw					= $this->input->post('address_rw');
			$city					= $this->input->post('supplier_city');
			$postal_code			= $this->input->post('address_postal');
			$area_id				= $this->input->post('area_id');
			$npwp					= $this->input->post('supplier_npwp');
			$phone_number			= $this->input->post('supplier_phone');
			$pic_name				= $this->input->post('supplier_pic');
			
			$db_item = $this->get_db_from_stub();
			
			$this->db->where($where);
			
			$this->db->set('name', $name);			
			$this->db->set('address', $address);
			$this->db->set('number', $number);		
			$this->db->set('block', $block);
			$this->db->set('rt', $rt);	
			$this->db->set('rw', $rw);
			$this->db->set('city', $city);	
			$this->db->set('postal_code', $postal_code);
			$this->db->set('npwp', $npwp);
			$this->db->set('phone_number', $phone_number);	
			$this->db->set('pic_name', $pic_name);
			
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