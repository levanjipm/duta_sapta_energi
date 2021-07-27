<?php 
//application/libraries/CreatorJwt.php
    require_once "JWT.php";

    class CreatorJwt
    {
       

        /*************This function generate token private key**************/ 

        PRIVATE $key = "o5Bkv0CLk9"; 
        public function GenerateToken($data)
        {          
            $jwt = JWT::encode($data, $this->key);
            return $jwt;
        }
        

       /*************This function DecodeToken token **************/

        public function DecodeToken($token)
        {          
            $decoded = JWT::decode($token, $this->key, array('HS256'));
            $decodedData = (array) $decoded;
            return $decodedData;
        }
    }