<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesApi extends CI_Controller {
	function __construct(){
		parent::__construct();

        $this->load->helper('url');
        require "third_party/JWT/CreatorJWT.php";

        $this->JWT = new CreatorJwt();
    }

    public function index(){

    }

    public function login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header("Content-Type:application/json");
        $this->load->model("User_model");
        $postdata = file_get_contents("php://input");
        $request        = json_decode($postdata);
        $email          = $request->email;
        $password       = $request->password;

        $result = $this->User_model->login($email, $password);
        if($result == null){
            $response       = array(
                "status" => "error",
                "message" => "Failed to log in.",
                "user" => array()
            );

            echo "0";
        } else {
            $response        = array(
                "status" => "success",
                "message" => "Successfully logged in.",
                "user" => $result
            );

            $tokenData = $response["user"];
            $token = $this->JWT->GenerateToken($tokenData);
            echo json_encode(array('Token'=>$token));
        }
    }
}
?>
