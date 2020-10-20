<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
    }

    public function login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header("Content-Type:application/json");

        $postdata = file_get_contents("php://input");
        $request    = json_decode($postdata);
        $username       = $request->username;
        $password       = $request->password;

        $this->load->model("Customer_model");
        $result     = $this->Customer_model->customerLogin($username, $password);
        if($result == null){
            $response       = array(
                "status" => "error",
                "message" => "Failed to log in.",
                "user" => array()
            );
        } else {
            $response        = array(
                "status" => "success",
                "message" => "Successfully logged in.",
                "user" => array(
                    "name" => $result->name,
                    "address" => $result->address,
                    "number" => $result->number,
                    "block" => $result->block,
                    "rt" => $result->rt,
                    "rw" => $result->rw,
                    "pic" => $result->pic,
                    "city" => $result->postal_code,
                    "phone_number" => $result->phone_number,
                    "pic_name" => $result->pic_name
                )
            );
        }

        echo json_encode($response);
        
    }
}
?>