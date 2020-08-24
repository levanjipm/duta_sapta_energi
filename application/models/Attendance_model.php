<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {
	private $table_attendance = 'Attendance_list';
		
		public $id;
		public $user_id;
		public $date;
		public $time;
		public $status;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->user_id				= $db_item->user_id;
			$this->date					= $db_item->date;
			$this->time					= $db_item->time;
			$this->status				= $db_item->status;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->user_id				= $this->user_id;
			$db_item->date					= $this->date;
			$db_item->time					= $this->time;
			$db_item->status				= $this->status;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->user_id				= $db_item->user_id;
			$stub->date					= $db_item->date;
			$stub->time					= $db_item->time;
			$stub->status				= $db_item->status;
			
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

		public function getUnattendedList($date)
		{
			$query	 = $this->db->query("
				SELECT users.*, a.status FROM users LEFT JOIN
				(
					SELECT attendance_list.* FROM users
					LEFT JOIN attendance_list ON attendance_list.user_id = users.id
					WHERE attendance_list.date = CURDATE()
				) as a
				ON a.user_id = users.id
				WHERE users.is_active = '1'
			");
			$result = $query->result();
			return $result;
		}

		public function insertItem($userId, $status)
		{
			$this->db->where('user_id', $userId);
			$this->db->where('date', date('Y-m-d'));
			$query		= $this->db->get($this->table_attendance);
			$result		= $query->num_rows();

			if($result == 0){
				$query		= $this->db->query("
					INSERT INTO Attendance_list (date, time, user_id, status) VALUES
					('" . date("Y-m-d") . "', NOW(), '$userId', '$status')
				");
				return $this->db->affected_rows();
			} else {
				return 0;
			}
		}
}