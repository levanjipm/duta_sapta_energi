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

		public function insertItem($userId, $status, $date = null, $time = null)
		{
			if($date == null || $time == null){
				$this->db->where('user_id', $userId);
				$this->db->where('date', date('Y-m-d'));
				$query		= $this->db->get($this->table_attendance);
				$result		= $query->num_rows();

				if($result == 0){
					$query		= $this->db->query("
						INSERT INTO attendance_list (date, time, user_id, status) VALUES
						('" . date("Y-m-d") . "', NOW(), '$userId', '$status')
					");
					return $this->db->affected_rows();
				} else {
					return 0;
				}
			} else {
				$this->db->where('user_id', $userId);
				$this->db->where('date', $date);
				$query		= $this->db->get($this->table_attendance);
				$result		= $query->num_rows();

				if($result == 0){
					$db_item	= array(
						"id" => "",
						"date" => $date,
						"time" => $time,
						"user_id" => $userId,
						"status" => $status
					);

					$this->db->insert($this->table_attendance, $db_item);
					return $this->db->affected_rows();
				} else {
					return 0;
				}
			}
		}

		public function getAttendanceSalary($month, $year, $userId)
		{
			$query		= $this->db->query("
				SELECT attendance_status.id, attendance_status.name, COALESCE(a.count,0) as count 
				FROM attendance_status
				LEFT JOIN (
					SELECT COUNT(attendance_list.id) as count, attendance_status.id 
					FROM Attendance_list
					JOIN attendance_status ON attendance_status.id = attendance_list.status
					WHERE attendance_list.user_id = '$userId'
					AND MONTH(attendance_list.date) = '$month'
					AND YEAR(attendance_list.date) = '$year'
					GROUP BY attendance_list.status
				) AS a
				ON a.id = attendance_status.id
			");
			$result = $query->result();
			return $result;
		}

		public function getItems($userId, $offset = 0, $limit = 10)
		{
			$this->db->select('attendance_status.name, attendance_status.description, attendance_status.point, attendance_list.date, attendance_list.time, attendance_list.id');
			$this->db->from('attendance_list');
			$this->db->join('attendance_status', 'attendance_list.status = attendance_status.id');
			$this->db->where('attendance_list.user_id', $userId);
			$this->db->order_by('attendance_list.date', "DESC");
			$this->db->limit($limit, $offset);

			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countItems($userId)
		{
			$this->db->where('user_id', $userId);
			$query		= $this->db->get($this->table_attendance);
			$result		= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$this->db->select('attendance_status.name, attendance_status.description, attendance_status.point, attendance_list.date, attendance_list.time, attendance_list.id');
			$this->db->from('attendance_list');
			$this->db->join('attendance_status', 'attendance_list.status = attendance_status.id');
			$this->db->where("attendance_list.id", $id);

			$query			= $this->db->get();
			$result			= $query->row();
			return $result;
		}

		public function getChartItems($userId, $month, $year)
		{
			$query		= $this->db->query("
				SELECT attendance_status.name, attendance_status.description, attendance_status.point, COUNT(attendance_list.id) AS count
				FROM attendance_list
				LEFT JOIN attendance_status ON attendance_list.status = attendance_status.id
				WHERE attendance_list.user_id = '$userId'
				AND MONTH(attendance_list.date) = '$month'
				AND YEAR(attendance_list.date) = '$year'
				HAVING COUNT(attendance_list.id) > 0
			");

			$result			= $query->result();
			return $result;
		}
	}
?>
