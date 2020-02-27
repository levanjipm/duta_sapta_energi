<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	private $table_user = 'users';
		
		public $id;
		public $name;
		public $address;
		public $bank_account;
		public $is_active;
		public $entry_date;
		public $password;
		public $role;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->address				= $db_item->address;
			$this->bank_account			= $db_item->bank_account;
			$this->is_active			= $db_item->is_active;
			$this->entry_date			= $db_item->entry_date;
			$this->password				= $db_item->password;
			$this->role					= $db_item->role;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->address				= $this->address;
			$db_item->bank_account			= $this->bank_account;
			$db_item->is_active				= $this->is_active;
			$db_item->entry_date			= $this->entry_date;
			$db_item->password				= $this->password;
			$db_item->role					= $this->role;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->address				= $db_item->address;
			$stub->bank_account			= $db_item->bank_account;
			$stub->is_active			= $db_item->is_active;
			$stub->entry_date			= $db_item->entry_date;
			$stub->password				= $db_item->password;
			$stub->role					= $db_item->role;
			
			return $stub;
		}
		
		public function map_list($users)
		{
			$result = array();
			foreach ($users as $user)
			{
				$result[] = $this->get_new_stub_from_db($user);
			}
			return $result;
		}
		
		public function count_member()
		{
			$this->load->model('User_model');
			$this->db->select('*');
			$this->db->from($this->table_user);
			$this->db->where('email =', $this->input->post('email'));
			$this->db->where('password =', md5($this->input->post('password')));
			$items = $this->db->get();
			$count = $items->num_rows();
			
			$row = $items->row();
			if(empty($row)){
				$login_status = array('Failed','');
			} else {
				$user_id = $row->id;
				$user_role = $row->role;
				$login_status = array('Success', $user_id, $user_role);
			}
			
			return $login_status;
		}
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_user);
			$users 		= $query->result();
			
			$items = $this->map_list($users);
			
			return $items;
		}
		
		public function insert_from_post()
		{
			$this->load->model('Customer_model');
			$this->db->select('*');
			$this->db->from($this->table_customer);
			$this->db->where('name =', $this->input->post('customer_name'));
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->name					= $this->input->post('client_name');
				$this->address				= $this->input->post('client_address');
				$this->city					= $this->input->post('client_city');
				$this->pic					= $this->input->post('client_pic');
				$this->phone				= $this->input->post('client_phone');
				$this->npwp					= $this->input->post('client_npwp');
				$this->created_by			= 1;
				$this->created_date			= date('Y-m-d');
				
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_client, $db_item);
			}
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id',$id);
			$query = $this->db->get($this->table_user);
			$user = $query->result();
			$result = $this->map_list($user);
			
			return $result;
			
		}
}