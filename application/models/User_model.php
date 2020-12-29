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
		public $email;
		
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
			$this->email				= $db_item->email;
			
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
			$db_item->email					= $this->email;
			
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
			$stub->email				= $db_item->email;
			
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
			$this->db->where('is_active', 1);
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
			$this->db->order_by('name');
			$query 		= $this->db->get($this->table_user);
			$users 		= $query->result();
			
			$items = $this->map_list($users);
			
			return $items;
		}
		
		public function getById($userId)
		{
			$this->db->where('id',$userId);
			$query = $this->db->get($this->table_user);
			$result = $query->row();
			
			return $result;
		}
		
		public function getItems($offset = 0, $term = "", $limit = 10)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			$this->db->limit($limit, $offset);
			$query = $this->db->get($this->table_user);
			$result = $query->result();
			
			return $result;
		}
		
		public function countItems($term = "")
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			$query = $this->db->get($this->table_user);
			$result = $query->num_rows();
			
			return $result;
		}
		
		public function update_status($status, $id)
		{
			$this->db->set('is_active', $status);
			$this->db->where('id', $id);
			
			$this->db->update($this->table_user);
		}
		
		public function insertItem()
		{
			$this->id					= "";
			$this->name					= $this->input->post('name');
			$this->address				= $this->input->post('address');
			$this->bank_account			= $this->input->post('bank_account');
			$this->is_active			= 1;
			$this->entry_date			= date('Y-m-d');
			$this->password				= md5($this->input->post('password'));
			$this->image_url			= null;
			$this->access_level			= $this->input->post('access_level');
			$this->email				= $this->input->post('email');

			$db_item 					= $this->get_db_from_stub($this);
			$db_result 					= $this->db->insert($this->table_user, $db_item);
			$insertId					= $this->db->insert_id();
			
			return $this->db->insert_id() == null? null: $insertId;
		}

		public function updateProfilePicture($userId, $imageUrl)
		{
			$this->db->set('image_url', $imageUrl);
			$this->db->where('id', $userId);
			$this->db->update($this->table_user);
		}

		public function getSalesItems($offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT users.* FROM users
				JOIN (
					SELECT user_authorization.user_id FROM user_authorization
					WHERE department_id = '2'
				) AS a
				ON a.user_id = users.id
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countSalesItems()
		{
			$query		= $this->db->query("
				SELECT users.* FROM users
				JOIN (
					SELECT user_authorization.user_id FROM user_authorization
					WHERE department_id = '2'
				) AS a
				ON a.user_id = users.id
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function getAccountantItems($offset = 0, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT users.* FROM users
				JOIN (
					SELECT user_authorization.user_id FROM user_authorization
					WHERE department_id = '1'
				) AS a
				ON a.user_id = users.id
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countAccountantItems()
		{
			$query		= $this->db->query("
				SELECT users.* FROM users
				JOIN (
					SELECT user_authorization.user_id FROM user_authorization
					WHERE department_id = '1'
				) AS a
				ON a.user_id = users.id
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function countActiveUser()
		{
			$this->db->where("is_active", 1);
			$query		= $this->db->get($this->table_user);
			$result		= $query->num_rows();
			return $result;
		}

		public function getActiveUser()
		{
			$this->db->where("is_active", 1);
			$this->db->order_by('name');

			$query		= $this->db->get($this->table_user);
			$result		= $query->result();
			return $result;
		}

		public function updateAccessLevelById($userId, $accessLevel)
		{
			if($accessLevel <=5 && $accessLevel > 0){
				$this->db->set('access_level', $accessLevel);
				$this->db->where('id', $userId);
				$this->db->update($this->table_user);
			}
		}

		public function updateById($id, $name, $email, $bank, $address, $password)
		{
			$this->db->set('name', $this->db->escape_str($name));
			$this->db->set('email', $this->db->escape_str($email));
			$this->db->set('bank_account', $this->db->escape_str($bank));
			$this->db->set('address', $this->db->escape_str($address));
			if($password != NULL){
				$this->db->set('name', md5($password));
			}
			$this->db->where('id', $id);
			$this->db->update($this->table_user);
			return $this->db->affected_rows();
		}

		public function getAccessLevelRatio()
		{
			$query		= $this->db->query("
				SELECT COUNT(users.id) AS count, users.access_level
				FROM users
				WHERE users.is_active = '1'
				GROUP BY users.access_level
			");

			$result			= $query->result();
			$responseArray	= array();
			$data			= (array) $result;
			foreach($data as $item){
				$responseArray[$item->access_level] = $item->count;
			}

			return $responseArray;
		}
}
