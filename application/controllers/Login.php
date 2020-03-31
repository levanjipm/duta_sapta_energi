<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index()
	{
		$this->load->view('login_page');
	}
	
	public function member_login()
	{
		$this->load->model('User_model');
		$result_login = $this->User_model->count_member();
		if($result_login[0] == 'Failed'){
			redirect('/Login', 'refresh');
		} else if($result_login[0] == 'Success'){
			$login_array = array(
				'user_id' => $result_login[1],
			);
			
			$this->session->set_userdata($login_array);
			redirect('/', 'refresh');
		}
	}
}