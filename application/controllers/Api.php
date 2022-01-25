<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();

        $this->load->helper('url');
        require_once APPPATH.'third_party/JWT/CreatorJWT.php';

        $this->JWT = new CreatorJwt();
    }

    public function Index(){
        $this->load->view('index.php');
    }

    public function login()
    {
        header('Access-Control-Allow-Origin: https://customer.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $postdata = file_get_contents("php://input");
        $request        = json_decode($postdata);
        $email       = $request->email;
        $password       = $request->password;

        $this->load->model("User_model");
        $result     = $this->User_model->login($email, $password);
        if($result == null){
            echo 0;
        } else {
            $tokenData = $result;
            $token = $this->JWT->GenerateToken($tokenData);
            echo json_encode(array('Token'=>$token));
        }
    }

    public function getRoutes()
    {
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Route_model");
            $data           = $this->Route_model->getRoutes();
            echo json_encode($data);
        }

        
    }

    public function getDeliverables($routeId, $offset = 0){
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Sales_order_model");
            $data       = $this->Sales_order_model->getByRoute($routeId, $offset);
            echo json_encode($data);
        }
        
    }

    public function getUndeliverables($routeId, $offset = 0){
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Sales_order_model");
        $data       = $this->Sales_order_model->getByUndeliverableRoute($routeId, $offset);
        echo json_encode($data);
        }
    }

    public function getSalesOrderByName($name)
    {
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Sales_order_model");
            $data           = $this->Sales_order_model->getByName($name);
            echo json_encode($data);
        }
    }

    public function getSuppliersWithIncompletedPurchaseOrders()
    {
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Purchase_order_model");
            $data           = $this->Purchase_order_model->getSuppliersWithIncompletedPurchaseOrders();
            echo json_encode($data);
        }
    }

    public function getPurchaseOrdersBySupplierId($supplierId)
    {
        header('Access-Control-Allow-Origin: https://warehouse.dutasaptae.management');
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Access-Control-Allow-Origin");
        header('Content-Type: application/json, charset=utf-8');

        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Purchase_order_model");
            $data           = $this->Purchase_order_model->getBySupplierId($supplierId);
            echo json_encode($data);
        }
    }

    public function createGoodReceipt(){
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $postdata = file_get_contents("php://input");
            $request        = json_decode($postdata);

            $date           = $request->date;
            $name           = $request->name;
            $createdBy      = $request->createdBy;
            $guid           = $request->uid;
            $items          = $request->item;

            $this->load->model("Good_receipt_model");
            $result      = $this->Good_receipt_model->createGoodReceipt($name, $date, $createdBy, $guid, $items);
            echo $result;
        }
    }

    public function createDeliveryOrder()
	{
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $postdata           = file_get_contents("php://input");
            $request            = json_decode($postdata);
            $guid			    = $request->uid;
            $date               = $request->date;
            $items              = $request->item;
            $taxing             = $request->taxing;

            //GUID is generated via UUID Package by Angular client side
            //Date format received is the classic 'YYYY-MM-DD' format

            //First, we will check wether this sales order is still creatable by checking
            //The sent quantity, quantity, and to be sent quantity
            //and taking the status column into account.

            //Check if the item array is correctly constructed, not filled
            // with 0

            //Since the items format contains quantity and id
            $itemArray      = array();
            foreach($items as $item){
                $id         = $item->id;
                $quantity   = $item->quantity;

                if($quantity > 0){
                    $itemArray[$id]     = $quantity;
                }
                
                next($items);
            }

            //Getting data from an ID Array and sending it to the
            // Create function one we find out that the quantity submitted
            // is allowed.

            $this->load->model("Sales_order_detail_model");
            $salesOrderArray        = $this->Sales_order_detail_model->getByIdArray(array_keys($itemArray));
            $updateArray            = array();
            $deliveryOrderItemArray = array();

            foreach($salesOrderArray as $salesOrderItem){
                $quantity           = $salesOrderItem->quantity;
                $sent               = $salesOrderItem->sent;
                $toBeSent           = $itemArray[$salesOrderItem->id];
                if($quantity == ($sent + $toBeSent)){
                    array_push($updateArray, array(
                        "id" => $salesOrderItem->id,
                        "sent" => $sent + $toBeSent,
                        "status" => 1
                    ));

                    array_push($deliveryOrderItemArray, array(
                        "sales_order_id" => $salesOrderItem->id,
                        "quantity" => $toBeSent,
                    ));
                } else if($quantity > ($sent + $toBeSent)) {
                    array_push($updateArray, array(
                        "id" => $salesOrderItem->id,
                        "sent" => $sent + $toBeSent,
                        "status" => 0
                    ));

                    array_push($deliveryOrderItemArray, array(
                        "sales_order_id" => $salesOrderItem->id,
                        "quantity" => $toBeSent,
                    ));
                }
                next($salesOrderArray);
            }

            $this->load->model("Delivery_order_model");
            $deliveryOrderResult            = $this->Delivery_order_model->insertItem($date,$taxing, $deliveryOrderItemArray, $guid);
            if($deliveryOrderResult == 1){
                $this->Sales_order_detail_model->updateSalesOrderSent($updateArray);

                echo 1;
            } else {
                echo 0;
            }
        }
        
	}

    public function getBrands()
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Item_model");
            $result         = $this->Item_model->getBrands();
            echo json_encode($result);
        }
    }

    public function getItemTypesByBrand($brand)
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Item_model");
            $result         = $this->Item_model->getItemTypesByBrand($brand);
            echo json_encode($result);
        }
    }

    public function getItemsByType()
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $postdata = file_get_contents("php://input");
            $idArray        = json_decode($postdata);
            $this->load->model("Item_model");
            $result         = $this->Item_model->getByItemTypeIdArray($idArray);
            echo json_encode($result);
        }
    }

    public function getUnconfirmedDeliveryOrder()
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Delivery_order_model");
            $result         = $this->Delivery_order_model->getUnconfirmed();
            echo json_encode($result);
        }
    }

    public function getUnconfirmedGoodReceipt()
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Good_receipt_model");
            $result         = $this->Good_receipt_model->getUnconfirmed();
            echo json_encode($result);
        }
    }

    public function getDeliveryOrderById($id)
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Delivery_order_model");
            $result     = $this->Delivery_order_model->getDeliveryOrderById($id);
            echo json_encode($result);
        }
    }

    public function confirmDeliveryOrderById()
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $postdata = file_get_contents("php://input");
            $request        = json_decode($postdata);
            $id             = $request->id;
            $confirmedBy    = $request->confirmedBy;

            $this->load->model("User_model");
            $user           = $this->User_model->getById($confirmedBy);

            if($user == NULL)
            {
                echo 0;
            } else {
                $this->load->model("Delivery_order_model");
                $result         = $this->Delivery_order_model->confirmById(1, $id);
                echo ($result) ? 1 : 0;
            }
        }
    }

    public function checkStock($keyword, $page = 1)
	{
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Stock_model");
            $data           = array();
            $offset         = ($page - 1) * 10;
            $data['items']  = $this->Stock_model->searchStock($keyword, $offset);
            $data['count']  = $this->Stock_model->countStock($keyword);

            echo json_encode($data);
        }
	}

    public function getGoodReceiptById($id)
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            $this->load->model("Good_receipt_model");
            $data       = array();
            $data['general']     = $this->Good_receipt_model->getById($id);
            $data['items']          = $this->Good_receipt_model->getItemsById($id);

            echo json_encode($data);
        }
    }

    public function confirmGoodReceiptById($id)
    {
        $headers = apache_request_headers();
        if(!array_key_exists('Authorization', $headers) || $this->JWT->DecodeToken(substr($headers['Authorization'], 7, 500)) == NULL){
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {
            
            $authorization = substr($headers['Authorization'], 7, 500);
            $data       = $this->JWT->DecodeToken($authorization);

            $this->load->model('Good_receipt_model');
            if ($this->Good_receipt_model->updateStatusById(1, $id, $data['id']) == 1)
            {
                $this->load->model('Good_receipt_detail_model');

                $batch = $this->Good_receipt_detail_model->getStockBatchByCodeGoodReceiptId($id);

                $expectedInput = count($batch);

                $this->load->model('Stock_model');
                $result = $this->Stock_model->insertItem($batch);

                if($result == $expectedInput){
                    echo 1;
                } else {
                    $this->Good_receipt_detail_model->deleteByCodeGoodReceiptId($id);
                    $this->Stock_model->deleteItemFromGoodReceipt($id);
                    $this->Good_receipt_model->updateStatusById(-1, $id);

                    echo 0;
                }
            } else {
                echo 0;
            }
        }
    }
}
?>
