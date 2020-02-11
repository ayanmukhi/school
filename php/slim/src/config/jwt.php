<?php
namespace config;

use \Firebase\JWT\JWT as token; 

class jwt 
{
    private $secretkey;

    public function __construct() {
        $this->secretkey = 'secret';
    }

    public function jwttokenencryption($sic, $status = "U") 
    {   



        //constructing payload
        $payload = json_encode(array('status' => $status, 'sic' => $sic));

        //encrypting using firebase library
        $encoded = token::encode($payload, $this->secretkey, 'HS256');

        return $encoded;

    }

    public function jwttokendecryption($token) {
       
        $obj = [];
        $error = false;
        //decypting signature using firebase library
        try {
            
            $decoded = token::decode($token, $this->secretkey, array('HS256'));

        } catch(\Exception $e) {
            $result['message']=$e->getMessage();
            $error = true;
        } finally {
            if($error) {
                $obj["verification"]= "failed";
                return json_encode($obj);   
            } else {
                $obj["verification"] = "passed";
                $data = json_decode($decoded);
                $obj["sic"] = $data->sic;
                $obj["status"] = $data->status;
                return json_encode($obj);
            }        
        }
        
        
        
    }

}
  
?>