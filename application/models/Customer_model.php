<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {
	private $table_customer = 'customer';
		
		public $id;
		public $name;
		public $address;
		public $number;
		public $block;
		public $rt;
		public $rw;
		public $city;
		public $postal_code;
		public $area_id;
		public $npwp;
		public $phone_number;
		public $pic_name;
		public $date_created;
		public $created_by;
		public $is_black_list;
		public $term_of_payment;
		public $latitude;
		public $longitude;
		public $plafond;
		public $uid;
		public $password;
		
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
			$this->area_id				= $db_item->area_id;
			$this->npwp					= $db_item->npwp;
			$this->phone_number			= $db_item->phone_number;
			$this->pic_name				= $db_item->pic_name;
			$this->date_created			= $db_item->date_created;
			$this->created_by			= $db_item->created_by;
			$this->is_black_list		= $db_item->is_black_list;
			$this->plafond				= $db_item->plafond;
			$this->latitude				= $db_item->latitude;
			$this->longitude			= $db_item->longitude;
			$this->term_of_payment		= $db_item->term_of_payment;
			$this->uid					= $db_item->uid;
			$this->password				= $db_item->password;
			
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
			$db_item->area_id				= $this->area_id;
			$db_item->npwp					= $this->npwp;
			$db_item->phone_number			= $this->phone_number;
			$db_item->pic_name				= $this->pic_name;
			$db_item->date_created			= $this->date_created;
			$db_item->created_by			= $this->created_by;
			$db_item->latitude				= $this->latitude;
			$db_item->plafond				= $this->plafond;
			$db_item->longitude				= $this->longitude;
			$db_item->is_black_list			= $this->is_black_list;
			$db_item->term_of_payment		= $this->term_of_payment;
			$db_item->uid					= $this->uid;
			$db_item->password				= $this->password;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->address				= $db_item->address;
			$stub->number				= $db_item->number;
			$stub->block				= $db_item->block;
			$stub->rt					= $db_item->rt;
			$stub->rw					= $db_item->rw;
			$stub->city					= $db_item->city;
			$stub->postal_code			= $db_item->postal_code;
			$stub->area_id				= $db_item->area_id;
			$stub->npwp					= $db_item->npwp;
			$stub->phone_number			= $db_item->phone_number;
			$stub->pic_name				= $db_item->pic_name;
			$stub->date_created			= $db_item->date_created;
			$stub->created_by			= $db_item->created_by;
			$stub->latitude				= $db_item->latitude;
			$stub->longitude			= $db_item->longitude;
			$stub->is_black_list		= $db_item->is_black_list;
			$stub->plafond				= $db_item->plafond;
			$stub->term_of_payment		= $db_item->term_of_payment;
			$stub->uid					= $db_item->uid;
			$stub->password				= $db_item->password;
			
			return $stub;
		}
		
		public function map_list($customers)
		{
			$result = array();
			foreach ($customers as $customer)
			{
				$result[] = $this->get_new_stub_from_db($customer);
				continue;
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
			$this->db->order_by('name');
			$this->db->limit($limit, $offset);			
			$query 		= $this->db->get($this->table_customer);
			$items	 	= $query->result();
			
			return $items;
		}
		
		public function countItems($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
			}
			
			$query		= $this->db->get($this->table_customer);
			$result		= $query->num_rows();
			
			return $result;
		}

		public function showActiveItems($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
				$this->db->or_like('city', $filter, 'both');
			}

			$this->db->where('is_black_list',0);
			$this->db->order_by('name');
			$this->db->limit($limit, $offset);			
			$query 		= $this->db->get($this->table_customer);
			$items	 	= $query->result();
			
			return $items;
		}
		
		public function countActiveItems($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
			}
			$this->db->where('is_black_list', 0);
			$query		= $this->db->get($this->table_customer);
			$result		= $query->num_rows();
			
			return $result;
		}

		private function generateUid()
		{
			$validation = false;
			while($validation == false){
				$name		= rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
				$this->db->where('uid', $name);
				$query		= $this->db->get($this->table_customer);
				$result		= $query->num_rows();

				if($result == 0){
					$validation = true;
				} else {
					$this->Customer_model->generateUid();
				}
			}

			return $name;
			
		}
		
		public function insertItem()
		{
			$this->db->where('name', $this->input->post('customer_name'));
			$query = $this->db->get($this->table_customer);
			$count = $query->num_rows();
			
			if($count == 0){
				$this->id					= '';
				$this->name					= $this->input->post('customer_name');
				$this->address				= $this->input->post('customer_address');
				$this->number				= $this->input->post('address_number');
				$this->block				= $this->input->post('address_block');
				$this->rt					= $this->input->post('address_rt');
				$this->rw					= $this->input->post('address_rw');
				$this->city					= $this->input->post('customer_city');
				$this->postal_code			= $this->input->post('address_postal');
				$this->area_id				= $this->input->post('area_id');
				$this->npwp					= $this->input->post('customer_npwp');
				$this->phone_number			= $this->input->post('customer_phone');
				$this->latitude				= $this->input->post('latitude');
				$this->longitude			= $this->input->post('longitude');
				$this->pic_name				= $this->input->post('customer_pic');
				$this->date_created			= date('Y-m-d');
				$this->created_by			= $this->session->userdata('user_id');
				$this->is_black_list		= 0;
				$this->term_of_payment		= 30;
				$this->plafond				= '0';
				$this->uid					= $this->Customer_model->generateUid();
				$this->password				= NULL;
				$this->visiting_frequency	= $this->input->post('visit');
				
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_customer, $db_item);
				
				return ($this->db->affected_rows() == 0) ? NULL : $this->db->insert_id();
			} else {
				return NULL;
			}
		}
		
		public function deleteById()
		{
			$this->db->db_debug = FALSE;
			
			$this->db->where('id', $this->input->post('id'));
			$this->db->delete($this->table_customer);
			
			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_customer, 1);
			
			$item = $query->row();
			
			return ($item !== null) ? $item : null;
		}
		
		public function updateItemById()
		{
			$this->db->db_debug = FALSE;
			$customer_id				= $this->input->post('id');

			$db_item			= array(
				'name'				=> $this->input->post('name'),
				'address' 			=> $this->input->post('address'),
				'number' 			=> $this->input->post('number'),
				'block' 			=> $this->input->post('block'),
				'rt' 				=> $this->input->post('rt'),
				'rw' 				=> $this->input->post('rw'),
				'city' 				=> $this->input->post('city'),
				'latitude'			=> ($this->input->post('latitude') == "")? null : $this->input->post('latitude'),
				'longitude'			=> ($this->input->post('longitude') == "")? null : $this->input->post('longitude'),
				'postal_code' 		=> $this->input->post('postal'),
				'area_id' 			=> $this->input->post('area_id'),
				'npwp' 				=> $this->input->post('npwp'),
				'phone_number' 		=> $this->input->post('phone'),
				'pic_name' 			=> $this->input->post('pic'),
				'visiting_frequency'	=> $this->input->post('visit')
			);
			
			$this->db->where('id', $customer_id);
			$this->db->update($this->table_customer, $db_item);
			
			return $this->db->affected_rows();
		}

		public function countActiveCustomer($month, $year){
			$this->db->select('DISTINCT(customer.id)');
			$this->db->from('customer');
			$this->db->join('code_sales_order', 'code_sales_order.customer_id = customer.id');
			$this->db->join('sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('delivery_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id');
			$this->db->join('invoice', 'code_delivery_order.invoice_id = invoice.id');

			$this->db->where('MONTH(invoice.date)', $month);
			$this->db->where('YEAR(invoice.date)', $year);

			$query = $this->db->get();
			$result = $query->num_rows();

			return $result;
		}
		
		public function show_all_by_id($customer_id_array)
		{
			$this->db->where_in('id', $customer_id_array);

			$query = $this->db->get($this->table_customer);
			$items	= $query->result();
			return $items;
		}
		
		public function show_unconfirmed_plafond_customers($offset = 0, $term = '', $limit = 25)
		{
			$this->db->select('customer.*, users.name as created_by, plafond_submission.submitted_date, plafond_submission.id as submission_id');
			$this->db->from('plafond_submission');
			$this->db->join('customer', 'plafond_submission.customer_id = customer.id');
			$this->db->join('users', 'plafond_submission.submitted_by = users.id');
			$this->db->where('plafond_submission.is_confirm', 0);
			$this->db->where('plafond_submission.is_delete', 0);
			if($term != ''){
				$this->db->like('customer.name', $term , 'both');
				$this->db->or_like('customer.address', $term , 'both');
				$this->db->or_like('customer.postal_code', $term , 'both');
				$this->db->or_like('customer.block', $term , 'both');
				$this->db->or_like('customer.pic_name', $term , 'both');
				$this->db->or_like('customer.rt', $term , 'both');
				$this->db->or_like('customer.rw', $term , 'both');
				$this->db->or_like('customer.city', $term , 'both');
			};
			
			$this->db->limit($limit, $offset);
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_unconfirmed_plafond_customers($term = '')
		{
			$this->db->select('plafond_submission.id,');
			$this->db->from('plafond_submission');
			$this->db->join('customer', 'plafond_submission.customer_id = customer.id');
			$this->db->where('plafond_submission.is_confirm', 0);
			$this->db->where('plafond_submission.is_delete', 0);
			if($term != ''){
				$this->db->like('name', $term , 'both');
				$this->db->or_like('customer.address', $term , 'both');
				$this->db->or_like('customer.postal_code', $term , 'both');
				$this->db->or_like('customer.block', $term , 'both');
				$this->db->or_like('customer.pic_name', $term , 'both');
				$this->db->or_like('customer.rt', $term , 'both');
				$this->db->or_like('customer.rw', $term , 'both');
				$this->db->or_like('customer.city', $term , 'both');
			};
			$query		= $this->db->get();
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function update_plafond($customer_id, $plafond, $top)
		{
			if($plafond >= 0 && $plafond != NULL){
				$this->db->set('plafond', $plafond);
			} else if($top >= 0 && $top != NULL){
				$this->db->set('term_of_payment', $top);
			}

			$this->db->where('id', $customer_id);
			$this->db->update($this->table_customer);
		}

		public function showCustomerAccountantItems($offset = 0, $term = "", $accountantId = NULL, $limit = 10)
		{
			if($limit == 0){
				$query			= $this->db->query("
					SELECT customer.*, IF(customer_accountant.id IS NULL, 0, 1) as accountantStatus
					FROM customer
					LEFT JOIN customer_accountant
					ON customer.id = customer_accountant.customer_id
					ORDER BY customer.name
				");
			} else {
				$query			= $this->db->query("
					SELECT customer.*, IF(customer_accountant.id IS NULL, 0, 1) as accountantStatus
					FROM customer
					LEFT JOIN customer_accountant
					ON customer.id = customer_accountant.customer_id
					WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%' OR customer.city LIKE '%$term%'
					ORDER BY customer.name
					LIMIT $limit OFFSET $offset;
				");
			}
			
			$result		= $query->result();
			return $result;
		}

		public function getVisitRecommendedList($offset = 0, $limit = 10)
		{
			$query			= $this->db->query("
				SELECT customer.*, code_visit_list.date 
				FROM customer
				JOIN (
					SELECT visit_list.customer_id, code_visit_list.id
					FROM visit_list
					JOIN code_visit_list ON visit_list.code_visit_list_id
					WHERE result <> 0
					GROUP BY customer_id
					ORDER BY date DESC
				) visitListTable
				ON customer.id = visitListTable.customer_id
				JOIN code_visit_list ON visitListTable.id = code_visit_list.id
			");
		}

		public function updateVisitById($id, $visit)
		{
			$this->db->set('visiting_frequency', $visit);
			$this->db->where('id', $id);
			$this->db->update($this->table_customer);

			return $this->db->affected_rows();
		}

		public function customerLogin($uid, $password)
		{
			$this->db->where('uid', $uid);
			$this->db->where('password', md5($password));
			$query		= $this->db->get($this->table_customer);
			$result		= $query->row();
			return $result;
		}

		public function registerCustomer($customerUID, $password)
		{
			$this->db->set('password', md5($password));
			$this->db->where('uid', $customerUID);
			$this->db->where('password', NULL);
			$this->db->update($this->table_customer);
			$result		= $this->db->affected_rows();
			return $result;
		}

		public function getByAreaId($areaId)
		{
			$query		= $this->db->query("
				SELECT customer_target.brand, customer_target.dateCreated, customer_target.value,
				customer.*
				FROM customer_target
				JOIN customer
				ON customer_target.customer_id = customer.id
				WHERE customer.area_id = '$areaId'
			");

			$customers			= $query->result();
			$response			= array();
			$targets			= array();
			foreach($customers as $customer){
				$id				= $customer->id;
				if(!array_key_exists($id, $response)){
					$response[$id]['id'] = $customer->id;
					$response[$id]['name']	= $customer->name;
					$response[$id]['address']	= $customer->address;
					$response[$id]['number']	= $customer->number;
					$response[$id]['block']		= $customer->block;
					$response[$id]['postal_code']	= $customer->postal_code;
					$response[$id]['city']			= $customer->city;
					$response[$id]['rt']			= $customer->rt;
					$response[$id]['rw']			= $customer->rw;
					$response[$id]['latitude']		= $customer->latitude;
					$response[$id]['longitude']		= $customer->longitude;
					
					$targets[$id]					= array();
					$targetItem						= array(
						"dateCreated" => $customer->dateCreated,
						"brand" => $customer->brand,
						"value" => $customer->value
					);
					array_push($targets[$id], $targetItem);
				} else {
					$targetItem						= array(
						"dateCreated" => $customer->dateCreated,
						"brand" => $customer->brand,
						"value" => $customer->value
					);
					array_push($targets[$id], $targetItem);
				}

				next($customer);
			}

			foreach($targets as $customerId => $target){
				if(!array_key_exists("target", $response[$customerId])){
					$response[$customerId]['target'] = array();
				}

				foreach($target as $targetItem){
					$brand = $targetItem["brand"];
					$value = $targetItem["value"];
					$dateCreated = $targetItem["dateCreated"];

					if(!array_key_exists($brand, $response[$customerId]['target'])){
						$response[$customerId]['target'][$brand] = array(
							"brand" => $brand,
							"value" => $value,
							"dateCreated" => $dateCreated
						);
					} else {
						if($response[$customerId]['target'][$brand]["dateCreated"] < $dateCreated){
							$response[$customerId]['target'][$brand] = array(
								"brand" => $brand,
								"value" => $value,
								"dateCreated" => $dateCreated
							);
						}
					}

					continue;
				}

				continue;
			}

			return $response;
		}

		public function getAllItemsByArea()
		{
			$query		= $this->db->query("
				SELECT customer.*, customer_area.name AS area_name
				FROM customer
				JOIN customer_area ON customer.area_id = customer_area.id
				ORDER BY customer_area.name ASC, customer.name ASC
			");

			$result			= $query->result();
			$response			= array();
			foreach($result as $data){
				if(!array_key_exists($data->area_id, $response)){
					$response[$data->area_id]				= array();
					$response[$data->area_id]['name'] 		= $data->area_name;
					$response[$data->area_id]['customers'] 	= array();
				}

				$arrayData		= (array)$data;
				array_push($response[$data->area_id]['customers'], $arrayData);
				next($result);
			}

			return $response;
		}

		public function getNoo($month, $year, $brand)
		{
			$dateParam			= $year . '-' . str_pad($month, 2, 0) . "-01";
			$query			= $this->db->query("
				SELECT customer.*, customer_area.name as area
				FROM customer
				JOIN customer_area ON customer.area_id = customer_area.id
				WHERE customer.id IN
				(
					SELECT code_sales_order.customer_id
					FROM code_sales_order
					JOIN sales_order ON code_sales_order.id = sales_order.code_sales_order_id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE code_sales_order.is_confirm = '1'
					AND MONTH(code_sales_order.date) = '$month'
					AND YEAR(code_sales_order.date) = '$year'
					AND item.brand = '$brand'
				)
				AND customer.id NOT IN (
					SELECT code_sales_order.customer_id
					FROM code_sales_order
					WHERE code_sales_order.date < $dateParam
					AND code_sales_order.is_confirm = '1'
				)
			");

			$result			= $query->result();
			return $result;
		}

		public function getAllCustomers()
		{
			$this->db->select('customer.*, customer_area.name AS area');
			$this->db->from('customer');
			$this->db->join('customer_area', 'customer.area_id = customer_area.id');
			$this->db->order_by('customer.name');

			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function resetPasswordById($customerId)
		{
			$this->db->set('password', null);
			$this->db->where('id', $customerId);
			$this->db->where('password !=', null);
			$this->db->update($this->table_customer);
			return $this->db->affected_rows();
		}
}
