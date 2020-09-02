<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization_model extends CI_Model {
	private $table_user = 'user_authorization';
		
		public $id;
		public $user_id;
		public $department_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->user_id				= $db_item->user_id;
			$this->department_id		= $db_item->department_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->user_id				= $this->user_id;
			$db_item->department_id			= $this->department_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->user_id				= $db_item->user_id;
			$stub->department_id		= $db_item->department_id;
			
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
		
		public function getByUserId($user_id)
		{
			$this->db->select('user_authorization.*, department.name, department.index_url, department.icon');
			$this->db->from('user_authorization');
			$this->db->join('department', 'user_authorization.department_id = department.id', 'left');
			$this->db->where('user_authorization.user_id', $user_id);
			$this->db->order_by('department.name', 'asc');
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}

		public function getAllFilterByUserId($userId)
		{
			$query			= $this->db->query("
				SELECT department.*, IF(a.id IS NULL, 0, 1) as status FROM
				department
				LEFT JOIN(
					SELECT user_authorization.id, users.name, user_authorization.department_id
					FROM user_authorization
					JOIN users ON user_authorization.user_id = users.id
					WHERE users.id = '$userId'
				) AS a
				ON department.id = a.department_id
				ORDER BY department.name ASC
			");

			$result = $query->result();
			return $result;
		}

		public function updateByUserId($userId, $departmentIdArray)
		{
			$this->db->where('user_id', $userId);
			$this->db->delete($this->table_user);

			$batch = array();
			foreach($departmentIdArray as $departmentId)
			{
				$batch[] = array(
					"id" => "",
					"user_id" => $userId,
					"department_id" => $departmentId
				);
			}

			$this->db->insert_batch($this->table_user, $batch);
			
		}
}
