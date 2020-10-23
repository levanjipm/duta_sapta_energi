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
                    "pic" => $result->pic_name,
                    "city" => $result->city,
                    "postal_code" => $result->postal_code,
                    "phone_number" => $result->phone_number,
                    "term_of_payment" => $result->term_of_payment
                )
            );
        }

        echo json_encode($response);
    }

    public function getCustomerData()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header("Content-Type:application/json");

        $postdata = file_get_contents("php://input");
        $this->load->model("Invoice_model");
        $result     = $this->Invoice_model->getBalanceByCustomerUID($postdata);
        $value      = (float)$result->value - (float)$result->paid;

        $this->load->model("Sales_order_model");
        $pendingSalesOrders     = $this->Sales_order_model->countPendingByCustomerUID($postdata);
        $data['balance']    = $value;
        $data['pending']    = $pendingSalesOrders;
        echo json_encode($data);
    }

    public function getCustomerSalesHistory()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header("Content-Type:application/json");

        $postdata = file_get_contents("php://input");
        $this->load->model("Invoice_model");
        $result       = $this->Invoice_model->getCustomerSalesHistory($postdata);

        for($i = 0; $i < 12; $i++){
			$month		= date('m', strtotime("-" . $i . "months"));
			$year		= date('Y', strtotime("-" . $i . "months"));
			$batchArray[$i] = array(
				"label" => date("M Y", mktime(0,0,0,$month, 1, $year)),
				"value" => 0
			);
		}

		foreach($result as $data){
			$month			= $data->month;
            $year			= $data->year;
            $value          = $data->value;

            $date			= mktime(0,0,0, $month, 1, $year);
            $today          = strtotime("now");
            $datediff		= floor(($today - $date)/(30 * 60 * 60 * 24));

            $batchArray[$datediff]['value'] = (float)$value;
        }
        
        $batch          = (object)$batchArray;
        echo(json_encode($batch));
    }

    public function getCustomerInvoices()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        $postdata = file_get_contents("php://input");
        $this->load->model("Invoice_model");
        $data           = $this->Invoice_model->getIncompletedTransactionByCustomerUID($postdata);
        echo(json_encode($data));
    }
}
?>