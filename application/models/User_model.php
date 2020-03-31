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
		public $image_url;
		public $access_level;
		
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
			$this->image_url			= $db_item->image_url;
			$this->access_level			= $db_item->access_level;
			
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
			$db_item->image_url				= $this->image_url;
			$db_item->access_level			= $this->access_level;
			
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
			$stub->image_url			= $db_item->image_url;
			$stub->access_level			= $db_item->access_level;
			
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
				$login_status = array('Success', $user_id);
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
		
		public function show_by_id($id)
		{
			$this->db->where('id',$id);
			$query = $this->db->get($this->table_user);
			$user = $query->row();
			
			return $user;
		}
}